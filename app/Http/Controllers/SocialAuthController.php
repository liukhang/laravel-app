<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\SocialAccountService;
use Laravel\Socialite\Facades\Socialite;


class SocialAuthController extends Controller
{
    public function redirect($social)
    {
        return Socialite::driver($social)->scopes(['read:user', 'public_repo'])->redirect();
    }

    public function callback(Request $request, $social)
    {
        $user = SocialAccountService::createOrGetUser(Socialite::driver($social)->user(), $social);
        Auth::login($user, true);
        
        return redirect()->to('/dashboard');
    }
}
