<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột 'role' để xác định vai trò của người dùng (admin/user)
            $table->string('role')->default('user');

            // Thêm cột 'phone' để lưu số điện thoại
            $table->string('phone')->nullable();

            // Thêm cột 'address' để lưu địa chỉ
            $table->string('address')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
