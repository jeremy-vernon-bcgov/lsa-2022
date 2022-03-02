<?php

namespace App\Policies;

use App\Models\Recipient;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecipientPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view recipients');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recipient  $recipient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Recipient $recipient)
    {
        if ($user->hasRole('orgContact')) {
            return $recipient->organization_id == $user->organization_id;
        }
        else {
            return $user->can('view recipients');
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('add recipients');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recipient  $recipient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Recipient $recipient)
    {
        return $user->can('edit recipients');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recipient  $recipient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Recipient $recipient)
    {
        if ($user->hasRole('orgContact')) {
            return $recipient->organization_id == $user->organization_id;
        }
        return $user->can('destroy recipients');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recipient  $recipient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Recipient $recipient)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recipient  $recipient
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Recipient $recipient)
    {
        //
    }
}