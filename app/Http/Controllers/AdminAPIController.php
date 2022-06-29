<?php

namespace App\Http\Controllers;

use App\Exports\JobsExport;
use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Files\FilesRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\JobRepository\JobRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Supports\ResponseHelper;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Validations\Validation;

class AdminAPIController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;
    protected $filesRepository;
    protected $directoryRepository;
    protected $jobRepository;
    protected $groupRepository;

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
     * @param GroupRepositoryInterface $groupRepository
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        FilesRepositoryInterface $filesRepository,
        DirectoryRepositoryInterface $directoryRepository,
        JobRepositoryInterface $jobRepository,
        GroupRepositoryInterface $groupRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->filesRepository = $filesRepository;
        $this->directoryRepository = $directoryRepository;
        $this->jobRepository = $jobRepository;
        $this->groupRepository = $groupRepository;
        // check admin panel account
        if (Auth::check()) {
            if (Auth::user()->role !== config('const.admin') || Auth::user()->role !== config('const.sub_admin')) {
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
     * @param int $id of editors
     * @return mixed
     */
    public function manualAssignJob(Request $request, $id)
    {
        $param = $request->all();
        $editor = $this->userRepository->find($id);
        $listJobOfEditor = $this->jobRepository->listJobForEditor($editor->id);
        $listJobNotEditor = $this->jobRepository->listJobsNotEditor($editor->id);
        return view('admin.editors.manual_assign', compact(
            'param', 'editor', 'listJobOfEditor', 'listJobNotEditor'
        ));
    }

    /**
     * Controller function export jobs data to csv
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCSV(Request $request)
    {
        $param = $request->all();
        if (isset($param['month'])) {
            // Export excel file by month
            return \Maatwebsite\Excel\Facades\Excel::download(new JobsExport($param), 'jobs_'.Carbon::now()->subMonths($param['month'])->format('Y-m-d').'.xlsx');
        }
        if (isset($param['date'])) {
            if (!is_null($param['date_from']) && !is_null($param['date_to'])) {
                $dateFrom = Carbon::create($param['date_from']);
                $dateTo = Carbon::create($param['date_to']);
                // Export excel file by month
                return \Maatwebsite\Excel\Facades\Excel::download(new JobsExport($param), 'jobs_'.$dateFrom->format('d_m_Y') . '_to_' . $dateTo->format('d_m_Y') .'.xlsx');
            } else {
                return redirect('/admin/dashboard')->with('thong_bao', 'Please chose a day!');
            }
        }
    }

    /**
     * @return Factory|View
     */
    public function DashBoard()
    {
        return view('admin.index');
    }

    /**
     * Logout function
     *
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/admin/login');
    }

    /**
     * Controller function list all Customer
     *
     * @param Request $request
     * @return mixed
     */
    public function listCustomers(Request $request)
    {
        $param = $request->all();
        $customers = $this->userRepository->listCustomers($param);
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Controller function list all Customer
     *
     * @param Request $request
     * @return mixed
     */
    public function searchCustomers(Request $request)
    {
        $param = $request->all();
        if (isset($param['create']) && $param['create'] != null) {
            if ($this->addCustomers($request)) {
                return redirect('/admin/customers')->with('thong_bao', 'User account creation successful');
            }
            return redirect('/admin/customers')->with('thong_bao', 'User account creation failed, check the system again');
        }
        if (isset($param['search']) && $param['search'] != null) {
            $customers = $this->userRepository->listCustomers($param);
            return view('admin.customers.index', compact('customers'));
        }
    }

    /**
     * Controller function add new customer
     *
     * @param Request $request
     * @return mixed
     */
    public function addCustomers(Request $request)
    {
        Validation::validationUsers($request);
        // Initialize user data
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
            Storage::disk('ftp')->makeDirectory(config('const.base_path') . '/clients/' . $param['name']);
            Storage::disk('ftp')->makeDirectory(config('const.base_path') . '/clients/' . $param['name'] . '/done');
            return true;
        }
        // Error response redirect
        return false;
    }

    /**
     * Controller function render view edit a customer
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function editCustomers(Request $request, $id)
    {
        $customer = $this->userRepository->find($id);
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Controller function update a customers
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function updateCustomers(Request $request, $id)
    {
        $customer = $this->userRepository->find($id);
        if (empty($customer)) {
            return redirect('/admin/customers/')->with('thong_bao', 'User account not found.');
        }
        Validation::validationUsers($request);
        $param = $request->all();
        if ($param['password'] != null) {
            $param['password'] = Hash::make($request->password);
        } else {
            unset($param['password']);
        }
        if ($this->userRepository->update($param, $id)) {
            return redirect('/admin/customers/edit/' . $id)->with('thong_bao', 'User account success.');
        }
        // Response error message
        return redirect('/admin/customers/edit' . $id)->with('thong_bao', 'User account errors, please check again.');
    }

    /**
     * Controller function soft delete a customer
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function deleteCustomers(Request $request, $id)
    {
        // Check customers exits
        $customer = $this->userRepository->find($id);
        if (empty($customer)) {
            return redirect('/admin/customers/')->with('thong_bao', 'User account not found.');
        }
        try {
            // Change deleted_at
            DB::table('users')->where('id', $id)->update([
                'deleted_at' => Carbon::now()
            ]);
            // Update directors of customers
            DB::table('directors')->where('user_id', $customer->id)
                ->where('status', 2)->update([
                    'status' => 1,
                    'editor_id' => 0
                ]);
            return redirect('/admin/customers/')->with('thong_bao', 'Delete customer success.');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        // Response error message
        return redirect('/admin/customers/')->with('thong_bao', 'User account delete errors, please check again.');
    }

    /**
     * Controller function list all editors
     *
     * @param Request $request
     * @return mixed
     */
    public function listEditors(Request $request)
    {
        $param = $request->all();
        $editors = $this->userRepository->listEditors($param);
        return view('admin.editors.index', compact('editors'));
    }

    /**
     * Controller function search editors
     *
     * @param Request $request
     * @return mixed
     */
    public function searchEditors(Request $request)
    {
        $param = $request->all();
        if (isset($param['create']) && $param['create'] != null) {
            if ($this->addEditor($request)) {
                return redirect('/admin/editors')->with('thong_bao', 'User account creation successful');
            }
            return redirect('/admin/editors')->with('thong_bao', 'User account creation failed, check the system again');
        }
        if (isset($param['search']) && $param['search'] != null) {
            $editors = $this->userRepository->listEditors($param);
            return view('admin.editors.index', compact('editors'));
        }
    }

    /**
     * Controller function add new customer
     *
     * @param Request $request
     * @return mixed
     */
    public function addEditor(Request $request)
    {
        Validation::validationUsers($request);
        // Initialize user data
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
            try {
                // Create director in NAS storage
                Storage::disk('ftp')->makeDirectory(config('const.base_path') . '/editors/' . $create->name . '_' . $create->id);
                Storage::disk('ftp')->makeDirectory(config('const.base_path') . '/editors/' . $create->name . '_' . $create->id . '/done');
                return true;
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return false;
            }
            return true;
        }
        // Error response redirect
        return false;
    }

    /**
     * Controller function update editors
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function updateEditors(Request $request, $id)
    {
        $editor = $this->userRepository->find($id);
        if (empty($editor)) {
            return redirect('/admin/editors/')->with('thong_bao', 'Editor account not found.');
        }
        Validation::validationUsers($request);
        $param = $request->all();
        if ($param['password'] != null) {
            $param['password'] = Hash::make($request->password);
        } else {
            unset($param['password']);
        }
        if ($this->userRepository->update($param, $id)) {
            return redirect('/admin/editors/')->with('thong_bao', 'Editor account success.');
        }
        // Response error message
        return redirect('/admin/editors/')->with('thong_bao', 'Editor account errors, please check again.');
    }

    /**
     * Controller function delete editor
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function deleteEditor(Request $request, $id)
    {
        // Check Editors has exits
        $editor = $this->userRepository->find($id);
        if (empty($editor)) {
            return redirect('/admin/editors/')->with('thong_bao', 'Editor account not found.');
        }
        try {
            // Soft delete editor
            DB::table('users')->where('id', $editor->id)->update([
                 'deleted_at' => Carbon::now()
            ]);
            // Remove task of the editor
            DB::table('directors')->where('editor_id', $editor->id)->where('status', 2)->update([
                'editor_id' => null,
                'status' => 1
            ]);
            return redirect('/admin/editors/')->with('thong_bao', 'Editor has been deleted.');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        // Response error system
        return redirect('/admin/editors/')->with('thong_bao', 'Editor account errors.');
    }

    /**
     * Controller function assign jobs to editors
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function assignJobs(Request $request, $id)
    {
        // Check Editors has exits
        $editor = $this->userRepository->find($id);
        if (empty($editor)) {
            return redirect('/admin/editors/')->with('thong_bao', 'Editor account not found.');
        }
        if (!is_null($editor->deleted_at)) {
            return redirect('/admin/editors/')->with('thong_bao', 'Editor account not found.');
        }
        // Assign jobs for editor
        $jobs = $this->jobRepository->adminAssingJob($id);
        if ($jobs == true) {
            return redirect('/admin/editors/')->with('thong_bao', 'Assign jobs for editor success.');
        }
        // Response data errors
        return redirect('/admin/editors/')->with('thong_bao', 'Assign jobs for editor failed system.');
    }

    /**
     * Controller function list all QC
     *
     * @param Request $request
     * @return mixed
     */
    public function listQC(Request $request)
    {
        $param = $request->all();
        $qcs = $this->userRepository->listQC($param);
        return view('admin.qc.index', compact('qcs'));
    }

    /**
     * Controller function search QC
     *
     * @param Request $request
     * @return mixed
     */
    public function searchQC(Request $request)
    {
        $param = $request->all();
        if (isset($param['create']) && $param['create'] != null) {
            if ($this->addQC($request)) {
                return redirect('/admin/qc')->with('thong_bao', 'User account creation successful');
            }
            return redirect('/admin/qc')->with('thong_bao', 'User account creation failed, check the system again');
        }
        if (isset($param['search']) && $param['search'] != null) {
            $qcs = $this->userRepository->listQC($param);
            return view('admin.qc.index', compact('qcs'));
        }
    }

    /**
     * Controller function add new customer
     *
     * @param Request $request
     * @return mixed
     */
    public function addQC(Request $request)
    {
        Validation::validationUsers($request);
        // Initialize user data
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
            return true;
        }
        // Error response redirect
        return false;
    }

    /**
     * Controller function edit for qc
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function editQC(Request $request, $id)
    {
        $qc = $this->userRepository->find($id);
        if (empty($qc)) {
            return redirect('/admin/qc/')->with('thong_bao', 'QC account not found.');
        }
        Validation::validationUsers($request);
        $param = $request->all();
        if ($param['password'] != null) {
            $param['password'] = Hash::make($request->password);
        } else {
            unset($param['password']);
        }
        if ($this->userRepository->update($param, $id)) {
            return redirect('/admin/qc/')->with('thong_bao', 'QC account success.');
        }
        // Response error message
        return redirect('/admin/qc/')->with('thong_bao', 'QC account errors, please check again.');
    }

    /**
     * Controller function delete QC
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function deleteQC(Request $request, $id)
    {
        // Check QC exits
        $qc = $this->userRepository->find($id);
        if (empty($qc)) {
            return redirect('/admin/qc/')->with('thong_bao', 'QC account not found.');
        }
        // Soft delete editors
        $param['status'] = 0;
        if ($this->userRepository->update($param, $id)) {
            return redirect('/admin/qc/')->with('thong_bao', 'QC account deleted.');
        }
        // Response error system
        return redirect('/admin/qc/')->with('thong_bao', 'QC account errors.');
    }

    /**
     * Controller list all jobs for dashboard
     *
     * @param Request $request
     * @return mixed
     */
    public function listJobsDashBoard(Request $request)
    {
        $jobs = $this->directoryRepository->getAllJobsDashBoard(null);
        $customers = $this->userRepository->listUsers('ASC');
        $editors = $this->userRepository->editors('ASC');
        return view('admin.jobs.index', compact('jobs', 'customers', 'editors'));
    }

    /**
     * Controller function search jobs for dashboard
     *
     * @param Request $request
     * @return Factory|View
     */
    public function searchJobsDashBoard(Request $request)
    {
        $param = $request->all();
        $jobs = $this->directoryRepository->getAllJobsDashBoard($param);
        $customers = $this->userRepository->listUsers('ASC');
        $editors = $this->userRepository->editors('ASC');
        return view('admin.jobs.index', compact('jobs', 'customers', 'editors'));
    }

    /**
     * Controller render view assign user belong qc
     *
     * @param Request $request
     * @param $id
     * @return Factory|View|\Illuminate\Http\RedirectResponse
     */
    public function assignUsers(Request $request, $id)
    {
        $qc = $this->userRepository->find($id);
        if (empty($qc)) {
            return redirect()->back()->with('status', 'Cannot find qc');
        }
        $userNotAssign = $this->userRepository->getUserNotAssign();
        $userBelongMe = $this->userRepository->userBelongQC($id);
        return view('admin.qc.assign', compact('qc', 'userBelongMe', 'userNotAssign'));
    }

    /**
     * Controller function remove a user belong qc
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeBelong(Request $request, $id)
    {
        $remove = $this->userRepository->removeUserAssign($id);
        if ($remove) {
            return redirect()->back()->with('thong_bao', 'Remove user success.');
        }
        return redirect()->back()->with('thong_bao', 'Remove user failed, check again system.');
    }

    /**
     * Controller function assign user belong to qc
     *
     * @param Request $request
     * @param int $qcId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function assigningUsers(Request $request, $qcId)
    {
        $param = $request->all();
        if (isset($param['user_id'])) {
            foreach ($param['user_id'] as $userId) {
                $this->userRepository->assignedUser($userId, $qcId);
            }
            return redirect('/admin/qc/assign-user/' . $qcId)->with('thong_bao', 'Assign user success.');
        } else {
            return redirect('/admin/qc/assign-user/' . $qcId)->with('thong_bao', 'Please select user.');
        }
    }

    /**
     * Controller function list all groups and render view show all the groups
     *
     * @param Request $request
     * @return Factory|View
     */
    public function listGroups(Request $request)
    {
        $groups = $this->groupRepository->listAll();
        return view('admin.groups.index', compact('groups'));
    }

    /**
     * Controller function create new group
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createGroup(Request $request)
    {
        $param = $request->all();
        // Validation param group_name
        if (!isset($param['group_name']) || is_null($param['group_name'])) {
            return redirect('/admin/groups/')->with('thong_bao', 'Please enter name of group.');
        }
        $create = $this->groupRepository->create($param);
        if ($create) {
            // Create success end return success msg
            return redirect('/admin/groups/')->with('thong_bao', 'Create new groups success.');
        }
        // Response error and check system
        return redirect('/admin/groups/')->with('thong_bao', 'Create new groups failed, please check system again.');
    }

    /**
     * Controller function update name a group
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $param = $request->all();
        // Validation param group_name
        if (!isset($param['group_name']) || is_null($param['group_name'])) {
            return redirect('/admin/groups/')->with('thong_bao', 'Please enter name of group.');
        }
        // Update the data
        $update = $this->groupRepository->update($param, $id);
        if ($update) {
            // Create success end return success msg
            return redirect('/admin/groups/')->with('thong_bao', 'Update groups success.');
        }
        // Response error and check system
        return redirect('/admin/groups/')->with('thong_bao', 'Update groups failed, please check system again.');
    }

    /**
     * Controller function assign customer for group
     *
     * @param Request $request
     * @param int $id
     * @return Factory|View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function assignCustomer(Request $request, $id)
    {
        $group = $this->groupRepository->find($id);
        // Check group already
        if (!$group) {
            return redirect('/admin/groups/')->with('thong_bao', 'Cannot find the group.');
        }
        // list user group
        $listUserFreeGroup = $this->groupRepository->listUserFreeGroup($group->id);
        $listUserInGroup = $this->groupRepository->listUserInGroup($group->id);
        // Render view data
        return view('admin.groups.assign', compact('group', 'listUserFreeGroup', 'listUserInGroup'));
    }

    /**
     * Controller function assign customer to a group
     *
     * @param Request $request
     * @param $groupId
     * @param $customerId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function assignCustomerToGroup(Request $request, $groupId, $customerId)
    {
        $param['group_id'] = $groupId;
        $param['time_assign'] = Carbon::now();
        if ($this->userRepository->update($param, $customerId)) {
            return redirect('/admin/groups/' . $groupId . '/assign-customers')->with('thong_bao', 'Assign customer to group success.');
        }
        return redirect('/admin/groups/' . $groupId . '/assign-customers')->with('thong_bao', 'Assign failed, please check again.');
    }

    /**
     * Controlle function remove customer in group
     *
     * @param Request $request
     * @param $groupId
     * @param $customerId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeCustomerInGroup(Request $request, $groupId, $customerId)
    {
        $param['group_id'] = null;
        if ($this->userRepository->update($param, $customerId)) {
            return redirect('/admin/groups/' . $groupId . '/assign-customers')->with('thong_bao', 'Remove customer to group success.');
        }
        return redirect('/admin/groups/' . $groupId . '/assign-customers')->with('thong_bao', 'Remove failed, please check again.');
    }

    /**
     * Controller function lst and render view group
     *
     * @param Request $request
     * @param int $id
     * @return Factory|View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editorAssignGroup(Request $request, $id)
    {
        $editor = $this->userRepository->find($id);
        // Check editor already
        if (!$editor) {
            return redirect('/admin/editors/')->with('thong_bao', 'Editor not found.');
        }
        // List group
        $listGroupsForMe = $this->groupRepository->listGroupsForEditor($editor->id);
        $listGroupsNotForMe = $this->groupRepository->listGroupsNotForEditor($editor->id);
        // Render view
        return view('admin.editors.assign', compact('editor', 'listGroupsForMe', 'listGroupsNotForMe'));
    }

    /**
     * Controller function assign group for editor
     *
     * @param Request $request
     * @param int $editorId
     * @param int $groupId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function assignGroupForEditor(Request $request, $editorId, $groupId)
    {
        if ($this->groupRepository->assignGroupForEditor($editorId, $groupId)) {
            return redirect('/admin/editors/assign-group/' . $editorId)->with('thong_bao', 'Assign group success.');
        }
        // Response assign failed
        return redirect('/admin/editors/assign-group/' . $editorId)->with('thong_bao', 'Assign group failed, please check system again.');
    }

    /**
     * Controller function remove a group for editor
     *
     * @param Request $request
     * @param int $editorGroupId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeGroupForEditor(Request $request, $editorGroupId)
    {

        if ($this->groupRepository->removeGroupForEditor($editorGroupId)) {
            return redirect()->back()->with('thong_bao', 'Remove group success.');
        }
        // Response assign failed
        return redirect()->back()->with('thong_bao', 'Remove group failed, please check system again.');
    }

    public function deleteGroup(Request $request, $id)
    {
        $group = $this->groupRepository->find($id);
        if (empty($group)) {
            return redirect('/admin/groups/')->with('thong_bao', 'Groups not found.');
        }
        $checkDataDependent = $this->groupRepository->checkGroupDependent($group->id);
        if ($checkDataDependent == false) {
            return redirect('/admin/groups/')->with('thong_bao', 'This record cannot be deleted because it has dependent data.');
        }
        $delete = $this->groupRepository->delete($group->id);
        if ($delete) {
            return redirect('/admin/groups/')->with('thong_bao', 'Group deleted.');
        }
        return redirect('/admin/groups/')->with('thong_bao', 'Group delete failed.');
    }

    /**
     * Controller function list sub admin
     *
     * @param Request $request
     * @return Factory|View
     */
    public function listSubAdmin(Request $request)
    {
        $listSubAdmin = $this->userRepository->listSubAdmin();
        return view('admin.subadmin.index', compact('listSubAdmin'));
    }

    /**
     * Controller function list notification
     *
     * @param Request $request
     * @return Factory|View
     */
    public function listNotifications(Request $request)
    {
        $listNotifications = DB::table('notifications')->orderBy('id', 'DESC')
            ->paginate(config('const.paginate'));
        return view('admin.notifications.index', compact('listNotifications'));
    }

    /**
     * Controller function create new notification
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createNotification(Request $request)
    {
        $param = $request->all();
        if (is_null($param['notifications']) || $param['notifications'] == '') {
            return redirect('/admin/notifications/')->with('thong_bao', 'Please enter the message content.');
        }
        try {
            // Update all another record status un active
            if ($param['status'] == 1) {
                DB::table('notifications')->update([
                    'status' => 0,
                ]);
            }
            DB::table('notifications')->insert([
                'notifications' => $param['notifications'],
                'status' => $param['status'],
            ]);
            return redirect('/admin/notifications/')->with('thong_bao', 'Create new notification success.');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect('/admin/notifications/')->with('thong_bao', 'Error systems, please check again.');
        }
    }

    /**
     * Controller function edit a notifications
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editNotification(Request $request, $id)
    {
        $param = $request->all();
        if (is_null($param['notifications']) || $param['notifications'] == '') {
            return redirect('/admin/notifications/')->with('thong_bao', 'Please enter the message content.');
        }
        try {
            DB::table('notifications')->where('id', $id)->update([
                'notifications' => $param['notifications'],
                'status' => $param['status'],
            ]);
            // Update all another record status un active
            if ($param['status'] == 1) {
                DB::table('notifications')->where('id', '<>', $id)->update([
                    'status' => 0,
                ]);
            }
            return redirect('/admin/notifications/')->with('thong_bao', 'Update notification success.');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect('/admin/notifications/')->with('thong_bao', 'Error systems, please check again.');
        }
    }

    /**
     * Controller function delete a notifications
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteNotification(Request $request, $id)
    {
        try {
            DB::table('notifications')->where('id', $id)->delete();
            return redirect('/admin/notifications/')->with('thong_bao', 'Delete a notification success.');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect('/admin/notifications/')->with('thong_bao', 'Error systems, please check again.');
        }
    }

    /**
     * Controller function create new sub admin
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createSubAdmin(Request $request)
    {
        $param = $request->all();
        $param['status'] = config('const.users.status.active');
        $param['total_file'] = 0;
        $param['group_id'] = 0;
        $param['role'] = config('const.sub_admin');
        $param['password'] = Hash::make($param['password']);
        Validation::validationUsers($request);
        $createSubAdmin = $this->userRepository->create($param);
        if ($createSubAdmin) {
            // Response success insert record
            return redirect('/admin/sub-admin/')->with('thong_bao', 'Create sub admin success.');
        }
        // Response error system
        return redirect('/admin/sub-admin/')->with('thong_bao', 'System error, please check again');
    }

    /**
     * Controller function edit sub admin
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editSubAdmin(Request $request, $id)
    {
        $subAdmin = $this->userRepository->find($id);
        // Check sub admin already exit's
        if (empty($subAdmin)) {
            return redirect('/admin/sub-admin/')->with('thong_bao', 'Cannot find sub admin.');
        }
        // Check change password and unset in the field
        $param = $request->all();
        if (is_null($param['password']) || $param['password'] == '') {
            unset($param['password']);
        }
        if (!is_null($param['password']) && $param['password'] != '') {
            $param['password'] = Hash::make($param['password']);
        }
        // Update the sub admin
        $updateSubAdmin = $this->userRepository->update($param, $subAdmin->id);
        if ($updateSubAdmin) {
            // Response success insert record
            return redirect('/admin/sub-admin/')->with('thong_bao', 'Edit sub admin success.');
        }
        // Response error system
        return redirect('/admin/sub-admin/')->with('thong_bao', 'System error, please check again');
    }

    /**
     * Controller function delete a sub admin
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteSubAdmin(Request $request, $id)
    {
        $subAdmin = $this->userRepository->find($id);
        // Check sub admin already exit's
        if (empty($subAdmin)) {
            return redirect('/admin/sub-admin/')->with('thong_bao', 'Cannot find sub admin.');
        }
        // Destroy the sub admin
        $destroySubAdmin = $this->userRepository->delete($subAdmin->id);
        if ($destroySubAdmin) {
            // Response success insert record
            return redirect('/admin/sub-admin/')->with('thong_bao', 'Delete sub admin success.');
        }
        // Response error system
        return redirect('/admin/sub-admin/')->with('thong_bao', 'System error, please check again');
    }

    /**
     * Controller function render view create jobs for sub admin
     *
     * @param Request $request
     * @return Factory|View
     */
    public function subAdminCreateJobs(Request $request)
    {
        $listUsers = $this->userRepository->listUsers('ASC');
        return view('admin.subadmin.createJob', compact('listUsers'));
    }

    /**
     * Controller function check and get main folder in the day
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function subAdminGetMainFolder(Request $request)
    {
        $param = $request->all();
        // Check user already
        $user = $this->userRepository->find($param['user']);
        if (!$user) {
            return app()->make(ResponseHelper::class)->notfound('User not found.');
        }
        // Get the main folder in the day
        $dir = $this->directoryRepository->checkDirOnDay($user->id);
        // Response data
        return app()->make(ResponseHelper::class)->success($dir);
    }

    /**
     * Controller function create a main folder
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function subAdminCreateMainFolder(Request $request)
    {
        $param = $request->all();
        $param['director'] = 'dir_' . Carbon::now('Asia/Ho_Chi_Minh')->format('mdY');
        $user = $this->userRepository->find($param['user']);
        if (empty($user)) {
            return app()->make(ResponseHelper::class)->error();
        }
        // Main folder
        $dir = self::BASE_PATH . '/clients/' . $user->name . '/' . $param['director'];
        try {
            // Create dir on NAS storage
            Storage::disk('ftp')->makeDirectory($dir);
            // Create database directory
            $param['nas_dir'] = $param['director'];
            $param['vps_dir'] = '-';
            $param['user_id'] = $user->id;
            $param['path'] = '';
            $createDir = $this->directoryRepository->create($param);
            $createDir->directory_id = $createDir->id;
            if ($createDir) {
                return app()->make(\App\Helpers\ResponseHelper::class)->success($createDir);
            }
            // response data has directory
            return app()->make(ResponseHelper::class)->error();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }

    /**
     * Controller function create job by SubAdmin
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function subAdminCreateJob(Request $request)
    {
        $param = $request->all();
        $user = $this->userRepository->find($param['user']);
        $dir = self::BASE_PATH . '/clients/' . $user->name . '/';
        // Sub folder
        $parentDir = $this->directoryRepository->find($param['idMainFolder']);
        $dir = $dir . $parentDir->nas_dir . '/' . $param['director'];
        $param['type'] = $param['typeJob'];
        $param['status'] = 1;
        $param['parent_id'] = $param['idMainFolder'];
        $param['level'] = 2;
        try {
            // Create dir on NAS storage
            Storage::disk('ftp')->makeDirectory($dir);
            // Create database directory
            $param['user_id'] = $user->id;
            $param['nas_dir'] = $param['director'];
            $param['vps_dir'] = '-';
            $param['path'] = json_encode([
                'id' => $parentDir->id,
                'name' => $parentDir->nas_dir,
            ]);
            $param['customer_note'] = $param['noteJob'];
            $createDir = $this->directoryRepository->create($param);
            $createDir->directory_id = $createDir->id;
            if ($createDir) {
                return app()->make(\App\Helpers\ResponseHelper::class)->success($createDir);
            }
            // response data has directory
            return app()->make(ResponseHelper::class)->error();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return app()->make(ResponseHelper::class)->error();
        }
    }

    public function subAdminCreateMultiJob(Request $request)
    {
        $param = $request->all();
        $user = $this->userRepository->find($param['user']);
        $dir = self::BASE_PATH . '/clients/' . $user->name . '/';
        // Sub folder
        $parentDir = $this->directoryRepository->find($param['idMainFolder']);
        $dir = $dir . $parentDir->nas_dir . '/';
        $arrErr = 0;
        if ($param['numberJobToBeCreated'] > 0) {
            for ($i = 1; $i<= $param['numberJobToBeCreated']; $i++) {
                $param['type'] = $param['typeJob'];
                $param['status'] = 1;
                $param['parent_id'] = $param['idMainFolder'];
                $param['level'] = 2;
                try {
                    // Create dir on NAS storage
                    Storage::disk('ftp')->makeDirectory($dir . $param['director'][$i]);
                    // Create database directory
                    $param['user_id'] = $user->id;
                    $param['nas_dir'] = $param['director'][$i];
                    $param['vps_dir'] = '-';
                    $param['path'] = json_encode([
                        'id' => $parentDir->id,
                        'name' => $parentDir->nas_dir,
                    ]);
                    $param['customer_note'] = $param['noteJob'];
                    $createDir = $this->directoryRepository->create($param);
                    $createDir->directory_id = $createDir->id;
                    if (!$createDir) {
                        $arrErr++;
                    }
                } catch (\Exception $exception) {
                    Log::error($exception->getMessage());
                    return app()->make(ResponseHelper::class)->error();
                }
            }
        }
        // Response to client error or success
        if ($arrErr > 0) {
            return app()->make(ResponseHelper::class)->error();
        } else {
            return app()->make(ResponseHelper::class)->success();
        }
    }

    /**
     * Controller function remove manual assign jobs for the editors
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeManualAssign(Request $request, $id)
    {
        $job = $this->directoryRepository->find($id);
        if (empty($job)) {
            return redirect()->back()->with('thong_bao', 'Jobs not found.');
        }
        $param['status'] = 1;
        $param['editor_id'] = null;
        $remove = $this->directoryRepository->update($param, $job->id);
        if ($remove) {
            return redirect()->back()->with('thong_bao', 'Remove job success.');
        }
        return redirect()->back()->with('thong_bao', 'Remove job failed.');
    }

    /**
     * Controller function manual assign jobs for the editor
     *
     * @param Request $request
     * @param int $id
     * @param $editorId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function manualAssign(Request $request, $id, $editorId)
    {
        $job = $this->directoryRepository->find($id);
        if (empty($job)) {
            return redirect()->back()->with('thong_bao', 'Jobs not found.');
        }
        $param['status'] = 2;
        $param['editor_id'] = $editorId;
        $assign = $this->directoryRepository->update($param, $job->id);
        if ($assign) {
            return redirect()->back()->with('thong_bao', 'Assign job success.');
        }
        return redirect()->back()->with('thong_bao', 'Assign job failed.');
    }

    /**
     * Cotroller function admin edit job
     *
     * @param Request $request
     * @param $id
     * @return Factory|View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function adminEditJob(Request $request, $id)
    {
        $job = $this->directoryRepository->find($id);
        if (empty($job)) {
            return redirect('/admin/jobs')->with('thong_bao', 'Cannot find jobs');
        }
        $job->customer_txt = $this->userRepository->find($job->user_id)->name;
        $editor = $this->userRepository->find($job->editor_id);
        if (empty($editor)) {
            $job->editor_txt = '-';
        } else {
            $job->editor_txt = $editor->name;
        }
        // Response view data
        return view('admin.jobs.edit', compact('job'));
    }

    /**
     * Controller function admin update jobs
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function adminUpdateJob(Request $request, $id)
    {
        $param = $request->all();
        $update = $this->directoryRepository->update($param, $id);
        if ($update) {
            return redirect('/admin/jobs/edit/' . $id)->with('thong_bao', 'Update success.');
        } else {
            return redirect('/admin/jobs/edit/' . $id)->with('thong_bao', 'Error, please check again.');
        }
    }

    /**
     * Controller function admin delete a jobs none assign
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function adminDeleteJob(Request $request, $id)
    {
        $job = $this->directoryRepository->find($id);
        if (empty($job)) {
            return redirect('/admin/jobs/')->with('thong_bao', 'Cannot find job.');
        }
        // status 0: reject, 1 chưa assign, 2 đã assign, 3 confirm, 4 done
        if ($job->status != 1 || !is_null($job->editor_id)) {
            return redirect('/admin/jobs/')->with('thong_bao', 'The job cannot be deleted because it is related to processing.');
        }
        $dirJob = $this->directoryRepository->dirJob($job->id);
        try {
            Storage::disk('ftp')->deleteDirectory($dirJob);
            $this->directoryRepository->delete($id);
            return redirect('/admin/jobs/')->with('thong_bao', 'Delete job success.');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect('/admin/jobs/')->with('thong_bao', 'System errors please check log system.');
        }
    }

    /**
     * Controller function render partial view
     *
     * @param Request $request
     * @return string
     */
    public function adminAssignJobMN(Request $request)
    {
        $param = $request->all();
        $job = $this->directoryRepository->find($param['jobId']);
        $editors = $this->userRepository->editors('ASC');
        return view('admin.jobs.partials.selectEditors', compact('job', 'editors'))->render();
    }

    /**
     * Controller function assign jobs for editors.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function adminAssignJobP(Request $request)
    {
        $param = $request->all();
        if (!$param['editor_id'] || is_null($param['editor_id'])) {
            return redirect('/admin/jobs/')->with('thong_bao', 'Please select an editors');
        }
        $param['status'] = 2;
        // Began assign jobs
        $assign = $this->directoryRepository->update($param, $param['job_id']);
        if ($assign) {
            return redirect('/admin/jobs/')->with('thong_bao', 'Assign success.');
        } else {
            return redirect('/admin/jobs/')->with('thong_bao', 'System errors please check log system.');
        }
    }
}
