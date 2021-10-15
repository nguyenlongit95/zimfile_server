<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class LoginAndRegisterController extends Controller
{
    //
    protected $userRepository;

    /**
     * Construct function
     */
    public function __construct(UserRepositoryInterface $userReporitory)
    {
        $this->userRepository = $userReporitory;
    }

    /**
     * Function render view login
     */
    public function getLogin() {
        return view("Login");
    }

    /**
     * Function check data login post and return to admin panel
     */
    public function postLogin(Request $request) {
        if(Auth::attempt(["email" => $request->email,"password" => $request->password])) {
            if (Auth::user()->role == 0) {
                return redirect("admin/dashboard")->with("thong_bao","Login success,welcome adminstator!");
            }else {
                Auth::logout();
                return redirect('/admin/login')->with("thong_bao","You don't have to pay attention to the staff.");
            }
        }else{
            return redirect('/admin/login')->with("thong_bao","Login false, please check again username or password");
        }
    }
}
