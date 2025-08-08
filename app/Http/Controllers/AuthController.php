<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }

        $token = auth()->attempt($credentials);
        return response()->json([
            'token' => $token,
            'user' => auth()->user(),
            'role' => auth()->user()->role,
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'تم تسجيل الخروج']);
    }
}
