<?php

namespace App\Http\Services;

use App\Http\DTOs\User\CrupdateUser;
use App\Http\Mappers\UserMapper;
use App\Models\Discussion;
use App\Models\Email;
use App\Models\Friendship;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as DatabaseQueryBuilder;
use Illuminate\Support\Facades\Storage;

class UserService{
    public function __construct(
        public UserMapper $userMapper,
        public FileStorageService $fileStorageService
    ) { }
    public function create(CrupdateUser $crupdateUser){
        if(!$this->isEmailVerified($crupdateUser->email)){
            throw new exception("email not verified");
        }
        $filePath = null;
        if($crupdateUser->pfp!=null){
            $fileName = uuid_create() . ".jpg";
            $filePath = $this->fileStorageService->saveFileAs($crupdateUser->pfp, $fileName, "profilePictures");
        }
        $pfpLink = request()->getSchemeAndHttpHost() . "/" . $filePath;
        $attributes= [
            "id"=>uuid_create(),
            "firstname"=>$crupdateUser->firstname,
            "lastname"=>$crupdateUser->lastname,
            "username"=>$crupdateUser->username,
            "email"=>$crupdateUser->email,
            "password"=>$crupdateUser->password,
            "birthdate"=>$crupdateUser->birthdate,
            "pfp"=>$pfpLink,
            "daily_discussions_token"=>uuid_create(),
            "daily_discussions_token_creation_date"=>Carbon::now()->toDateTimeString()
        ];
        $saved = User::query()->create($attributes);
        return $saved;
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
        $toReturn = Friendship::query()
            ->with([
                "senderUser" => fn($v)=>$v,
                "receiverUser" => fn($v)=>$v
            ])
            ->where(function (EloquentBuilder $builder) use ($currentUser){
                $builder
                    ->where("sender_user_id", $currentUser->id)
                    ->where("allowed", 1);
            })
            ->orWhere(function (EloquentBuilder $builder) use ($currentUser){
                $builder
                    ->where("receiver_user_id", $currentUser->id)
                    ->where("allowed", 1);
            })
            ->get();

        return $toReturn->map(function (Friendship $each) use ($currentUser){
            $each->receiverUser["friendship_id"]=$each->id;
            if($currentUser->id === $each->senderUser->id){
                return $each->receiverUser;
            }else{
                return $each->senderUser;
            }
        });
    }

    public function findAllFriendsNotInDiscussion($discussionId){
        $currentUser = auth()->user();
        $toReturn = User::query()
        ->where("id", "!=", $currentUser->id)
        ->where(function (EloquentBuilder $builder) use ($currentUser){
            $builder->whereHas("senderUser", function (EloquentBuilder $builder) use ($currentUser){
                $builder->where("friendships.sender_user_id", $currentUser->id)->where("friendships.allowed", 1);
            })
            ->orWhereHas("receiverUser", function (EloquentBuilder $builder) use ($currentUser){
                $builder->where("friendships.receiver_user_id", $currentUser->id)->where("friendships.allowed", 1);
            });
        })
        ->whereDoesntHave("discussions", function (EloquentBuilder $builder) use ($discussionId){
            $builder->where("discussion_id", $discussionId);
        })
        ->get();
        // ->getQuery()->ddRawSql();
        return $toReturn;
    }

    public function findAllNonFriends(){
        $currentUser = auth()->user();
        $toReturn = User::query()
            // prevent the current user to be on the list
            // ->where("users.id", "!=", $currentUser->id)
            // ->where(function (EloquentBuilder $builder){

            // })
            ->where("id", "!=", $currentUser->id)
            ->whereNot(function (EloquentBuilder $builder) use ($currentUser){
                $builder->whereHas("senderUser", function (EloquentBuilder $builder) use ($currentUser){
                    $builder->where("friendships.sender_user_id", $currentUser->id);
                })
                ->orWhereHas("receiverUser", function (EloquentBuilder $builder) use ($currentUser){
                    $builder->where("friendships.receiver_user_id", $currentUser->id);
                });
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
