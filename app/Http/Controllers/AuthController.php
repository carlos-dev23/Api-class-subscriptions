<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function store(LoginRequest $request)
    {
        $credential = $request->validated();
        if (! Auth::attempt($credential)) {
            return response()->json(['status' => 'error', 'message' => 'Las credenciales no son correctas'],401);
        }
        try{
            $user = Auth::user();
            $user->tokens()->delete();
            //Se define auna política de sessión única
            $token = $user->createToken('token')->plainTextToken;
            return response()->json(['status' => 'success', 'data' => $token]);
        }catch(Exception $e){
            report($e);
            return response()->json(['status' => 'error','message' => 'Fallo en el servidor'],500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['status' => 'success', 'message' => 'Usuario desconectado exitosamente']);
        } catch (Exception $e) {
            report($e);
            return response()->json(['status' => 'error', 'message' => 'Error al desconectar al usuario, intentelo mas tarde'],500);
        }
    }
}
