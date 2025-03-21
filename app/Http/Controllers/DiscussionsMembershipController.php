<?php

use App\Http\Services\DiscussionMembershipService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DiscussionsMembershipController extends Controller
{
    public function __construct(
        private DiscussionMembershipService $discussionMembershipService
    )
    {

    }
    public function findAllMembersOfDisscussion(){

    }

    public function findAllDiscussionUserIsIn(){

    }

    public function createDiscussionMembership(Request $request){
        $validatedRequest = $request->validate([
            "discussion_id" => "required",
            "user_id" => "required",
            "permission" => "regex:/read|write/u"
        ]);
        return $this->discussionMembershipService->createDiscussionMembership(
            $validatedRequest["discussion_id"],
            $validatedRequest["user_id"],
            $validatedRequest["permission"],
        );
    }

}
