<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use MediaTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'    => 'required',
            'newPrice' => 'required',
            'oldPrice'    => 'required',
            'offer'    => 'required',
            'category'    => 'required',
            'color'    => 'required',
            'size'    => 'required',
            'abstract'    => 'required',
            'featuer'    => 'required',
            'pin_code'    => 'required|max:3|unique:products',
            'description'    => 'required',
            'videos'    => 'required',
            'is_special'=> 'required',
            'is_complete'=> 'required',


            'image_1'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_2'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_3'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_4'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_5'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
            'image_6'    => 'sometimes|file|image|mimes:jpg,gif,png,webp',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = new Product();
        $product->user_id= Auth::id();
        $product->fill($validator->validated());
        $product->save();

        $this->handleRequestMediaFiles($product, $request);

        // get the newly created product with media images and reviews count

        $product = Product::with('media')
            ->withCount('reviews')
            ->findOrFail($product->id);

        return new ProductResource($product);
    }

    public function show($id)
    {
        // get the product with media images and reviews count
        $product = Product::with('media', 'reviews', 'reviews.user')
            ->withCount('reviews')
            ->rating()
            ->findOrFail($id);

        return new ProductResource($product);
    }

    public function index(Request $request)
    {
        $products = Product::with('media')
            ->withCount('reviews')
            ->rating()
            ->when($request->query('new_arrival'), function ($query) {
                $query
                    ->where('created_at', '>=', now()->subMonth())
                    ->orderBy('id', 'DESC');
            })
            ->when($request->query('top_rated'), function ($query) {
                $query
                    ->having('rating', '>', 0)
                    ->orderBy('rating', 'DESC');
            })
            ->when($request->query('is_special'), function ($query) {
                $query
                    ->where('is_special', '=', 1);                  
            })
            ->when($request->query('seller_products'), function ($query) {
                $query
                    ->where('user_id','=',Auth::id());
                 })->get();

        return ProductResource::collection($products);


    }
    public function sellerProducts(Request $request, $id)
    {
        $products = Product::with('media')
            ->withCount('reviews')
            ->rating()
            ->where('user_id', $id)
            ->when($request->query('new_arrival'), function ($query) {
                $query
                    ->where('created_at', '>=', now()->subMonth())
                    ->orderBy('id', 'DESC');
            })
            ->when($request->query('top_rated'), function ($query) {
                $query
                    ->having('rating', '>', 0)
                    ->orderBy('rating', 'DESC');
            })
            ->when($request->query('is_special'), function ($query) {
                $query
                    ->where('is_special', '=', 1);                  
            })
            
            ->get();

        return ProductResource::collection($products);


    }
    public function relatedProducts(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $products = Product::with('media')
            ->withCount('reviews')
            ->rating()
            ->whereNot('id', $product->id)
            ->where('category', $product->category)
            ->when($request->query('new_arrival'), function ($query) {
                $query
                    ->where('created_at', '>=', now()->subMonth())
                    ->orderBy('id', 'DESC');
            })
            ->when($request->query('top_rated'), function ($query) {
                $query
                    ->having('rating', '>', 0)
                    ->orderBy('rating', 'DESC');
            })
            ->when($request->query('is_special'), function ($query) {
                $query
                    ->where('is_special', '=', 1);                  
            })
            
            ->get();

        return ProductResource::collection($products);


    }



public function delete($id){
    $product= Product::find($id);
    $product->delete($id);
    return response()->json('deleted');
}

public function update(Request $request)
{

        $product = Product::find($request->id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $product->user_id= Auth::id();
        $product->update($request->all());
        $product->save();

        $this->handleRequestMediaFiles($product, $request);
   
        return["updated Successfully"];

}
    
}
