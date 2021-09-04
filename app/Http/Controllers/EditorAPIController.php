<?php

namespace App\Http\Controllers;

use App\Repositories\Directory\DirectoryRepositoryInterface;
use App\Repositories\Files\FilesRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Supports\ResponseHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EditorAPIController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;
    protected $filesRepository;
    protected $directoryRepository;

    /**
     * Define global variable base path
     */
    const BASE_PATH = '/disk1/DATA';

    /**
     * UserAPIController constructor.
     * @param UserRepositoryInterface $userRepository
     * @param FilesRepositoryInterface $filesRepository
     * @param DirectoryRepositoryInterface $directoryRepository
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        FilesRepositoryInterface $filesRepository,
        DirectoryRepositoryInterface $directoryRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->filesRepository = $filesRepository;
        $this->directoryRepository = $directoryRepository;
    }

    public function listJobs(Request $request)
    {

    }
}
