<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('center_classes', function (Blueprint $table) {
            $table->decimal('hours_per_session', 4, 2)->default(2)->after('schedule');
        });
    }

    public function down(): void
    {
        Schema::table('center_classes', function (Blueprint $table) {
            $table->dropColumn('hours_per_session');
        });
    }
};

