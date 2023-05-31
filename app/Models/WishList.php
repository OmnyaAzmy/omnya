<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $fillable = [
        'title','newPrice','oldPrice','product_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    /*
    protected $casts = [
        'quantity' => 'integer',
    ];
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
