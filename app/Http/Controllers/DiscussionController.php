<?php

namespace App\Http\Controllers;

use App\Http\DTOs\User\GetUser;
use App\Http\Services\DiscussionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use exception;

class DiscussionController extends Controller
{
    public function __construct(
        public DiscussionService $discussionService
    )
    {

    }
    public function createDiscussion(Request $request){
        $validatedDiscussion = $request->validate([
            "name" => "required",
            "message_restriction_regex" => "nullable",
            "image" => "file|nullable"
        ]);
        if(!isset($validatedDiscussion["image"])) $validatedDiscussion["image"] = null;
        $discussion = $this->discussionService->createDiscussion($validatedDiscussion["name"], $validatedDiscussion["message_restriction_regex"], $validatedDiscussion["image"]);
        return response()->json($discussion);
    }

    public function updateDiscussion(Request $request){
        $validatedDiscussion = $request->validate([
            "name" => "nullable",
            "id" => "required",
            "message_restriction_regex" => "nullable",
            "image"=>"nullable"
        ]);

        if(!isset($validatedDiscussion["image"])) $validatedDiscussion["image"] = null;
        if(!isset($validatedDiscussion["message_restriction_regex"])) $validatedDiscussion["message_restriction_regex"] = null;
        return $this->discussionService->updateDiscussion(
            $validatedDiscussion["name"],
            $validatedDiscussion["id"],
            $validatedDiscussion["message_restriction_regex"],
            $validatedDiscussion["image"]
        );
    }

    public function createDiscussionWithAnotherUser(Request $request){
        $validatedDiscussion = $request->validate([
            "id" => "required",
            "discussion_name" => "required"
        ]);

        return response()->json($this->discussionService->createDiscussionWithAnotherUser($validatedDiscussion["id"], $validatedDiscussion["discussion_name"]));
    }

    public function findAllDiscussionCurrentUserIsIn(){
        $toReturn = $this->discussionService->findAllDiscussionUserIsIn();
        return response()->json(["per_page" => $toReturn->perPage(),"items" => $toReturn->items(), "total" => $toReturn->total()]);
    }

    public function findDiscussionById(Request $request){
        $id = $request->query("id");
        if($id === null){
            throw new exception("id required");
        }
        return $this->discussionService->findDiscussionById($id);
    }
}
