<?php

namespace App\Http\Controllers;

use App\Http\Resources\WishListResource;
use App\Models\WishList;
use App\Models\Product;
use App\Models\Shop;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishListController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|exists:products,id',
            //'quantity'    => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Auth::user()->wishlist()->updateOrCreate(
            [
                'product_id' => $request->product_id,
            ],
            $validator->validated()
        );

        $wishlistItems = Auth::user()->wishlist()
            ->with('product', 'product.media')
            ->get();

        return WishListResource::collection($wishlistItems);
    }

    public function index()
    {
        $wishlistItems = Auth::user()->wishlist()
            ->with('product', 'product.media')
            ->get();

        return WishListResource::collection($wishlistItems);
    }

    public function delete($id)
    {
        $wishlist = Auth::user()->wishlist()->findOrFail($id);
        $wishlist->delete($id);
        return response()->json('deleted');
    }
}
