<?php

namespace App\Http\services\Product;

use App\Models\Product;
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

    public function show($request){
        $query = Product::select('id', 'name', 'price', 'price_sale', 'thumb')->where('active', 1);
        if($request->input('price') ){
            $query->orderBy('price', $request->input('price'));
        };
           return $query->orderByDesc('id')->paginate(8);
    }

}