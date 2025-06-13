<?php

namespace App\Services;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class MessageService{

    public function getMessagesByChatId(int $chatId):Collection{
        $messages=Message::where('chat_id',$chatId)->get();
        return $messages;
    }

    public function createMessage(int $chatId, int $mentionedUserId, string $content):void{
        $message=new Message;
        $message->chat_id=$chatId;
        $message->mentioned_user_id=$mentionedUserId;
        $message->content=$content;
        $message->save();
    }
}