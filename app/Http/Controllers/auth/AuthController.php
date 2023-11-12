<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Creamos el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // Generar el token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            // Intentar autenticar al usuario con las credenciales recibidas.
            if (!$tokenCheck = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'las credenciales ingresadas son incorrectas.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'token no creado',
            ], 500);
        }

            // Obtenemos al usuario autenticado
            $user = auth()->user();

        return response()->json([
            'token' => $tokenCheck,
            'user' => $user,
        ]);
    }

    public function verifyToken()
    {
        try {
            $token = JWTAuth::getToken();

            if(!$token) {
                return response()->json([
                    'error', 'Token no proporcionado.'
                ], 400);
            }

            // Verificar si el token es valido
            $user = JWTAuth::parseToken()->authenticate();

            // si el token es válido retornamos una respuesta exitosa
            return response()->json([
                'message' => 'Token válido',
                'user' => $user
            ]);


        } catch (JWTException $e) {
            // Manejo de excepciones
            return response()->json(['error' => 'Token inválido'], 401);
        }
    }
}
