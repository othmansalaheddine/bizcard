<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class ApiController extends Controller
{
    //regiter API (Post, formdata)


    public function register(Request $request){
        
        dd($request);
        $request->validate(
            [
                "name" => "required",
                "email"=> "required|email|unique:users",
                "password"=> "required|confirmed"
            ]);
            //data saving
           User::create([
             "name"=> $request->name,
             "email"=> $request->email,
             "password"=> Hash::make($request->password)
        ]);
        return response()->json([
            "status"=> true,
            "message"=> "User register successfully"
            ]);

    }
    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password"=> "required"
            ]);
            $user = User::where("email", $request->email)->first();

            if(!empty($user)){
                if(Hash::check($request->password, $user->password)){
                    $token = $user->createToken("mytoken")->plainTextToken;
                     
                    return response()->json([
                        "status"=> true,
                        "message"=> "login succefull",
                        "token"=> $token
                        ]);
                }
            }

    }
    public function profile(){
       
        $data = auth()->user(); //user auth

        return response()->json([
            "status"=> true,
            "message" => "Pofile data",
            "user" => $data
        ]);
         
 
    }
    public function logout(){

       auth()->user()->tokens()->delete();
       return response()->json([
        "status"=> true,
        "message"=> "U're logged out"
        ]);

    }


}
