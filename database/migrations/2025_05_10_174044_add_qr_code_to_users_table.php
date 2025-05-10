<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('qr_code')->nullable()->after('ulid');
        });
        
        // Update existing users to set qr_code value
        $this->updateQrCodesForExistingUsers();
    }
    
    /**
     * Set qr_code value for all existing users
     */
    private function updateQrCodesForExistingUsers(): void
    {
        $users = DB::table('users')->get();
        $qrGeneratorUrl = env('QR_GENERATOR', 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&format=png&data=https://geogapp.site/cert-farmer/');
        
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['qr_code' => $qrGeneratorUrl . $user->ulid]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('qr_code');
        });
    }
};
