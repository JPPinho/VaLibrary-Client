<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $bookId = $this->input('book_id');
        if (!$bookId) {
            return false;
        }

        $book = Book::find($bookId);
        if (!$book) {
            return false;
        }

        $user = Auth::user();
        $isOwner = $user->id === $book->owner_id;
        $hasBorrowed = $book->loans()->where('borrower_id', $user->id)->exists();

        return $isOwner || $hasBorrowed || $user->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_id' => 'required|exists:book,id',
            'body' => 'required|string|min:10',
            'page' => 'nullable|integer',
        ];
    }
}
