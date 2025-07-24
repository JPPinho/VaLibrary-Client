<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanRequest;
use Illuminate\Support\Facades\DB;

class LoanRequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        if ($users->count() === 0 || $books->count() < 2) {
            $this->command->warn('Not enough users or books to seed loan requests. Skipping.');
            return;
        }

        foreach ($users as $borrower) {
            DB::transaction(function () use ($borrower, $books) {
                $bookForFulfilled = $books->where('owner_id', '!=', $borrower->id)->random();

                $historicalLoan = Loan::firstOrCreate(
                    [
                        'book_id' => $bookForFulfilled->id,
                        'borrower_id' => $borrower->id,
                        'returned_at' => now()->subDays(rand(5, 15)),
                    ],
                    [
                        'created_at' => now()->subDays(rand(20, 30)),
                    ]
                );

                LoanRequest::firstOrCreate(
                    [
                        'book_id' => $bookForFulfilled->id,
                        'borrower_id' => $borrower->id,
                        'loan_id' => $historicalLoan->id,
                    ]
                );

                $availableBookForRequest = Book::query()
                    ->where('owner_id', '!=', $borrower->id)
                    ->whereDoesntHave('loans', function ($query) {
                        $query->whereNull('returned_at');
                    })
                    ->whereDoesntHave('loanRequests', function ($query) {
                        $query->whereNull('loan_id');
                    })
                    ->inRandomOrder()
                    ->first();

                if ($availableBookForRequest) {
                    LoanRequest::firstOrCreate(
                        [
                            'book_id' => $availableBookForRequest->id,
                            'borrower_id' => $borrower->id,
                            'loan_id' => null
                        ]
                    );
                    $this->command->info("Seeded requests for {$borrower->email}");
                } else {
                    $this->command->warn("Could not find a valid book for a pending request for {$borrower->email}. Skipped.");
                }
            });
        }
    }
}
