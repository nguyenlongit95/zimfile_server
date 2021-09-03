<?php

namespace App\Repositories\JobRepository;

interface JobRepositoryInterface
{
    /**
     * @param $file
     * @param $directoryId
     * @param $type
     * @return mixed
     */
    public function uploadJobs($file, $directoryId, $type);

    /**
     * @param array $param
     * @return mixed
     */
    public function userListJobs($param);
}
