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

    /**
     * @param $param
     * @return mixed
     */
    public function getJobsForEditor($param);

    /**
     * @param $param
     * @return mixed
     */
    public function getJobsForQC($param);

    /**
     * @param $param
     * @return mixed
     */
    public function checkJobsBeforeAssign($param);

    /**
     * @param $request
     * @param $path
     * @param $dir
     * @param $job
     * @return mixed
     */
    public function uploadFileProduct($request, $path, $dir, $job);

    /**
     * @param $param
     * @return mixed
     */
    public function checkJobOld($param);
}
