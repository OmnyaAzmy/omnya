<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    //protected $primaryKey = '$id';
    public $table = 'contacts';
    public $fillable = ['name', 'phone','email', 'subject', 'message'];
    //protected $timestamps = true;


    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
