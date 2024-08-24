<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Slider\SliderService;
use App\Http\Requests\Slider\SliderRequest;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    protected $slider;
    public function __construct(SliderService $slider) //xai cho nhieu noi
    {
        $this->slider = $slider;
    }
    public function create(){
        return view('admin.sliders.add', [
            'title' => 'Thêm sliders mới'
        ]);
    }

    public function store(SliderRequest $request){
        $this->slider->insert($request);

        Return redirect()->back();
    }

    public function index(){
        $sliders = $this->slider->get();
        return view('admin.sliders.list', [
            'title' => 'Danh sách sliders',
            'sliders' => $sliders
        ]);
    }

    public function show(Slider $slider){
        return view('admin.sliders.edit', [
            'title' => 'Cập nhật slider',
            'slider' => $slider
        ]);
    }

    public function update(SliderRequest $request, Slider $slider){
        $this->slider->update($request, $slider);
        return redirect('admin/sliders/list');
    }

    public function destroy(Request $request)
    {
        $result = $this->slider->delete($request);
        if ($result) {
            return response()->json(['error' => false, 'message' => 'Xóa thành công']);
        }
            return response()->json(['error' => true , 'message' => 'Xóa thất bại']);
    }
}
