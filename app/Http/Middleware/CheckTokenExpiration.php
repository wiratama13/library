<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Periksa apakah pengguna terautentikasi dengan Sanctum
        $token = $request->bearerToken();

        if ($token) {
            // Ambil token dari tabel personal_access_tokens
            $personalAccessToken = PersonalAccessToken::findToken($token);

            // Cek apakah token ada dan apakah sudah kedaluwarsa
            if ($personalAccessToken && $personalAccessToken->expires_at && Carbon::now()->greaterThan($personalAccessToken->expires_at)) {
                // Hapus token jika sudah kedaluwarsa
                $personalAccessToken->delete();

                return response()->json(['message' => 'Token has expired, please login again'], 401);
            }
        }

        return $next($request);
    }
}
