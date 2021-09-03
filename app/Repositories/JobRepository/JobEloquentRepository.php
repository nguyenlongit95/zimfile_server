<?php

namespace App\Repositories\JobRepository;

use App\Models\Jobs;
use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Eloquent\EloquentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
            ->orderBy('id', 'DESC')->paginate(config('const.paginate'));
    }
}
