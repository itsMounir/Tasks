<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = User::latest()->paginate(100)->all();
        $data = User::latest()->get();
        $message = 'success';

        foreach ($data as $user) {
            //dd($data[0]);
            $this->getCreatedFromAttribute($user);
            //$user['created_from'] = $user->created_at->diffForHumans();
        }

        return response()->json([
            'message' => $message,
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = new User;

        $user->forceFill(array_merge([
            'password' => $request->password
        ],$request->all()))->save();

        return response()->json([
            'message' => 'success',
            'data' => $user
        ],200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed , try again later.',
            ]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'user not found',
            ],200);
        }

        //$user['created_from'] = $user->created_at->diffForHumans();
        $this->getCreatedFromAttribute($user);

        //dd($user);
        return response()->json([
            'message' => 'success',
            'data' => $user
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            DB::transaction(function () use ($id , $request) {
                $user = User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'user not found',
            ],200);
        }

        $user->forceFill(array_merge([
            'password' => $request->password
        ],$request->all()))->save();

        return response()->json([
            'message' => 'success',
            'data' => $user
        ],200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed , try again later.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use($id) {
                $user = User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'user not found',
            ],200);
        }

        $user->delete();

        return response()->json([
            'message' => 'user deleted successfully',
            'data' => $user
        ],200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed , try again later.',
            ]);
        }

    }
}
