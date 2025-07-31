<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ScannerController extends Controller
{
    protected $languages = [
        'por' => 'pt_pt',
        'eng' => 'en',
    ];

    public function store(Request $request)
    {
        $validated = $request->validate(['isbn' => 'required|string']);
        $isbn = $validated['isbn'];

        $response = Http::get("https://openlibrary.org/search.json?isbn={$isbn}");

        if (!$response->successful() || $response->json('numFound') === 0) {
            return redirect()->route('books.index')->with('error', 'Book not found for this ISBN.');
        }

        $bookData = $response->json('docs.0');

        try {
            DB::transaction(function () use ($bookData) {
                $authorIds = [];
                $authorNames = $bookData['author_name'] ?? ['Unknown Author'];

                foreach ($authorNames as $authorName) {
                    $author = Author::firstOrCreate(['name' => $authorName]);
                    $authorIds[] = $author->id;
                }
                $language = Language::all()->first();
                if(isset($bookData['language']) && isset($this->languages[$bookData['language']])){
                    $language = Language::all()->firstWhere('code', $this->languages[$bookData['language']]);
                }
                $book = Book::create([
                    'name' => $bookData['title'],
                    'owner_id' => Auth::id(),
                    'language_id' => $language->id(), // Defaulting to 1 (e.g., 'English'). You might want to parse this.
                ]);

                $book->authors()->attach($authorIds);
            });
        } catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', 'An error occurred while adding the book.');
        }

        return redirect()->route('books.index')->with('status', 'Book successfully added from ISBN scan!');
    }}
