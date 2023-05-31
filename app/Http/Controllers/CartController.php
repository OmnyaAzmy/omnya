<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Shop;
use App\Traits\MediaTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'    => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Auth::user()->cart()->updateOrCreate(
            [
                'product_id' => $request->product_id,
            ],
            $validator->validated()
        );

        $cartItems = Auth::user()->cart()
            ->with('product', 'product.media')
            ->get();

        return CartResource::collection($cartItems);
    }

    public function index()
    {
        $cartItems = Auth::user()->cart()
            ->with('product', 'product.media')
            ->get();

        return CartResource::collection($cartItems);
    }

    public function delete($id)
    {
        $cart = Auth::user()->cart()->findOrFail($id);
        $cart->delete($id);
        return response()->json('deleted');
    }
}
