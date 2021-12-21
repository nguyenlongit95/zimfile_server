<?php


namespace App\Repositories\Group;


interface GroupRepositoryInterface
{
    /**
     * @param $groupId
     * @return mixed
     */
    public function listUserFreeGroup($groupId);

    /**
     * @param $groupId
     * @return mixed
     */
    public function listUserInGroup($groupId);

    /**
     * @param $editorId
     * @return mixed
     */
    public function listGroupsForEditor($editorId);

    /**
     * @param $editorId
     * @return mixed
     */
    public function listGroupsNotForEditor($editorId);

    /**
     * @param $editorId
     * @param $groupId
     * @return mixed
     */
    public function assignGroupForEditor($editorId, $groupId);

    /**
     * @param $editorId
     * @param $groupId
     * @return mixed
     */
    public function removeGroupForEditor($editorId, $groupId);
}
