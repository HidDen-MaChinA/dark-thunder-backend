<?php

namespace App\Http\Mappers\User;

use App\Http\DTOs\User\GetUser;
use App\Models\User;

class GetUserMapper {
   public function __construct() {

   }
   public function entityToDTO(User $user) : GetUser{
      return new GetUser(
         $user->id,
         $user->firstname,
         $user->lastname,
         $user->username,
         $user->email
      );
   }
}
