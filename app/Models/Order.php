<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Cart;

class Order extends Model
{

    protected $fillable = [
        'total',
        'address',
        'company',
        'payment',
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'subtotal',
        'shipingCost',
        'paymentNames',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function cart()
{
    return $this->belongsTo(Cart::class);
}
}
