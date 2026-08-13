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
        try {
            $credential = $request->validated();
            if (Auth::attempt($credential)) {
                $user = User::where('email', $credential['email'])->first();
                $last_token = DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->first();
                if ($last_token != null) {
                    $user->tokens()->where('tokenable_id', $user->id)->delete();
                }
                $token = $user->createToken('token')->plainTextToken;

                return response()->json(['status' => 'success', 'data' => $token]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al iniciar sesión, intentelo mas tarde'], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $id_user = $request->idUser;
            $user = User::find($id_user);
            $user->tokens()->delete();

            return response()->json(['status' => 'success', 'message' => 'Usuario desconectado exitosamente']);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Error al desconectar al usuario, intentelo mas tarde']);
        }
    }
}
