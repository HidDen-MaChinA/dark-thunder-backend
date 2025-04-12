<?php

namespace App\Http\Services;

use App\Http\DTOs\User\CrupdateUser;
use App\Http\Mappers\UserMapper;
use App\Models\Email;
use App\Models\Friendship;
use App\Models\User;
use DateTime;
use exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder;

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
        $toReturn = User::query()->where("id", $currentUser->id)->with(
            [
                "receiverUser" => fn($query)=>$query->where("allowed", 1),
                "senderUser" => fn($query)=>$query->where("allowed", 1),
            ]
        )->where("id", $currentUser->id)->first();
        return $toReturn->senderUser->merge($toReturn->receiverUser);
    }

    public function findAllNonFriends(){
        $currentUser = auth()->user();
        $toReturn = User::query()
            // prevent the current user to be on the list
            ->where("id", "!=", $currentUser->id)
            // if you have no friend you are in the list
            ->whereDoesntHave("receiverUser")
            ->whereDoesntHave("senderUser")
            // any user that have friends but are not the current user's friend yet
            ->orWhereHas("receiverUser", function ($query) use ($currentUser) {
                $query->where("receiver_user_id", "!=", $currentUser->id);
            })
            ->WhereHas("senderUser", function ($query) use ($currentUser) {
                $query->where("sender_user_id", "!=", $currentUser->id);
            })
            ->get();
        return $toReturn;
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
