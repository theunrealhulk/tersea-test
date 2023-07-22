<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request){

        $data=$request->validate([
            'email' =>'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('email',$data['email'])->first();
        if($user){
            $user->load('employee');
            $user->load('invitations');
            if($user && Hash::check ($data ['password'],$user -> password)){
                return response()->json([
                    'message'=>'Login successful',
                    'isAdmin'=>$user->isAdmin(),
                    'token'=>$user->createToken('auth_token')->plainTextToken
                ],201);
            }
            else{
                return response()->json([
                    'message'=>'incorrect email or password',
                ],401);
            }
        }
        return response()->json([
            'message'=>'user not found',
        ],404);
    }
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message'=>'disconnected',
        ],201);
    }

}
