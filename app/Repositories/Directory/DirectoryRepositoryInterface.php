<?php


namespace App\Repositories\Directory;


interface DirectoryRepositoryInterface
{
    /**
     * @param $userId
     * @param $parentId
     * @return mixed
     */
    public function listDirectories($userId, $parentId);

    /**
     * @param $dirId
     * @return mixed
     */
    public function dirJob($dirId);

    /**
     * @param $parentId
     * @return mixed
     */
    public function getParentDir($parentId);

    /**
     * @param $jobPath
     * @param $dir
     * @return mixed
     */
    public function copyJobsToEditor($jobPath, $dir);

    /**
     * @param $editorId
     * @param $dirId
     * @return mixed
     */
    public function deleteFileInEditorFolder($editorId, $dirId);

    /**
     * @return mixed
     */
    public function getMyJobs();

    /**
     * @param null $param
     * @return mixed
     */
    public function getAllJobsDashBoard($param = null);

    /**
     * @param $userId
     * @return mixed
     */
    public function checkDirOnDay($userId);

    /**
     * @param $userId
     * @return mixed
     */
    public function createMainFolder($userId);

    /**
     * @param $jobId
     * @return mixed
     */
    public function cancelJob($jobId);

    /**
     * @return mixed
     */
    public function listMyJobs();
}
