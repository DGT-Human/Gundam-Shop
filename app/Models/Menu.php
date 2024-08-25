<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'content',
        'slug',
        'active',
        'thumb'
    ];

    public function products(){
        return $this->hasMany(Product::class, 'menu_id', 'id');
    }

    public function parent(){
        return $this->hasOne(Menu::class, 'id', 'parent_id');
    }

}
