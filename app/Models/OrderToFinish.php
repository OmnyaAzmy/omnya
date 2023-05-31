<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderToFinish extends Model
{
    use HasFactory;
    public $table = 'ordertofinish';
    public $fillable = ['name', 'product_id', 'email','address', 'phone_number', 'description'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
