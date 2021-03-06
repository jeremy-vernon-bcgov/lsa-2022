<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class RegisteredUserController extends Controller
{

  /**
  * Return a listing of Recipients.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {

    $this->authorize('viewAny', User::class);

    return User::with(['organizations', 'roles'])->get();

  }

  /**
  * Retrieve the full record for a recipient.
  *
  * @param  \App\Models\Recipient  $recipient
  * @return \Illuminate\Http\Response
  */
  public function show(string $id)
  {

    $this->authorize('viewAny', User::class);

    return User::with(['organizations', 'roles'])
    ->where('users.id', $id)
    ->firstOrFail();
  }

  /**
  * Handle an incoming registration request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  *
  * @throws \Illuminate\Validation\ValidationException
  */
  public function store(Request $request)
  {

    $this->authorize('create', User::class);

    Log::info('User Registration Requested', array(
      'name' => $request->name,
      'email' => $request->email,
      'role' => $request->role,
      'organizations' => $request->organizations
    ));

    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'guid' => $request->guid,
      'idir' => $request->idir,
      'remember_token' => $request->remember_token,
    ]);

    // assign user role
    $user->assignRole($request->input('role'));

    // If orgContact, assign organizations
    if ($request->input('role') === 'orgContact' && $request->input('organizations')) {

      foreach ($request->input('organizations') as $org_id) :
        $organization = Organization::find($org_id);
        $this->addOrganization($user, $organization);
      endforeach;
    }

    event(new Registered($user));
    return array('registered' => $user->id);

  }

  /**
  * Creates the association between a user and an organization
  *
  * @param User $user
  * @param Organization $organization
  */

  private function addOrganization(User $user, Organization $organization) {
    $user->organizations()->attach($organization);
    $user->save();
  }

  private function removeOrganization(User $user, Organization $organization) {
    $user->organizations()->detach($organization);
    $user->save();
  }

  /**
  * Handle an incoming user profile update request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  *
  * @throws \Illuminate\Validation\ValidationException
  */
  public function update(Request $request, string $id)
  {

    $this->authorize('update', User::class);

    // find user and update profile data
    $user = User::where('id', $id)->firstOrFail();

    if (!empty($user)) {

      // validate inputs
      $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255'],
      ]);

      // update profile data
      $user->name = $request->input('name');
      $user->email = $request->input('email');
      $user->idir = $request->input('idir');
      $user->guid = $request->input('guid');

      // assign user role
      $user->syncRoles([$request->input('role')]);

      // If orgContact, sync associated organizations
      if ($request->input('role') === 'orgContact' && $request->input('organizations')) {
        $user->organizations()->sync($request->input('organizations'));
      }

      $user->save();
      return array(
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'idir' => $user->guid,
        'organizations' => $user->organizations(),
        'roles' => $user->getRoleNames()
      );
    }
  }


    /**
    * Handle an incoming user profile update role request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    *
    * @throws \Illuminate\Validation\ValidationException
    */
    public function updateRole(Request $request, string $id)
    {

      $this->authorize('update', User::class);

      // find user and update profile data
      $user = User::where('id', $id)->firstOrFail();

      if (!empty($user)) {

        // (re)assign user role
        $user->syncRoles([$request->input('role')]);

        $user->save();
        return array(
          'id' => $user->id,
          'name' => $user->name,
          'email' => $user->email,
          'idir' => $user->guid,
          'organizations' => $user->organizations(),
          'roles' => $user->getRoleNames()
        );
      }
    }

  /**
  * Delete user record by ID
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  *
  * @throws \Illuminate\Validation\ValidationException
  */
  public function destroy($id)
  {
    $this->authorize('destroy', User::class);

    $user = User::where('id', $id)->firstorfail()->delete();
    Log::info('User record deleted:', array(
      'id' => $id
    ));
    return array('userdelete' => $id);
  }
}
