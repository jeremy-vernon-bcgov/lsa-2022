<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class OptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->authorize('viewAny', Option::class);
      return Option::all();
    }

    /**
     * Retrieve option value of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showByKey()
    {
      $this->authorize('viewAny', Option::class);
      return Option::where('key', $key)->firstOrFail();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Option::class);

        // create new global option
        $option = Option::create([
          'key' => $request->input('key'),
          'value' => $request->input('value')
        ]);

        $option->save();
        return $option;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Option $option)
    {
        $this->authorize('update', Option::class);
        $option->value = $request->input('value');
        $option->save();
        return $option;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Option $option)
    {
        $this->authorize('delete', Option::class);
        $option->delete();
        return $option;
    }
}
