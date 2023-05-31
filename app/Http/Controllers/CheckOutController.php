<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Mail\OrderPlaced;
use Illuminate\Http\Request;
use App\Mail\AdminOrderPlaced;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\CheckOutResource;
use Illuminate\Support\Facades\Validator;

class CheckOutController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)->get();
    
        if ($cart->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }
    
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'last_name'  => 'nullable|string|between:2,100',
            'phone'      => 'required|string|min:6|unique:users',
            'address' => ['required', 'string'],
            'company' => ['nullable', 'string'],
            'total' => ['required', 'string'],
            'subtotal' => ['required', 'string'],
            'shipingCost' => ['required', 'string'],
            'payment' => ['required', 'string', 'in:0,1,2'],
            'notes' => ['nullable', 'string'],
            'paymentNames' => ['nullable', 'string'],

        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Calculate the total price of the products in the cart.
        //$total = 0;
        //foreach ($cart as $item) {
        //    $total += $item->product->newPrice * $item->quantity;
        //}
    
        // Add the shipping price to the total price.
       // $shippingCost= 100;
    
        $order = new Order();
        $order->user_id = Auth::id();
        $order->product_id = $cart->first()->product_id;
        $order->fill($validator->validated());
        //$order->total = $total + $shippingCost;
        $order->save();
    
      
    // Send email to the user.
    Mail::to($request->user()->email)->send(new OrderPlaced($order,$cart));
    
    // Send email to the admin.
    $user = User::find($order->user_id);
        if ($user->type !== 2) {
            $admins = User::where('type', 2)->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new AdminOrderPlaced($order,$cart));
            }
        }

    Cart::where('user_id', $request->user()->id)->delete();

        return [(new CheckOutResource($order))];
    }
}    

