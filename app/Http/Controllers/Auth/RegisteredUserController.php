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
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        Log::info('User Registration Requested', array(
          'name' => $request->name,
          'email' => $request->email,
          'role' => $request->role
        ));

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'idir' => ['required', 'string', 'max:255', 'unique:users'],
            'guid' => ['required', 'string', 'max:255', 'unique:users'],
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
        if ($request->input('role') == 'orgContact' && $request->input('organizations')) {
            foreach ($request->input(organizations) as $org_id) :
                $organization = Organization::find($org_id);
                $this->addOrganization($user, $organization);
            endforeach;
        }


        event(new Registered($user));

        // Auth::login($user);



        return response()->noContent();
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
    public function update(Request $request)
    {
        Log::info('User profile update requested', array('context' => $request->email));

        // find user and update profile data
        $user = User::where('email', $request->input('email'))->firstOrFail();

        if (!empty($user)) {

          // validate inputs
          $request->validate([
              'name' => ['required', 'string', 'max:255'],
              'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
              'idir' => ['required', 'string', 'max:255', 'unique:users'],
              'guid' => ['required', 'string', 'max:255', 'unique:users'],
          ]);

          // update profile data
          $user->name = $request->input('name');
          $user->email = $request->input('email');
          $user->idir = $request->input('idir');
          $user->guid = $request->input('guid');

          // assign user role
          $user->syncRoles([$request->input('role')]);

          $user->save();
          return $this->getFullRecipient($recipient);
        }
    }
}
