<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id'];

    // Thiết lập mối quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Thiết lập mối quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

