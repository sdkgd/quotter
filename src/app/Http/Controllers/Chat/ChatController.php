<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ChatService;
use App\Services\QuserService;
use App\Services\MessageService;

class ChatController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request, 
        int $chatId, 
        ChatService $chatService, 
        QuserService $quserService,
        MessageService $messageService,
    )
    {
        $chat=$chatService->getChatById($chatId);
        if(Auth::user()->cannot('enter',$chat)) abort(403);
        $user1=$quserService->getUserById($chat->user1_id)->resource;
        $user2=$quserService->getUserById($chat->user2_id)->resource;
        $users=array($user1->display_name,$user2->display_name);
        $messages=$messageService->getMessagesByChatId($chatId);
        return response()->json([
            'chatId'=>$chatId,
            'users'=>$users,
            'messages'=>$messages,
        ],200);
    }
}
