<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Files\FilesRepositoryInterface;
use App\Repositories\JobRepository\JobRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Validations\Validation;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class adminController extends Controller
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
    }
    /*
     * Quan tri chung cho website
     * Chia cac vung widgets tuong tu cho website
     * widget sẽ được hiểu là các thành phần phụ của website
     * */
    public function DashBoard() 
    {
        return view('admin.index');
    }

    /**
     * Logout function
     */
    public function logout(Request $request) 
    {
        Auth::logout();
        return redirect('/admin/login');
    }

    /**
     * Controller function list all Customer
     */
    public function listCustomers(Request $request) 
    {
        $param = $request->all();
        $customers = $this->userRepository->listCustomers($param);
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Controller function list all Customer
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
     */
    public function addCustomers(Request $request)
    {
        Validation::validationCustomer($request);
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
            Storage::disk('ftp')->makeDirectory(config('const.base_path') . $param['name']);
            Storage::disk('ftp')->makeDirectory(config('const.base_path') . $param['name'] . '/done');
            return true;
        }
        // Error response redirect
        return false;
    }

    /**
     * Controller function render view edit a customer
     */
    public function editCustomers(Request $request, $id)
    {
        $customer = $this->userRepository->find($id);
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Controller function update a customers
     */
    public function updateCustomers(Request $request, $id)
    {
        $customer = $this->userRepository->find($id);
        if (empty($customer)) {
            return redirect('/admin/customers/')->with('thong_bao', 'User account not found.');
        }
        Validation::validationCustomer($request);
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
}
