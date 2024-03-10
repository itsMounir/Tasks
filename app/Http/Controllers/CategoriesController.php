<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categoty\{StoreCategotyRequest,UpdateCategotyRequest};
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        $data = Category::with(['childrens', 'products.owner'])->parent()->get();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategotyRequest $request){
            return DB::transaction(function () use ($request) {

                $category = Category::create($request->input());

                $fileName = 'category-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                $category->storeImage($request->file('image')->storeAs('categories/images', $fileName, 'public'));

                return response()->json( [
                    'message' => 'category created successfully',
                    'data' => $category
                ]);
            });
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(string $id){
        $category = Category::findOrFail($id);
        $category = $category->load(['childrens', 'products.user']);

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
        return DB::transaction(function () use($id,$request) {
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

        return response()->json([
            'message' => 'category updated successfully',
            'data' => $category
        ]);});
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return DB::transaction(function () use ($id){
            $category = Category::find($id);

            if (! $category) {
                return response()->json([
                    'message' => 'category not found',
                ],200);
            }

            $category->delete();

            return response()->json( [
                'message' => 'category deleted successfully',
            ]);
        });
    }
}
