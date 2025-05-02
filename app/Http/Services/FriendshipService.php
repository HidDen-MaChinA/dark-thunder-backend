<?php

namespace App\Http\Services;

use App\Models\Friendship;
use App\Models\User;
use exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FriendshipService {
    public function __construct(
    )
    {

    }

    public function createUserFriendship($userId){
        $currentUser = auth()->user();

        /* when creating a UserFriendship, the sender_user_id is always the id of the user sending it and any
        other creation attempt with the same users won't be allowed before the existing one is deleted */
        $friendShip = Friendship::query()
            ->where("receiver_user_id", $currentUser->id)
            ->Where("sender_user_id", $userId)
            ->orWhere("sender_user_id", $currentUser->id)
            ->Where("receiver_user_id", $userId)->first();
        if($friendShip != null){
            throw new exception("this friendship already exist");
        }
        $toSave = new Friendship([
            "id" => uuid_create(),
            "receiver_user_id" => $userId,
            "sender_user_id" => $currentUser->id,
            "allowed" => false
        ]);

        $toSave->save();
        return $toSave;
    }

    public function allowUserFriendship($friendshipId){
        $currentUser = auth()->user();

        // only the receiver can allow the UserFriendship
        return Friendship::query()
            ->where("receiver_user_id", $currentUser->id)
            ->where("id", $friendshipId)
            ->update([
                "allowed" => true
            ]);
    }

    public function findAllNotAllowedUserFriendshipSent(){
        $currentUser = auth()->user();
        return Friendship::query()
            ->with([
                "senderUser" => fn($v)=>$v,
                "receiverUser" => fn($v)=>$v
            ])
            ->where("sender_user_id", $currentUser->id)
            ->where("allowed", false)
            ->paginate(20);
    }

    public function findAllNotAllowedUserFriendshipReceived(){
        $currentUser = auth()->user();
        return Friendship::query()
            ->with([
                "senderUser" => fn($v)=>$v,
                "receiverUser" => fn($v)=>$v
            ])
            ->where("receiver_user_id", $currentUser->id)
            ->where("allowed", false)
            ->paginate(20);
    }

    public function deleteUserFriendship($friendshipId){
        $currentUser = auth()->user();

        // the two party can delete the UserFriendship either it is allowed or not at any time.
        return Friendship::query()
            ->where("id", $friendshipId)
            ->where(function (EloquentBuilder $builder) use ($currentUser){
                $builder
                    ->where("receiver_user_id", $currentUser->id)
                    ->orWhere("sender_user_id", $currentUser->id);
            })
            ->delete();
    }

}
