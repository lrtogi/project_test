<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use DB;
use Log;

class APIController extends Controller
{
    public function register(Request $request)
    {
        log::debug('masuk');
        try{
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'user.username' => 'required',
                'user.email' => 'required|email',
                'user.encrypted_password' => 'required',
                'user.phone' => 'required|numeric',
                'user.address' => 'required',
                'user.city' => 'required',
                'user.country' => 'required',
                'user.name' => 'required',
                'user.postcode' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['result' => false, 'message' => $validator->errors()]);       
            }

            $findUser = User::where(function($query) use($request){
                $query->orWhere('username', $request->user["username"]);
                $query->orWhere('email', $request->user["email"]);
            })->count();
            if($findUser > 0){
                return response()->json(['result' => false, 'message' => 'Username or email has already been used']);       
            }

            $user = User::create([
                'email' => $request->user["email"],
                'password' => Hash::make($request->user["encrypted_password"]),
                'name' => $request->user["name"],
                'username' => $request->user['username'],
                'phone' => $request->user['phone'],
                'country' => $request->user['country'],
                'city' => $request->user['city'],
                'postcode' => $request->user['postcode'],
                'address' => $request->user['address']
            ]);
            $token = $user->createToken('tokens')->plainTextToken;
            DB::commit();
            return response()->json([
                "email" => $user->email,
                "token" => $token,
                "username" => $user->username
            ]);
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'result' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function login(Request $request)
    {
        try{
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;
                $success['email'] = $user->email;
                $success['token'] = $token;
                $success['username'] = $user->username;
                return response()->json($success);
            } 
            else{
                return response()->json(['result' => false, 'message' => 'Invalid login attempt']);
            } 

            $findUser = User::where('email', $request->email)->count();
            if($findUser > 0){
                return response()->json(['result' => false, 'message' => ['email' => ['Duplicated email']]]);       
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage() . " on line ". $e->getLine() . " on file ". $e->getFile()
            ]);
            log::debug($e->getMessage() . " on line ". $e->getLine() . " on file ". $e->getFile());
        }
    }
}
