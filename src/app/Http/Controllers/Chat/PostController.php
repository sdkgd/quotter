<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Message\CreateRequest;
use App\Models\Message;
use App\Models\Chat;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateRequest $request)
    {
        $userId=Auth::id();
        $chatId=$request->getChatId();
        $chat=Chat::where('id',$chatId)->firstOrFail();
        if($chat->user1_id===$userId || $chat->user2_id===$userId){
            $message=new Message;
            $message->chat_id=$chatId;
            $message->mentioned_user_id=$userId;
            $message->content=$request->getMessage();
            $message->save();
            return redirect()->route('chat.index',['chatId'=>$chatId]);
        }else{
            abort(403);
        }
    }
}
