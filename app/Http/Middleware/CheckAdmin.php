<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra user đã đăng nhập và có role là admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            // Trả về response 403 ngay lập tức
            return response()->json([
                'message' => 'Unauthorized access'
            ], 403);
            
            // Hoặc có thể redirect với thông báo
            // return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}
