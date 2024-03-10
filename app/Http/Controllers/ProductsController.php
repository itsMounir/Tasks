<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\{
    StoreProductRequest,
    UpdateProductRequest
};
use App\Models\{Product,User};
use App\Notifications\Products\{
    Approval,
    Added,
};
use Illuminate\Support\Facades\{
    Auth,
    DB,
    Notification
};
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        return DB::transaction(function () use ($request){
            $user = Auth::user();
            $product = Product::create(
                array_merge($request->input(),
                ['user_id' => $user->id]
                    ));
            $images = $request->file('images');
            $i =0;
            foreach ($images as $image) {
                $fileName = 'product-' . (time() + $i++). '.' . $image->getClientOriginalExtension();
                $product->storeImage($image->storeAs('products/images', $fileName, 'public'));
            }
            //dd(User::where('is_admin',true)->get()->toArray());
            // dd($admins);
            DB::afterCommit(function () use ($user,$product){
                $admins = User::where('is_admin',true)->get();
                Notification::send($admins, new Added($user,$product));
            });

        return response()->json([
            'message' => 'The product added successfully, please wait to accept it by admin.',
        ]);
            });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

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
        return DB::transaction(function () use ($id,$request) {
            $product = Product::findOrFail($id);

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

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => $product
            ]);
            });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        return DB::transaction(function () use ($id) {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
            'data' => $product]);
        });
    }

    public function approval(Product $product,Request $request) {
        $request->validate(['approved' => 'required']);

        return response()->json(DB::transaction(function () use ($product,$request){
            $admin = Auth::user();
            //dd($request->approved == true);
            if ($request->approved) {
                $product->changeStatus('accepted');
                //dd($product->owner);
                DB::afterCommit(function () use($product,$admin) {
                    $product->owner->notify(new Approval("$product->name accepted by $admin->name"));
                });
                return ['Done'];
            }
            else {
                $product->changeStatus('rejected');
                $product->owner->notify(new Approval("$product->name rejected by $admin->name"));
                return ['Done'];
            }}));
    }
}
