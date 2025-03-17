<?php

namespace App\Http\Controllers;

use App\Http\DTOs\User\CrupdateUser;
use App\Http\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct(
        public UserService $userService
    ) {}

    public function quit(Request $request){
        $userPayload = $request->validate([
            "regex:\<|>\g",
            "email" => "email|required",
            "password" => "password|required"
        ]);
        return response()->json(["done" => $this->userService->quit($userPayload["password"], $userPayload["email"])]);
    }

    public function create(Request $request)
    {
        $userPayload = $request->validate([
            "regex:\<|>\g",
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required",
            "birthdate" => "date",
            "pfp" => "nullable",
            "email" => "email|required",
            "password" => "required",
        ]);
        $crupdateUser = new CrupdateUser(
            null,
            $userPayload["firstname"],
            $userPayload["lastname"],
            $userPayload["username"],
            $userPayload["birthdate"],
            isset($userPayload["pfp"]) ? $userPayload["pfp"] : null,
            $userPayload["email"],
            $userPayload["password"],
        );
        $this->userService->create($crupdateUser) ?  response()->json(["status" => "user created"]) : response()->json(["status" => "user could not be created"]);
    }
    public function update(Request $request)
    {
        $userPayload = $request->validate([
            "regex:\<|>\g",
            "id" => "required",
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required",
            "birthdate" => "date",
            "pfp" => "nullable",
            "email" => "email|required",
            "password" => "required"
        ]);
        $crupdateUser = new CrupdateUser(
            $userPayload["id"],
            $userPayload["firstname"],
            $userPayload["lastname"],
            $userPayload["username"],
            $userPayload["birthdate"],
            $userPayload["pfp"],
            $userPayload["email"],
            $userPayload["password"],
        );
        $this->userService->update($crupdateUser) ? response()->json(["status" => "user updated"]) : response()->json(["status" => "user could not be updated"]);
    }
}
