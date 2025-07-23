<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;
    protected $table = 'book';
    public function authors()
    {
        return $this->belongsToMany(Author::class, "book_author")
            ->using(BookAuthor::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function loans() {
        return $this->hasMany(Loan::class, 'book_id');
    }
}
