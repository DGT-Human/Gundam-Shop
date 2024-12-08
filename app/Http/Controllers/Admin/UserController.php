<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Services\user\AccountAdminService;

class UserController extends Controller
{
    protected $AccountAdminService;

    public function __construct(AccountAdminService $AccountAdminService)
    {
        $this->AccountAdminService = $AccountAdminService;
    }
    public function index(){
        return view('admin.user.list', [
            'title' => 'Danh Sách Tài Khoản',
            'users' => $this->AccountAdminService->getAll()
        ]);
    }

    public function show($id){
        return view('admin.user.detail',[
            'title' => 'Chi tiết tài khoản',
            'user' => User::find($id),
            ]
        );
    }

    public function update(Request $request, $id){
        $this->AccountAdminService->update($request, $id);
        return redirect()->back()->with('success', 'Dữ liệu đã được cập nhật thành công');
    }

    public function changePassword(Request $request, $id){
        $this->AccountAdminService->changePassword($request, $id);
        return redirect()->back()->with('success', 'Đổi mật khẩu thành công');
    }
}
