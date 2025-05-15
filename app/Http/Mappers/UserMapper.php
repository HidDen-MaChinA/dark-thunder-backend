<?php

namespace App\Http\Mappers;

use App\Http\DTOs\User\CrupdateUser;
use App\Http\DTOs\User\GetUser;
use App\Http\DTOs\User\SimplifiedUser;
use App\Models\User;
use Faker\Core\Uuid;
use Illuminate\Support\Carbon;

class UserMapper {
   public function __construct() {

   }
   public function entityToDTOGetUser(User $user) : GetUser{
      return new GetUser(
         $user->id,
         $user->firstname,
         $user->lastname,
         $user->username,
         $user->birthdate,
         $user->pfp,
         $user->email,
         $user->daily_discussions_token
      );
   }

   public function entityToDTOSimplifiedUser(User $user) : SimplifiedUser{
      return new SimplifiedUser(
         $user->id,
         $user->username,
         $user->pfp
      );
   }

   public function DTOCrupdateUserToEntity(CrupdateUser $crupdateUser){
    $attributes= [
        "firstname"=>$crupdateUser->firstname,
        "lastname"=>$crupdateUser->lastname,
        "username"=>$crupdateUser->username,
        "email"=>$crupdateUser->email,
        "password"=>$crupdateUser->password,
        "birthdate"=>$crupdateUser->birthdate,
        "pfp"=>$crupdateUser->pfp,
        "daily_discussions_token"=>uuid_create(),
        "daily_discussions_token_creation_date"=>Carbon::now()->toDateTimeString()
    ];
    if($crupdateUser->id != null){
        $attributes["id"] = $crupdateUser->id;
    }else{
        $attributes["id"] = uuid_create();
    }
    return new User($attributes);
   }
}
