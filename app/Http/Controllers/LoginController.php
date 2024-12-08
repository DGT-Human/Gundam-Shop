<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\Product\ProductService;

class LoginController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {
        return view('account.login', [
            'title' => 'Đăng nhập',
            'productCarts' => $this->productService->getAll()
            ]);
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

        $request -> session() -> flash('error', 'Account not found or password is incorrect');
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
        session()->flash('success', 'Register successfully');
        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

}
