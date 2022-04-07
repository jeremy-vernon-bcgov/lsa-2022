<?php

namespace App\Policies;

use App\Models\Award;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class AwardPolicy
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
    return $user->hasRole('admin') || $user->hasRole('super-admin');
  }

  /**
  * Determine whether the user can view the model.
  *
  * @param  \App\Models\User  $user
  * @return \Illuminate\Auth\Access\Response|bool
  */
  public function view(User $user)
  {
    return $user->hasRole('admin') || $user->hasRole('super-admin');
  }

  /**
  * Determine whether the user can generate a report for the model.
  *
  * @param  \App\Models\User  $user
  * @return \Illuminate\Auth\Access\Response|bool
  */
  public function report(User $user)
  {
    return $user->hasRole('admin') || $user->hasRole('super-admin');
  }

  /**
  * Determine whether the user can create models.
  *
  * @param  \App\Models\User  $user
  * @return \Illuminate\Auth\Access\Response|bool
  */
  public function create(User $user)
  {
    return $user->hasRole('admin') || $user->hasRole('super-admin');
  }

  /**
  * Determine whether the user can update the model.
  *
  * @param  \App\Models\User  $user
  * @return \Illuminate\Auth\Access\Response|bool
  */
  public function update(User $user)
  {
    return $user->hasRole('admin') || $user->hasRole('super-admin');
  }

  /**
  * Determine whether the user can delete the model.
  *
  * @param  \App\Models\User  $user
  * @return \Illuminate\Auth\Access\Response|bool
  */
  public function delete(User $user)
  {
    return $user->hasRole('admin') || $user->hasRole('super-admin');
  }

  /**
  * Determine whether the user can restore the model.
  *
  * @param  \App\Models\User  $user
  * @return \Illuminate\Auth\Access\Response|bool
  */
  public function restore(User $user)
  {
    return $user->hasRole('admin') || $user->hasRole('super-admin');
  }

  /**
  * Determine whether the user can permanently delete the model.
  *
  * @param  \App\Models\User  $user
  * @return \Illuminate\Auth\Access\Response|bool
  */
  public function forceDelete(User $user)
  {
    return $user->hasRole('admin') || $user->hasRole('super-admin');
  }
}
