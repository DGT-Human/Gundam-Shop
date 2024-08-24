<?php

namespace App\Http\View\Composers;

use App\Repositories\UserRepository;
use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Create a new profile composer.
     */
    public function __construct() {

    }

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $menus = Menu::select('id', 'name', 'parent_id')->where('active', 1)->orderByDesc('id')->get();
        $view->with('menus', $menus);
    }
}