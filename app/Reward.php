<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $table = 'rewards';

    public $timestamps = true;

    protected $fillable = [
        'price_reward',
        'description',
        'points_required',
        'stock',
        'expiry_date',
        'image',
        'is_active',
        'redemption_limit'  // ADD THIS
    ];
    
    protected $casts = [
        'points_required' => 'integer',
        'stock' => 'integer',
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
        'redemption_limit' => 'integer',  // ADD THIS
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relationship with RedeemedHistory - matches reward_name with description
    public function redeemedHistories()
    {
        return $this->hasMany(RedeemedHistory::class, 'reward_name', 'description');
    }

    public function isExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->isPast();
    }

    // ADD THIS METHOD
    public function isLimitReached()
    {
        if ($this->redemption_limit === null || $this->redemption_limit <= 0) {
            return false;
        }
        
        $claimsCount = \App\RedeemedHistory::where('reward_name', 'LIKE', '%' . $this->description . '%')
            ->count();
        
        return $claimsCount >= $this->redemption_limit;
    }

    // ADD THIS ACCESSOR
    public function getClaimsCountAttribute()
    {
        return \App\RedeemedHistory::where('reward_name', 'LIKE', '%' . $this->description . '%')
            ->count();
    }

    public function isAvailable()
    {
        return $this->is_active && 
               $this->stock > 0 && 
               !$this->isExpired() &&
               !$this->isLimitReached() &&
               is_null($this->deleted_at);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1)
                    ->whereNull('deleted_at');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', 1)
                    ->where('stock', '>', 0)
                    ->where(function($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>', now());
                    })
                    ->whereNull('deleted_at');
    }
}