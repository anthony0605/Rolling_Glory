<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
        if(Hash::check($password, $user->password)){
            $api_token = base64_encode(Str::random(40));
            $user->update(['api_token' => $api_token]);
            return response()->json([
                'success' => true,
                'message' => 'Success login',
                'data' => [
                    'user' => $user,
                    'token' => $api_token
                ] 
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Password not the same',
            ], 401);
        }
    }

    public function register(Request $request){
        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $data = [
            'id' => Str::uuid()->toString(),
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'user_role' => 'user'
        ];
        
        $register = User::create($data);

        if($register){
            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => $register
            ], 201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User registration failed',
                'data' => ''
            ], 400);
        }
    }
}
