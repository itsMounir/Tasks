<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        throw_if(Auth::user()->cannot('update', $role), new AuthorizationException);
        return response()->json(DB::transaction(function () use ($request, $role) {
            $permissions = $request->all()['permissions'];
            $role->permissions()->sync($permissions);
            return ['Done'];
        }));
    }

}
