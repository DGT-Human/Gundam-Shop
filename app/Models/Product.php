<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'content',
        'menu_id',
        'price',
        'price_sale',
        'quantity',
        'active',
        'thumb'
    ];


    public function menu()
    {
        return $this->hasOne(Menu::class, 'id', 'menu_id')->withDefault(['name' => 'Chưa có danh mục']);
    }
}
