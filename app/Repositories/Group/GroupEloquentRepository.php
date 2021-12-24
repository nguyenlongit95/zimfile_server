<?php

namespace App\Repositories\Group;

use App\Models\Group;
use App\Models\User;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupEloquentRepository extends EloquentRepository implements GroupRepositoryInterface
{
    /**
     * @return mixed
     */
    public function getModel()
    {
        return Group::class;
    }

    /**
     * Sql function list all user not assign the group using group_id
     *
     * @param int $groupId
     * @return mixed
     */
    public function listUserFreeGroup($groupId)
    {
        return User::where('role', config('const.user'))->where('group_id', null)->get();
    }

    /**
     * Sql function list all user in group using group_id
     *
     * @param int $groupId
     * @return mixed
     */
    public function listUserInGroup($groupId)
    {
        return User::where('role', config('const.user'))->where('group_id', $groupId)->get();
    }

    /**
     * Sql function list all groups for editor
     *
     * @param int $editorId
     * @return array
     */
    public function listIdGroupsForEditor($editorId)
    {
        return DB::table('editor_groups')->where('editor_id', $editorId)->pluck('group_id')->toArray();
    }

    /**
     * Sql function list all group free for the editor
     *
     * @param int $editorId
     * @return mixed
     */
    public function listGroupsForEditor($editorId)
    {
        return DB::table('editor_groups')->join('groups', 'editor_groups.group_id', '=', 'groups.id')
            ->where('editor_groups.editor_id', $editorId)->orderBy('editor_groups.id', 'ASC')
            ->select(
                'editor_groups.id', 'groups.group_name', 'editor_groups.editor_id'
            )->get();
    }

    /**
     * Sql function list all group not for the editor
     *
     * @param int $editorId
     * @return mixed
     */
    public function listGroupsNotForEditor($editorId)
    {
        return Group::whereNotIn('id', $this->listIdGroupsForEditor($editorId))->get();
    }

    /**
     * Sql function assign group for editor
     *
     * @param int $editorId
     * @param int $groupId
     * @return mixed
     */
    public function assignGroupForEditor($editorId, $groupId)
    {
        try {
            return DB::table('editor_groups')->insert([
                'editor_id' => $editorId,
                'group_id' => $groupId,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }
    }

    /**
     * Sql function remove group for a editor
     *
     * @param int $editorId
     * @param int $groupId
     * @return mixed
     */
    public function removeGroupForEditor($editorId, $groupId)
    {
        try {
            return DB::table('editor_groups')->where('editor_id', $editorId)
                ->where('group_id', $groupId)->delete();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return null;
        }
    }
}
