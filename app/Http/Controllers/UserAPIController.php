<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Files\FilesRepositoryInterface;
use App\Repositories\JobRepository\JobRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserAPIController extends Controller
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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
        if (Auth::check()) {
            if (Auth::user()->status == config('const.users.status.block')) {
                return app()->make(ResponseHelper::class)->unAuthenticated();
            }
        }
    }

    /**
     * Function test connect API
     *
     * @param Request $request
     * @return null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function index(Request $request)
    {
        return app()->make(ResponseHelper::class)->success();
    }

    /**
     * Function test connect NAS Storage
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function connectNAS(Request $request)
    {
        $test = Storage::disk('ftp')->exists('/disk1/DATA/');
        return app()->make(ResponseHelper::class)->success($test);
    }

    /**
     * Controller function create director on nas and vps, database
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createDir(Request $request)
    {
        $param = $request->all();
        if (!Auth::check()) {
            return app()->make(ResponseHelper::class)->unAuthenticated();
        }
        if (!isset($param['director'])) {
            return app()->make(ResponseHelper::class)->error();
        }
        $dir = self::BASE_PATH . '/' . Auth::user()->name;
        if ($param['level'] > 1 && $param['parent_id'] > 0) {
            // Sub folder
            $parentDir = $this->directoryRepository->find($param['parent_id']);
            $dir = $dir . $parentDir->nas_dir . $param['director'];
        } else {
            // Main folder
            $dir = self::BASE_PATH . '/' . Auth::user()->name . '/' . $param['director'];
        }
        try {
            // Create dir on NAS storage
            Storage::disk('ftp')->makeDirectory($dir);
            // Create database directory
            $param['nas_dir'] = $param['director'];
            $param['vps_dir'] = '-';
            $param['user_id'] = Auth::user()->id;
            $param['path'] = json_encode($param['path']);
            $createDir = $this->directoryRepository->create($param);
            $createDir->directory_id = $createDir->id;
            if ($createDir) {
                return app()->make(ResponseHelper::class)->success($createDir);
            }
            // response data has directory
            return app()->make(ResponseHelper::class)->error();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }

    /**
     * Controller function upload a file
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createJobs(Request $request)
    {
        if (!$request->hasFile('file')) {
            return app()->make(ResponseHelper::class)->error(trans('message.data_notfound'));
        }
        $param = $request->all();
        $file = $request->file('file');
        $validateFile = $this->filesRepository->validateFile($file);
        switch ($validateFile) {
            case 1:
                // passed
                $file = $request->file('file');
                $upFile = $this->jobRepository->uploadJobs($file, $param['directory_id'], $param['type']);
                if ($upFile == false) {
                    return app()->make(ResponseHelper::class)->error();
                }
                return app()->make(ResponseHelper::class)->success($upFile);
            case 2:
                return app()->make(ResponseHelper::class)->validation(trans('validation.files.max_file_size'));
                break;
            case 3:
                return app()->make(ResponseHelper::class)->validation(trans('validation.files.file_ext'));
                break;
            default:
                return app()->make(ResponseHelper::class)->error();
                break;
        }
    }

    /**
     * Function controller upload multiple files
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function uploadMultiFile(Request $request)
    {
        if (!$request->hasFile('files')) {
            return app()->make(ResponseHelper::class)->error(trans('message.data_notfound'));
        }
        $files = $request->file('files');
        // Validate file
        $validateImg = 0;
        foreach ($files as $file) {
            if ($this->filesRepository->validateFile($file) !== 1) {
                $validateImg++;
            }
        }
        if ($validateImg > 0) {
            return app()->make(ResponseHelper::class)->validation(trans('validation.files.err_file'));
        }
        // Upload multi file
        $param = $request->all();
        $arrFileUploadSuccess = array();
        foreach ($files as $file) {
            $upFile = $this->jobRepository->uploadJobs($file,  $param['directory_id'], $param['type']);
            $fileUploadStt = null;
            if ($upFile == false) {
                $fileUploadStt = [
                    'name' => $file->getClientOriginalName(),
                    'status_upload' => 'Error',
                    'time_upload' => Carbon::now()
                ];
            } else {
                $fileUploadStt = [
                    'name' => $file->getClientOriginalName(),
                    'status_upload' => 'Success',
                    'time_upload' => Carbon::now()
                ];
            }
            array_push($arrFileUploadSuccess, $fileUploadStt);
        }

        return app()->make(ResponseHelper::class)->success($arrFileUploadSuccess);
    }

    /**
     * Controller function get all directories an user
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listDirectories(Request $request)
    {
        if (!Auth::check()) {
            return app()->make(ResponseHelper::class)->unAuthenticated();
        }
        $param = $request->all();
        $resData['directors'] = $this->directoryRepository->listDirectories(Auth::user()->getAuthIdentifier(), $param['parent_id']);
        if (!empty($resData['directors'])) {
            foreach ($resData['directors'] as $data) {
                if (!is_null($data->path)) {
                    $data->path = json_decode($data->path);
                }
            }
        }
        $resData['parent_director'] = $this->directoryRepository->getParentDir($param['parent_id']);
        return app()->make(ResponseHelper::class)->success(
            $resData
        );
    }

    /**
     * Controller function list all my files
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listMyJobs(Request $request)
    {
        if (!Auth::check()) {
            return app()->make(ResponseHelper::class)->unAuthenticated();
        }
        $param = $request->all();
        $jobs = $this->jobRepository->userListJobs($param);
        if (empty($jobs)) {
            return app()->make(ResponseHelper::class)->notFound(trans('message.file_notfound'));
        }

        return app()->make(ResponseHelper::class)->success($jobs);
    }

    /**
     * Function controller process forgot password
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function changePassword(Request $request)
    {
        $param = $request->all();
        if (!Auth::check()) {
            return app()->make(ResponseHelper::class)->unAuthenticated();
        }

        if (!isset($param['new_password'])) {
            return app()->make(ResponseHelper::class)->error();
        }
        $verifyPassword = $this->userRepository->verifyPassword($param['old_password']);
        if (!$verifyPassword) {
            return app()->make(ResponseHelper::class)->validation(trans('message.file_notfound'));
        }

        $param['password'] = Hash::make($request->new_password);
        if ($this->userRepository->update($param, Auth::user()->id)) {
            return app()->make(ResponseHelper::class)->success(Auth::user());
        } else {
            return app()->make(ResponseHelper::class)->error();
        }
    }

    /**
     * Controller function download file for jobs
     *
     * @param Request $request
     * @param $userId
     * @param $slug
     * @param $jobId
     * @return |null
     */
    public function downloadFileProduct(Request $request, $userId, $slug, $jobId)
    {
        $job = $this->jobRepository->find($jobId);
        if (!$job) {
            return null;
        }
        // Response download file for jobs
        return Storage::disk('ftp')->download(
            $this->directoryRepository->dirJob($job->director_id) . '/' . $job->file_jobs
        );
    }

    /**
     * Controller function get time server
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function timeServer(Request $request)
    {
        return app()->make(ResponseHelper::class)->success(Carbon::now()->toDateTimeString());
    }
}
