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
        return response()->json(DB::transaction(function () use ($request, $role) {

            $role->permissions()->delete();
            $permissions = [];

            if ($request->all()) {
                $permissions = $request->all()['permissions'];

                foreach ($permissions as $permission) {
                    $role->permissions()->create(['permission_id' => $permission]);
                }
            }

            return ['Done'];
        }));
    }

}
