<?php

namespace App\Http\services;
use Illuminate\Support\Facades\Storage;
class UploadService
{
    public function store($request)
    {
        try{
            if ($request->hasFile('file')) {
                // Lấy tên tệp gốc
                $file = $request->file('file')->getClientOriginalName();

                // Tạo đường dẫn đầy đủ cho thư mục lưu trữ
                $pathFull = 'uploads/' . date('Y/m/d');

                // Lưu trữ tệp trong 'storage/app/public/uploads/YYYY/MM/DD'
                $path = $request->file('file')->storeAs('public/' . $pathFull, $file);

                // Trả về URL đầy đủ cho tệp
                return Storage::url($path);
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}