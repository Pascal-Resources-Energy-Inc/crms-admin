<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RedeemedHistory extends Model
{
    protected $table = 'redeemed_histories';
    
    protected $fillable = [
        'user_id',
        'reward_id',
        'points_amount',
        'status',
        'viewed',
        'redeemed_at',
    ];

    public $timestamps = true;

    // Relationship to User
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    // Relationship to Reward
    public function reward()
    {
        return $this->belongsTo('App\Reward', 'reward_id');
    }

    // Cast dates properly
    protected $dates = [
        'created_at',
        'updated_at',
        'redeemed_at'
    ];

    // Default values
    protected $attributes = [
        'viewed' => 0,
        'status' => 'Submitted'
    ];
}