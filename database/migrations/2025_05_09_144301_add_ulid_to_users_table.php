<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Uid\Ulid;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ulid', 26)->nullable()->after('id');
        });
        
        // Generate ULIDs for existing users
        $this->generateUlidsForExistingUsers();
        
        // Make the column unique and not nullable after filling it
        Schema::table('users', function (Blueprint $table) {
            $table->string('ulid', 26)->nullable(false)->unique()->change();
        });
    }
    
    /**
     * Generate ULIDs for all existing users
     */
    private function generateUlidsForExistingUsers(): void
    {
        $users = DB::table('users')->get();
        
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['ulid' => (new Ulid())->toBase32()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ulid');
        });
    }
};
