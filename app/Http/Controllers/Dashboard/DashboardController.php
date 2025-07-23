<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::getUser();

        $user->load('books', 'activeLoans.book');

        $lentBooks = Book::query()
            ->where('owner_id', $user->id)
            ->whereHas('loans', function ($query) use ($user) {
                $query->whereNull('returned_at');
                $query->where('borrower_id', '!=', $user->id);
            })
            ->with(['loans' => function ($query) use ($user) {
                $query->whereNull('returned_at')
                ->where('borrower_id', '!=', $user->id)
                ->with('borrower');
            }])->get();
        return view('dashboard', [
            'user' => $user,
            'lentBooks' => $lentBooks,
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
