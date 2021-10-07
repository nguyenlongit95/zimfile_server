<?php

namespace App\Repositories\Directory;

use App\Models\Director;
use App\Models\User;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\JobRepository\JobEloquentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Function copy file jobs to editor folders
     *
     * @param $jobPath
     * @param $dir
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function copyJobsToEditor($jobPath, $dir)
    {
        // list all jobs in directors
        $listJobFile = app()->make(JobEloquentRepository::class)->jobInDir($dir);
        if (is_null($listJobFile)) {
            return null;
        }
        // Init path and copy file jobs to folder of editor
        $editorPath = config('const.base_path') . 'editors/' . Auth::user()->name . '_' . Auth::user()->id;
        foreach ($listJobFile as $job) {
            try {
                // Copy file to editor folder
                Storage::disk('ftp')->copy($jobPath . '/' . $job->file_jobs, $editorPath . '/' . $job->file_jobs);
            } catch (\Exception $exception) {
                Log::error($exception->getMessage());
                return null;
            }
        }
        // Response pass status
        return true;
    }

    /**
     * Function delete all file in editor folder
     *
     * @param int $editorId
     * @return mixed
     */
    public function deleteFileInEditorFolder($editorId)
    {
        $editor = User::find($editorId);
        $editorPath = config('const.base_path') . 'editors/' . $editor->name . '_' . $editor->id;
        $files = Storage::disk('ftp')->files($editorPath);
        try {
            Storage::disk('ftp')->delete($files);
            Log::info('QC: ' . Auth::user()->id . ' accept job and delete all file jobs in folder editor: ' . $editor);
            return true;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }

    }

    /**
     * Function get MyJobs
     *
     * @return mixed
     */
    public function getMyJobs()
    {
        return Director::where('editor_id', Auth::user()->id)->where('status', 2)->first();
    }
}
