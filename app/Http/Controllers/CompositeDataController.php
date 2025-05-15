<?php

namespace App\Http\Controllers;

use App\Http\Services\CompositeDataService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CompositeDataController extends Controller
{
    public function __construct(
        private CompositeDataService $compositeDataService
    ) { }

    public function findDiscussionsIdsUserInByDailyDiscussionsToken(Request $request){
        $validatedRequest = $request->validate(["daily_discussions_token"=>"uuid"]);
        return $this->compositeDataService->findDiscussionsIdsUserInByDailyDiscussionsToken($validatedRequest["daiy_discussions_token"]);
    }
}
