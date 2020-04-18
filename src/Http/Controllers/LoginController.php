<?php

namespace Neliserp\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

use Neliserp\Core\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return $this->sendFailedLoginResponse($request);
        }

        if (! $user->is_active) {
            return $this->sendFailedLoginResponse($request, 'Temporarily disable user account.');
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // TODO: log number of failed attempts.
        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new Response('', 204)
                    : redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse(Request $request, $message = '')
    {
        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            'username' => [
                $message ?: trans('auth.failed')
            ],
        ]);
    }
}
