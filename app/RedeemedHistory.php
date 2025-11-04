<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RedeemedHistory extends Model
{
    protected $table = 'redeemed_histories';
    
    protected $fillable = [
        'user_id',
        'reward_id',
        'reward_name',
        'description',
        'transaction_type',
        'points_amount',
        'viewed',
        'balance_before',
        'balance_after',
        'status'
    ];
    
    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}