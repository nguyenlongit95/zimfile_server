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
     * @param $parentId
     * @return mixed
     */
    public function listDirectories($userId, $parentId)
    {
        $dir = null;
        // List base dir
        if ($parentId == 0) {
            $dir = Director::where('user_id', $userId)->where('parent_id', 0)
                ->orderBy('id', 'DESC')->paginate(config('const.paginate'));
        }
        // List subs dir
        if ($parentId > 0) {
            $dir = Director::where('parent_id', $parentId)->orderBy('id', 'DESC')
                ->paginate(config('const.paginate'));
        }
        // response dir
        return $dir;
    }
}
