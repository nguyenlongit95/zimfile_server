<?php

namespace App\Repositories\JobRepository;

interface JobRepositoryInterface
{
    /**
     * @param $file
     * @param $directoryId
     * @return mixed
     */
    public function uploadJobs($file, $directoryId);

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
    public function listJobForAdmin($param);

    /**
     * @param $param
     * @return mixed
     */
    public function checkJobsBeforeAssign($param);

    /**
     * @param $param
     * @return mixed
     */
    public function checkJobsRejected($param);

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

    /**
     * @param $param
     * @return mixed
     */
    public function manualAssignJob($param);

    /**
     * @param $dir
     * @return mixed
     */
    public function jobInDir($dir);

    /**
     * @param $id
     * @return mixed
     */
    public function adminAssingJob($id);

    /**
     * @param $id
     * @return mixed
     */
    public function qcGetCheckJobs($id);
}
