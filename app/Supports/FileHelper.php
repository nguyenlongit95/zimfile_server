<?php

namespace App\Supports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FileHelper
{
    /**
     * Define global variable
     */
    const MAX_SIZE = 2097152;

    /**
     * Function save store avatar
     *
     * @param $request
     * @return bool
     */
    public function avatar($request)
    {
        $file = $request->file('avatar');
        try {
            $fileName = 'avatar_' . Auth::user()->id . '_' . $file->getClientOriginalName();
            $file->move(public_path(config('const.avatar_path')), $fileName);
            return $fileName;
        } catch (\Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    /**
     * Function check avatar
     *
     * return data error: 0 has error file size, 2 has error file ext
     * @param $request
     * @return int
     */
    public function checkAvatar($request)
    {
        $file = $request->file('avatar');
        if (filesize($file) >= self::MAX_SIZE) {
            return 0;
        }
        if ($file->getClientOriginalExtension() !== 'png') {
            return 2;
        }

        return true;
    }

    /**
     * Function delete a avatar
     *
     * @param $request
     * @return bool
     */
    public function removeAvatar($request)
    {
        if (file_exists(asset(config('const.avatar_path')) . Auth::user()->avatar)) {
            return unlink(asset(config('const.avatar_path')) . Auth::user()->avatar);
        } else {
            return false;
        }
    }

    /**
     * Function check image articles type and size
     *
     * @param $request
     * @param int $id of article
     * @return string|null
     */
    public function saveImageArticle($request, $id)
    {
        $file = $request->file('image');
        try {
            $fileName = 'article_' . $id . '_' . $file->getClientOriginalName();
            $file->move(public_path(config('const.article_image_path')), $fileName);
            return $fileName;
        } catch (\Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    /**
     * Function check thumbnail articles type and size
     *
     * @param $request
     * @param int $id of article
     * @return string|null
     */
    public function saveThumbnailArticle($request, $id)
    {
        $file = $request->file('thumbnail');
        try {
            $fileName = 'thumbnail_' . $id . '_' . $file->getClientOriginalName();
            $file->move(public_path(config('const.article_thumbnail_path')), $fileName);
            return $fileName;
        } catch (\Exception $exception) {
            Log::error($exception);
            return null;
        }
    }
}
