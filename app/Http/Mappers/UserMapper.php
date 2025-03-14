<?php

namespace App\Http\Mappers;

use App\Http\DTOs\User\CrupdateUser;
use App\Http\DTOs\User\GetUser;
use App\Models\User;
use Faker\Core\Uuid;

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
         $user->email
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
    ];
    if($crupdateUser->id != null){
        $attributes["id"] = $crupdateUser->id;
    }else{
        $attributes["id"] = uuid_create();
    }
    return new User($attributes);
   }
}
