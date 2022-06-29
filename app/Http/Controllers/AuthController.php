<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repositories\User\UserRepositoryInterface;
use App\Supports\SystemLogHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * AuthController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Function controller login
     *
     * @param Request $request
     * @return
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' =>$request->password])) {
                if (Auth::user()->status == config('const.users.status.block')) {
                    $this->logout($request);
                    return app()->make(ResponseHelper::class)->unAuthenticated();
                }
                $user = $this->userRepository->find(Auth::user()->id);
                if (!is_null($user->deleted_at)) {
                    return app()->make(ResponseHelper::class)->unAuthenticated();
                }
                $token = $user->createToken(Auth::user()->email . '_' . Auth::id());
                $token->profiles = Auth::user();
                return app()->make(ResponseHelper::class)->success($token);
            } else {
                return app()->make(ResponseHelper::class)->unAuthenticated();
            }
        } catch (\Exception $exception) {
            Log::error('Login error' . $exception->getMessage());
        }
    }

    /**
     * Logout controller function
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return app()->make(\App\Supports\ResponseHelper::class)->unAuthenticated();
        }
        try {
            Auth::user()->authAcesToken()->delete();
            Log::info('User: ' . Auth::user()->id . ' Logout has: ' . Carbon::now());
            return app()->make(ResponseHelper::class)->success();
        } catch (\Exception $exception) {
            Log::error($exception);
            return app()->make(ResponseHelper::class)->error();
        }
    }

    /**
     * Controller function login for admin account
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function adminLogin(Request $request)
    {
        $param = $request->all();
        if (!isset($param['email']) || !isset($param['password'])) {
            return app()->make(ResponseHelper::class)->validation(trans('validation.validate_login'));
        }
        if (Auth::attempt(['email' => $param['email'], 'password' => $param['password']])) {
            if (Auth::user()->role == config('const.admin')) {
                $user = $this->userRepository->find(Auth::user()->id);
                $token = $user->createToken(Auth::user()->email . '_' . Auth::id());
                $token->profiles = Auth::user();
                return app()->make(ResponseHelper::class)->success($token);
            } else {
                $this->logout($request);
                return app()->make(ResponseHelper::class)->unAuthenticated();
            }
        } else {
            return app()->make(ResponseHelper::class)->unAuthenticated();
        }
    }
}
