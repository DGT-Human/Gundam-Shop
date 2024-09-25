<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('account.login', ['title' => 'Đăng nhập']);
    }

    public function register()
    {
        return view('account.signup', ['title' => 'Đăng ký']);
    }

    public function store(Request $request){

        $request -> validate(['email' => 'required|email:filter', 'password' => 'required']);
        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $request->input('remember')))
        {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('index'); // Chuyển hướng đến trang admin
            }
            return redirect()->route('home');
        }

        $request -> session() -> flash('error', 'Tài khoản hoặc mật khẩu không đúng');
        return redirect()->back();
    }

    public function postregister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:filter|unique:users,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ]);
        $request->merge(['password' => Hash::make($request->password)]);
        $user = User::create($request->all());
        Auth::login($user);
        session()->flash('success', 'Đăng ký tài khoản thành công');
        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

}
