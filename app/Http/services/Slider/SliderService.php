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
            // Debug request
            \Log::info('Request Data:', [
                'all' => $request->all(),
                'has_file' => $request->hasFile('thumb')
            ]);

            // Xử lý file
            $fileName = '';
            if ($request->hasFile('thumb')) {
                $file = $request->file('thumb');
                $fileName = $file->store('sliders', 'public');
                \Log::info('File stored as: ' . $fileName);
            }

            // Tạo slider
            $slider = Slider::create([
                'name' => $request->input('name'),
                'url' => $request->input('url'),
                'thumb' => $request->input('thumb'),
                'sort_by' => (int)$request->input('sort_by'),
                'active' => (int)$request->input('active')
            ]);

            \Log::info('Created slider:', ['slider' => $slider]);

            if ($slider) {
                Session::flash('success', 'Thêm Slider thành công');
                return true;
            }

            Session::flash('error', 'Thêm Slider không thành công');
            return false;

        } catch (\Exception $err) {
            \Log::error('Error creating slider: ' . $err->getMessage());
            Session::flash('error', 'Thêm Slider không thành công');
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