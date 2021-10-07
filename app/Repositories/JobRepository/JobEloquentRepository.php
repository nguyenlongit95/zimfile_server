<?php

namespace App\Repositories\JobRepository;

use App\Models\Director;
use App\Models\Files;
use App\Models\Jobs;
use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\Files\FilesRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

class JobEloquentRepository extends EloquentRepository implements JobRepositoryInterface
{
    const BASE_PATH = '/disk1/DATA';

    /**
     * @return mixed
     */
    public function getModel()
    {
        return Jobs::class;
    }

    /**
     * Function create an jobs and save file to store
     *
     * @param $file
     * @param $directoryId
     * @param $type
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function uploadJobs($file, $directoryId)
    {
        // get dir path
        $directory = app()->make(DirectoryRepositoryInterface::class)->find($directoryId);
        $pathFile = null;
        if (!$directory) {
            return null;
        }
        $pathFile = $directory->nas_dir;
        if ($directory->parent_id > 0) {
            $parentDir = app()->make(DirectoryRepositoryInterface::class)->find($directory->parent_id);
            $pathFile = $parentDir->nas_dir . '/' . $pathFile;
        }
        try {
            $path = config('const.base_path') . '/' . Auth::user()->name . '/' . $pathFile . '/' . $file->getClientOriginalName();
            // Upload file to storage
            $putNASStorage = Storage::disk('ftp')->put($path, $file->get());
            if (!$putNASStorage) {
                return false;
            }
            // Insert into database
            $param['user_id'] = Auth::user()->getAuthIdentifier();
            $param['director_id'] = $directoryId;
            $param['file_id'] = null;
            $param['file_jobs'] = $file->getClientOriginalName();
            $param['status'] = 1;                                   // status default 1 is not assign
            $param['time_upload'] = Carbon::now();
            $param['time_confirm'] = null;
            $param['time_done'] = null;
            $param['type'] = 0;
            $param['file_jobs_thumbnail'] = null;         // Thumbnails
            Log::info('User: ' . Auth::user()->email . ' create a job in : ' . $path);
            return $this->create($param);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * Function list all jobs an user
     *
     * @param array $param
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function userListJobs($param)
    {
        // Find query
        $jobs = Jobs::on();
        if (isset($param['status'])) {
            $jobs = $jobs->where('status', $param['status']);
        }
        if (isset($param['type'])) {
            $jobs = $jobs->where('type', $param['type']);
        }
        // Select data
        $jobs = $jobs->where('user_id', Auth::user()->getAuthIdentifier())
            ->orderBy('id', 'DESC')->with(['files'])->paginate(config('const.paginate'));
        if (is_null($jobs)) {
            return null;
        }
        // merge data path file
        return $this->addOnPathFile($jobs);
    }

    /**
     * Function get jobs for editor
     *
     * @param array $param
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getJobsForEditor($param)
    {
        return Director::where('level', 2)->where('editor_id', null)->orWhere('editor_id', Auth::id())
            ->where('status', 0)->orderBy('id', 'ASC')->first();
    }

    /**
     * Function list jobs for QC, QC can only see jobs with status 3 confirm
     *
     * @param array $param
     * @return mixed
     */
    public function getJobsForQC($param)
    {
        return Director::join('users', 'directors.editor_id', 'users.id')->where('directors.level', 2)->where('directors.editor_id', '<>', null)
            ->where('directors.status', 3)->select(
                'directors.id', 'directors.user_id', 'directors.nas_dir', 'directors.level', 'directors.parent_id',
                'directors.path', 'directors.type', 'directors.status', 'directors.editor_id', 'directors.note',
                'users.name as editor_name'
            )->get();
    }

    /**
     * Function check the work before handing it over to the person in charge
     *
     * @param array $param
     * @return mixed
     */
    public function checkJobsBeforeAssign($param)
    {
        $job = DB::table('directors')->where('editor_id', '=', Auth::user()->id)->where('status', 2)->count();
        if ($job > 0) {
            return false;
        }
        // Return pass param
        return true;
    }

