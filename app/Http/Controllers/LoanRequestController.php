<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:book,id',
        ]);

        $book = Book::find($validated['book_id']);
        $user = Auth::user();

        if ($book->owner_id === $user->id) {
            return back()->with('error', 'You cannot request a book you own.');
        }

        if (!$book->is_available) {
            return back()->with('error', 'This book is not available for loan.');
        }

        $existingRequest = LoanRequest::where('book_id', $book->id)
            ->where('borrower_id', $user->id)
            ->whereNull('loan_id')
            ->exists();

        if ($existingRequest) {
            return back()->with('error', 'You have already requested this book.');
        }

        LoanRequest::create([
            'book_id' => $book->id,
            'borrower_id' => $user->id,
        ]);

        return back()->with('status', 'Loan request submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanRequest $loanRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanRequest $loanRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LoanRequest $loanRequest)
    {
        $book = $loanRequest->book;
        $owner = $book->owner;

        if (Auth::id() !== $owner->id && Auth::user()->role !== 'admin') {
            abort(403, 'You are not authorized to approve this request.');
        }

        if ($loanRequest->loan_id) {
            return back()->with('error', 'This request has already been fulfilled.');
        }

        if (!$book->is_available) {
            return back()->with('error', 'This book is no longer available and cannot be loaned.');
        }

        try {
            DB::transaction(function () use ($loanRequest, $book) {
                $newLoan = Loan::create([
                    'book_id' => $book->id,
                    'borrower_id' => $loanRequest->borrower_id,
                    'due_at' => Carbon::now()->addMonth(),
                ]);

                $loanRequest->update(['loan_id' => $newLoan->id]);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while approving the loan. Please try again: ' . $e->getMessage());
        }

        return back()->with('status', 'Loan approved successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanRequest $loanRequest)
    {
        $canDelete = Auth::id() === $loanRequest->borrower_id ||
            Auth::id() === $loanRequest->book->owner_id ||
            Auth::user()->role === 'admin';

        if (!$canDelete) {
            abort(403, 'You are not authorized to cancel this request.');
        }

        $loanRequest->delete();

        return back()->with('status', 'Loan request successfully cancelled.');
    }
}
