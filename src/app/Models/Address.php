<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'postcode',
        'address',
        'building',
    ];

    public function user()
    {
        return $this->belongTo(User::class);
    }

    public function product()
    {
        return $this->belongTo(User::class);
    }
}
