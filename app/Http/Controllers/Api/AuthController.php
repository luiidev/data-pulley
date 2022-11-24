<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'nick' => 'required|string|max:32',
            'password' => 'required|string|max:32',
            'device_name' => 'required|string|max:32',
        ]);

        $user = User::query()
            ->where('nick', $request->input('nick'))
            ->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'nick' => ['Usuario y/o contraseña incorrecta.'],
            ]);
        }

        $user->makeHidden(['address', 'id_number', 'phone', 'first_name', 'last_name']);

        /**
         * Create a new token
         */
        $token = $user->createToken(
            $request->input('device_name')
        );

        /**
         * Delete others tokens
         */
        $user->tokens()
            ->where('id', '<>', $token->accessToken->getAttribute('id'))
            ->delete();

        return response()
            ->json([
                'message' => 'Login',
                'token' => $token->plainTextToken,
                'data' => $user,
            ]);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->tokens()->delete();

        Auth::guard('web')->logout();

        return response()->json(['message' => 'Logout.']);
    }

    public function me(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => '', 'data' => $request->user()]);
    }
}
