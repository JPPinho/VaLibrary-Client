<?php

namespace App\Http\Controllers\Notes;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Note;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Note::query()
            ->with(['book', 'user'])
            ->select('note.*')
            ->join('book', 'note.book_id', '=', 'book.id')
            ->orderBy('book.name');

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $query->when($request->filled('search_term'), function ($q) use ($request) {
            $searchTerm = '%' . $request->search_term . '%';
            $q->where(function ($subQ) use ($searchTerm) {
                $subQ->where('note.body', 'like', $searchTerm)
                    ->orWhereHas('book', function ($bookQuery) use ($searchTerm) {
                        $bookQuery->where('name', 'like', $searchTerm);
                    });
            });
        });

        $notes = $query->paginate(20)->withQueryString();

        return view('notes.index', [
            'notes' => $notes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        $books = Book::query()
            ->where('owner_id', $user->id)
            ->orWhereHas('loans', function ($query) use ($user) {
                $query->where('borrower_id', $user->id);
            })
            ->orderBy('name')
            ->get();

        return view('notes.create', [
            'books' => $books
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:book,id',
            'body' => 'required|string|min:10',
            'page' => 'nullable|integer',
        ]);

        $book = Book::find($validated['book_id']);
        $user = Auth::user();

        $isOwner = $user->id === $book->owner_id;
        $hasBorrowed = $book->loans()->where('borrower_id', $user->id)->exists();

        if (!$isOwner && !$hasBorrowed) {
            abort(403, 'You are not authorized to add a note to this book.');
        }

        Note::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'body' => $validated['body'],
            'page' => $validated['page'],
            'status_id' => Status::where('status_code', 'Verified')->first()->id,
        ]);

        return redirect()->route('notes.index')->with('status', 'Note created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        //
    }

    public function createImageNote(Book $book)
    {
        return view('notes.create-image', [
            'book' => $book
        ]);
    }

    public function storeImageNote(Request $request, Book $book)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $note = Note::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'body' => 'Image Note - ' . now()->toFormattedDateString(),
            'status_id' => Status::where('status_code', 'Unverified')->first()->id,
        ]);

        $image = $request->file('image');
        $hash = Str::random(10);
        $extension = $image->getClientOriginalExtension();
        $filename = "{$note->id}_{$hash}.{$extension}";
        $path = $image->storeAs('notes', $filename, 'public');

        $note->update(['image_path' => $path]);

        return redirect()->route('books.show', $book)->with('status', 'Image note added successfully!');
    }
}
