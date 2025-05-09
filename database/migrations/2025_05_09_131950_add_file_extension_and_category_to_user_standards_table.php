<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_standards', function (Blueprint $table) {
            $table->string('file_extension')->nullable()->after('file_path');
            $table->string('file_category')->nullable()->after('file_extension');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_standards', function (Blueprint $table) {
            $table->dropColumn('file_extension');
            $table->dropColumn('file_category');
        });
    }
};
