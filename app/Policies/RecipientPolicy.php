<?php

namespace App\Policies;

use App\Models\Recipient;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

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
    // restrict org contacts viewable to associated organizations
    if ($user->hasRole('orgContact')) {
      $orgs = [];
      foreach ($user->organizations as $org) {
        $orgs[] = $org->id;
      }
      // Log::info('Check orgs', array(
      //   'orgs' => $orgs,
      //   'recipient' => $recipient->organization_id
      // ));
      return $user->can('view recipients') && in_array($recipient->organization_id, $orgs);
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

    // restrict org contacts editable to associated organizations
    if ($user->hasRole('orgContact')) {
      $orgs = [];
      foreach ($user->organizations()->get() as $org){
        $orgs[] = $org->id;
      }
      return $user->can('edit recipients') && in_array($recipient->organization_id, $orgs);
    }
    else {
      return $user->can('edit recipients');
    }
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
    // restrict org contacts viewable to associated organizations
    if ($user->hasRole('orgContact')) {
      $orgs = [];
      foreach ($user->organizations as $org) {
        $orgs[] = $org->id;
      }
      return in_array($recipient->organization_id, $orgs);
    }
    else {
      return $user->can('delete recipients');
    }
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
