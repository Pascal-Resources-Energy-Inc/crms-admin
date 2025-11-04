<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PasswordResetOtp extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'password_reset_otps';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Boot the model and set created_at automatically.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = Carbon::now();
        });

        static::created(function ($model) {
            Log::info('OTP Record Created', [
                'email' => $model->email,
                'otp' => $model->otp,
                'expires_at' => $model->expires_at,
                'created_at' => $model->created_at
            ]);
        });

        static::deleted(function ($model) {
            Log::info('OTP Record Deleted', [
                'email' => $model->email,
                'otp' => $model->otp,
                'deleted_at' => Carbon::now()
            ]);
        });
    }

    /**
     * Generate a secure 6-digit OTP.
     *
     * @return string
     */
    public static function generateOtp(): string
    {
        // Generate a secure random 6-digit OTP
        $otp = '';
        for ($i = 0; $i < 6; $i++) {
            $otp .= random_int(0, 9);
        }
        
        // Ensure it's always 6 digits (pad with leading zeros if needed)
        return str_pad($otp, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new OTP record for the given email.
     *
     * @param string $email
     * @param int $expiryMinutes
     * @return static
     */
    public static function createForEmail(string $email, int $expiryMinutes = 10): self
    {
        $email = strtolower(trim($email));

        // Delete any existing OTPs for this email to prevent multiple active OTPs
        self::where('email', $email)->delete();

        // Create new OTP record
        $otp = self::create([
            'email' => $email,
            'otp' => self::generateOtp(),
            'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
        ]);

        return $otp;
    }

    /**
     * Verify OTP for the given email and OTP code.
     *
     * @param string $email
     * @param string $otp
     * @return bool
     */
    public static function verify(string $email, string $otp): bool
    {
        $email = strtolower(trim($email));
        $otp = trim($otp);

        $record = self::where('email', $email)
                     ->where('otp', $otp)
                     ->where('expires_at', '>', Carbon::now())
                     ->first();

        if ($record) {
            // Delete the record after successful verification to prevent reuse
            $record->delete();
            return true;
        }

        return false;
    }

    /**
     * Check if the current OTP record has expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->expires_at);
    }

    /**
     * Check if the OTP is still valid (not expired).
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Get the time remaining before expiry in minutes.
     *
     * @return int
     */
    public function getTimeRemainingInMinutes(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInMinutes($this->expires_at, false);
    }

    /**
     * Get the time remaining before expiry in seconds.
     *
     * @return int
     */
    public function getTimeRemainingInSeconds(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return Carbon::now()->diffInSeconds($this->expires_at, false);
    }

    /**
     * Find active OTP for email.
     *
     * @param string $email
     * @return static|null
     */
    public static function findActiveForEmail(string $email): ?self
    {
        $email = strtolower(trim($email));

        return self::where('email', $email)
                  ->where('expires_at', '>', Carbon::now())
                  ->orderBy('created_at', 'desc')
                  ->first();
    }

    /**
     * Clean up expired OTP records.
     *
     * @return int Number of deleted records
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', Carbon::now())->delete();
    }

    /**
     * Clean up all OTPs for a specific email.
     *
     * @param string $email
     * @return int Number of deleted records
     */
    public static function cleanupForEmail(string $email): int
    {
        $email = strtolower(trim($email));
        return self::where('email', $email)->delete();
    }

    /**
     * Check if email has reached the maximum number of OTP attempts.
     *
     * @param string $email
     * @param int $maxAttempts
     * @param int $timeWindowMinutes
     * @return bool
     */
    public static function hasReachedMaxAttempts(
        string $email, 
        int $maxAttempts = 5, 
        int $timeWindowMinutes = 60
    ): bool {
        $email = strtolower(trim($email));
        
        $attemptCount = self::where('email', $email)
                           ->where('created_at', '>', Carbon::now()->subMinutes($timeWindowMinutes))
                           ->count();

        return $attemptCount >= $maxAttempts;
    }

    /**
     * Get formatted expiry time.
     *
     * @return string
     */
    public function getFormattedExpiryAttribute(): string
    {
        return $this->expires_at->format('Y-m-d H:i:s');
    }

    /**
     * Get human readable expiry time.
     *
     * @return string
     */
    public function getHumanExpiryAttribute(): string
    {
        return $this->expires_at->diffForHumans();
    }

    /**
     * Scope to get only valid (non-expired) OTPs.
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope to get only expired OTPs.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', Carbon::now());
    }

    /**
     * Scope to get OTPs for a specific email.
     */
    public function scopeForEmail($query, string $email)
    {
        return $query->where('email', strtolower(trim($email)));
    }
}