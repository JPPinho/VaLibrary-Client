<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::firstOrCreate(['code' => 'en'], ['name' => 'English']);
        Language::firstOrCreate(['code' => 'pt_pt'], ['name' => 'Português (Portugal)']);
        Language::firstOrCreate(['code' => 'pt_br'], ['name' => 'Português (Brasil)']);
        Language::firstOrCreate(['code' => 'es_es'], ['name' => 'Espanhol']);
        Language::firstOrCreate(['code' => 'fr_fr'], ['name' => 'Francês']);
        $this->command->info('Default languages have been seeded.');
    }
}
