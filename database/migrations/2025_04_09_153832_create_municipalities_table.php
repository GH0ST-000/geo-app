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
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region')->nullable();
            $table->timestamps();
        });

        // Seed the municipalities data
        $this->seedMunicipalities();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }

    /**
     * Seed municipalities of Georgia
     */
    private function seedMunicipalities(): void
    {
        $municipalities = [
            // Tbilisi
            ['name' => 'Tbilisi', 'region' => 'Tbilisi'],
            
            // Adjara
            ['name' => 'Batumi', 'region' => 'Adjara'],
            ['name' => 'Kobuleti', 'region' => 'Adjara'],
            ['name' => 'Khelvachauri', 'region' => 'Adjara'],
            ['name' => 'Keda', 'region' => 'Adjara'],
            ['name' => 'Shuakhevi', 'region' => 'Adjara'],
            ['name' => 'Khulo', 'region' => 'Adjara'],
            
            // Guria
            ['name' => 'Ozurgeti', 'region' => 'Guria'],
            ['name' => 'Lanchkhuti', 'region' => 'Guria'],
            ['name' => 'Chokhatauri', 'region' => 'Guria'],
            
            // Imereti
            ['name' => 'Kutaisi', 'region' => 'Imereti'],
            ['name' => 'Tskaltubo', 'region' => 'Imereti'],
            ['name' => 'Samtredia', 'region' => 'Imereti'],
            ['name' => 'Chiatura', 'region' => 'Imereti'],
            ['name' => 'Tkibuli', 'region' => 'Imereti'],
            ['name' => 'Sachkhere', 'region' => 'Imereti'],
            ['name' => 'Zestafoni', 'region' => 'Imereti'],
            ['name' => 'Kharagauli', 'region' => 'Imereti'],
            ['name' => 'Terjola', 'region' => 'Imereti'],
            ['name' => 'Bagdati', 'region' => 'Imereti'],
            ['name' => 'Vani', 'region' => 'Imereti'],
            ['name' => 'Khoni', 'region' => 'Imereti'],
            
            // Kakheti
            ['name' => 'Telavi', 'region' => 'Kakheti'],
            ['name' => 'Gurjaani', 'region' => 'Kakheti'],
            ['name' => 'Sagarejo', 'region' => 'Kakheti'],
            ['name' => 'Dedoplistskaro', 'region' => 'Kakheti'],
            ['name' => 'Sighnaghi', 'region' => 'Kakheti'],
            ['name' => 'Lagodekhi', 'region' => 'Kakheti'],
            ['name' => 'Akhmeta', 'region' => 'Kakheti'],
            ['name' => 'Kvareli', 'region' => 'Kakheti'],
            
            // Mtskheta-Mtianeti
            ['name' => 'Mtskheta', 'region' => 'Mtskheta-Mtianeti'],
            ['name' => 'Dusheti', 'region' => 'Mtskheta-Mtianeti'],
            ['name' => 'Tianeti', 'region' => 'Mtskheta-Mtianeti'],
            ['name' => 'Kazbegi', 'region' => 'Mtskheta-Mtianeti'],
            
            // Racha-Lechkhumi and Kvemo Svaneti
            ['name' => 'Ambrolauri', 'region' => 'Racha-Lechkhumi and Kvemo Svaneti'],
            ['name' => 'Oni', 'region' => 'Racha-Lechkhumi and Kvemo Svaneti'],
            ['name' => 'Tsageri', 'region' => 'Racha-Lechkhumi and Kvemo Svaneti'],
            ['name' => 'Lentekhi', 'region' => 'Racha-Lechkhumi and Kvemo Svaneti'],
            
            // Samegrelo-Zemo Svaneti
            ['name' => 'Zugdidi', 'region' => 'Samegrelo-Zemo Svaneti'],
            ['name' => 'Senaki', 'region' => 'Samegrelo-Zemo Svaneti'],
            ['name' => 'Martvili', 'region' => 'Samegrelo-Zemo Svaneti'],
            ['name' => 'Khobi', 'region' => 'Samegrelo-Zemo Svaneti'],
            ['name' => 'Poti', 'region' => 'Samegrelo-Zemo Svaneti'],
            ['name' => 'Abasha', 'region' => 'Samegrelo-Zemo Svaneti'],
            ['name' => 'Chkhorotsku', 'region' => 'Samegrelo-Zemo Svaneti'],
            ['name' => 'Tsalenjikha', 'region' => 'Samegrelo-Zemo Svaneti'],
            ['name' => 'Mestia', 'region' => 'Samegrelo-Zemo Svaneti'],
            
            // Samtskhe-Javakheti
            ['name' => 'Akhaltsikhe', 'region' => 'Samtskhe-Javakheti'],
            ['name' => 'Borjomi', 'region' => 'Samtskhe-Javakheti'],
            ['name' => 'Adigeni', 'region' => 'Samtskhe-Javakheti'],
            ['name' => 'Aspindza', 'region' => 'Samtskhe-Javakheti'],
            ['name' => 'Akhalkalaki', 'region' => 'Samtskhe-Javakheti'],
            ['name' => 'Ninotsminda', 'region' => 'Samtskhe-Javakheti'],
            
            // Kvemo Kartli
            ['name' => 'Rustavi', 'region' => 'Kvemo Kartli'],
            ['name' => 'Gardabani', 'region' => 'Kvemo Kartli'],
            ['name' => 'Marneuli', 'region' => 'Kvemo Kartli'],
            ['name' => 'Bolnisi', 'region' => 'Kvemo Kartli'],
            ['name' => 'Dmanisi', 'region' => 'Kvemo Kartli'],
            ['name' => 'Tetritskaro', 'region' => 'Kvemo Kartli'],
            ['name' => 'Tsalka', 'region' => 'Kvemo Kartli'],
            
            // Shida Kartli
            ['name' => 'Gori', 'region' => 'Shida Kartli'],
            ['name' => 'Khashuri', 'region' => 'Shida Kartli'],
            ['name' => 'Kareli', 'region' => 'Shida Kartli'],
            ['name' => 'Kaspi', 'region' => 'Shida Kartli'],
        ];

        foreach ($municipalities as $municipality) {
            DB::table('municipalities')->insert([
                'name' => $municipality['name'],
                'region' => $municipality['region'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
