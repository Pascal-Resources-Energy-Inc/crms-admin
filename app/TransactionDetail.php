<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    //
    public function dealer()
    {
        return $this->belongsTo(User::class,'dealer_id','id');
    }
    
    public function customer()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}