<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Note;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() === 0 || Book::count() === 0 || Status::count() === 0) {
            $this->command->warn('Cannot seed notes. Please ensure users, books, and statuses are seeded first.');
            return;
        }

        Note::factory()->count(40)->create();

        $this->command->info('Successfully seeded 40 random notes.');
    }}
