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
            "message_restriction_regex" => "nullable"
        ]);
        $discussion = $this->discussionService->createDiscussion($validatedDiscussion["name"], $validatedDiscussion["message_restriction_regex"]);
        return response()->json($discussion);
    }

    public function updateDiscussion(Request $request){
        $validatedDiscussion = $request->validate([
            "name" => "required",
            "id" => "required",
            "message_restriction_regex" => "nullable"
        ]);
        return $this->discussionService->updateDiscussion(
            $validatedDiscussion["name"],
            $validatedDiscussion["id"],
            $validatedDiscussion["message_restriction_regex"]
        );
    }

    public function createDiscussionWithAnotherUser(Request $request){
        $validatedDiscussion = $request->validate([
            "id" => "required",
            "discussion_name" => "required"
        ]);
        return $this->discussionService->createDiscussionWithAnotherUser($validatedDiscussion["id"], $validatedDiscussion["discussion_name"]);
    }

    public function findAllDiscussionsCreated(Request $request){
        $page = $request->query("page");
        return response()->json($this->discussionService->findAllDiscussionsCreated($page));
    }
}
