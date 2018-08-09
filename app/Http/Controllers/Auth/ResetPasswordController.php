<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Support\Facades\Log; // ログ出力で使用

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    /* add logging */

    public function showResetForm(Request $request, $token = null)
    {
        Log::info('ResetPasswordController::showResetForm()');
        if($request->email===NULL)
        {
            Log::info('email: NULL');
        }
        else
        {
            Log::info('email: '.$request->email);
        }

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        Log::info('ResetPasswordController::reset()');

        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($response)
                    : $this->sendResetFailedResponse($request, $response);
    }

    protected function resetPassword($user, $password)
    {
        Log::info('ResetPasswordController::resetPassword()');
        if($user===NULL)
        {
            Log::info('user: NULL');
        }
        else
        {
            Log::info('name: '.$user->name);
            Log::info('email: '.$user->email);
        }
        if($password===NULL)
        {
            Log::info('password: NULL');
        }
        else
        {
            Log::info('password: ***');
        }

        $user->password = Hash::make($password);
        Log::info('password: '.$user->password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    protected function sendResetResponse($response)
    {
        Log::info('ResetPasswordController::sendResetResponse()');
        if(trans($response)===NULL)
        {
            Log::info('status: NULL');
        }
        else
        {
            Log::info('status: '.trans($response));
        }

        return redirect($this->redirectPath())
                            ->with('status', trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        Log::info('ResetPasswordController::sendResetFailedResponse()');
        if(trans($response)===NULL)
        {
            Log::info('email: NULL');
        }
        else
        {
            Log::info('email: '.trans($response));
        }

        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }

}
