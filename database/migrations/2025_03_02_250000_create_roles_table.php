<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique()->comment('Mã vai trò: admin, teacher, staff...');
            $table->string('label')->comment('Tên hiển thị');
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false)->comment('Vai trò hệ thống không xóa được');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
