<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates specific loans between pre-defined users and books.
     */
    public function run(): void
    {
        $this->command->info('Starting LoanSeeder...');

        $adminUser = User::where('role', 'admin')->first();
        $regularUser = User::where('role', 'regular')->first();

        $vislumbresBook = Book::where('name', 'like', 'Vislumbres da Graça%')->first();
        $itsGoodBook = Book::where('name', 'like', "It's good to be a man%")->first();

        if (!$adminUser || !$regularUser || !$vislumbresBook || !$itsGoodBook) {
            $this->command->error('Required users or books not found. Please run User and Book seeders first.');
            return;
        }

        Loan::firstOrCreate(
            [
                'book_id' => $vislumbresBook->id,
                'borrower_id' => $regularUser->id,
            ],
            [
                'due_at' => now()->addWeeks(2),
                'returned_at' => null, // This loan is active
            ]
        );
        $this->command->info("'{$vislumbresBook->name}' was lent to '{$regularUser->name}'.");


        Loan::firstOrCreate(
            [
                'book_id' => $itsGoodBook->id,
                'borrower_id' => $adminUser->id,
            ],
            [
                'due_at' => now()->addDays(10),
                'returned_at' => now()->subDays(5), // This loan has been returned
            ]
        );
        $this->command->info("'{$itsGoodBook->name}' was lent to '{$adminUser->name}'.");

        $this->command->info('LoanSeeder completed successfully.');
    }
}
