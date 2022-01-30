<?php

namespace App\Repositories\Directory;

use App\Models\Director;
use App\Models\Jobs;
use App\Models\User;
use App\Repositories\Eloquent\EloquentRepository;
use App\Repositories\JobRepository\JobEloquentRepository;
use Carbon\Carbon;
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
            $dir = Director::where('parent_id', 0)->where('user_id', $userId)->orderBy('id', 'DESC')->get();
        }
        // List subs dir
        if ($parentId > 0) {
            $dir = Director::where('parent_id', $parentId)->where('user_id', $userId)->orderBy('id', 'DESC')->get();
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
            $path = $path . config('const.base_path') . '/clients/' . $user->name . '/' . $dir->nas_dir;
        }
        // Path level 2
        if ($dir->level > 1 && $dir->parent_id > 0) {
            $parent = Director::find($dir->parent_id);
            $path = $path . config('const.base_path') . '/clients/' . $user->name . '/' . $parent->nas_dir . '/' . $dir->nas_dir;
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
                continue;
            }
        }
        // Response pass status
        return true;
    }

    /**
     * Function delete all file in editor folder
     *
     * @param int $editorId
     * @param $dirId
     * @return mixed
     */
    public function deleteFileInEditorFolder($editorId, $dirId)
    {
        $editor = User::find($editorId);
        $editorPath = config('const.base_path') . 'editors/' . $editor->name . '_' . $editor->id;
        try {
            $tmpDir = app()->make(DirectoryRepositoryInterface::class)->dirJob($dirId);
            $arrFiles = Storage::disk('ftp')->files($tmpDir);
            foreach ($arrFiles as $file) {
                $fileName = array_reverse(explode('/', $file))[0];
                Storage::disk('ftp')->delete($editorPath . '/' . $fileName);
                Log::info('QC: ' . Auth::user()->id . ' accept job and delete all file jobs in folder editor: ' . $editor . ' file: ' . $fileName);
            }
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
        return Director::where('editor_id', Auth::user()->id)->where('level', 2)->where('status', 2)->first();
    }

    /**
     * Sql function get all jobs for admin dashboard
     *
     * @param null $param
     * @return mixed
     */
    public function getAllJobsDashBoard($param = null)
    {
        // Sql get the jobs data
        $jobs = Director::join('users', 'directors.user_id', 'users.id')
            ->select(
                'users.email', 'users.name as user_name', 'directors.id', 'directors.user_id',
                'directors.nas_dir', 'directors.status', 'directors.type',
                'directors.editor_id', 'directors.note', 'directors.customer_note', 'directors.created_at'
            )->where('directors.level', 2)
            ->where('directors.parent_id', '<>', 0)
            ->where(function ($query) use ($param) {
                // Pass param condition param search
                if (isset($param['name_editor']) && !is_null($param['name_editor'])) {
                    $users = User::where('name', 'LIKE', $param['name_editor'])->pluck('id');
                    $query->whereIn('directors.editor_id', $users);
                }
                if (isset($param['name_user']) && !is_null($param['name_user'])) {
                    $query->where('users.name', 'LIKE', '%' . $param['name_user'] . '%');
                }
                if (isset($param['type']) && !is_null($param['type'])) {
                    $query->where('directors.type', $param['type']);
                }
                if (isset($param['status']) && !is_null($param['status'])) {
                    $query->where('directors.status', $param['status']);
                }
                if (isset($param['date']) && !is_null($param['date'])) {
                    $query->whereDate('directors.created_at', $param['date']);
                }
                // Response sql search
                return $query;
            });
        $jobs = $jobs->orderBy('directors.id', 'DESC')->paginate(config('const.paginate'));

        // Check empty jobs and return null data
        if (empty($jobs)) {
            return null;
        }
        // Add on data editor assign to jobs
        foreach ($jobs as $job) {
            $user = User::where('id', $job->editor_id)->select('name')->first();
            if (empty($user)) {
                $job->editor_name = '-';
            } else {
                $job->editor_name = $user->name;
            }
            $job = Director::convertType($job);
            $job = Director::convertStatus($job);
        }
        // Response the jobs
        return $jobs;
    }

    /**
     * Function check dir on the day
     *
     * @param int $userId
     * @return mixed
     */
    public function checkDirOnDay($userId)
    {
        // Get director in the day
        $dir = Director::whereDate('created_at', Carbon::now())->where('level', 1)->where('user_id', $userId)
            ->orderBy('id', 'DESC')->first();
        return $dir;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function createMainFolder($userId)
    {

    }

    /**
     * Function update dir and cancel jobs
     *
     * @param int $dirId
     * @return mixed
     */
    public function cancelJob($dirId)
    {
        try {
            return DB::table('directors')->where('id', $dirId)->update([
                'editor_id' => null,
                'status' => 1
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }
    }

    /**
     * Sql function list all my jobs for editor
     *
     * 0: reject, 1 chưa assign, 2 đã assign, 3 confirm, 4 done
     * @return mixed
     */
    public function listMyJobs()
    {
        $jobs = Director::where('editor_id', Auth::id())->whereIn('status', [3, 0])
            ->orderByRaw('status', [
                3, 0
            ])->paginate(config('const.paginate'));
        if (!empty($jobs)) {
            foreach ($jobs as $job) {
                $clients = User::find($job->user_id);
                $job->customer_name = $clients->name;
            }
            return $jobs;
        }
        return null;
    }
}
