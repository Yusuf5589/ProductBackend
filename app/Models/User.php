<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens, HasFactory,HasFactory;
    protected $table = "user";
    protected $fillable = ["username", "gmail", "age", "phonenumber", "password", "created_at", "updated_at"];
}
