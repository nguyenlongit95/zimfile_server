<?php

namespace App\Repositories\Directory;

use App\Models\Director;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\Auth;

class DirectoryEloquentRepository extends EloquentRepository implements DirectoryRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getModel()
    {
        return Director::class;
    }

    /**
     * Sql function list all directories
     *
     * @param integer $userId
     * @return mixed
     */
    public function listDirectories($userId)
    {
        return $this->_model->where('user_id', $userId)->orderBy('id', 'DESC')
            ->paginate(config('const.paginate'));
    }
}
