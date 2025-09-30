<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureWhatsappNumberIsSet
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        $token = Session::get('keycloak_id_user');
        $service = new \App\Services\KeycloakService();
        $whatsappNumber = $service->getWhatsappNumber($token);
        if ($user && empty($whatsappNumber) && !$request->is('update-whatsapp-number')) {
            return redirect('/update-whatsapp-number');
        }

        return $next($request);
    }
}
