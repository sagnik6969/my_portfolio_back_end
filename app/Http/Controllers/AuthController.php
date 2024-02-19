<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AdminLoginNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['logout']);
    }


    public function login(Request $request)
    {

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        // $user = User::where('email', $request->email)->first();

        // if (!$user) {
        //     throw ValidationException::withMessages([
        //         'email' => ['The provided credentials are incorrect']
        //     ]);
        // }

        // if (!Hash::check($request->password, $user->password)) {

        //     throw ValidationException::withMessages([
        //         'email' => ['The provided credentials are incorrect']
        //     ]);
        // }

        // $user->tokens()->delete();

        // $tokenExpirationTime = now()->addDays(2);

        // $token = $user->createToken('api-token', ['*'], $tokenExpirationTime);
        if (Auth::attempt($data)) {

            $request->session()->regenerate();

            $user = auth()->user();

            $user->notify(new AdminLoginNotification());
            return response()->json([
                'user' => $user
            ]);

        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect']
        ]);

    }

    public function logout(Request $request)
    {
        // $request->user()->tokens()->delete();

        // return response()->json([
        //     'message' => 'logged out successfully'
        // ]);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'logged out successfully'
        ]);

    }
}
