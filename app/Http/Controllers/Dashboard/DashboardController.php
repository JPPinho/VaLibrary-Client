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

        $user->load('books', 'activeLoans.book','lentBooks','loanRequests','books.loanRequests');
        return view('dashboard', [
            'user' => $user
        ]);
    }
}
