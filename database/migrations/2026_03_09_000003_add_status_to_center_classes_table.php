<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('center_classes', function (Blueprint $table) {
            $table->string('status', 30)->default('not_started')->after('is_active')
                ->comment('not_started, in_progress, paused, completed');
        });
    }

    public function down(): void
    {
        Schema::table('center_classes', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
