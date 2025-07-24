<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userAuth = Auth::user();
        $query = User::with(['loans', 'activeLoans', 'notes' ,'books', 'lentBooks.loans'])->latest();

        if ($userAuth->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $users = $query->paginate(15);

        return view('users.index', [
            'users' => $users
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
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user->load([
            'books',
            'activeLoans.book',
            'lentBooks.loans.borrower',
            'loanRequests',
            'books.loanRequests'
        ]);

        return view('users.show', [
            'user' => $user
        ]);
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
