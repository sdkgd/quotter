<?php

namespace App\Services;
use App\Models\Chat;

class ChatService{

    public function getChatById(int $chatId):Chat{
        $chat=Chat::where('id',$chatId)->firstOrFail();
        return $chat;
    }

    public function createChatRoom(int $user1_id, int $user2_id):int{
        if($user1_id>$user2_id){
            $tmp=$user1_id;
            $user1_id=$user2_id;
            $user2_id=$tmp;
        }

        $room=Chat::where([
            ['user1_id',$user1_id],
            ['user2_id',$user2_id],
        ])->first();

        if(!$room){
            $room=new Chat;
            $room->user1_id=$user1_id;
            $room->user2_id=$user2_id;
            $room->save();
        }
        return $room->id;
    }
}