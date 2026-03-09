<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month'); // 1..12
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'year', 'month'], 'teacher_salary_payments_unique');
            $table->index(['year', 'month'], 'teacher_salary_payments_year_month_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_salary_payments');
    }
};

