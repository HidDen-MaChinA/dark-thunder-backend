<?php

use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\DiscussionsMembershipController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- |
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/auth/whoami', [AuthentificationController::class, 'whoami']);
    Route::post('/auth/logout', [AuthentificationController::class, 'logout']);

    //UserController part
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/user/quit', [UserController::class, 'quit']);
    Route::get('/user/friends', [UserController::class, 'findAllFriends']);
    Route::get('/user/nonFriends', [UserController::class, 'findAllNonFriends']);

    //DiscussionController part
    Route::post('/discussion/create', [DiscussionController::class, 'createDiscussion']);
    Route::post('/discussion/update', [DiscussionController::class, 'updateDiscussion']);
    Route::post('/discussion/create/user', [DiscussionController::class, 'createDiscussionWithAnotherUser']);
    Route::get('/discussions', [DiscussionController::class, 'findAllDiscussionCurrentUserIsIn']);

    //DiscussionsMembershipController part
    Route::post('/discussion/member/create', [DiscussionsMembershipController::class, 'createDiscussionMembership']);
    Route::post('/discussion/member/permission/update', [DiscussionsMembershipController::class, 'updateDiscussionMembershipPermission']);
    Route::get('/discussion/members', [DiscussionsMembershipController::class, 'findAllMembersOfDiscussion']);

    //MessageController part
    Route::post('/discussion/message/create', [MessageController::class, 'createMessage']);
    Route::post('/discussion/message/delete', [MessageController::class, 'deleteMessage']);
    Route::post('/discussion/message/update', [MessageController::class, 'updateMessage']);
    Route::get('/discussion/messages', [MessageController::class, 'findLatestMessages']);

    //FriendShipController part
    Route::post('/user/friendship/create', [FriendshipController::class, 'create']);
    Route::post('/user/friendship/delete', [FriendshipController::class, 'delete']);
    Route::post('/user/friendship/allow', [FriendshipController::class, 'allowUserFriendship']);
    Route::get('/user/friendships/received', [FriendshipController::class, 'findAllNotAllowedUserFriendshipReceived']);
    Route::get('/user/friendships/sent', [FriendshipController::class, 'findAllNotAllowedUserFriendshipSent']);
});


Route::middleware('guest')->middleware('remove-cors')->group(function(){
    Route::post('/guest/email/sendVerificationCode', [EmailController::class, 'sendVerificationCode']);
    Route::post('/guest/email/verify', [EmailController::class, 'verifyEmail']);
    Route::post('/guest/user/create', [UserController::class, 'create']);
    Route::post('/guest/auth/login', [AuthentificationController::class, 'login']);
});
