<?php

namespace App\Http\services\Product;

use App\Models\Product;
use App\Models\Menu;
class ProductService
{
    const LIMIT = 4;
    public function get($page = null)
    {
        $product = Product::select('id', 'name', 'price', 'price_sale', 'thumb')->orderByDesc('id')->
        when($page != null, function ($query) use ($page) {
            $query->offset($page * self::LIMIT);
        })->limit(self::LIMIT)->get();
        return $product;
    }

    public function getAll(){
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')->where('active', 1)->orderByDesc('id')->get();
    }

    public function show($request, $id){
        $menuChild = Menu::where('parent_id', $id)->get();
        $query = Product::select('id', 'name', 'price', 'price_sale', 'thumb')->where('active', 1)->whereIn('menu_id', $menuChild->pluck('id'));
        if($request->input('price') ){
            $query->orderBy('price', $request->input('price'));
        };
           return $query->orderByDesc('id')->paginate(8);
    }

    public function getProduct($id){
        return Product::where('id', $id)
            ->where('active', 1)
            ->with('menu')
            ->firstOrFail();
    }

    public function more($id){
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
    }

    public function search(mixed $keyword)
    {
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')->where('name', 'like', "%$keyword%")->where('active', 1)->paginate(8);
    }

    public function searchMoney(mixed $input, mixed $input1, mixed $input2, mixed $input3)
    {
        if($input3 == 0){
            $menu = Menu::where('parent_id', $input2)->get();
            foreach ($menu as $item){
                $menuChild[] = $item->id;
            }
            return Product::select('id', 'name', 'price', 'price_sale', 'thumb')->whereIn('menu_id', $menuChild)->whereBetween('price', [$input, $input1])->where('active', 1)->get();        }
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')->whereBetween('price', [$input, $input1])->where('menu_id', $input2)->where('active', 1)->get();
    }

}