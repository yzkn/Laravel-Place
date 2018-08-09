<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Log; // ログ出力で使用

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request) {
        Log::info('LoginController::logout()');

        $auth_user = Auth::user();
        if($auth_user===NULL)
        {
            Log::info('auth_user: NULL');
        }
        else
        {
            Log::info('auth_user: '.$auth_user->id);
        }

        Auth::logout();
        return redirect('/login');
    }

    /* add logging */

    public function showLoginForm()
    {
        Log::info('LoginController::showLoginForm()');

        return view('auth.login');
    }

    public function login(Request $request)
    {
        Log::info('LoginController::login()');

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            Log::info('hasTooManyLoginAttempts');

            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            Log::info('attemptLogin');

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        Log::info('incrementLoginAttempts');
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        Log::info('LoginController::validateLogin()');

        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        Log::info('LoginController::attemptLogin()');

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        Log::info('LoginController::credentials()');

        return $request->only($this->username(), 'password');
    }

    protected function sendLoginResponse(Request $request)
    {
        Log::info('LoginController::sendLoginResponse()');

        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    protected function authenticated(Request $request, $user)
    {
        Log::info('LoginController::authenticated()');
        //
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        Log::info('LoginController::sendFailedLoginResponse()');

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

}
