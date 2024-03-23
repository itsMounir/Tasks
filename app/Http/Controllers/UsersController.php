<?php

namespace App\Http\Controllers;

use App\Filters\UsersFilters;
use App\Http\Requests\User\{StoreUserRequest,UpdateUserRequest};
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(UsersFilters $usersFilters)
    {
        $data = $usersFilters->applyFilters(User::query())->get();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = new User;
            $user->forceFill(array_merge([
                'password' => $request->password,
                ],$request->except('password_confirmation','image')));
            $user->save();

            $fileName = 'user-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $user->storeImage($request->file('image')->storeAs('users/images', $fileName, 'public'));
            return response()->json( [
                'message' => 'success',
                'data' => $user
            ]);
        });

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'message' => 'success',
            'data' => $user
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        return DB::transaction(function () use ($user , $request) {
            $user->update($request->input());

            if ($request->hasFile('image')) {
                $fileName = 'user-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $user->updateImage($request->file('image')->storeAs('users/images', $fileName, 'public'));
            }
            return response()->json([
                'message' => 'success',
                'data' => $user
        ]);
            });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return DB::transaction(function () use($user) {

            $user->delete();

            return response()->json( [
                'message' => 'user deleted successfully',
                'data' => $user
            ]);
        });
    }
}
