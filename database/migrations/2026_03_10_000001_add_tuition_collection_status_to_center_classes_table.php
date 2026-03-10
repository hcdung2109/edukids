<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('center_classes', function (Blueprint $table) {
            $table->string('tuition_collection_status', 30)->default('not_collected')->after('status')
                ->comment('not_collected, collecting, completed');
        });
    }

    public function down(): void
    {
        Schema::table('center_classes', function (Blueprint $table) {
            $table->dropColumn('tuition_collection_status');
        });
    }
};
