<?php

namespace App\Http\Controllers;

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
class UserCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {

        // If session expires, resend code automatically when redirected to this page
        /*        if (! Session::has('user_2fa') ) {
                    auth()->user()->generateCode();
                }*/

        return view('2fa-verification');
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
        $request->validate([
            'code' => 'required|string'
        ]);

        $find = UserCode::where('user_id', auth()->id())
            ->where('code', $request->code)
            ->where('updated_at', '>=', now()->subMinutes(2))
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

}
