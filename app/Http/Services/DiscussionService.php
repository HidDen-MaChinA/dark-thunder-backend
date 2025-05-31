<?php

namespace App\Http\Services;

use App\Models\Discussion;
use App\Models\DiscussionsMembership;
use App\Models\User;
use exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DiscussionService{
    public function __construct(
        private DiscussionMembershipService $discussionMembershipService,
        private Discussion $discussionModel,
        private FileStorageService $fileStorageService
    ) { }
    public function createDiscussion(string $name, string | null $messageRestrictionRegex, UploadedFile|null $image){
        $currentUser = auth()->user();
        $usedId = uuid_create();

        $filePath = null;
        if($image!=null){
            $fileName = uuid_create() . ".jpg";
            $filePath = $this->fileStorageService->saveFileAs($image, $fileName, "discussionsPictures");
        }
        $imageLink = request()->getSchemeAndHttpHost() . "/" . $filePath;
        $toSave = new Discussion([
            'id' => $usedId,
            'name' => $name,
            'creator_id' => $currentUser->id,
            'image' => $imageLink,
            'message_restriction_regex' => $messageRestrictionRegex
        ]);
        $toSave->save();
        $memberShipToSave = new DiscussionsMembership([
            "id" => uuid_create(),
            "user_id" => $currentUser->id,
            "discussion_id" => $usedId,
            "add_date" => now()->toDateTimeString(),
            "permission" => "mod"
        ]);
        $memberShipToSave->save();
        return $toSave;
    }

    public function updateDiscussion(string $name, string $id, string | null $messageRestrictionRegex, UploadedFile|null $image)
    {
        $filePath = null;
        $arr = [];
        $arr["name"] = $name;
        if($image!=null){
            $fileName = uuid_create() . ".jpg";
            $filePath = $this->fileStorageService->saveFileAs($image, $fileName, "discussionsPictures");
            $imageLink = request()->getSchemeAndHttpHost() . "/" . $filePath;
            $arr["image"] = $imageLink;
        }
        if($messageRestrictionRegex!= null) $arr["message_restriction_regex"] = $messageRestrictionRegex;
        return Discussion::query()->where('id', $id)->update($arr);
    }

    public function createDiscussionWithAnotherUser($userId, $discussionName){
        $currentUser = auth()->user();
        $newdiscussionId = uuid_create();
        $toSave = new Discussion([
            'id' => $newdiscussionId,
            'name' => $discussionName,
            'creator_id' => $currentUser->id
        ]);
        $toSave->save();
        $discussionsToSave = [
            [
                "id" => uuid_create(),
                "user_id" => $currentUser->id,
                "discussion_id" => $newdiscussionId,
                "add_date" => now()->toDateTimeString(),
                "permission" => "mod"
            ], [
                "id" => uuid_create(),
                "user_id" => $$userId,
                "discussion_id" => $newdiscussionId,
                "add_date" => now()->toDateTimeString(),
                "permission" => "mod"
            ]
        ];
        DiscussionsMembership::query()->getQuery()->insert($discussionsToSave);
        return $toSave;
    }

    public function findAllDiscussionUserIsIn(){
        $currentUser = auth()->user();
        $result = Discussion::query()->whereHas("members", function ($query) use ($currentUser) {
            $query->where("discussions_memberships.user_id","=", $currentUser->id);
        })->paginate(20);

        return $result;
    }

    public function findDiscussionById($id){
        return Discussion::query()->find($id);
    }

}
