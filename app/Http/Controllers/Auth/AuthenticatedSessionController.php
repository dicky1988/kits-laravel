<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function storeOld(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        /**
         * =====================================================
         * 1️⃣ COBA LOGIN KE API STARA
         * =====================================================
         */
        try {
            $response = Http::timeout(10)->post(
                config('services.stara.login'),
                [
                    'username' => $request->username,
                    'password' => $request->password,
                ]
            );

            if ($response->successful()) {
                $payload = $response->json();

                if (($payload['status'] ?? null) === 'Success') {

                    $data     = $payload['data'];
                    $userInfo = $data['user_info'];

                    $user = User::updateOrCreate(
                    // IDENTITAS LOGIN = USERNAME
                        ['username' => $userInfo['username']],
                        [
                            'name'              => $userInfo['name'],
                            'nip'               => preg_replace('/\s+/', '', $userInfo['nipbaru']),
                            'nip_lama'          => $userInfo['user_nip'],
                            'email'             => isset($userInfo['email'])
                                ? Str::lower($userInfo['email'])
                                : null,
                            'password'          => bcrypt($request->password),
                            'email_verified_at' => now(),

                            'api_token'       => blank($data['api_token'] ?? null) ? null : $data['api_token'],
                            'api_token_web'   => blank($data['api_token_web'] ?? null) ? null : $data['api_token_web'],
                            'api_token_smile' => blank($data['api_token_smile'] ?? null) ? null : $data['api_token_smile'],
                            'jwt_token'       => blank($data['token'] ?? null) ? null : $data['token'],
                        ]
                    );

                    Auth::login($user);
                    $request->session()->regenerate();

                    return redirect()->intended(route('dashboard'));
                }
            }
        } catch (\Throwable $e) {
            logger()->warning('STARA API login failed, fallback to local auth', [
                'username' => $request->username,
                'error'    => $e->getMessage(),
            ]);
        }

        /**
         * =====================================================
         * 2️⃣ FALLBACK: LOGIN USER LOKAL (DB)
         * =====================================================
         */
        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        /**
         * =====================================================
         * 3️⃣ GAGAL TOTAL
         * =====================================================
         */
        throw ValidationException::withMessages([
            'username' => 'Login gagal. Sistem autentikasi sedang bermasalah atau kredensial tidak valid.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
