<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function facebookRedirect(): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver('facebook')->scopes('public_profile')->redirect();
    }

    public function loginWithFacebook(): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $isUser = User::where('facebook_id', $user->id)->first();

            if ($isUser) {
                Auth::login($isUser);
                return redirect()->route('dashboard');
            } else {
                $createUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id' => $user->id,
                    'password' => bcrypt($user->getName().'@'.$user->getId()),
                    'role_id' => 2,
                ]);

                Auth::login($createUser);
                return redirect()->route('dashboard');
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            Session::flash('login_error', $exception->getMessage());
            return redirect('/admin/login');
        }
    }
}
