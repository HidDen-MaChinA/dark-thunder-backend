<?php

namespace App\Http\Services;

use App\Models\Discussion;
use App\Models\DiscussionsMembership;
use App\Models\Message;
use exception;

class MessageService{
    public function __construct(
    ) { }

    public function createMessage(string $value,string $discussionId){
        $currentUser = auth()->user();
        if(!$this->isUserInDiscussion($currentUser->id, $discussionId)){
            throw new exception("user not in the discussion");
        }
        $toSave = new Message([
            'value' => $value,
            'user_id' => $currentUser->id,
            'discussion_id' => $discussionId,
        ]);
        $toSave->save();
        return 1;
    }

    public function deleteMessage($id){
        $currentUser = auth()->user();
        Message::query()
            ->where("id", $id)
            ->where("user_id", $currentUser->id)
            ->delete();
        return 1;
    }

    public function updateMessage($id, $value){
        $currentUser = auth()->user();
        Message::query()
            ->where("id", $id)
            ->where("user_id", $currentUser->id)
            ->update([
                "value" => $value
            ]);
        return 1;
    }

    public function findMessagesSentToDiscussion($discussionId){
        $currentUser = auth()->user();
        return Message::query()
            ->where("user_id", $currentUser->id)
            ->where("discussion_id", $discussionId)
            ->latest()
            ->paginate(25);
    }

    // find out if the user with $userId is in the discussion referenced by discussionId
    private function isUserInDiscussion($userId, $discussionId){
        $memberShip = DiscussionsMembership::query()
            ->where("discussion_id", $discussionId)
            ->where("user_id", $userId)
            ->first();
        return !($memberShip == null);
    }
}
