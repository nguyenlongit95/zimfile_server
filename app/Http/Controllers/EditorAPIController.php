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
        if ($this->jobRepository->checkJobsBeforeAssign($param) == false) {
            return app()->make(ResponseHelper::class)->validation('This job has been assigned.');
        }
        // Check for old job confirm
        if ($this->jobRepository->checkJobOld($param) == false) {
            return app()->make(ResponseHelper::class)->validation('You still have unfinished business, finish it then get more work.');
        }
        // Update jobs data
        $param['editor_assign'] = Auth::user()->getAuthIdentifier();
        $param['status'] = 2;
        $param['id'] = $param['job_id'];
        try {
            $this->jobRepository->update($param, $param['id']);
            return app()->make(ResponseHelper::class)->success($this->jobRepository->find($param['id']));
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
        if (!$request->hasFile('file')) {
            return app()->make(ResponseHelper::class)->validation('Please upload the product file.');
        }
        // Get for job
        $job = $this->jobRepository->find($param['job_id']);
        if (!$job) {
            return app()->make(ResponseHelper::class)->error();
        }
        // Get director
        $dir = $this->directoryRepository->find($job->director_id);
        if (!$dir) {
            return app()->make(ResponseHelper::class)->error();
        }
        // parse path director
        $path = $this->directoryRepository->dirJob($dir->id);
        $uploadFileProduct = $this->jobRepository->uploadFileProduct($request, $path, $dir, $job);
        if (!$uploadFileProduct) {
            return app()->make(ResponseHelper::class)->error();
        }
        // response data success
        return app()->make(ResponseHelper::class)->success($uploadFileProduct);
    }
}
