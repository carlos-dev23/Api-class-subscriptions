<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;

class UserController extends Controller
{
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        try {
            $user = User::create($data);
            $token = $user->createToken('auth_token')->plainTextToken;
            $user['token'] = $token;
        } catch (Exception $e) {
            report($e);
            return response()->json(['status' => false, 'message' => 'Error al crear al usuario, intentelo mas tarde'],500);
        }
        return response()->json(['status' => 'success', 'user' => $user]);
    }
}
