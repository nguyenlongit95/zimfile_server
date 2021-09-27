<?php

namespace App\Repositories\Directory;

use App\Models\Director;
use App\Models\User;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $dir = Director::where('user_id', $userId)->where('parent_id', 0)->orderBy('id', 'DESC')->get();
        }
        // List subs dir
        if ($parentId > 0) {
            $dir = Director::where('parent_id', $parentId)->orderBy('id', 'DESC')->get();
        }
        $dir->parent_dir = $this->getParentDir($parentId);
        // response dir
        return $dir;
    }

    /**
     * Find parent directors and compare data
     *
     * @param $parentId
     * @return mixed
     */
    public function getParentDir($parentId)
    {
        $dir = DB::table('directors')->where('id', $parentId)->first();
        if (!is_null($dir) && !is_null($dir->path)) {
            $dir->path = json_decode($dir->path);
        }
        // response data
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
        $path = '';
        $dir = Director::find($dirId);
        if (!$dir) {
            return null;
        }
        $user = User::find($dir->user_id);
        // Path level 1
        if ($dir->level == 1 && $dir->parent_id == 0) {
            $path = $path . config('const.base_path') . $user->name . '/' . $dir->nas_dir;
        }
        // Path level 2
        if ($dir->level > 1 && $dir->parent_id > 0) {
            $parent = Director::find($dir->parent_id);
            $path = $path . config('const.base_path') . $user->name . '/' . $parent->nas_dir . '/' . $dir->nas_dir;
        }
        // Response data path
        return $path;
    }
}
