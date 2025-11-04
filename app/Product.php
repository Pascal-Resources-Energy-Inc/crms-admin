<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    
    protected $fillable = [
        'product_name',
        'price',
        'deposit',
        'product_image'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];
}