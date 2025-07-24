<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Language;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BooksController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with([
            'authors',
            'owner',
            'language',
            'loanRequests' => function ($query) {
                $query->where('borrower_id', auth()->id())
                    ->whereNull('loan_id');
            }
        ]);

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

        $query->when($request->filled('language_id'), function ($q) use ($request) {
            $q->where('language_id', $request->language_id);
        });

        $query->when($request->filled('available'), function ($q) {
            $q->whereDoesntHave('loans', function ($subQ) {
                $subQ->whereNull('returned_at');
            });
        });

        $authors = Author::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();

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
        return view('books.create', [
            'authors' => Author::orderBy('name')->get(),
            'languages' => Language::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'language_id' => 'required|exists:languages,id',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:author,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $book = Book::create([
                    'name' => $validated['name'],
                    'language_id' => $validated['language_id'],
                    'owner_id' => auth()->id(),
                ]);

                $book->authors()->sync($validated['authors']);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'There was a problem creating the book: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('books.index')->with('success', 'Book created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('authors', 'owner', 'loans.borrower', 'notes.user', 'notes.status');
        $user = Auth::user();

        $isOwner = $user->id === $book->owner_id;
        $hasBorrowed = $book->loans()->where('borrower_id', $user->id)->exists();

        return view('books.show', [
            'book' => $book,
            'canAddNote' => $isOwner || $hasBorrowed || $user->role === 'admin',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        $authors = Author::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();

        return view('books.edit', [
            'book' => $book,
            'authors' => $authors,
            'languages' => $languages,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('book')->ignore($book->id)],
            'language_id' => 'required|exists:languages,id',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:author,id',
        ]);

        try {
            DB::transaction(function () use ($validated, $book) {
                $book->update([
                    'name' => $validated['name'],
                    'language_id' => $validated['language_id'],
                ]);

                $book->authors()->sync($validated['authors']);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'There was a problem updating the book.')->withInput();
        }

        return redirect()->route('books.show', $book)->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
