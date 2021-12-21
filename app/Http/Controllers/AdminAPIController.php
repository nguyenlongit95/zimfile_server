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
use Illuminate\Support\Facades\Hash;
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
        $param = $request->all();
        // Export excel file by month
        return \Maatwebsite\Excel\Facades\Excel::download(new JobsExport($param['month']), 'jobs_'.Carbon::now()->subMonths($param['month'])->format('Y-m-d').'.xlsx');
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
        // Soft delete customers
        if ($this->userRepository->deleteCustomer($id)) {
            return redirect('/admin/customers/')->with('thong_bao', 'User account delete success.');
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
        // Soft delete editors
        $param['status'] = 0;
        if ($this->userRepository->update($param, $id)) {
            return redirect('/admin/editors/')->with('thong_bao', 'Editor account deleted.');
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
        return view('admin.jobs.index', compact('jobs'));
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
        return view('admin.jobs.index', compact('jobs'));
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
     * @param int $editorId
     * @param int $groupId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeGroupForEditor(Request $request, $editorId, $groupId)
    {
        if ($this->groupRepository->removeGroupForEditor($editorId, $groupId)) {
            return redirect('/admin/editors/assign-group/' . $editorId)->with('thong_bao', 'Remove group success.');
        }
        // Response assign failed
        return redirect('/admin/editors/assign-group/' . $editorId)->with('thong_bao', 'Remove group failed, please check system again.');
    }
}
