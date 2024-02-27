<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categoty\StoreCategotyRequest;
use App\Http\Requests\Categoty\UpdateCategotyRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
         $data = Category::where('type','main')
         ->with(['products','products.owner' => function ($query) {
            $query->where('name','like','%a%');
         }])->get();

        return response()->json([
            'message' => 'success',
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategotyRequest $request){
       try {
            $responseData = DB::transaction(function () use ($request) {
                if($request?->type == 'main')
                {
                    if ($request?->parent_id) {
                        $category = Category::create($request->input());
                    }
                    else {
                        $category = Category::create(array_merge($request->input(),['parent_id' => null]));
                    }
                }
                else {
                    $category = Category::create(array_merge($request->input(),['type' => 'sub']));
                }

                $fileName = 'category-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $category->storeImage($request->file('image')->storeAs('categories/images', $fileName, 'public'));

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
     */
    public function show(string $id){
        $category = Category::where('id',$id)
             ->with(['products','products.owner' => function ($query) {
                $query->where('name','like','%a%');
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
            //dd(is_null($request->parent_id));

            if ($request?->type == 'sub' && is_null($request->parent_id) && is_null($request->parent_id)) {
                throw new \Exception("parent_id is required to complete the update .", 1);
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
                'message' => $e->getMessage(),
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
