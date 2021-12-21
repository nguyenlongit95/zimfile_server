<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\UserDiscount;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\MailLog\MailLogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
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
     * @param int $id
     * @return int
     */
    public function deleteCustomer($id)
    {
        return DB::table('users')->where('id', $id)->update([
            'status' => 0
        ]);
    }

    /**
     * Function list all editors
     *
     * @param array $param
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder
     */
    public function listEditors($param)
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
        // Addon data jobs assign
        $user = $user->where('role', 2)->where('status', 1)->orderBy('id', 'DESC')
            ->paginate(config('const.paginate'));
        if (!empty($user)) {
            foreach ($user as $value) {
                $dir = DB::table('directors')->where('editor_id', $value->id)
                    ->where('status', 2)->count();
                if ($dir > 0) {
                    $value->assigned = true;
                } else {
                    $value->assigned = false;
                }
            }
        }
        // Response data
        return $user;
    }

    /**
     * Function list all QC
     *
     * @param array $param
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listQC($param)
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
        return $user->where('role', 3)->where('status', 1)->orderBy('id', 'DESC')
        ->paginate(config('const.paginate'));
    }

    /**
     * Sql function get all user free
     *
     * @return mixed
     */
    public function getUserNotAssign()
    {
        return User::where('role', 1)->whereNotIn('id', $this->getUserAssigned())->get();
    }

    /**
     * Sql function get all user assigned
     *
     * @return mixed
     */
    public function getUserAssigned()
    {
        return DB::table('user_assign')->pluck('user_id');
    }

    /**
     * Sql function list and get all id of user belong a qc
     *
     * @param int $qcId
     * @return mixed
     */
    public function listIdUserBelongQc($qcId)
    {
        return DB::table('user_assign')->where('qc_id', $qcId)->pluck('user_id');
    }

    /**
     * Sql function get all User belong a qc
     *
     * @param int $qcId
     * @return mixed
     */
    public function userBelongQC($qcId)
    {
        return User::join('user_assign', 'users.id', 'user_assign.user_id')->where('user_assign.qc_id', $qcId)->get();
    }

    /**
     * Sql function remove user assigned
     *
     * @param int $id
     * @return mixed
     */
    public function removeUserAssign($id)
    {
        try {
            return DB::table('user_assign')->where('id', $id)->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * Sql function assign user belong a qc
     *
     * @param int $userId
     * @param int $qcId
     * @return mixed
     */
    public function assignedUser($userId, $qcId)
    {
        try {
            return DB::table('user_assign')->insert([
                'user_id' => $userId,
                'qc_id' => $qcId
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }


    /**
     * Function list all user free priority
     *
     * @param int $editorId
     * @return mixed
     */
    public function listUserPriorityFree($editorId)
    {
        // Get all id user has priority for me
        $priorityForMe = $this->getIdUserPriorityForMe($editorId);
        // Return all customer not assign priority for this editorId
        return DB::table('users')->where('role', config('const.user'))
            ->whereNotIn('id', $priorityForMe)
            ->select('id', 'name', 'email')
            ->get();
    }

    /**
     * Function list all user priority assign for me
     *
     * @param int $editorId
     * @return mixed
     */
    public function listUserPriorityForMe($editorId)
    {
        // Get all id user has priority for me
        $priorityForMe = $this->getIdUserPriorityForMe($editorId);
        // Return all customer assign for me
        return DB::table('users')->join('user_priority', 'users.id', 'user_priority.user_id')
            ->where('user_priority.editor_id', $editorId)
            ->where('users.role', config('const.user'))
            ->whereIn('users.id', $priorityForMe)
            ->orderBy('user_priority.priority', 'ASC')
            ->select('users.id', 'users.name', 'users.email', 'user_priority.priority')
            ->get();
    }

    /**
     * Sql function get all id user assign priority for me
     *
     * @param int $editorId
     * @return mixed
     */
    public function getIdUserPriorityForMe($editorId)
    {
        return DB::table('user_priority')->where('editor_id', $editorId)->pluck('user_id');
    }

    /**
     * Sql function assign priority
     *
     * @param int $editorId
     * @param int $userId
     * @param int $priority
     * @return mixed
     */
    public function assignPriority($editorId, $userId, $priority)
    {
        try {
            return DB::table('user_priority')->insert([
                'user_id' => $userId,
                'editor_id' => $editorId,
                'priority' => $priority
            ]);
        } catch (\Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    /**
     * Sql function remove assign priority
     *
     * @param int $id
     * @param int $userId
     * @return mixed
     */
    public function removeAssignPriority($id, $userId)
    {
        try {
            return DB::table('user_priority')->where('editor_id', $id)->where('user_id', $userId)
                ->delete();
        } catch (\Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    /**
     * Sql function list all sub admin
     *
     * @return mixed
     */
    public function listSubAdmin()
    {
        return DB::table('users')->where('role', config('const.sub_admin'))
            ->orderBy('id', 'DESC')->get();
    }
}
