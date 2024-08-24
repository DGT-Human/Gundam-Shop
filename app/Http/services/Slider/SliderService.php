<?php

namespace App\Http\services\Slider;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\Models\Slider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SliderService
{
    public function insert($request)
    {
        try {

            Slider::Create($request->all());
            Session::flash('success', 'Thêm mới thành công');
        } catch (\Exception $e) {
            Session::flash('error', 'Thêm mới thất bại');
            log::info($e->getMessage());
            return false;
        }
    }

    public function show()
    {
        return Slider::select('name', 'thumb', 'url')->orderByDesc('id')->get();
    }

    public function get()
    {
        return Slider::orderByDesc('id')->paginate(15);
    }

    public function update($request, $slider)
    {
        try {
            $slider->update($request->all());
            Session::flash('success', 'Cập nhật thành công');
        } catch (\Exception $e) {
            Session::flash('error', 'Cập nhật thất bại');
            log::info($e->getMessage());
            return false;
        }
    }

    public function delete($request)
    {
        $slider = Slider::where ('id', $request->input('id'))->first();
        if($slider){
            $path = str_replace('storage', 'public', $slider->thumb);

            // Kiểm tra xem ảnh có tồn tại hay không và xóa nó
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
            $slider->delete();
            return true;

        }else {
            return false;
        }
    }

}