<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class PermissionsController extends Controller
{

  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    // get authenticated user
    $this->authorize('viewAny', Permission::class);

    // exclude super-administrator permissions
    return Role::with('permissions')
    ->where('name', '!=', 'super-admin')
    ->get();
  }

  /**
  * View permissions for requested role
  *
  * @return \Illuminate\Http\Response
  */
  public function view(Role $role)
  {
    // get authenticated user
    $this->authorize('viewAny', Permission::class);

    // exclude super-administrator permissions
    return Role::with('permissions')
    ->where('name', '=', $role->name)
    ->get();
  }

  /**
  * Update record of permission for requested role.
  *
  */

  public function update (Role $role, Request $request) {
    $this->authorize('update', Permission::class);

    // get permissions for role
    $permissions = $request->input('permissions');

    Log::info(
      'Update Permissions', array(
        'permission' => $permissions,
        'role' => $role
      ));

    $role->syncPermissions($permissions);
    return;
  }

}
