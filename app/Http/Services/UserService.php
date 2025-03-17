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
        public UserMapper $userMapper,
        public User $userModel,
        public Email $emailModel
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
            $toSave = $this->userMapper->DTOCrupdateUserToEntity($crupdateUser);
            return $toSave->update();
        }
        return false;
    }

    public function quit(string $password, string $email){
        $authentified = auth()->attempt(["email" => $email, "password" => $password]);
        if($authentified){
            $currentUser = auth()->user();
            $currentUser->quit = true;
            return $currentUser->update();
        }else{
            return false;
        }
    }

    private function isEmailVerified($email){
        $subject = $this->emailModel->all(["verified_at"])->where("email", $email)->get(0);
        if(isset($subject)){
            $dateDiff = now()->toDateTime()->diff(new DateTime($subject["verified_at"]));
            if($dateDiff->h > 2){
                return false;
            }
        }
        return true;
    }
}
