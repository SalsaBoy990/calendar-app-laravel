<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Interface\UserCodeInterface;
use App\Models\UserCode;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;

/**
 *
 */
class UserCodeController extends Controller implements UserCodeInterface
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        return view('auth.2fa-verification');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request): Application|RedirectResponse|Redirector
    {
        // Redirect if the user have already submitted the correct code
        // case: multiple opened windows with the 2fa page, user clicks the Login button in one of them
        // after submitting the code at another page 'instance'
        if ($this->codeAlreadySubmitted() === true) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'code' => 'required|string'
        ]);

        $find = UserCode::where('user_id', auth()->id())
            ->where('code', $request->code)
            ->where('updated_at', '>=', now()->subMinutes(self::CODE_VALIDITY_EXPIRATION))
            ->first();

        if (!is_null($find)) {
            Session::put('user_2fa', auth()->id());

            return redirect(RouteServiceProvider::HOME);
        }

        return back()->with('error', __('You entered wrong code.'));
    }


    /**
     * Resend the login code for 2FA
     *
     * @return RedirectResponse
     */
    public function resend(): RedirectResponse
    {
        auth()->user()->generateCode();

        return back()->with('success', __('We sent you code on your email.'));
    }

    /**
     * @return bool
     */
    private function codeAlreadySubmitted(): bool
    {
        $submitted = false;
        // redirect to the SPA (if user already submitted the correct code)
        if (auth()->user()->enable_2fa === 1 && Session::has('user_2fa')) {
            $submitted = true;
        }
        return $submitted;
    }

}
