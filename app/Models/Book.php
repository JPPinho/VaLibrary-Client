<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $table = 'book';

    protected $fillable = [
        'name',
        'language_id',
        'owner_id',
    ];

    public function authors()
    {
        return $this->belongsToMany(Author::class, "book_author")
            ->using(BookAuthor::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'book_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'book_id');
    }

    protected function isAvailable(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->loans()->whereNull('returned_at')->count() === 0,
        );
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function loanRequests()
    {
        return $this->hasMany(LoanRequest::class, 'book_id');
    }

    public function isOwnedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->owner_id === $user->id;
    }
    public function hasPendingRequestFrom(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->loanRequests()
            ->where('borrower_id', $user->id)
            ->whereNull('loan_id')
            ->exists();
    }
}
