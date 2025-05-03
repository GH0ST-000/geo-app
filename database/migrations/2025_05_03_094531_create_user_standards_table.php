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
        Schema::create('user_standards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('slug'); // honey_standard, dairy_standard, crop_standard
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable(); // image, document, pdf, etc.
            $table->string('file_path')->nullable();
            $table->timestamps();
            
            // Add an index for faster lookups by user and slug
            $table->index(['user_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_standards');
    }
};
