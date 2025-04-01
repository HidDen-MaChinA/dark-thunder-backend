<?php

namespace App\Http\Services;

use App\Http\DTOs\User\CrupdateUser;
use App\Http\Mappers\UserMapper;
use App\Models\Email;
use App\Models\User;
use DateTime;
use exception;

class UserService{
    public function __construct(
        public UserMapper $userMapper
    ) { }
    public function create(CrupdateUser $crupdateUser){
        if(!$this->isEmailVerified($crupdateUser->email)){
            throw new exception("email not verified");
        }
        $toSave = $this->userMapper->DTOCrupdateUserToEntity($crupdateUser);
        $toSave->save();
        return $toSave;
    }

    public function update(CrupdateUser $crupdateUser){
        if(!$this->isEmailVerified($crupdateUser->email)){
            throw new exception("email not verified");
        }
        $authentified = auth()->attempt(["email"=>$crupdateUser->email, "password"=>$crupdateUser->password]);
        if($authentified){
            User::query()->where("id", $crupdateUser->id)->update([
                "firstname" => $crupdateUser->firstname,
                "lastname" => $crupdateUser->lastname,
                "username" => $crupdateUser->username,
                "birthdate" => $crupdateUser->birthdate,
                "pfp" => $crupdateUser->pfp,
            ]);
            return true;
        }
        return false;
    }


    public function quit(string $password, string $email){
        $authentified = auth()->attempt(["email" => $email, "password" => $password]);
        if($authentified){
            $currentUser = auth()->user();
            User::query()->where("id", $currentUser->id)->update([
                "quit" => true
            ]);
            return true;
        }else{
            return false;
        }
    }

    public function findAllFriends(){
        $currentUser = auth()->user();
        return User::query()
            ->with(["senderUser", "receiverUser"])
            ->whereHas("senderUser")->withWhereHas("receiverUser");
   }

    private function isEmailVerified($email){
        $subject = Email::query()->where("email", $email)->first();
        if(isset($subject)){
            $dateDiff = now()->toDateTime()->diff(new DateTime($subject->verified_at));
            if($dateDiff->h > 2){
                return false;
            }
        }
        return true;
    }
}
