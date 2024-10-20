<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ward')->nullable(); // Phường
            $table->string('district')->nullable(); // Quận
            $table->string('city')->nullable(); // Thành phố (đã có)
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ward', 'district', 'city']);
        });
    }
};
