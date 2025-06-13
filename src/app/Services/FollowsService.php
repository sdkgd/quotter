<?php

namespace App\Services;
use App\Models\Follows;
use Illuminate\Database\Eloquent\Collection;

class FollowsService{
    public function getFollows(int $userId):Collection{
        $users=Follows::where('following_user_id',$userId)->get();
        return $users;
    }

    public function getFollowers(int $userId):Collection{
        $users=Follows::where('followed_user_id',$userId)->get();
        return $users;
    }

    public function isFollow(int $userId1, int $userId2):bool{
        $follows=Follows::where([
            ['following_user_id',$userId1],
            ['followed_user_id',$userId2],
        ])->first();
        if($follows) return true;
        else return false;
    }

    public function createFollow(int $userId1, int $userId2):void{
        if(!$this->isFollow($userId1,$userId2)){
            $follows=new Follows;
            $follows->following_user_id=$userId1;
            $follows->followed_user_id=$userId2;
            $follows->save();
        }
    }

    public function deleteFollow(int $userId1, int $userId2):void{
        $follows=Follows::where([
            ['following_user_id',$userId1],
            ['followed_user_id',$userId2],
        ])->firstOrFail();
        $follows->delete();
    }
}