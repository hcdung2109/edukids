<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('quantity')->default(1);
            $table->text('note')->nullable();
            $table->foreignId('center_id')->constrained('centers')->cascadeOnDelete();
            $table->foreignId('center_class_id')->nullable()->constrained('center_classes')->nullOnDelete();
            $table->foreignId('managed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_tools');
    }
};
