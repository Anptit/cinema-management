<?php

namespace App\Http\Controllers;

use App\Domain\Enums\UserRoles;
use App\Domain\Models\Profile;
use Illuminate\Http\Request;
use App\Domain\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::guard('api')->user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(StoreUserRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? UserRoles::CUSTOMER->value
        ]);

        $profile = Profile::create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'address' => $request->address,
            'identity_card' => $request->identity_card,
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'profile' => $profile
        ]);
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::guard('api')->user(),
            'authorisation' => [
                'token' => JWTAuth::refresh(JWTAuth::getToken()),
                'type' => 'bearer',
            ]
        ]);
    }
}
