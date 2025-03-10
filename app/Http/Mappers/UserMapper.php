<?php

namespace App\Http\Mappers;

use App\Http\DTOs\User\CrupdateUser;
use App\Http\DTOs\User\GetUser;
use App\Models\User;

class UserMapper {
   public function __construct() {

   }
   public function entityToDTOGetUserGetUser(User $user) : GetUser{
      return new GetUser(
         $user->id,
         $user->firstname,
         $user->lastname,
         $user->username,
         $user->email
      );
   }
   public function DTOCrupdateUserToEntity(CrupdateUser $crupdateUser){
    $attributes= [
        "firstname"=>$crupdateUser->firstname,
        "lastname"=>$crupdateUser->lastname,
        "username"=>$crupdateUser->username,
        "email"=>$crupdateUser->email,
    ];
    if($crupdateUser->id != null){
        $attributes["id"] = $crupdateUser->id;
    }
    return new User($attributes);
   }
}
