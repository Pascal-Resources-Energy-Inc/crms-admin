<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = Auth::user();

            if (!$this->validateSelectedRole($request, $user) || $this->isUserInactive($user)) {
                Auth::logout();
                throw ValidationException::withMessages([
                    $this->username() => ['The provided credentials are incorrect.'],
                ]);
            }

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateSelectedRole($request, $user)
    {
        $selectedRole = strtolower($request->input('selected_role'));
        $userRole     = strtolower($user->role);

        if (!$selectedRole) {
            return true;
        }

        $validRoles = ['dealer', 'client'];
        return in_array($userRole, $validRoles) && $userRole === $selectedRole;
    }

    protected function isUserInactive($user)
    {
        if ($user->role === 'Dealer') {
            $dealer = \App\Dealer::where('user_id', $user->id)->first();
            return !$dealer || (isset($dealer->status) && strtolower($dealer->status) === 'inactive');
        }

        if ($user->role === 'Client') {
            $client = \App\Client::where('user_id', $user->id)->first();
            if (!$client || (isset($client->status) && strtolower($client->status) === 'inactive')) {
                return true;
            }

            $serial = $client->serial->serial_number ?? $client->serial_number ?? null;
            return empty($serial);
        }

        return true;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['The provided credentials are incorrect.'],
        ]);
    }
}
