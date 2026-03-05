<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_class_id')->constrained('center_classes')->cascadeOnDelete();
            $table->date('session_date');
            $table->string('note', 255)->nullable();
            $table->timestamps();
        });

        Schema::table('class_sessions', function (Blueprint $table) {
            $table->index(['center_class_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};
