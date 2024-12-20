<?php

namespace App\Http\services\Menu;

use Illuminate\Support\Str;
use App\Models\Menu;
use Illuminate\Support\Facades\Session;


class MenuService
{
    public function create($request)
    {
        try {
            //Cach nay la cho tung cai
            return Menu::create([
                'name' => (string) $request->input('name'),
                'parent_id' => (string) $request->input('parent_id'),
                'description' => (string) $request->input('description'),
                'content' => (string) $request->input('content'),
                'active' => (string) $request->input('active'),
                'slug' => str::slug($request->input('name'),'-'),
                'thumb' => (string) $request->input('thumb')
            ]);

        } catch (\Exception $e) {
            Session::flash('error', 'Thêm menu thất bại');
            return false;
        }
        return true;
    }

    public function show(){
        return Menu::select('id', 'parent_id', 'slug', 'name','thumb')->where('parent_id', 1)->orderbyDesc('id')->get();
    }

    public function getParent(){
        return Menu::where('parent_id',0)->get();
    }

    public function getAll(){
        return Menu::orderbyDesc('id')->paginate(20);
    }

    public function destroy($request){
        $id = (int) $request -> input('id');
        $menu = Menu::where('id', $id)->first();
        if($menu){
            return Menu::where('id', $id)->orWhere('parent_id', $id)->delete();
        }

        return false;
    }

    public function update($request, $menu){
        try {
            if($request->input('parent_id') != $menu->id){
                $menu -> parent_id = (string) $request -> input('parent_id');
            }
            $menu -> name = (string) $request -> input('name');
            $menu -> description = (string) $request -> input('description');
            $menu -> content = (string) $request -> input('content');
            $menu -> active = (string) $request -> input('active');
            $menu -> slug = str::slug($request -> input('name'),'-');
            $menu -> thumb = (string) $request -> input('thumb');
            $menu -> save();
        } catch (\Exception $e) {
            Session::flash('error', 'Cập nhật menu thất bại');
            return false;
        }
        return true;
    }

    public function find($id){
        return Menu::where('id', $id)->where('active', 1)->firstOrFail();
    }

    public function getProducts($menu, $request){
        $query = $menu->products()->select('id', 'name', 'price', 'price_sale','thumb')->where('active', 1);

            if($request->input('price') ){
                $query->orderBy('price', $request->input('price'));
            };
        return $query->orderByDesc('id')->paginate(8);
    }

    public function search($keyword){
        return Menu::select('id', 'name')->where('name', 'like', "%$keyword%")->get();
    }
}
