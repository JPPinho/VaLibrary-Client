<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    protected $table = 'loan_request';

    protected $fillable = [
        'borrower_id',
        'book_id',
        'loan_id',
    ];

    public function borrower() {
        return $this->belongsTo(User::class);
    }
    public function book() {
        return $this->belongsTo(Book::class);
    }
    public function fulfilledLoan() {
        return $this->belongsTo(Loan::class);
    }
}
