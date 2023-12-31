<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum',['except'=>['login','register','registerAdmin']]);
    // }
    
    public function register(Request $reqest){

        // $validator = Validator::make($reqest->all(), [
        //     'name'=>'required',
        //     'phone_number'=>'required|min:8|unique:users,phone_number',
        //     'password'=>'required|min:6|confirmed'
        // ]);

        // if($validator->fails()){
        //     return response()->json($validator->errors()->toJson(),400);
        // }

        $reqest->validate([
            'name'=>'required',
            'phone_number'=>'required|min:8|unique:users,phone_number',
            'password'=>'required|min:6|confirmed'
        ]);

        $user=User::create([
            'name'=>$reqest->name,
            'phone_number'=>$reqest->phone_number,
            'password'=>Hash::make($reqest->password),
        ]);

        $token=$user->createToken("token")->plainTextToken;

        return response()->json([
            'message'=>$user,
            'token'=>$token
        ],200);

    }

    public function registerAdmin(Request $reqest){
        
        $validator = Validator::make($reqest->all(), [
            'name'=>'required',
            'phone_number'=>'required|min:8|unique:users,phone_number',
            'password'=>'required|min:6|confirmed'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        // $reqest->validate([
        //     'name'=>'required',
        //     'phone_number'=>'required|min:8|unique:users,phone_number',
        //     'password'=>'required|min:6|confirmed',
            
        // ]);

        $user=User::create([
            'name'=>$reqest->name,
            'phone_number'=>$reqest->phone_number,
            'password'=>Hash::make($reqest->password),
            'is_admin'=>1
        ]);

        $token=$user->createToken("token")->plainTextToken;

        return response()->json([
            'message'=>$user,
            'token'=>$token
        ],200);

    }

    //////////////////////////////////////
    public function login(Request $reqest){

        $validator = Validator::make($reqest->all(), [
            'phone_number'=>'required|min:8',
            'password'=>'required|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        // $reqest->validate([
        //     'phone_number'=>'required|min:8',
        //     'password'=>'required|min:6'
        // ]);

        
        $user=User::where('phone_number','=',$reqest->phone_number)->first();

        if(isset($user->id)){

                if(Hash::check($reqest->password,$user->password)){
                    $token=$user->createToken("token")->plainTextToken;
                    
                }
                else{
                    return response()->json([
                        'message'=>'wrong password',
                    ],403);
                }
        }else{

            return response()->json([
                'message'=>'wrong phone number',
            ],403);
        }

        return response()->json([
           'user'=>$user,
           'token'=>$token
        ],200);
    }

   
    /////////////////////////////////////////////
    public function logout(Request $request)
    {
        // $user=Auth::user();
        // $user->currentAccessToken()->delete();

        $request->user()->currentAccessToken()->delete();
        
//auth()->user()->tokens()->delete();

         return response()->json([
            'message' =>'logout done',
        ],200);
    }

    public function profile(){
        
        return response()->json([
            'message'=>'profile',
            'data'=>auth()->user(),
        ],200);
    }

    public function edite(Request $request){
        
            $request->validate([
                'name'=>'required'
            ]);

            $user=User::find(auth()->user()->id);

            $user->update([
                'name'=>$request->name,
            ]);
            return response()->json([
                'message'=>'user updated',
                'user'=>auth()->user(),
            ],200);

            

    }
}
