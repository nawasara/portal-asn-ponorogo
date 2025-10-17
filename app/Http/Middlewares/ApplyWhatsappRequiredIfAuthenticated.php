<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApplyWhatsappRequiredIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        // Jika belum login, langsung lanjut
        if (!Auth::check()) {
            return $next($request);
        }

        // Jika sudah login, jalankan middleware whatsapp.required
        return app(EnsureWhatsappNumberIsSet::class)
            ->handle($request, $next);
    }
}
