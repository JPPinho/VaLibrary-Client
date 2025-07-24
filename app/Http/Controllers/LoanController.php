<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Loan::with(['book.owner', 'borrower'])->latest();

        if ($user->role !== 'admin') {
            $query->where(function ($q) use ($user) {
                $q->where('borrower_id', $user->id)
                    ->orWhereHas('book', function ($bookQuery) use ($user) {
                        $bookQuery->where('owner_id', $user->id);
                    });
            });

        }

        $loans = $query->paginate(15);

        return view('loans.index', [
            'loans' => $loans
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Loan $loan)
    {
        $ownerId = $loan->book->owner_id;

        if (Auth::id() !== $ownerId && Auth::user()->role !== 'admin') {
            abort(403, 'You are not authorized to modify this loan.');
        }

        if ($loan->returned_at) {
            return back()->with('error', 'This loan has already been marked as returned.');
        }

        $loan->update(['returned_at' => Carbon::now()]);

        return back()->with('status', 'Book successfully marked as returned.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        //
    }
}
