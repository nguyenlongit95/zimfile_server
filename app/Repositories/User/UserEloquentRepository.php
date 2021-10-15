<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\UserDiscount;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\MailLog\MailLogRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserEloquentRepository extends EloquentRepository implements UserRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getModel()
    {
        return User::class;
    }

    /**
     * Sql function verify password
     *
     * @param String $password
     * @return mixed
     */
    public function verifyPassword($password)
    {
        return Hash::check($password, Auth::user()->getAuthPassword());
    }

    /**
     * Sql function list all users
     *
     * @param String $sort ASC or DESC
     * @return mixed
     */
    public function listUsers($sort = 'DESC')
    {
        return $this->_model->where('role', config('const.user'))->orderBy('id', $sort)
            ->select(
                'id', 'name', 'email', 'address', 'phone', 'total_file'
            )->paginate(config('const.paginate'));
    }

    /**
     * Function get all directory and image of image
     *
     * @param object $user
     * @return mixed
     */
    public function getDirAndImage($user)
    {

    }

    /**
     * Function list all customer
     * 
     * @param array $param
     */
    public function listCustomers($param) 
    {
        $user = User::on();
        // Condition srarch isset
        if (isset($param['name'])) {
            $user->where('name', 'like', '%' . $param['name'] . '%');
        }
        if (isset($param['email'])) {
            $user->where('email', 'like', '%' . $param['email'] . '%');
        }
        if (isset($param['phone'])) {
            $user->where('phone', 'like', '%' . $param['phone'] . '%');
        }
        if (isset($param['address'])) {
            $user->where('address', 'like', '%' . $param['address'] . '%');
        }
        // Response data
        return $user->where('role', 1)->where('status', 1)->orderBy('id', 'DESC')->paginate(config('const.paginate'));
    }

    /**
     * Function soft delete a customer
     * 
     * @param array $id
     */
    public function deleteCustomer($id)
    {
        return DB::table('users')->where('id', $id)->update([
            'status' => 0
        ]);
    }
}