    /**
     * Function upload file and save to nas file
     *
     * @param $request
     * @param $path
     * @param $dir
     * @param $job
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function uploadFileProduct($request, $path, $dir, $job)
    {
        $file = $request->file('file');
        if (app()->make(FilesRepositoryInterface::class)->validateFile($file) != 1) {
            return false;
        }
        try {
            $fileName = $file->getClientOriginalName();
            // save file product to nas storage
            Storage::disk('ftp')->put($path . '/' . $fileName, $file->get());
            // Insert data file to table Files
            $file = new Files();
            $file->director_id = $dir->id;
            $file->image = $fileName;
            $file->time_upload = Carbon::now();
            $file->status = 1;
            $file->thumbnail = '-';
            $file->name = Auth::user()->name;
            if ($file->save()) {
                // upgrade table jobs
                $param = [
                    'file_id' => $file->id,
                    'status' => 3,
                    'time_confirm' => Carbon::now(),
                ];
                return app()->make(JobRepositoryInterface::class)->update($param, $job->id);
            }
            // Failed save to database
            return null;
        } catch (\Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    /**
     * Function check for old jobs
     *
     * @param array $param
     * @return mixed
     */
    public function checkJobOld($param)
    {
        $jobs = Director::where('editor_id', Auth::user()->id)
            ->where('status', 2)->count();
        if ($jobs > 0) {
            return false;
        }
        return true;
    }

    /**
     * Function list all jobs for admin
     *
     * @param array $param
     * @return mixed
     */
    public function listJobForAdmin($param)
    {
        $jobs = Jobs::on();
        if (isset($param['status'])) {
            $jobs = $jobs->where('status', $param['status']);
        }
        if (isset($param['type'])) {
            $jobs = $jobs->where('type', $param['type']);
        }

        return $jobs->orderBy('id', 'DESC')->paginate(config('const.paginate'));
    }

    /**
     * Function editor assign for jobs
     *
     * @param array $param
     * @return mixed
     */
    public function manualAssignJob($param)
    {
        $job = Jobs::find($param['job_id']);
        if (!$job) {
            return false;
        }
        // Check if this job has been assigned to anyone
        if ($job->editor_assign !== 0) {
            return false;
        }
        // update editor for jobs
        return DB::table('jobs')->where('id', $job->id)->update([
             'editor_assign' => $param['editor_id']
        ]);
    }

    /**
     * Function get job in director
     *
     * @param object $dir
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function jobInDir($dir)
    {
        $jobs = Jobs::where('director_id', $dir->id)->with(['files'])->orderBy('id', 'DESC')
            ->get();
        if (is_null($jobs)) {
            return null;
        }
        // Response jobs data
        return $this->addOnPathFileInDir($jobs);
    }

    /**
     * Function add on data path file
     *
     * @param $jobs
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function addOnPathFile($jobs)
    {
        foreach ($jobs as $job) {
            $fileJobs = app()->make(DirectoryRepositoryInterface::class)->dirJob($job->director_id) . '/' . $job->file_jobs;
            $job->file_jobs = $fileJobs;
            //$job->file_jobs_thumbnail = env('APP_URL') . '/' . $job->file_jobs_thumbnail;
            $job->file_jobs_thumbnail = null;
            // if jobs exits file
            if (is_null($job->files)) {
                continue;
            }
            $job->files->path_download = config('const.public_ip') . $job->user_id . '/' . md5($job->user_id) . '/' . $job->id;
            $job->files->image = app()->make(DirectoryRepositoryInterface::class)->dirJob($job->files->director_id) . '/' . $job->files->image;
        }
        // response data
        return $jobs;
    }

    /**
     * Add on param arrtibute jobs in directors
     *
     * @param $jobs
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function addOnPathFileInDir($jobs)
    {
        foreach ($jobs as $job) {
            $fileJobs = app()->make(DirectoryRepositoryInterface::class)->dirJob($job->director_id) . '/' . $job->file_jobs;
            // if jobs exits file
            if (!is_null($job->files)) {
                $job->files->path_download = config('const.public_ip') . $job->user_id . '/' . md5($job->user_id) . '/' . $job->id;
                $job->files->image = app()->make(DirectoryRepositoryInterface::class)->dirJob($job->files->director_id) . '/' . $job->files->image;
            }
            $job->file_jobs_thumbnail = null;
        }
        // response data
        return $jobs;
    }
}
