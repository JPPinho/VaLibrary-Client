<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;
use App\Models\User;

class BookAndAuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates specific books and their authors, handling the
     * many-to-many relationship. It's idempotent and can be run multiple times.
     */
    public function run(): void
    {
        $owner = User::first();
        if (!$owner) {
            $this->command->error('No users found in the database. Please run the UserSeeder first.');
            return;
        }

        $gloriaFurman = Author::firstOrCreate(['name' => 'Gloria Furman']);

        $vislumbres = Book::firstOrCreate(
            ['name' => 'Vislumbres da Graça - Valorizando o Evangelho na rotina do lar'],
            [
                'owner_id' => $owner->id,
                'language' => 'pt_pt'
            ]
        );

        $vislumbres->authors()->syncWithoutDetaching([$gloriaFurman->id]);

        $this->command->info("Seeded: '{$vislumbres->name}' by {$gloriaFurman->name}");

        $michaelFoster = Author::firstOrCreate(['name' => 'Michael Foster']);
        $dominicTennant = Author::firstOrCreate(['name' => 'Dominic Bnonn Tennant']);

        $itsGood = Book::firstOrCreate(
            ['name' => "It's good to be a man - A handbook for Godly masculinity"],
            [
                'owner_id' => $owner->id,
                'language' => 'en'
            ]
        );

        $itsGood->authors()->syncWithoutDetaching([$michaelFoster->id, $dominicTennant->id]);

        $this->command->info("Seeded: '{$itsGood->name}' by {$michaelFoster->name} & {$dominicTennant->name}");
    }
}
