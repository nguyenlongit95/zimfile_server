<?php

namespace App\Http\Controllers;

use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Files\FilesRepositoryInterface;
use App\Repositories\JobRepository\JobRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Supports\ResponseHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EditorAPIController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;
    protected $filesRepository;
    protected $directoryRepository;
    protected $jobRepository;

    /**
     * Define global variable base path
     */
    const BASE_PATH = '/disk1/DATA';

    /**
     * UserAPIController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param FilesRepositoryInterface $filesRepository
     * @param DirectoryRepositoryInterface $directoryRepository
     * @param JobRepositoryInterface $jobRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        FilesRepositoryInterface $filesRepository,
        DirectoryRepositoryInterface $directoryRepository,
        JobRepositoryInterface $jobRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->filesRepository = $filesRepository;
        $this->directoryRepository = $directoryRepository;
        $this->jobRepository = $jobRepository;
    }

    /**
     * Controller function list my jobs for editor
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listJobs(Request $request)
    {
        $param = $request->all();
        return app()->make(ResponseHelper::class)->success($this->jobRepository->getJobsForEditor($param));
    }

    /**
     * Controller function editor get a job
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getJob(Request $request)
    {
        $param = $request->all();
        $checkJobsRejected = $this->jobRepository->checkJobsRejected($param);
        if (!empty($checkJobsRejected)) {
            $jobPath = $this->directoryRepository->dirJob($checkJobsRejected->id);
            $checkJobsRejected->path_job_lan = str_replace('disk1/','', config('const.public_nas_ip') . $jobPath);
            $checkJobsRejected->path_job_online = str_replace('disk1/','', config('const.public_nas_address') . $jobPath);
            return app()->make(ResponseHelper::class)->success($checkJobsRejected);
        }
        $checkJobs = $this->jobRepository->checkJobsBeforeAssign($param);
        if ($checkJobs != null) {
            $jobPath = $this->directoryRepository->dirJob($checkJobs->id);
            $checkJobs->path_job_lan = str_replace('disk1/','', config('const.public_nas_ip') . $jobPath);
            $checkJobs->path_job_online = str_replace('disk1/','', config('const.public_nas_address') . $jobPath);
            return app()->make(ResponseHelper::class)->success($checkJobs);
        }
        $dir = $this->jobRepository->getJobsForEditor($param);
        if (empty($dir) || is_null($dir)) {
            return app()->make(ResponseHelper::class)->validation('Jobs not found.');
        }
        $jobPath = $this->directoryRepository->dirJob($dir->id);
        $jobInDirect = $this->jobRepository->jobInDir($dir);
        $dir->path_job_lan = str_replace('disk1/','', config('const.public_nas_ip') . $jobPath);
        $dir->path_job_online = str_replace('disk1/','', config('const.public_nas_address') . $jobPath);
        $param['editor_assign'] = Auth::user()->getAuthIdentifier();
        $param['status'] = 2;
        if (!empty($jobInDirect)) {
            foreach ($jobInDirect as $item) {
                // Update jobs data
                $this->jobRepository->update($param, $item->id);
            }
        }
        // Update folder
        try {
            $param['editor_id'] = Auth::user()->getAuthIdentifier();
            // Copy file job to dir of editors
            $this->directoryRepository->update($param, $dir->id);
            return app()->make(ResponseHelper::class)->success($dir);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }

    /**
     * Controller function download file job for editor
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function downloadJob(Request $request)
    {
        $param = $request->all();
        // Find an job
        $job = $this->jobRepository->find($param['job_id']);
        if (!$job) {
            return app()->make(ResponseHelper::class)->error();
        }
        // Get director of file job
        $dir = $this->directoryRepository->dirJob($job->director_id);
        if (!$dir) {
            return app()->make(ResponseHelper::class)->error();
        }
        // Response download file
        return Storage::disk('ftp')->download($dir . '/' . $job->file_jobs);
    }

    /**
     * Function upload file product and switch mod for job to confirm status
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function confirmJob(Request $request)
    {
        $param = $request->all();
        // Get for job
        $dir = $this->directoryRepository->find($param['dir_id']);
        if (!$dir) {
            return app()->make(ResponseHelper::class)->error();
        }
        $jobInDIr = $this->jobRepository->jobInDir($dir);
        if (!empty($jobInDIr)) {
            foreach ($jobInDIr as $job) {
                $dataJob['time_confirm'] = Carbon::now();
                $dataJob['time_upload'] = Carbon::now();
                $dataJob['status'] = 3;
                $this->jobRepository->update($dataJob, $job->id);
            }
        }
        // Data init update job to confirm
        $data['status'] = 3;
        try {
            // Update jobs to be confirm
            if (!$this->directoryRepository->update($data, $dir->id)) {
                return app()->make(ResponseHelper::class)->error();
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        // response data success
        return app()->make(ResponseHelper::class)->success($data);
    }

    /**
     * Controller function list a notifications has active
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getNotifications(Request $request)
    {
        $notifications = DB::table('notifications')->where('status', 1)->orderBy('id', 'DESC')->first();
        // Data notfound
        if (empty($notifications)) {
            return app()->make(ResponseHelper::class)->notFound("Notification not found");
        }
        // Response data
        return app()->make(ResponseHelper::class)->success($notifications);
    }

    /**
     * Controller function cancel job
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function cancelJob(Request $request)
    {
        $param = $request->all();
        $dir = $this->directoryRepository->find($param['dir_id']);
        if (empty($dir)) {
            return app()->make(ResponseHelper::class)->notFound("Director not found");
        }
        $cancel = $this->directoryRepository->cancelJob($dir->id);
        if (!$cancel) {
            return app()->make(ResponseHelper::class)->error();
        }
        return app()->make(ResponseHelper::class)->success($this->directoryRepository->find($param['dir_id']));
    }

    /**
     * Controller function list old jobs for editors
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listMyJobs(Request $request)
    {
        $jobs = $this->directoryRepository->listMyJobs();
        return app()->make(ResponseHelper::class)->success($jobs);
    }
}
