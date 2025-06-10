<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\Quser;

class MakeChatRoomController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $userName)
    {
        $user1=Auth::id();
        $quser=Quser::where('user_name',$userName)->firstOrFail();
        $user2=$quser->id;

        if($user1>$user2){
            $tmp=$user1;
            $user1=$user2;
            $user2=$tmp;
        }

        $room=Chat::where([
            ['user1_id',$user1],
            ['user2_id',$user2],
        ])->first();

        if($room){
            return redirect()->route('chat.index',['chatId'=>$room->id]);
        }else{
            $room=new Chat;
            $room->user1_id=$user1;
            $room->user2_id=$user2;
            $room->save();
            return redirect()->route('chat.index',['chatId'=>$room->id]);
        }
    }
}
