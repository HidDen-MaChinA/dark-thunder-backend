<?php

namespace App\Http\Controllers;

use App\Http\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MessageController extends Controller
{
    public function __construct(
        private MessageService $messageService
    ) { }

    public function createMessage(Request $request){
        $validatedRequest = $request->validate([
            "value" => "max:500|required",
            "discussion_id" => "required|uuid"
        ]);

        return $this->messageService->createMessage(
            $validatedRequest["value"],
            $validatedRequest["discussion_id"]
        );
    }

    public function deleteMessage(Request $request){
        $validatedRequest = $request->validate([
            "id" => "required",
        ]);
        return $this->messageService->deleteMessage($validatedRequest["id"]);
    }

    public function updateMessage(Request $request){
        $validatedRequest = $request->validate([
            "id" => "required",
            "value" => "max:500|required",
        ]);

        return $this->messageService->updateMessage(
            $validatedRequest["id"],
            $validatedRequest["value"]
        );
    }

    public function findLatestMessages(Request $request){
        $discussionId = $request->query("discussion_id");
        $toReturn = $this->messageService->findMessagesSentToDiscussion($discussionId);
        return response()->json($toReturn->items());
    }
}
