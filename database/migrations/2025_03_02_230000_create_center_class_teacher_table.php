<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('center_class_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_class_id')->constrained('center_classes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['center_class_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('center_class_teacher');
    }
};
