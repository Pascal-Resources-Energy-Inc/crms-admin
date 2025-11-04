<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email_address', 'number', 'address', 'status', 'serial_number'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function serial()
    {
        return $this->belongsTo(Stove::class, 'serial_number', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function latestTransaction()
    {
        return $this->hasOne(TransactionDetail::class)->latest('date');
    }

    public function stove()
    {
        return $this->belongsTo(Stove::class, 'serial_number', 'id');
    }

}   