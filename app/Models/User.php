<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function loans() {
        return $this->hasMany(Loan::class, 'borrower_id');
    }
    public function activeLoans() {
        return $this->hasMany(Loan::class, 'borrower_id')
            ->whereNull('returned_at');
    }

    public function notes() {
        return $this->hasMany(Note::class, 'user_id' );
    }

    public function books() {
        return $this->hasMany(Book::class, 'owner_id');
    }

    public function lentBooks() {
        return $this->hasMany(Book::class, 'owner_id')
            ->whereHas('loans', function ($query) {
                $query->whereNull('returned_at');
            });
    }

    public function loanRequests() {
        return $this->hasMany(LoanRequest::class, 'borrower_id');
    }

}
