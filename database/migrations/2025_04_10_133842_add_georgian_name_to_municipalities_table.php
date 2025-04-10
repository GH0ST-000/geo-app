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
        Schema::table('municipalities', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
            $table->string('name_ka')->after('name_en');
            $table->renameColumn('region', 'region_en');
            $table->string('region_ka')->after('region_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('municipalities', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
            $table->dropColumn('name_ka');
            $table->renameColumn('region_en', 'region');
            $table->dropColumn('region_ka');
        });
    }
};
