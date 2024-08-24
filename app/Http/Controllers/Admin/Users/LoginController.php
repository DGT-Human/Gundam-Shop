<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.users.login', ['title' => 'Đăng nhập hệ thống']);
    }

    public function store(Request $request){

        $request -> validate(['email' => 'required|email:filter', 'password' => 'required']);
        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $request->input('remember')))
        {
            return redirect()->route('index');
        }

        $request -> session() -> flash('error', 'Tài khoản hoặc mật khẩu không đúng');
        return redirect()->back();
    }

}
