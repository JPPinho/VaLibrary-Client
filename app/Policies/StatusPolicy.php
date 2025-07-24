<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StatusPolicy
{
    /**
     * Determine whether the users can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the users can view the model.
     */
    public function view(User $user, Status $status): bool
    {
        return false;
    }

    /**
     * Determine whether the users can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the users can update the model.
     */
    public function update(User $user, Status $status): bool
    {
        return false;
    }

    /**
     * Determine whether the users can delete the model.
     */
    public function delete(User $user, Status $status): bool
    {
        return false;
    }

    /**
     * Determine whether the users can restore the model.
     */
    public function restore(User $user, Status $status): bool
    {
        return false;
    }

    /**
     * Determine whether the users can permanently delete the model.
     */
    public function forceDelete(User $user, Status $status): bool
    {
        return false;
    }
}
