<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = User::latest()->paginate(100)->all();
        $data = User::latest()->get();
        $message = 'success';

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

        //dd($request->file('picture')->store('pictures'));
        try {
            $responseData = DB::transaction(function () use ($request) {
            $user = new User;

             $user->forceFill(array_merge([
                'password' => $request->password,
            ],$request->input()));

            //dd($user);

            // $url = $request->file('image')->store('images','public');
            $user->save();

            $fileName = 'user-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $user->storeImage($request->file('image')->storeAs('users/images', $fileName, 'public'));


            return [
                'message' => 'success',
                'data' => $user
            ];

            });

            return response()->json($responseData);
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
            $responseData = DB::transaction(function () use ($id , $request) {
                $user = User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'user not found',
            ],200);
        }

        $user->update($request->input());

        if ($request->hasFile('image')) {
            $fileName = 'user-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
            $user->updateImage($request->file('image')->storeAs('users/images', $fileName, 'public'));
        }


        return [
            'message' => 'success',
            'data' => $user
        ];
            });
            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                $e
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $responseData = DB::transaction(function () use($id) {
                $user = User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'user not found',
            ],200);
        }

        $user->delete();

        return [
            'message' => 'user deleted successfully',
            'data' => $user
        ];
            });
            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed , try again later.',
            ]);
        }

    }
}
