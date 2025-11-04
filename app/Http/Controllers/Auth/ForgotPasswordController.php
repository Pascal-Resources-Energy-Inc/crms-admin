<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\PasswordResetOtp;
use App\Mail\PasswordResetOtp as PasswordResetOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class ForgotPasswordController extends Controller
{

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We cannot find a user with that email address.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $email = strtolower(trim($request->email));

            $user = User::where('email', $email)->first();
            
            if (!$user) {
                return back()->withErrors([
                    'email' => 'We cannot find a user with that email address.'
                ])->withInput();
            }

            PasswordResetOtp::where('email', $email)->delete();

            $otpRecord = PasswordResetOtp::createForEmail($email);

            Log::info('Password Reset OTP Created', [
                'email' => $email,
                'otp' => $otpRecord->otp,
                'expires_at' => $otpRecord->expires_at,
                'user_id' => $user->id,
                'created_at' => $otpRecord->created_at
            ]);

            Mail::to($email)->send(new PasswordResetOtpMail($otpRecord->otp));

            return redirect()->route('password.otp', ['email' => $email])
                           ->with('status', 'OTP has been sent to your email address!');

        } catch (Exception $e) {
            Log::error('Failed to send password reset OTP', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'email' => 'Failed to send OTP. Please try again later.'
            ])->withInput();
        }
    }

    public function showOtpForm(Request $request)
    {
        $email = $request->get('email');

        if (!$email) {
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'Email parameter is required to proceed.']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'Invalid email format.']);
        }

        $email = strtolower(trim($email));

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'Invalid email address.']);
        }

        $activeOtp = PasswordResetOtp::where('email', $email)
                                   ->where('expires_at', '>', Carbon::now())
                                   ->first();

        if (!$activeOtp) {
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'No active OTP found. Please request a new one.']);
        }

        return view('auth.passwords.verify-otp', [
            'email' => $email
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'otp.required' => 'OTP is required.',
            'otp.size' => 'OTP must be exactly 6 digits.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = strtolower(trim($request->email));
        $otp = trim($request->otp);

        Log::info('OTP Verification Attempt', [
            'email' => $email,
            'otp_submitted' => $otp,
            'timestamp' => Carbon::now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            $otpRecord = PasswordResetOtp::where('email', $email)
                                       ->where('otp', $otp)
                                       ->first();

            if (!$otpRecord) {
                Log::warning('OTP Verification Failed - Record Not Found', [
                    'email' => $email,
                    'otp_submitted' => $otp,
                    'existing_otps' => PasswordResetOtp::where('email', $email)->get(['otp', 'expires_at'])->toArray()
                ]);

                return back()->withErrors([
                    'otp' => 'Invalid OTP code. Please check and try again.'
                ])->withInput();
            }

            if ($otpRecord->isExpired()) {
                Log::warning('OTP Verification Failed - Expired', [
                    'email' => $email,
                    'otp' => $otp,
                    'expires_at' => $otpRecord->expires_at,
                    'current_time' => Carbon::now()
                ]);

                $otpRecord->delete();

                return back()->withErrors([
                    'otp' => 'OTP has expired. Please request a new one.'
                ])->withInput();
            }

            $otpRecord->delete();

            Log::info('OTP Verification Successful', [
                'email' => $email,
                'user_id' => User::where('email', $email)->value('id')
            ]);

            session([
                'otp_verified' => true,
                'reset_email' => $email,
                'otp_verified_at' => Carbon::now(),
                'verification_token' => hash('sha256', $email . now() . config('app.key'))
            ]);

            return redirect()->route('password.reset.form', ['email' => $email]);

        } catch (Exception $e) {
            Log::error('OTP Verification Error', [
                'email' => $email,
                'otp' => $otp,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'otp' => 'An error occurred during verification. Please try again.'
            ])->withInput();
        }
    }

    public function showResetForm(Request $request)
    {
        $email = $request->get('email');

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'Valid email is required to reset password.']);
        }

        $email = strtolower(trim($email));

        if (!session('otp_verified') || session('reset_email') !== $email) {
            Log::warning('Unauthorized password reset form access', [
                'email' => $email,
                'session_verified' => session('otp_verified'),
                'session_email' => session('reset_email'),
                'ip_address' => $request->ip()
            ]);

            session()->forget(['otp_verified', 'reset_email', 'otp_verified_at', 'verification_token']);
            
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'Please verify your OTP first to reset your password.']);
        }

        $verifiedAt = session('otp_verified_at');
        if ($verifiedAt && Carbon::parse($verifiedAt)->addMinutes(15)->isPast()) {
            Log::info('Password reset session expired', [
                'email' => $email,
                'verified_at' => $verifiedAt,
                'current_time' => Carbon::now()
            ]);

            session()->forget(['otp_verified', 'reset_email', 'otp_verified_at', 'verification_token']);
            
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'Session expired for security reasons. Please start the process again.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            session()->forget(['otp_verified', 'reset_email', 'otp_verified_at', 'verification_token']);
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'User account not found.']);
        }

        return view('auth.passwords.reset', [
            'email' => $email
        ]);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'User not found.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = strtolower(trim($request->email));

        if (!session('otp_verified') || session('reset_email') !== $email) {
            Log::warning('Unauthorized password reset attempt', [
                'email' => $email,
                'session_verified' => session('otp_verified'),
                'session_email' => session('reset_email'),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            session()->forget(['otp_verified', 'reset_email', 'otp_verified_at', 'verification_token']);
            
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'Please verify your OTP first to reset your password.']);
        }

        $verifiedAt = session('otp_verified_at');
        if ($verifiedAt && Carbon::parse($verifiedAt)->addMinutes(15)->isPast()) {
            session()->forget(['otp_verified', 'reset_email', 'otp_verified_at', 'verification_token']);
            
            return redirect()->route('password.request')
                           ->withErrors(['email' => 'Session expired. Please start the password reset process again.']);
        }

        try {
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                Log::error('User not found during password reset', ['email' => $email]);
                return back()->withErrors(['email' => 'User account not found.']);
            }

            if (Hash::check($request->password, $user->password)) {
                return back()->withErrors([
                    'password' => 'New password must be different from your current password.'
                ])->withInput();
            }

            $user->update([
                'password' => Hash::make($request->password),
                'updated_at' => Carbon::now()
            ]);

            Log::info('Password reset completed successfully', [
                'email' => $email,
                'user_id' => $user->id,
                'reset_at' => Carbon::now()
            ]);

            session()->forget(['otp_verified', 'reset_email', 'otp_verified_at', 'verification_token']);

            PasswordResetOtp::where('email', $email)->delete();

            return redirect()->route('login')
                           ->with('status', 'Your password has been reset successfully! You can now login with your new password.');

        } catch (Exception $e) {
            Log::error('Password reset failed', [
                'email' => $email,
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'password' => 'An error occurred while resetting your password. Please try again.'
            ])->withInput();
        }
    }

    public function cleanupExpiredOtps()
    {
        try {
            $deletedCount = PasswordResetOtp::where('expires_at', '<', Carbon::now())->delete();
            
            Log::info('Expired OTPs cleaned up', ['deleted_count' => $deletedCount]);
            
            return $deletedCount;
        } catch (Exception $e) {
            Log::error('Failed to cleanup expired OTPs', [
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }
}