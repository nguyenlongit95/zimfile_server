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
}
