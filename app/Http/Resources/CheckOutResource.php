<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckOutResource extends JsonResource
{
    // Define a map of payment codes to names
    private $paymentNames = [
        0 => 'Paypal',
        1 => 'Cash',
        2 => 'Bank',
    ];

    // ...

    public function toArray($request)
    {
        $paymentName = $this->paymentNames[$this->payment];
        $user = User::find(Auth::id());
        return [
            'message' => 'Order Place Successfully',
            'id' =>  $this->id,
            'total' => $this->total,
            'address' => $this->address,
            'payment' => [
                'code' => $this->payment, // Include the payment code
                'name' => $paymentName,   // Include the payment name
            ],
            'company' => $this->company,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'subtotal' => $this->subtotal,
            'shipingCost' => $this->shipingCost,
            'user' => [
                'id' =>  $user->id,
                'email' => $user->email,
            ],
        ];
    }
}
