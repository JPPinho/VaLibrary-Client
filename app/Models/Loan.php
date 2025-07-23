<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    /** @use HasFactory<\Database\Factories\LoansFactory> */
    use HasFactory;

    protected $table = 'loan';

    public $casts = [
        'returned_at' => 'datetime',
        'due_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function borrower() {
        return $this->belongsTo(User::class, 'borrower_id');
    }
    public function book() {
        return $this->belongsTo(Book::class);
    }
}
