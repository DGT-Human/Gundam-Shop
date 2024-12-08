<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\services\Product\ProductService;

class ForgotPasswordController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    // Hiển thị form quên mật khẩu
    public function showForgotPasswordForm()
    {
        return view('account.forgot-password', [
            'title' => 'Quên mật khẩu',
            'productCarts' => $this->productService->getAll(),
        ]);
    }

    // Xử lý form quên mật khẩu
    public function handleForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Kiểm tra email có tồn tại trong hệ thống không
        $user = DB::table('users')->where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        if ($user->role === 'admin') {
            return redirect()->route('index');
        }
        // Lưu email vào session để sử dụng cho bước reset password
        session(['reset_email' => $request->email]);

        // Điều hướng đến trang reset password
        return redirect()->route('reset.password');
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetPasswordForm()
    {
        // Kiểm tra xem đã có email từ bước trước chưa
        if (!session('reset_email')) {
            session()->flash('error', 'Email not found');
            return redirect()->route('forgot.password');
        }

        return view('account.reset-password',[
            'title' => 'Đặt lại mật khẩu',
            'productCarts' => $this->productService->getAll(),
        ]);
    }

    // Xử lý đặt lại mật khẩu
    public function handleResetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        // Lấy email từ session
        $email = session('reset_email');
        if (!$email) {
            session()->flash('error', 'Email not found');
            return redirect()->route('forgot.password');
        }

        // Cập nhật mật khẩu mới cho người dùng
        DB::table('users')->where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Xóa email khỏi session
        session()->forget('reset_email');
        session()->flash('success', 'Password has been reset successfully!');
        // Chuyển hướng về trang đăng nhập
        return redirect('/users/login');
    }
}
