<?php

namespace App\Http\services\user;

use App\Models\User;
use Illuminate\Support\Collection;

class AccountAdminService
{
    public function getAll()
    {
        return User::where('Role', 'user')->orderBy('id', 'desc')->paginate(15);
    }

    public function update($request, $id)
    {
        $user = User::find($id);
        if(!$user){
            return false;
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->city = $request->input('city');
        $user->save();
        return true;
    }

    public function changePassword($request, $id)
    {
        $user = User::find($id);
        if(!$user){
            return false;
        }
        if ($request->input('password') != $request->input('password_confirmation')) {
            return false;
        }
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return true;
    }
}