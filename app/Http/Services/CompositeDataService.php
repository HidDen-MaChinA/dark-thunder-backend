<?php

namespace App\Http\Services;

use App\Models\DiscussionsMembership;
use App\Models\User;
use exception;

class CompositeDataService{
    public function findDiscussionsIdsUserInByDailyDiscussionsToken(string $dailyDiscussionsToken){
        $user = User::query()->where("daily_discussions_token", $dailyDiscussionsToken)->first();
        if($user == null){
            throw new exception("user channel not recognized");
        }
        $discussionsIds = DiscussionsMembership::query()->where("user_id", $user->id)->get(["discussion_id"]);
        return $discussionsIds;
    }
}