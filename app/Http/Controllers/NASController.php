<?php

namespace App\Http\Controllers;

use App\Helpers\NASHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NASController extends Controller
{
    /**
     * NASController constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Controller function connect to nas server storage
     *
     * @param Request $request
     * @return mixed|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function connectNAS(Request $request)
    {
        $param = $request->all();
        return app()->make(ResponseHelper::class)->success();
    }

    /**
     * Function read file and save to storage
     *
     * @param Request $request
     * @return mixed
     */
    public function readFile(Request $request)
    {
        $file = Storage::disk('ftp')->download('/disk1/DATA/longnct_test/longnguyen.jpg');
//        $saveStorage = Storage::put('/sources/longnct_test/longnguyen.jpg', $file);
        return $file;
    }

    /**
     * Function upload a file to NAS
     *
     * Using: $request->file()
     * @param Request $request
     */
    public function upFile(Request $request)
    {
        $file = Storage::get('sources/longnct_test/longnguyen1.jpg');
        $path = Storage::disk('ftp')->put('/disk1/DATA/longnct_test/longnguyen1.jpg', $file);
        dd($path);
    }
}
