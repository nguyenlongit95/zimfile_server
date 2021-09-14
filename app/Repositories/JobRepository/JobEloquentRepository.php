<?php

namespace App\Repositories\JobRepository;

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
    public function uploadJobs($file, $directoryId, $type)
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
            $pathFile = $parentDir->nas_dir . $pathFile;
        }
        try {
            $path = self::BASE_PATH . '/' . Auth::user()->name . $pathFile . '/' . $file->getClientOriginalName();
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
            $param['status'] = 1;   // status 1 is not assign
            $param['time_upload'] = Carbon::now();
            $param['time_confirm'] = null;
            $param['time_done'] = null;
            $param['type'] = $type;
            Log::info('User: ' . Auth::user()->email . ' create a job in : ' . $path);
            return $this->create($param);
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * Function list all jobs an user
     *
     * @param array $param
     * @return mixed
     */
    public function userListJobs($param)
    {
        $jobs = Jobs::on();
        if (isset($param['status'])) {
            $jobs = $jobs->where('status', $param['status']);
        }
        if (isset($param['type'])) {
            $jobs = $jobs->where('type', $param['type']);
        }

        return $jobs->where('user_id', Auth::user()->getAuthIdentifier())
            ->orderBy('id', 'DESC')->with(['files'])->paginate(config('const.paginate'));
    }

    /**
     * Function get jobs for editor
     *
     * @param array $param
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getJobsForEditor($param)
    {
        $jobs = Jobs::on();
        if (isset($param['status'])) {
            $jobs = $jobs->where('status', $param['status']);
        }
        if (isset($param['type'])) {
            $jobs = $jobs->where('type', $param['type']);
        }

        return $jobs->where('editor_assign', Auth::user()->getAuthIdentifier())
            ->orWhere('editor_assign', null)
            ->orderBy('id', 'DESC')->paginate(config('const.paginate'));
    }

    /**
     * Function list jobs for QC
     *  QC can only see jobs with status of 2 assigned, 3 confirm
     * @param array $param
     * @return mixed
     */
    public function getJobsForQC($param)
    {
        $jobs = Jobs::on();
        if (isset($param['status'])) {
            $jobs = $jobs->where('status', $param['status']);
        }
        if (isset($param['type'])) {
            $jobs = $jobs->where('type', $param['type']);
        }

        return $jobs->whereIn('status', [2, 3])
            ->orderBy('id', 'DESC')->with(['files'])->paginate(config('const.paginate'));
    }

    /**
     * Function check the work before handing it over to the person in charge
     *
     * @param array $param
     * @return mixed
     */
    public function checkJobsBeforeAssign($param)
    {
        $job = Jobs::find($param['job_id']);
        if (!$job) {
            return false;
        }
        if ($job->editor_assign == Auth::user()->getAuthIdentifier() || !is_null($job->editor_assign)) {
            return false;
        }

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
        $jobs = Jobs::where('editor_assign', Auth::user()->getAuthIdentifier())
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
}
