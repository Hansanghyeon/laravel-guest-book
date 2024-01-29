<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function callback()
    {
        $githubUser = Socialite::driver('github')->user();

        // 사용자가 존재하지 않는 경우 기존 사용자를 업데이트하십시오
        $user = User::updateOrCreate([
            'email' => $githubUser->getEmail(),
        ], [
            'provider_id' => $githubUser->getId(),
            'name' => $githubUser->getName() ?? $githubUser->getNickname(),
            'token' => $githubUser->token,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }
}
