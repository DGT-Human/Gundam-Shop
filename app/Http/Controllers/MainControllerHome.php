<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Slider\SliderService;
use App\Http\Services\Menu\MenuService;

class MainControllerHome extends Controller
{
    protected $sliderService;
    protected $menuService;

    public function __construct(SliderService $sliderService, MenuService $menuService)
    {
        $this->sliderService = $sliderService;
        $this->menuService = $menuService;
    }
    public function index()
    {
        return view('main',[
            'title' => 'Shop Fashion Online',
            'sliders' => $this->sliderService->show(),
            'menus' => $this->menuService->show(),
        ]);
    }
}
