<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8'
        ]);
        $user = User::where('name', $request->name)->first();
        if( $user && Hash::check($request->password, $user->password) ){
            $user->tokens()->delete();
            $token = $user->createToken('acess_token');
            return response()->json([
                'token' => $token->plainTextToken,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ], 201);
        }
        return response()->json([
            'message' => 'not authorized, unauthenticated'
        ], 401);
    }
    public function register(Request $request){
        // $request->name
        // $request->email
        // $request->password
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);
        $user = User::create([
            'name' => $request->json('name'),
            'email' => $request->json('email'),
            'password' => Hash::make($request->json('password'))
        ]);
        $token = $user->createToken('access_token');

        return response()->json([
            'token' => $token->plainTextToken,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ], 201);
    }
    public function logout(){
        $user = User::findOrFail(auth()->id());
        $user->tokens()->delete();

        return response()->json([
            'message' => 'token successfully deleted'
        ], 204);
    }
}
