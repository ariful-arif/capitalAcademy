<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {

        return Socialite::driver('google')->stateless()->redirect();
    }

  public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    // You can store a fixed string instead of a random password if using social login only
    $rawPassword = Str::random(24);
    $user = User::updateOrCreate([
        'email' => $googleUser->getEmail(),
    ], [
        'name' => $googleUser->getName(),
        'google_id' => $googleUser->getId(),
        'photo' => $googleUser->getAvatar(),
        'role' => "student",
        'status' => 1,
        'password' => bcrypt($rawPassword),
    ]);

    // Now use the same raw password in the token request
    $response = Http::asForm()->post(url('/oauth/token'), [
        'grant_type' => 'password',
        'client_id' => env('PASSPORT_CLIENT_ID'),
        'client_secret' => env('PASSPORT_CLIENT_SECRET'),
        'username' => $user->email,
        'password' => $rawPassword,
        'scope' => '',
    ]);

    // Check for errors in response
    if ($response->failed()) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to retrieve token.',
            'error' => $response->json(),
        ], 401);
    }

    $data = $response->json();

    return response()->json([
        'status' => true,
        'status_code' => 200,
        'message' => 'Login successful.',
        'redirect_url' => "",
        'data' => [
            'token_type' => $data['token_type'],
            'expires_in' => $data['expires_in'],
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
            ],
        ],
    ], 200);
}

}
