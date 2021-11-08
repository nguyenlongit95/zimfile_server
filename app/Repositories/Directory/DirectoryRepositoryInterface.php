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
     * @return mixed
     */
    public function deleteFileInEditorFolder($editorId);

    /**
     * @return mixed
     */
    public function getMyJobs();

    /**
     * @return mixed
     */
    public function getAllJobsDashBoard();
}
