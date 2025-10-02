<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Constants\Constants;

class KeycloakController extends \App\Http\Controllers\Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('keycloak')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('keycloak')->user();

            $authUser = User::firstOrCreate([
                'email' => $user->getEmail(),
            ], [
                'name' => $user->getName(),
                'password' => bcrypt(Str::random(16)),
            ]);

            Auth::login($authUser, true);

            Session::put('keycloak_id_token', $user->accessTokenResponseBody['id_token']);
            Session::put('keycloak_id_user', $user->id);

            return redirect('/');
        } catch (\Exception $e) {
            return redirect()->route('index');
        }
    }

    public function logout(Request $request)
    {
        $token = Session::get('keycloak_id_token');
        Auth::logout();
        Session::flush();
        Session::regenerate();

        $redirectUri = Config::get('app.url');
        $url = Socialite::driver('keycloak')->getLogoutUrl();
        $params = [
            'id_token_hint' => $token,
            'post_logout_redirect_uri' => $redirectUri,
        ];
        $url .= '?' . http_build_query($params);

        return redirect($url);
    }
}
