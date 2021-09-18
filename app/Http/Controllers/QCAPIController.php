<?php

namespace App\Http\Controllers;

use App\Mail\MailNotification;
use App\Models\User;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class QCAPIController extends Controller
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
        return app()->make(ResponseHelper::class)->success($this->jobRepository->getJobsForQC($param));
    }

    /**
     * Controller function check jobs tobe confirm
     *
     * @param Request $request
     * @return
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function checkConfirmJobs(Request $request)
    {
        $param = $request->all();
        $job = $this->jobRepository->find($param['job_id']);
        if (!$job) {
            return app()->make(ResponseHelper::class)->error();
        }
        // 0: reject
        if ($param['status'] == 0) {
            if ($this->jobRepository->update($param, $job->id)) {
                return app()->make(ResponseHelper::class)->success(
                    $this->jobRepository->find($param['job_id'])
                );
            } else {
                return app()->make(ResponseHelper::class)->error();
            }
        }
        // 4 done
        if ($param['status'] == 4) {
            try {
                // Get path file product
                $path = 'http://3.12.146.14/api/' . $job->user_id . '/' . md5(Carbon::now()) . '/' . $job->id;
                $user = User::find($job->user_id);
                if ($this->jobRepository->update($param, $job->id)) {
                    // Send email to email user
                    Mail::to($user->email)->send(
                        new MailNotification($path)
                    );
                    // Send email to user
                    return app()->make(ResponseHelper::class)->success(
                        $this->jobRepository->find($param['job_id'])
                    );
                } else {
                    return app()->make(ResponseHelper::class)->error();
                }
            } catch (\Exception $exception) {
                Log::error($exception);
                return app()->make(ResponseHelper::class)->error();
            }
        }
        // Response error
        return app()->make(ResponseHelper::class)->error();
    }
}