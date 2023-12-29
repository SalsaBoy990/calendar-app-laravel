<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $request->only( 'email', 'password' );
        $rememberMe = (bool) $request->input('remember');
        if ( Auth::attempt( $credentials, $rememberMe ) ) {

            // only generate 2fa code when user had 2FA enabled!
            if ( auth()->user()->enable_2fa === 1 ) {
                auth()->user()->generateCode();

                return redirect()->route( '2fa.index' );
            } else {
                // business as usual, no 2FA needed
                if ( $request->hasSession() ) {
                    $request->session()->put( 'auth.password_confirmed_at', time() );
                }

                return $this->sendLoginResponse( $request );
            }

        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    protected function loggedOut(Request $request)
    {
        request()->session()->flash('flash.banner', __('Successful logout!'));
        request()->session()->flash('flash.bannerStyle', 'success');

        return;
    }
}
