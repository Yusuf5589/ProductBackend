<?php

namespace App\Http\Controllers;

use App\Jobs\RegisterJop;
use App\Jobs\RegisterMail;
use App\Jobs\SendMailJob;
use App\Mail\ForgotMail;
use App\Mail\MailSend;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    protected $data;

    public function __construct(UserRepository $userRepository){
        $this->data = $userRepository;
    }
 
    public function registerUser(Request $req){
        $req->validate([
            "username" => "required|min:8|max:32|unique:user,username",
            "gmail" => "required|email|unique:user,gmail",
            "age" => "required|integer",
            "phonenumber" => "required|min:10|max:10",
            "password" => "required|min:8|max:32",
        ], $this->data->valError());
        
        try {
            $this->data->registerUserRep($req->all());
            
            RegisterMail::dispatch($req->gmail, $req->username);
            
            
    
            return response()->json([
                "status"=> "success",
                "message"=> "Başarıyla kayıt oldun."
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }
       
    }

    public function userLogin(Request $req){
        $req->validate([
            "gmail" => "required|email|exists:user,gmail",
            "password" => "required|min:8|max:32",
        ], $this->data->valError());

        try {
            return $this->data->userLoginRep($req->all());
        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }
        

    }


    public function userLogout($id){
        
        try {

            return $this->data->userLogoutRep($id);

        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }
        
    }
    


    public function forgotPassword(Request $req){
        $req->validate([
            "gmail" => "required|email|exists:user,gmail",
        ], $this->data->valError());

        try {
            
            return $this->data->forgotPasswordRep($req->all());

        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }

    }

    public function forgotPasswordVerify($token, $gmail, Request $req){
        try {
            $tokentr = trim(str($token));
            if(!Cache::has("token{$tokentr}")){
                return response()->json([
                    "status"=> "error",
                    "message"=> $tokentr,
                ]);
            }
            else if(Cache::has("token{$tokentr}")){
                return $this->data->forgot($token, $gmail, $req->all());
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }
    }



    public function forgot(Request $req){
        try {
        } catch (\Throwable $th) {
            return response()->json([
                "status"=> "error",
                "message"=> $th->getMessage(),
            ]);
        }
    }
}