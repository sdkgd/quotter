<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quser;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $quser = Quser::where('email', $request->input('email'))->first();

        if (!$quser || !Hash::check($request->input('password'), $quser->password)) {
            return response()->json(['error' => '認証に失敗しました。'], 401);
        }
        $token = $quser->createToken('AccessToken')->plainTextToken;
        return response()->json(['token' => $token], 201);
    }

    public function register(Request $request)
    {
        $quser=Quser::create([
            'user_name' => $request->name,
            'display_name'=> $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        
        if($quser){
            return response()->json(['quser' => $quser],201);
        }else{
            return response()->json(['error' => 'ユーザ登録に失敗しました。'],500);
        }
    }

    public function user(Request $request){
        return response()->json(
            [
                'id' => $request->user()->id,
                'user_name' => $request->user()->user_name,
                'display_name' => $request->user()->display_name,
                'email' => $request->user()->email,
                'profile' => $request->user()->profile,
            ]
        ,200);
    }

    public function logout(Request $request)
    {
        PersonalAccessToken::findToken($request->bearerToken())->delete();
        return response()->json(['message' => 'ログアウトしました。'], 201);
    }
}
