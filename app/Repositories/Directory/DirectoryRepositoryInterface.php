<?php


namespace App\Repositories\Directory;


interface DirectoryRepositoryInterface
{
    /**
     * @param $userId
     * @return mixed
     */
    public function listDirectories($userId);
}
