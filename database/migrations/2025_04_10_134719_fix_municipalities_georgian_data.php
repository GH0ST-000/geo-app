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
        // First, delete all existing data
        DB::table('municipalities')->truncate();

        // Data for municipalities in both English and Georgian
        $municipalities = [
            // Tbilisi
            ['name_en' => 'Tbilisi', 'name_ka' => 'თბილისი', 'region_en' => 'Tbilisi', 'region_ka' => 'თბილისი'],
            
            // Adjara
            ['name_en' => 'Batumi', 'name_ka' => 'ბათუმი', 'region_en' => 'Adjara', 'region_ka' => 'აჭარა'],
            ['name_en' => 'Kobuleti', 'name_ka' => 'ქობულეთი', 'region_en' => 'Adjara', 'region_ka' => 'აჭარა'],
            ['name_en' => 'Khelvachauri', 'name_ka' => 'ხელვაჩაური', 'region_en' => 'Adjara', 'region_ka' => 'აჭარა'],
            ['name_en' => 'Keda', 'name_ka' => 'ქედა', 'region_en' => 'Adjara', 'region_ka' => 'აჭარა'],
            ['name_en' => 'Shuakhevi', 'name_ka' => 'შუახევი', 'region_en' => 'Adjara', 'region_ka' => 'აჭარა'],
            ['name_en' => 'Khulo', 'name_ka' => 'ხულო', 'region_en' => 'Adjara', 'region_ka' => 'აჭარა'],
            
            // Guria
            ['name_en' => 'Ozurgeti', 'name_ka' => 'ოზურგეთი', 'region_en' => 'Guria', 'region_ka' => 'გურია'],
            ['name_en' => 'Lanchkhuti', 'name_ka' => 'ლანჩხუთი', 'region_en' => 'Guria', 'region_ka' => 'გურია'],
            ['name_en' => 'Chokhatauri', 'name_ka' => 'ჩოხატაური', 'region_en' => 'Guria', 'region_ka' => 'გურია'],
            
            // Imereti
            ['name_en' => 'Kutaisi', 'name_ka' => 'ქუთაისი', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Tskaltubo', 'name_ka' => 'წყალტუბო', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Samtredia', 'name_ka' => 'სამტრედია', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Chiatura', 'name_ka' => 'ჭიათურა', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Tkibuli', 'name_ka' => 'ტყიბული', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Sachkhere', 'name_ka' => 'საჩხერე', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Zestafoni', 'name_ka' => 'ზესტაფონი', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Kharagauli', 'name_ka' => 'ხარაგაული', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Terjola', 'name_ka' => 'თერჯოლა', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Bagdati', 'name_ka' => 'ბაღდათი', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Vani', 'name_ka' => 'ვანი', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            ['name_en' => 'Khoni', 'name_ka' => 'ხონი', 'region_en' => 'Imereti', 'region_ka' => 'იმერეთი'],
            
            // Kakheti
            ['name_en' => 'Telavi', 'name_ka' => 'თელავი', 'region_en' => 'Kakheti', 'region_ka' => 'კახეთი'],
            ['name_en' => 'Gurjaani', 'name_ka' => 'გურჯაანი', 'region_en' => 'Kakheti', 'region_ka' => 'კახეთი'],
            ['name_en' => 'Sagarejo', 'name_ka' => 'საგარეჯო', 'region_en' => 'Kakheti', 'region_ka' => 'კახეთი'],
            ['name_en' => 'Dedoplistskaro', 'name_ka' => 'დედოფლისწყარო', 'region_en' => 'Kakheti', 'region_ka' => 'კახეთი'],
            ['name_en' => 'Sighnaghi', 'name_ka' => 'სიღნაღი', 'region_en' => 'Kakheti', 'region_ka' => 'კახეთი'],
            ['name_en' => 'Lagodekhi', 'name_ka' => 'ლაგოდეხი', 'region_en' => 'Kakheti', 'region_ka' => 'კახეთი'],
            ['name_en' => 'Akhmeta', 'name_ka' => 'ახმეტა', 'region_en' => 'Kakheti', 'region_ka' => 'კახეთი'],
            ['name_en' => 'Kvareli', 'name_ka' => 'ყვარელი', 'region_en' => 'Kakheti', 'region_ka' => 'კახეთი'],
            
            // Mtskheta-Mtianeti
            ['name_en' => 'Mtskheta', 'name_ka' => 'მცხეთა', 'region_en' => 'Mtskheta-Mtianeti', 'region_ka' => 'მცხეთა-მთიანეთი'],
            ['name_en' => 'Dusheti', 'name_ka' => 'დუშეთი', 'region_en' => 'Mtskheta-Mtianeti', 'region_ka' => 'მცხეთა-მთიანეთი'],
            ['name_en' => 'Tianeti', 'name_ka' => 'თიანეთი', 'region_en' => 'Mtskheta-Mtianeti', 'region_ka' => 'მცხეთა-მთიანეთი'],
            ['name_en' => 'Kazbegi', 'name_ka' => 'ყაზბეგი', 'region_en' => 'Mtskheta-Mtianeti', 'region_ka' => 'მცხეთა-მთიანეთი'],
            
            // Racha-Lechkhumi and Kvemo Svaneti
            ['name_en' => 'Ambrolauri', 'name_ka' => 'ამბროლაური', 'region_en' => 'Racha-Lechkhumi and Kvemo Svaneti', 'region_ka' => 'რაჭა-ლეჩხუმი და ქვემო სვანეთი'],
            ['name_en' => 'Oni', 'name_ka' => 'ონი', 'region_en' => 'Racha-Lechkhumi and Kvemo Svaneti', 'region_ka' => 'რაჭა-ლეჩხუმი და ქვემო სვანეთი'],
            ['name_en' => 'Tsageri', 'name_ka' => 'ცაგერი', 'region_en' => 'Racha-Lechkhumi and Kvemo Svaneti', 'region_ka' => 'რაჭა-ლეჩხუმი და ქვემო სვანეთი'],
            ['name_en' => 'Lentekhi', 'name_ka' => 'ლენტეხი', 'region_en' => 'Racha-Lechkhumi and Kvemo Svaneti', 'region_ka' => 'რაჭა-ლეჩხუმი და ქვემო სვანეთი'],
            
            // Samegrelo-Zemo Svaneti
            ['name_en' => 'Zugdidi', 'name_ka' => 'ზუგდიდი', 'region_en' => 'Samegrelo-Zemo Svaneti', 'region_ka' => 'სამეგრელო-ზემო სვანეთი'],
            ['name_en' => 'Senaki', 'name_ka' => 'სენაკი', 'region_en' => 'Samegrelo-Zemo Svaneti', 'region_ka' => 'სამეგრელო-ზემო სვანეთი'],
            ['name_en' => 'Martvili', 'name_ka' => 'მარტვილი', 'region_en' => 'Samegrelo-Zemo Svaneti', 'region_ka' => 'სამეგრელო-ზემო სვანეთი'],
            ['name_en' => 'Khobi', 'name_ka' => 'ხობი', 'region_en' => 'Samegrelo-Zemo Svaneti', 'region_ka' => 'სამეგრელო-ზემო სვანეთი'],
            ['name_en' => 'Poti', 'name_ka' => 'ფოთი', 'region_en' => 'Samegrelo-Zemo Svaneti', 'region_ka' => 'სამეგრელო-ზემო სვანეთი'],
            ['name_en' => 'Abasha', 'name_ka' => 'აბაშა', 'region_en' => 'Samegrelo-Zemo Svaneti', 'region_ka' => 'სამეგრელო-ზემო სვანეთი'],
            ['name_en' => 'Chkhorotsku', 'name_ka' => 'ჩხოროწყუ', 'region_en' => 'Samegrelo-Zemo Svaneti', 'region_ka' => 'სამეგრელო-ზემო სვანეთი'],
            ['name_en' => 'Tsalenjikha', 'name_ka' => 'წალენჯიხა', 'region_en' => 'Samegrelo-Zemo Svaneti', 'region_ka' => 'სამეგრელო-ზემო სვანეთი'],
            ['name_en' => 'Mestia', 'name_ka' => 'მესტია', 'region_en' => 'Samegrelo-Zemo Svaneti', 'region_ka' => 'სამეგრელო-ზემო სვანეთი'],
            
            // Samtskhe-Javakheti
            ['name_en' => 'Akhaltsikhe', 'name_ka' => 'ახალციხე', 'region_en' => 'Samtskhe-Javakheti', 'region_ka' => 'სამცხე-ჯავახეთი'],
            ['name_en' => 'Borjomi', 'name_ka' => 'ბორჯომი', 'region_en' => 'Samtskhe-Javakheti', 'region_ka' => 'სამცხე-ჯავახეთი'],
            ['name_en' => 'Adigeni', 'name_ka' => 'ადიგენი', 'region_en' => 'Samtskhe-Javakheti', 'region_ka' => 'სამცხე-ჯავახეთი'],
            ['name_en' => 'Aspindza', 'name_ka' => 'ასპინძა', 'region_en' => 'Samtskhe-Javakheti', 'region_ka' => 'სამცხე-ჯავახეთი'],
            ['name_en' => 'Akhalkalaki', 'name_ka' => 'ახალქალაქი', 'region_en' => 'Samtskhe-Javakheti', 'region_ka' => 'სამცხე-ჯავახეთი'],
            ['name_en' => 'Ninotsminda', 'name_ka' => 'ნინოწმინდა', 'region_en' => 'Samtskhe-Javakheti', 'region_ka' => 'სამცხე-ჯავახეთი'],
            
            // Kvemo Kartli
            ['name_en' => 'Rustavi', 'name_ka' => 'რუსთავი', 'region_en' => 'Kvemo Kartli', 'region_ka' => 'ქვემო ქართლი'],
            ['name_en' => 'Gardabani', 'name_ka' => 'გარდაბანი', 'region_en' => 'Kvemo Kartli', 'region_ka' => 'ქვემო ქართლი'],
            ['name_en' => 'Marneuli', 'name_ka' => 'მარნეული', 'region_en' => 'Kvemo Kartli', 'region_ka' => 'ქვემო ქართლი'],
            ['name_en' => 'Bolnisi', 'name_ka' => 'ბოლნისი', 'region_en' => 'Kvemo Kartli', 'region_ka' => 'ქვემო ქართლი'],
            ['name_en' => 'Dmanisi', 'name_ka' => 'დმანისი', 'region_en' => 'Kvemo Kartli', 'region_ka' => 'ქვემო ქართლი'],
            ['name_en' => 'Tetritskaro', 'name_ka' => 'თეთრიწყარო', 'region_en' => 'Kvemo Kartli', 'region_ka' => 'ქვემო ქართლი'],
            ['name_en' => 'Tsalka', 'name_ka' => 'წალკა', 'region_en' => 'Kvemo Kartli', 'region_ka' => 'ქვემო ქართლი'],
            
            // Shida Kartli
            ['name_en' => 'Gori', 'name_ka' => 'გორი', 'region_en' => 'Shida Kartli', 'region_ka' => 'შიდა ქართლი'],
            ['name_en' => 'Khashuri', 'name_ka' => 'ხაშური', 'region_en' => 'Shida Kartli', 'region_ka' => 'შიდა ქართლი'],
            ['name_en' => 'Kareli', 'name_ka' => 'ქარელი', 'region_en' => 'Shida Kartli', 'region_ka' => 'შიდა ქართლი'],
            ['name_en' => 'Kaspi', 'name_ka' => 'კასპი', 'region_en' => 'Shida Kartli', 'region_ka' => 'შიდა ქართლი'],
        ];

        // Insert the data with both English and Georgian names
        foreach ($municipalities as $municipality) {
            DB::table('municipalities')->insert([
                'name_en' => $municipality['name_en'],
                'name_ka' => $municipality['name_ka'],
                'region_en' => $municipality['region_en'],
                'region_ka' => $municipality['region_ka'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do here, we can't revert to a specific state
    }
};
