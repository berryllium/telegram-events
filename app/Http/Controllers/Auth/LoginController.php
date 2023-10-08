<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attemptWhen(
            $this->credentials($request),
            fn ($user) => $user->hasRole('supervisor') || $user->telegram_bots()->exists(),
            $request->filled('telegram_bot')
        );
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->getLastAttempted();
        if($user && $this->guard()->getProvider()->validateCredentials($user, $this->credentials($request))) {
            $messages = ['telegram_bot' => trans('auth.bot')];
        } else {
            $messages = [$this->username() => trans('auth.failed')];
        }
        throw ValidationException::withMessages($messages);
    }
}
