<?php

namespace App\Repositories\Directory;

use App\Models\Director;
use App\Models\User;
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

    /**
     * Function get dir an job
     *
     * @param int $dirId
     * @return null
     */
    public function dirJob($dirId)
    {
        $path = null;
        $dir = Director::find($dirId);
        if (!$dir) {
            return null;
        }
        $user = User::find($dir->user_id);
        // Path level 1
        if ($dir->level == 1 && $dir->parent_id == 0) {
            $path = $path . config('const.base_path') . $user->name . $dir->nas_dir;
        }
        // Path level 2
        if ($dir->level > 1 && $dir->parent_id > 0) {
            $parent = Director::find($dir->parent_id);
            $path = $path . config('const.base_path') . $user->name . $parent->nas_dir . $dir->nas_dir;
        }
        // Response data path
        return $path;
    }
}
