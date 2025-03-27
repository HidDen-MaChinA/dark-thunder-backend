<?php

namespace App\Http\Controllers;

use App\Http\Mappers\UserMapper;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class AuthentificationController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct(public UserMapper $userMapper) { }

    public function login(Request $request){
        $userLogin = $request->only([
            "email",
            "password"
        ]);

        $authentified = auth()->attempt($userLogin);
        if($authentified){
            $currentUser = auth()->user();
            if($currentUser->quit){
                return response()->json(["reason"=>"The user login" . $currentUser->username . " you entered have already quit the application"], 403);
            }
            auth()->login($currentUser);
            return response()->json(["token"=> $currentUser->createToken($userLogin['email'] . $currentUser->getAuthIdentifier() . "_token")->plainTextToken]);
        }else{
            return response()->json(["error" => "user not found"], 404);
        }
    }

    public function whoami(Request $request){
        return response()->json($this->userMapper->entityToDTOGetUser($request->user()));
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
