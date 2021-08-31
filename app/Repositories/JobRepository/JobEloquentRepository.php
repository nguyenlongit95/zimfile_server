<?php

namespace App\Repositories\JobRepository;

use App\Models\Jobs;
use App\Repositories\Eloquent\EloquentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JobEloquentRepository extends EloquentRepository implements JobRepositoryInterface
{
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
     * @param $directory
     * @param $directoryId
     * @param $type
     * @return bool
     */
    public function uploadJobs($file, $directory, $directoryId, $type)
    {
        try {
            $path = $directory . '/' . $file->getClientOriginalName();
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
