<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Message\CreateRequest;
use App\Services\ChatService;
use App\Services\MessageService;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        CreateRequest $request, 
        int $chatId,
        ChatService $chatService, 
        MessageService $messageService,
    )
    {
        $chatId=$request->getChatId();
        $chat=$chatService->getChatById($chatId);
        if(Auth::user()->cannot('enter',$chat)) abort(403);
        $messageService->createMessage($chatId,Auth::id(),$request->getMessage());
        return response()->json([],201);
    }
}
