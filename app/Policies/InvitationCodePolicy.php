<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InvitationCode;
use Illuminate\Auth\Access\Response;

class InvitationCodePolicy
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
    public function view(User $user, InvitationCode $invitationCode): bool
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
    public function update(User $user, InvitationCode $invitationCode): bool
    {
        return false;
    }

    /**
     * Determine whether the users can delete the model.
     */
    public function delete(User $user, InvitationCode $invitationCode): bool
    {
        return false;
    }

    /**
     * Determine whether the users can restore the model.
     */
    public function restore(User $user, InvitationCode $invitationCode): bool
    {
        return false;
    }

    /**
     * Determine whether the users can permanently delete the model.
     */
    public function forceDelete(User $user, InvitationCode $invitationCode): bool
    {
        return false;
    }
}
