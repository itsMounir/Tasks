<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categoty\StoreCategotyRequest;
use App\Http\Requests\Categoty\UpdateCategotyRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Traits\GetCreatedFromAttribute;

class CategoryController extends Controller
{
    use GetCreatedFromAttribute;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = User::latest()->paginate(100)->all();
        $data = Category::latest()->get();
        $message = 'success';

        foreach ($data as $category) {
            //dd($data[0]);
            $this->getCreatedFromAttribute($category);
        }

        return response()->json([
            'message' => $message,
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategotyRequest $request)
    {
        try {
            DB::transaction(function () use($request){
                $category = Category::create($request->all());

                return response()->json([
                    'message' => 'success',
                    'data' => $category
                ],200);
            },2);

        }catch (\Exception $e) {
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
        $category = Category::find($id);

        if (! $category) {
            return response()->json([
                'message' => 'category not found',
            ],200);
        }

        $this->getCreatedFromAttribute($category);


        //dd($category);
        return response()->json([
            'message' => 'success',
            'data' => $category
        ],200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategotyRequest $request, string $id)
    {
        try {
            DB::transaction(function () use($id,$request) {
                $category = Category::find($id);

            if (! $category) {
                return response()->json([
                    'message' => 'category not found',
                ],200);
            }
            $category->update($request->all());

            return response()->json([
                'message' => 'category updated successfully',
                'data' => $category
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
            DB::transaction(function () use ($id){
                $category = Category::find($id);

                if (! $category) {
                    return response()->json([
                        'message' => 'category not found',
                    ],200);
                }

                $category->delete();

                return response()->json([
                    'message' => 'category deleted successfully',
                    'data' => $category
                ],200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed , try again later.',
            ]);
        }

    }
}
