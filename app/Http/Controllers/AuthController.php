<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // github
    public function github_redirect()
    {
        return Socialite::driver('github')->redirect();
    }

    public function github_callback()
    {
        $githubUser = Socialite::driver('github')->user();

        // 사용자가 존재하지 않는 경우 기존 사용자를 업데이트하십시오
        $user = User::updateOrCreate([
            'email' => $githubUser->getEmail(),
        ], [
            'name' => $githubUser->getName(),
            // 'github_id' => $githubUser->getId(),
            // 'nick_name' => $githubUser->getNickname(),
            // 'github_avatar' => $githubUser->getAvatar(),
            // 'github_token' => $githubUser->token,
            // 'github_refresh_token' => $githubUser->refreshToken,
            // 'expires_in' => $githubUser->expiresIn,
        ]);

        Auth::login($user);

        return redirect('/');
    }

    // google
    public function google_redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function google_callback()
    {
        $githubUser = Socialite::driver('google')->user();

        // 사용자가 존재하지 않는 경우 기존 사용자를 업데이트하십시오
        $user = User::updateOrCreate([
            'email' => $githubUser->getEmail(),
        ], [
            'name' => $githubUser->getName(),
            // 'avatar' => $githubUser->getAvatar(),
            // 'github_id' => $githubUser->getId(),
            // 'nick_name' => $githubUser->getNickname(),
            // 'github_token' => $githubUser->token,
            // 'github_refresh_token' => $githubUser->refreshToken,
            // 'expires_in' => $githubUser->expiresIn,
        ]);

        Auth::login($user);

        return redirect('/');
    }
}
