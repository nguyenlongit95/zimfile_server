<?php

namespace App\Repositories\Files;

use App\Models\Files;
use App\Repositories\Eloquent\EloquentRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\File;
use Image;

class FilesEloquentRepository extends EloquentRepository implements FilesRepositoryInterface
{
    /**
     * Define global const
     */
    const THUMBNAIL_HEIGHT = 150;
    const THUMBNAIL_WIDTH = 150;
    const THUMBNAIL_RATE = 0.75;
    const MAX_FILE_SIZE = 524288000;
    const EXT_FAILED = 'exe';

    /**
     * @return mixed
     */
    public function getModel()
    {
        return Files::class;
    }

    /**
     * Function validate file
     *
     * @param $file
     * @return mixed
     */
    public function validateFile($file)
    {
        // compare has byte
        if ($file->getSize() >= self::MAX_FILE_SIZE) {
            return 2; // error size
        }
        // compare extend .exe
        if ($file->getClientOriginalExtension() == self::EXT_FAILED) {
            return 3; // error extend
        }
        // passed
        return 1;
    }

    /**
     * Function upload file
     *
     * @param File $file
     * @param string $directory
     * @param $directoryId
     * @return mixed
     */
    public function uploadFile($file, $directory, $directoryId)
    {
        try {
            $path = $directory . '/' . $file->getClientOriginalName();
            // Upload file to storage
            $putNASStorage = Storage::disk('ftp')->put($path, $file->get());
            if (!$putNASStorage) {
                return false;
            }
            // Insert into database
            $param['director_id'] = $directoryId;
            $param['image'] = $file->getClientOriginalName();
            $param['thumbnail'] = $file->getClientOriginalName();
            $param['time_upload'] = Carbon::now();
            $param['status'] = 1;
            $param['name'] = $file->getClientOriginalName();
            Log::info('User: ' . Auth::user()->email . ' upload file: ' . $path);
            return $this->create($param);
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * Private function create a thumbnail and return file
     *
     * @param File $file
     * @param String $path
     * @return null
     */
    private function _createThumbnail($file, $path)
    {
        try {
            $thumbnail = Image::make($file);
            $thumb = $thumbnail->fit(self::THUMBNAIL_HEIGHT, self::THUMBNAIL_WIDTH)->response();
            return Storage::disk('local')->put($path, $thumb->content());
        } catch (\Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    /**
     * Function list all file of user in a directory
     *
     * @param integer $dirId
     * @return mixed
     */
    public function listFile($dirId)
    {
        $files = $this->_model->leftJoin('directors', 'files.director_id', 'directors.id')
            ->where('files.director_id', $dirId)->orderBy('files.id', 'DESC')
            ->select(
                'files.id', 'files.director_id', 'files.time_upload', 'files.thumbnail', 'files.name',
                'directors.vps_dir', 'directors.nas_dir'
            )->paginate(config('const.paginate'));
        if (!empty($files)) {
            foreach ($files as $file) {
                $file->thumbnail = asset('/app'. $file->vps_dir . '/' . $file->thumbnail);
            }
        }

        return $files;
    }

    /**
     * Function delete a file
     *
     * @param integer $fileId
     * @return mixed
     */
    public function deleteFile($fileId)
    {
        $file = $this->_model->leftJoin('directors', 'files.director_id', 'directors.id')->find($fileId);
        if (empty($file)) {
            return null;
        }
        try {
            // delete file in vps server
            Storage::disk('local')->delete( $file->vps_dir . '/' . $file->thumbnail);
            // delete file in NAS server
            Storage::disk('ftp')->delete($file->vps_dir . '/' . $file->image);
            // Delete file in DB
            return $this->delete($fileId);
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }
}
