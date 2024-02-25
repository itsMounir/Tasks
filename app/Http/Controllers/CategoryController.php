<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categoty\StoreCategotyRequest;
use App\Http\Requests\Categoty\UpdateCategotyRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = User::latest()->paginate(100)->all();
        $data = Category::latest()->get();
        $message = 'success';

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
            $responseData = DB::transaction(function () use ($request) {
                $category = Category::create($request->input());

                if ($request->hasFile('image')) {
                    $fileName = 'category-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    $category->storeImage($request->file('image')->storeAs('categories/images', $fileName, 'public'));
                }

                return [
                    'message' => 'category created successfully',
                    'data' => $category
                ];
            });

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed, try again later.',
            ]);
        }


    }

    /**
     * Display the specified resource.
     *
     * When you execute show and index functions in categories controller retrieve the
      *  products of it and for each product retrieve the owner of it where owner name contains
      *  “a” letter.
     */
    public function show(string $id)
    {

        $category = Category::where('id',$id)->
        with(['products' => function ($query) use($id) {
            $query->where('category_id', $id)
                  ->with(['owner'=> function ($subQuery) {
                      $subQuery->where('name', 'like','%a%');
                  }]);
        }])->get();

        // dd($category[0]['products']->isEmpty()); #1

        //dd(is_null($category[0]['products'][0]['owner'] ));

        if ($category[0]['products']->isEmpty() || is_null($category[0]['products'][0]['owner'] )) {
            $category = [];
        }
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
        //dd($request->hasFile('image'));
        try {
            $responseData = DB::transaction(function () use($id,$request) {
                $category = Category::find($id);

            if (! $category) {
                return response()->json([
                    'message' => 'category not found',
                ],200);
            }
            $category->update($request->input());
           // dd($request->hasFile('image'));
            if ($request->hasFile('image')) {
                $fileName = 'category-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $category->updateImage($request->file('image')->storeAs('categories/images', $fileName, 'public'));
            }

            return [
                'message' => 'category updated successfully',
                'data' => $category
            ];});
            return response()->json($responseData);

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
            $responseData = DB::transaction(function () use ($id){
                $category = Category::find($id);

                if (! $category) {
                    return response()->json([
                        'message' => 'category not found',
                    ],200);
                }

                $category->delete();

                return [
                    'message' => 'category deleted successfully',
                    'data' => $category
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
