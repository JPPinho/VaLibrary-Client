<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Author;
use App\Models\Loan;
use App\Models\InvitationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds to create a rich set of demo data.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $this->command->info('Starting demo data seeder...');

            $inviteCode = InvitationCode::firstOrCreate(
                ['code' => 'DEMO-SEED-CODE'],
            );
            $languageIds = Language::pluck('id');
            if ($languageIds->isEmpty()) {
                $this->command->error('No languages found. Please run the LanguageSeeder first.');
                return; // Stop the seeder
            }
            $allUsers = collect();

            for ($i = 1; $i <= 10; $i++) {
                $user = User::create([
                    'name' => "User {$i}",
                    'email' => "users{$i}@example.com",
                    'password' => Hash::make('password'),
                    'role' => 'regular',
                    'invitation_code_id' => $inviteCode->id,
                ]);
                $allUsers->push($user);

                $this->command->info("Created: User {$i}");

                for ($j = 1; $j <= 3; $j++) {
                    $author = Author::create([
                        'name' => "Author for User {$i} Book {$j}"
                    ]);

                    $book = Book::create([
                        'name' => "User {$i} - Book {$j}",
                        'owner_id' => $user->id,
                        'language_id' => $languageIds->random()
                    ]);

                    $book->authors()->attach($author->id);
                }
            }

            $this->command->info('Creating loans for demo books...');
            $allBooks = Book::whereIn('owner_id', $allUsers->pluck('id'))->get();

            foreach ($allBooks as $book) {
                if (mt_rand(0, 1) === 1) {
                    $borrower = $allUsers->where('id', '!=', $book->owner_id)->random();

                    $loan = Loan::create([
                        'book_id' => $book->id,
                        'borrower_id' => $borrower->id,
                        'due_at' => now()->addWeeks(mt_rand(1, 4)),
                        'created_at' => now()->subDays(mt_rand(1, 20)),
                        'returned_at' => null,
                    ]);

                    if (mt_rand(0, 1) === 1) {
                        $loan->returned_at = $loan->created_at->addDays(mt_rand(3, 10));
                        $loan->save();
                    }
                }
            }
            $this->command->info('Demo data seeding complete!');
        });
    }
}
