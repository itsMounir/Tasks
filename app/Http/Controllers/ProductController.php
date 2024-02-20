<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = User::latest()->paginate(100)->all();
        $data = Product::latest()->filter(request(['search','category']))->paginate(30);
        foreach ($data as $product) {
            //dd($data[0]);
            $this->getCreatedFromAttribute($product);
        }
        $data['categories'] = Category::all();
        $message = 'success';

        return response()->json([
            'message' => $message,
            'data' => $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::transaction(function () use ($request){
                $product = Product::create($request->all());

        return response()->json([
            'message' => 'success',
            'data' => $product
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
        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found',
            ],200);
        }

        $this->getCreatedFromAttribute($product);

        $product->with('category')->where('id','=',$id)->get();



       // dd($product);

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
            DB::transaction(function () use ($id,$request) {
                $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found',
            ],200);
        }


        $product->update($request->all());

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
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
            DB::transaction(function () use ($id) {
                $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'message' => 'Product not found',
            ],200);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
            'data' => $product
        ],200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed , try again later.',
            ]);
        }

    }
}
