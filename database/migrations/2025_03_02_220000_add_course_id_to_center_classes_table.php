<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('center_classes', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('center_id')->constrained('courses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('center_classes', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });
    }
};
