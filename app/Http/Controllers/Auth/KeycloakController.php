<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Constants\Constants;
use App\Traits\SessionTrait;
use Illuminate\Http\Request;
use App\Services\KeycloakService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class KeycloakController extends \App\Http\Controllers\Controller
{
    use SessionTrait;

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
        // Keycloak logout
        $service = new KeycloakService();
        $url = $service->logout();

        $this->logoutLaravel();
        return $url ? redirect($url) : redirect('/');
    }
}
