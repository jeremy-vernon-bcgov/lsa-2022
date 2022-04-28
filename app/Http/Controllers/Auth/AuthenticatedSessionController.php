<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class AuthenticatedSessionController extends Controller
{

  /**
  * Handle an incoming authentication request.
  *
  * @param  \App\Http\Requests\Auth\LoginRequest  $request
  * @return \Illuminate\Http\Response
  */
  public function store(LoginRequest $request)
  {
    Log::info('User Login requested', array('context' => $request->email));

    $auth_result = $request->authenticate();
    $request->session()->regenerate();

    return array('registered' => true);

  }

  /**
  * Handle authentication check request.
  *
  * @param  \App\Http\Requests\Auth\LoginRequest  $request
  * @return \Illuminate\Http\Response
  */
  public function check(Request $request)
  {
    return array('authenticated' => Auth::check());
  }

  /**
  * Handle user profile data request.
  *
  * @param  \App\Http\Requests\Auth\LoginRequest  $request
  * @return \Illuminate\Http\Response
  */
  public function profile(Request $request)
  {
    // get user profile data
    $user = $request->user();

    // get the names of the user's roles
    if (!empty($user)){
      $user = User::where('users.id', $user->id)
        ->with(['organizations'])
        ->firstOrFail();
      $user->getRoleNames();
      $user->getAllPermissions();
    }

    return $user;
  }

  /**
  * Destroy an authenticated session.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request)
  {
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return array('loggedout' => true);
  }
}
