<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('authors', 'owner');


        $query->when($request->has('my_collection'), function ($q) {
            $q->where('owner_id', auth()->id());
        });

        $query->when($request->filled('title'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->title . '%');
        });

        $query->when($request->filled('owner'), function ($q) use ($request) {
            $q->whereHas('owner', function ($subQ) use ($request) {
                $subQ->where('name', 'like', '%' . $request->owner . '%');
            });
        });

        $query->when($request->filled('author'), function ($q) use ($request) {
            $q->whereHas('authors', function ($subQ) use ($request) {
                $subQ->where('id', $request->author);
            });
        });

        $query->when($request->filled('language'), function ($q) use ($request) {
            $q->where('language', $request->language);
        });

        $query->when($request->filled('available'), function ($q) {
            $q->whereDoesntHave('loans', function ($subQ) {
                $subQ->whereNull('returned_at'); // No loans where returned_at is null
            });
        });

        $authors = Author::orderBy('name')->get();
        $languages = Book::select('language')->distinct()->orderBy('language')->get();

        $books = $query->latest()->paginate(15)->withQueryString();

        return view('books.index', [
            'books' => $books,
            'authors' => $authors,
            'languages' => $languages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create',
        [
            'authors' => Author::all()
        ]);
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
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
