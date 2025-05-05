<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Step 1: Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Step 2: User Creation
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'status' => 1,
            ]);

            // Step 3: Token Creation
            $token = $user->createToken('UserToken')->accessToken;

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'User registered successfully.',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'status' => $user->status,
                    ],
                ],
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'A database error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An unexpected server error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function loginViaApi(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();

            if ($user->role === 'admin') {
                $request->session()->regenerate();

                return response()->json([
                    'status' => true,
                    'message' => 'Admin login successful',
                    'redirect' => url('/admin/dashboard'),
                    'session_id' => session()->getId(),
                ]);
            } else {
                Auth::logout();
                return response()->json([
                    'status' => false,
                    'message' => 'User is not an admin.',
                ], 403);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid credentials.',
        ], 401);
    }

    public function generateLoginLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'User is not an admin.',
            ], 403);
        }

        // Permanent signed URL (no expiration)
        $url = URL::signedRoute('admin.magic-login.redirect', ['user' => $user->id]);

        return response()->json([
            'status' => true,
            'login_link' => $url,
        ]);
    }

    public function login(Request $request)
    {
        try {
            // Step 1: Validation
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Step 2: Authenticate using Laravel Passport
            $response = Http::asForm()->post(url('/oauth/token'), [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ]);

            $data = $response->json();

            if ($response->successful()) {
                //  Get Authenticated User
                $user = User::where('email', $request->email)->first();

                if ($user->role == "admin") {
                    $user = User::where('email', $request->email)->first();

                    if (!$user || !Hash::check($request->password, $user->password)) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Invalid credentials',
                        ], 401);
                    }

                    if ($user->role !== 'admin') {
                        return response()->json([
                            'status' => false,
                            'message' => 'User is not an admin.',
                        ], 403);
                    }

                    // Permanent signed URL (no expiration)
                    $url = URL::signedRoute('admin.magic-login.redirect', ['user' => $user->id]);
                    return response()->json([
                        'status' => true,
                        'status_code' => 200,
                        'message' => 'Admin login successful.',
                        'redirect_url' => $url,
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
                    // return response()->json([
                    //     'status' => true,
                    //     'login_link' => $url,
                    // ]);
                    // return redirect()->away($url);
                }

                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Login successful.',
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
            } else {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Invalid credentials. Please check your email and password.',
                    'error' => $data,
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An unexpected server error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function refreshToken(Request $request)
    {
        try {
            // Step 1: Validation
            $validator = Validator::make($request->all(), [
                'refresh_token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 422,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Step 2: Request a new access token using the refresh token
            $response = Http::asForm()->post(url('/oauth/token'), [
                'grant_type' => 'refresh_token',
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                'refresh_token' => $request->refresh_token,
                'scope' => '',
            ]);

            $data = $response->json();

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'status_code' => 200,
                    'message' => 'Token refreshed successfully.',
                    'data' => [
                        'token_type' => $data['token_type'],
                        'expires_in' => $data['expires_in'],
                        'access_token' => $data['access_token'],
                        'refresh_token' => $data['refresh_token'],
                    ],
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'status_code' => $response->status(),
                    'message' => 'Could not refresh token. Please try again.',
                    'error' => $data,
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'An unexpected server error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Check if a valid token is provided
            if (!$request->bearerToken()) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Invalid or missing token.',
                ], 401);
            }

            // Check if the user is authenticated
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'status_code' => 401,
                    'message' => 'Unauthorized. User not authenticated.',
                ], 401);
            }

            // Revoke the current access token
            $token = $user->token();
            if ($token) {
                $token->revoke();
            }

            // Revoke refresh token (Optional)
            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $token->id ?? null)
                ->update(['revoked' => true]);

            return response()->json([
                'status' => true,
                'status_code' => 200,
                'message' => 'User logged out successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'status_code' => 500,
                'message' => 'Logout failed due to a server error.',
            ], 500);
        }
    }



}
