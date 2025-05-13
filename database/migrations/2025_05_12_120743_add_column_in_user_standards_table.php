<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('user_standards', function (Blueprint $table) {
            $table->text('reject_reason')->after('is_verified')->nullable();
        });
    }


    public function down(): void
    {
        Schema::table('user_standards', function (Blueprint $table) {
            $table->dropColumn('reject_reason');
        });
    }
};
