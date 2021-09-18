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
}
