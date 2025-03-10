<?php

namespace App\Http\Services;

use App\Http\DTOs\User\CrupdateUser;
use App\Http\Mappers\UserMapper;
use App\Models\User;

class UserService{
    public function __construct(
        public UserMapper $userMapper
    ) { }
    public function create(CrupdateUser $crupdateUser){
        $toSave = $this->userMapper->DTOCrupdateUserToEntity($crupdateUser);
        return $toSave->save();
    }

    public function update(CrupdateUser $crupdateUser){
        $authentified = auth()->attempt(["email"=>$crupdateUser->email, "password"=>$crupdateUser->password]);
        if($authentified){
            $toSave = $this->userMapper->DTOCrupdateUserToEntity($crupdateUser);
            return $toSave->update();
        }
    }

    public function quit(string $password, string $email){
        $authentified = auth()->attempt(["email" => $email, "password" => $password]);
        if($authentified){
            $currentUser = auth()->user();
            return $currentUser->save(["quit"=>true]);
        }else{
            return false;
        }
    }
}
