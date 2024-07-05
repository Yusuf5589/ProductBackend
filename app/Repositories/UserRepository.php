<?php

namespace App\Repositories;


use App\Jobs\SendMailJob;
use App\Mail\ForgotMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;
use PhpParser\Node\Expr\Isset_;

class UserRepository{


    public function valError(){
        return[
           "username.required" => "Kullanıcı adını boş bırakamazsınız.",
           "username.min" => "Kullanıcı adı en az 8 karakterli olmalı.",
           "username.max" => "Kullanıcı adı en fazla 32 karakterli olmalı.",
           "username.unique" => "Bu kullanıcı adı alınmış.",
           "gmail.required" => "Gmail alanını boş bırakamazsınız.",
           "gmail.email" => "Gmail alanı geçerli bir e-posta adresi olmalıdır.",
            "gmail.exists" => "Girilen gmail geçersiz.",
            "gmail.unique" => "Bu mail alınmış.",
           "age.required" => "Yaş kısmını boş bırakamazsınız.",
           "age.integer" => "Yaş kısmına sayısal değer girmeniz gerek.",
           "phonenumber.required" => "Numara kısmını boş bırakamazsınız.",
           "phonenumber.integer" => "Numara kısmına sayısal değer girmeniz gerek.",
            "phonenumber.min" => "Numara kısmı 10 karakterli olmalı.",
            "phonenumber.max" => "Numara kısmı 10 karakterli olmalı.",
           "password.required" => "Şifre alanını boş bırakamazsınız.",
           "password.min" => "Şifre en az 8 karakterli olmalı.",
           "password.max" => "Şifre en fazla 32 karakterli olmalı.",
        ];
    }

    public function registerUserRep(array $data){
            return User::create([
                "username" => $data["username"],
                "gmail" => $data["gmail"],
                "age" => $data["age"],
                "phonenumber" => $data["phonenumber"],
                "password" => Hash::make($data["password"]),
            ]);
    }

    public function userLoginRep(array $data){
            $user = User::where("gmail", $data["gmail"])->first();
            
            if(!Hash::check($data["password"],$user->password)){

                return response()->json([
                    "message" => "Şifrenizi yanlış girdiniz."
                ]);

            }    
                
            else if(Hash::check($data["password"],$user->password)){
                $token = $user->createToken("auth_token")->plainTextToken;

                $tokenId = PersonalAccessToken::where('token', hash('sha256', explode('|', $token)[1]))->first()->id;

                return response()->json([
                    "status"=> "success",
                    "message" => $user->username." başarıyla giriş yaptınız",
                    "token" => $token,
                    "tokenId" => $tokenId
                ]); 
            }

    }

    public function userLogoutRep($id){
        if(DB::table('personal_access_tokens')->where('id', $id)->exists()){
            DB::table('personal_access_tokens')->where("id", $id)->delete();

            return response()->json([
                "status"=> "success",
                "message"=> "Başarıyla token silindi."
            ]);
        }else{
            return response()->json([
                "status"=> "Error",
                "message"=> "Böyle bi token bulunmuyor."
            ]);
        }

    }


    public function forgotPasswordRep(array $data){

        $user = User::where("gmail", $data["gmail"])->first();

        $token= $user->createToken("auth_token")->plainTextToken;

        $tokenId = trim(PersonalAccessToken::where('token', hash('sha256', explode('|', $token)[1]))->first()->id);


        Cache::put("token{$token}", $token , 60 * 3);

        DB::table('personal_access_tokens')->where("id", $tokenId)->delete();

        $tokenId = Cache::get("token{$tokenId}");
        

        // session(["forgottoken" => $token]);
        // session(["gmail" => $user->gmail]);


        SendMailJob::dispatch($user->gmail, $token);
        

        return response()->json([
            "status"=> "success",
            "message"=> "Başarıyla mail yollandı.",
            "token" => $token,
        ]);

    }


    public function forgot($token, $gmail, $data){
       User::where("gmail", $gmail)->update([
        "password" => Hash::make($data["password"]),
       ]);
       return response()->json([
        "status"=> "success",
        "message"=> "Başarıyla şifre değişti"
       ]);
    }

}