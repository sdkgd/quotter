<?php

namespace App\Policies;

use App\Models\Quser;
use App\Models\Chat;

class ChatPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function enter(Quser $quser, Chat $chat):bool{
        if($chat->user1_id===$quser->id || $chat->user2_id===$quser->id){
            return true;
        }else{
            return false;
        }
    }
}
