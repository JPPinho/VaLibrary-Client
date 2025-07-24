<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NotesFactory> */
    use HasFactory;

    protected $table = 'note';

    protected $fillable = [
        'user_id',
        'book_id',
        'status_id',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
