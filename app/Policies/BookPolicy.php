<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Book;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
    public function before(User $user): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }


    /**
     * Determine whether the users can update the model.
     * This method is now only checked for non-admin users.
     */
    public function update(User $user, Book $book): bool
    {
        return $user->id === $book->owner_id;
    }
}
