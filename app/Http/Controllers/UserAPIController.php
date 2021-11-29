<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Files\FilesRepositoryInterface;
use App\Repositories\JobRepository\JobRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $dir = self::BASE_PATH . '/' . Auth::user()->name . '/';
        if ($param['level'] > 1 && $param['parent_id'] > 0) {
            // Sub folder
            $parentDir = $this->directoryRepository->find($param['parent_id']);
            $dir = $dir . $parentDir->nas_dir . '/' . $param['director'];
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
        if (!is_array($param['file'])) {
            $file = $request->file('file');
            $validateFile = $this->filesRepository->validateFile($file);
            switch ($validateFile) {
                case 1:
                    // passed
                    $upFile = $this->jobRepository->uploadJobs($file, $param['directory_id']);
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
        // Check each file to see if it is valid or not?
        $listFileFailed = 0;
        for ($i = 0; $i < count($param['file']); $i++) {
            $file = $param['file'][$i];
            if ($this->filesRepository->validateFile($file) != 1) {
                $listFileFailed++;
            }
        }
        if ($listFileFailed > 0) {
            return app()->make(ResponseHelper::class)->validation('Invalid file exists, please check again');
        }
        // Pass all file and upload file
        $listFileUpload = [];
        for ($j = 0; $j < count($param['file']); $j++) {
            $file = $param['file'][$j];
            $upFile = $this->jobRepository->uploadJobs($file, $param['directory_id']);
            if ($upFile == false) {
                return app()->make(ResponseHelper::class)->error();
            }
            $listFileUpload[$j] = $upFile;
        }
        // Response data upload
        return app()->make(ResponseHelper::class)->success($listFileUpload);
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
            $upFile = $this->jobRepository->uploadJobs($file,  $param['directory_id']);
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
                    try {
                        $data->path = json_decode($data->path);
                    } catch (\Exception $exception) {
                        Log::info($exception->getMessage());
                        $data->path= null;
                    }
                }
            }
        }
        $resData['parent_director'] = $this->directoryRepository->getParentDir($param['parent_id']);
        if (!empty($resData['parent_director'])) {
            try {
                $resData['parent_director']->path = json_decode($resData['parent_director']->path);
            } catch (\Exception $exception) {
                Log::info($exception->getMessage());
                $resData['parent_director']->path = null;
            }

        }
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
        // response data
        return app()->make(ResponseHelper::class)->success($jobs);
    }

    /**
     * Controller function show all job in a folder
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listJobInDir(Request $request)
    {
        $param = $request->all();
        if (!isset($param['dir_id']) || is_null($param['dir_id'])) {
            return app()->make(ResponseHelper::class)->notFound(trans('message.file_notfound'));
        }
        // Query find jobs
        $dir = $this->directoryRepository->find($param['dir_id']);
        if (is_null($dir)) {
            return app()->make(ResponseHelper::class)->notFound(trans('message.file_notfound'));
        }
        $data['jobs'] = $this->jobRepository->jobInDir($dir);
        $data['parent_director'] = $this->directoryRepository->getParentDir($param['dir_id']);
        // Response data in jobs
        return app()->make(ResponseHelper::class)->success($data);
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
     * @return |null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function downloadFileProduct(Request $request)
    {
        $param = $request->all();
        $user = User::find($param['user_id']);
        // Check user exits
        if (!$user) {
            return app()->make(ResponseHelper::class)->error();
        }
        // Init path download
        $pathUser = config('const.base_path') . $user->name . '/done/' . $param['date'];
        // Response download file for jobs
        return Storage::disk('ftp')->download(
            Storage::disk('ftp')->files($pathUser)[0]
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

    /**
     * Create new dir for editor
     *
     * @param Request $request
     */
    public function createDirEditor(Request $request)
    {
        $editors = DB::table('users')->where('role', 2)->get();
        if (!empty($editors)) {
            foreach ($editors as $editor) {
                // Create director in NAS
                Storage::disk('ftp')->makeDirectory(config('const.base_path') . '/editors/' . $editor->name . '_' . $editor->id);
                Storage::disk('ftp')->makeDirectory(config('const.base_path') . '/editors/' . $editor->name . '_' . $editor->id . '/done');
            }
        }
    }

    /**
     * Controller function list all directories in done folder
     *
     * @param Request $request
     * @return mixed|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function listDirInDone(Request $request)
    {
        // Connect NAS and list all folder in done folder
        $listDir = Storage::disk('ftp')->directories(config('const.base_path') . Auth::user()->name . '/done');
        // Check data response and response null data
        if (empty($listDir)) {
            return app()->make(ResponseHelper::class)->success();
        }
        // Add to array and response data
        $arrDirectories = array();
        foreach ($listDir as $dir) {
            array_push($arrDirectories, array_reverse(explode('/', $dir))[0]);
        }
        dd($arrDirectories);
        if (count($arrDirectories) > 0) {
            return app()->make(ResponseHelper::class)->success($arrDirectories);
        }
        // Response list all directories
        return app()->make(ResponseHelper::class)->error("Cannot find file products!");
    }

    public function testUploadMultiFiles(Request $request)
    {
        $param = $request->all();
        $listFiles = $param['listFiles'];
        if (is_array($listFiles)) {
            foreach ($listFiles as $file) {
                $file = $file;
                Storage::disk('public')->put(storage_path('disk1/DATA/long_nct_test/dir_08072021/' . $file->getClientOriginalName()), $file);
            }
        }
    }
}
