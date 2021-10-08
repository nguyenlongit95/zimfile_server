<?php

namespace App\Http\Controllers;

use App\Exports\JobsExport;
use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Files\FilesRepositoryInterface;
use App\Repositories\JobRepository\JobRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Supports\ResponseHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminAPIController extends Controller
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
        // check admin panel account
        if (Auth::check()) {
            if (Auth::user()->role !== config('const.admin')) {
                return app()->make(ResponseHelper::class)->unAuthenticated();
            }
        } else {
            return app()->make(ResponseHelper::class)->unAuthenticated();
        }
    }

    /**
     * Controller function list all users
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listUser(Request $request)
    {
        $param = $request->all();
        if (!isset($param['sort'])) {
            $param['sort'] = 'DESC';
        }

        $users = $this->userRepository->listUsers($param['sort']);
        if (empty($users)) {
            return app()->make(ResponseHelper::class)->notFound(trans('message.data_notfound'));
        }

        return app()->make(ResponseHelper::class)->success($users);
    }

    /**
     * Controller function list all directories and detail info of user
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function userDetail(Request $request)
    {
        $param = $request->all();
        $user = $this->userRepository->find($param['id']);
        if (empty($user)) {
            return app()->make(ResponseHelper::class)->notFound(trans('message.data_notfound'));
        }
        $resData = $user;
        // Response data this user
        return  app()->make(ResponseHelper::class)->success($resData);
    }

    /**
     * Controller function list all file of user
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listImage(Request $request)
    {
        $param = $request->all();
        if (!isset($param['directory_id'])) {
            return app()->make(ResponseHelper::class)->notFound(trans('message.data_notfound'));
        }

        $files = $this->filesRepository->listFile($param['directory_id']);
        if (empty($files)) {
            return app()->make(ResponseHelper::class)->notFound(trans('message.data_notfound'));
        }

        return app()->make(ResponseHelper::class)->success($files);
    }

    /**
     * Controller function destroy a file
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function deleteFile(Request $request)
    {
        $param = $request->all();
        $destroy = $this->filesRepository->deleteFile($param['file_id']);
        if ($destroy) {
            return app()->make(ResponseHelper::class)->success($param);
        }

        return app()->make(ResponseHelper::class)->error();
    }

    /**
     * Controller function delete multiple file with admin
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function deleteMultipleFile(Request $request)
    {
        $param = $request->all();
        $arrFileId = json_decode($param['files_id']);
        $deleteStt = null;
        for ($i = 0; $i < count($arrFileId); $i++) {
            if ($this->filesRepository->deleteFile($arrFileId[$i])) {
                $deleteStt[$i]['file_success']['id'] = $arrFileId[$i];
            } else {
                $deleteStt[$i]['file_errors']['id'] = $arrFileId[$i];
            }
        }

        return app()->make(ResponseHelper::class)->success($deleteStt);
    }

    /**
     * Controller function update profile
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function updateProfile(Request $request)
    {
        $param = $request->all();
        $update = $this->userRepository->update($param, Auth::user()->getAuthIdentifier());
        if ($update) {
            return app()->make(ResponseHelper::class)->success($this->userRepository->find(Auth::user()->id));
        }

        return app()->make(ResponseHelper::class)->error();
    }

    /**
     * Controller function create new an user
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createUser(Request $request)
    {
        $param = $request->all();
        // Init data user
        $param['email_verified_at'] = Carbon::now();
        $param['password'] = Hash::make($request->password);
        $param['verified_token'] = $param['password'];
        $param['status'] = config('const.users.status.active');
        $param['total_file'] = 0;
        $param['base_path'] = config('const.base_path');
        $param['role'] = config('const.user');
        $param['remember_token'] = Hash::make($request->password);
        $create = $this->userRepository->create($param);
        if ($create) {
            // Create director in NAS storage
            Storage::disk('ftp')->makeDirectory(config('const.base_path') . $param['name']);
            Storage::disk('ftp')->makeDirectory(config('const.base_path') . $param['name'] . '/done');
            return app()->make(ResponseHelper::class)->success($param);
        }
        // Response error
        return app()->make(ResponseHelper::class)->error();
    }

    /**
     * Controller function create new editor
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createEditor(Request $request)
    {
        $param = $request->all();
        // Init data user
        $param['email_verified_at'] = Carbon::now();
        $param['password'] = Hash::make($request->password);
        $param['verified_token'] = $param['password'];
        $param['status'] = config('const.users.status.active');
        $param['total_file'] = 0;
        $param['base_path'] = config('const.base_path');
        $param['role'] = config('const.editor');
        $param['remember_token'] = Hash::make($request->password);
        $create = $this->userRepository->create($param);
        if ($create) {
            // Create director in NAS storage
            Storage::disk('ftp')->makeDirectory(config('const.base_path') . 'editors/' . $param['name'] . '_' . $create->id);
            return app()->make(ResponseHelper::class)->success($param);
        }
        // Response error
        return app()->make(ResponseHelper::class)->error();
    }

    /**
     * Controller function create a QC
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createQC(Request $request)
    {
        $param = $request->all();
        // Init data user
        $param['email_verified_at'] = Carbon::now();
        $param['password'] = Hash::make($request->password);
        $param['verified_token'] = $param['password'];
        $param['status'] = config('const.users.status.active');
        $param['total_file'] = 0;
        $param['base_path'] = config('const.base_path');
        $param['role'] = config('const.qc');
        $param['remember_token'] = Hash::make($request->password);
        $create = $this->userRepository->create($param);
        if ($create) {
            return app()->make(ResponseHelper::class)->success($param);
        }
        // Response error
        return app()->make(ResponseHelper::class)->error();
    }

    /**
     * Controller function destroy the user
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function deleteUser(Request $request)
    {
        $param = $request->all();
        $user = $this->userRepository->find($param['user_id']);
        if (empty($user)) {
            return app()->make(ResponseHelper::class)->notFound(trans('message.data_notfound'));
        }
        $param['status'] = config('const.users.status.block');
        $deActive = $this->userRepository->update($param, $user->id);
        if ($deActive) {
            return app()->make(ResponseHelper::class)->success($this->userRepository->find($user->id));
        }

        return app()->make(ResponseHelper::class)->error();
    }

    /**
     * Controller function list all jobs for admin
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listJobs(Request $request)
    {
        return app()->make(ResponseHelper::class)->success(
            $this->jobRepository->listJobForAdmin($request->all())
        );
    }

    /**
     * Controller function manual assign jobs of admin
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function manualAssignJob(Request $request)
    {
        $param = $request->all();
        $assign = $this->jobRepository->manualAssignJob($param);
        if ($assign == false) {
            return app()->make(ResponseHelper::class)->error();
        }

        return app()->make(ResponseHelper::class)->success($this->jobRepository->find($param['job_id']));
    }

    /**
     * Controller function export jobs data to csv
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCSV(Request $request)
    {
        // Export excel file by month
        return \Maatwebsite\Excel\Facades\Excel::download(new JobsExport(), 'jobs_'.Carbon::now()->format('Y-m-d').'.xlsx');
    }
}
