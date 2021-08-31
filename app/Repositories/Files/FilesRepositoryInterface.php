<?php


namespace App\Repositories\Files;


interface FilesRepositoryInterface
{
    /**
     * @param $file
     * @return mixed
     */
    public function validateFile($file);

    /**
     * @param $file
     * @param $directory
     * @param $directoryId
     * @return mixed
     */
    public function uploadFile($file, $directory, $directoryId);

    /**
     * @param $dirId
     * @return mixed
     */
    public function listFile($dirId);

    /**
     * @param $fileId
     * @return mixed
     */
    public function deleteFile($fileId);
}
