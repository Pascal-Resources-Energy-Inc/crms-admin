<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email_address', 'number', 'facebook', 
        'address', 'store_name', 'store_type', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sales()
    {
        return $this->hasMany(TransactionDetail::class, 'dealer_id', 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}