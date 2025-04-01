<?php

namespace App\Http\Services;

use App\Models\Discussion;
use App\Models\DiscussionsMembership;
use App\Models\Friendship;
use exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use function Termwind\terminal;

class DiscussionMembershipService{
    public function __construct() { }
    public function createDiscussionMembership($discussionId, $userId, $permission){
        $currentUser = auth()->user();
        if(!$this->isMod($discussionId, $currentUser->id)){
            throw new exception("only mod can perfom this action");
        }
        if(!$this->isFriend($currentUser->id, $userId)){
            throw new exception("only possible if the two users where friends");
        }
        $usedId = uuid_create();
        $toSave = new DiscussionsMembership([
            "id" => $usedId,
            "user_id" => $userId,
            "discussion_id" => $discussionId,
            "add_date" => now()->toDateTimeString(),
            "permission" => $permission
        ]);
        $toSave->saveOrFail();
        return $toSave;
    }

    public function updateDiscussionMembershipPermission($id, $discussionId, $userId, $permission){
        $currentUser = auth()->user();
        if(!$this->isMod($discussionId, $currentUser->id)){
            throw new exception("only mod can perfom this action");
        }
        return DiscussionsMembership::query()
            ->where("id", $id)
            ->where("discussion_id", $discussionId)
            ->where("user_id", $userId)
            ->update([
                "permission" => $permission
            ]);
    }


    public function findAllDiscussionMembers($discussionId){
        $result = DiscussionsMembership::query()->where("discussion_id", $discussionId)->paginate(20);
        return ["items" => collect($result->items())->map(function (DiscussionsMembership $value){
            return $value->user;
        }), "total" => $result->total()];
    }

    /*  used to know if the currently authenticated user who try to do
     something in a discussion is actually a moderator. */
    private function isMod($discussionId, $userId){
        $userDiscussionMembership = DiscussionsMembership::query()
            ->where("discussion_id", $discussionId)
            ->where("user_id", $userId)
            ->get();
        return $userDiscussionMembership == 'mod';
    }

    // used to know if the two users corresponding with the ids are friends
    private function isFriend($userAId, $userBId){
        $friendship = Friendship::query()
            ->where("receiver_user_id", $userAId)
            ->where("sender_user_id", $userAId)
            ->orWhere("receiver_user_id", $userBId)
            ->orWhere("sender_user_id", $userBId)->first();
        return $friendship == null ? false : $friendship->allowed;
    }
}
