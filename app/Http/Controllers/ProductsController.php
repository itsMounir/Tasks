<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = User::latest()->paginate(100)->all();
        // $data = Product::latest()->filter(request(['search','category']))->paginate(30);
        $data = Product::latest()->get();

        return response()->json([
            'message' => 'success',
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $responseData = DB::transaction(function () use ($request){

                $product = Product::create($request->input());

                $images = $request->file('images');
                $i =0;
                foreach ($images as $image) {
                    $fileName = 'product-' . (time() + $i++). '.' . $image->getClientOriginalExtension();
                    $product->storeImage($image->storeAs('products/images', $fileName, 'public'));
                }

        return [
            'message' => 'success',
            'data' => $product
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
        $product = Product::find($id);

        //$product->getProduct()->find($id)?->get();
        if (! $product) {
            return response()->json([
                'message' => 'Product not found',
            ],200);
        }

        return response()->json([
            'message' => 'success',
            'data' => $product
        ],200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $responseData = DB::transaction(function () use ($id,$request) {
                $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found',
            ],200);
        }
        $product->deleteImages();
        $product->update($request->input());

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $i =0;
            foreach ($images as $image) {
                $fileName = 'product-' . (time() + $i). '.' . $image->getClientOriginalExtension();
                $product->storeImage($image->storeAs('products/images', $fileName, 'public'));
            }
        }

        return [
            'message' => 'Product updated successfully',
            'data' => $product
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $responseData = DB::transaction(function () use ($id) {
                $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found',
            ],200);
        }

        $product->delete();

        return [
            'message' => 'Product deleted successfully',
            'data' => $product
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
