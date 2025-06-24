<?php

namespace App\Services;
use App\Models\Quser;
use Illuminate\Database\Eloquent\Collection;
use App\Services\FollowsService;
use App\Http\Resources\QuserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuserService{
    private $followsService;

    public function __construct(FollowsService $followsService){
        $this->followsService=$followsService;
    }

    public function getUserById(int $id):QuserResource{
        $quser=Quser::where('id',$id)->firstOrFail();
        return new QuserResource($quser);
    }

    public function getUserByUserName(string $userName):QuserResource{
        $quser=Quser::where('user_name',$userName)->firstOrFail();
        return new QuserResource($quser);
    }

    public function getFollowsProfiles(int $id):AnonymousResourceCollection{
        $followers=$this->followsService->getFollows($id);
        $userIds=array();
        foreach($followers as $follower){
            array_push($userIds,$follower->followed_user_id);
        }

        $users=Quser::with('image')->whereIn('id',$userIds)->get();
        return QuserResource::collection($users);
    }

    public function getFollowersProfiles(int $id):AnonymousResourceCollection{
        $follows=$this->followsService->getFollowers($id);
        $userIds=array();
        foreach($follows as $follow){
            array_push($userIds,$follow->following_user_id);
        }

        $users=Quser::with('image')->whereIn('id',$userIds)->get();
        return QuserResource::collection($users);
    }

    public function setDisplayName(int $id, string $displayName):void{
        $quser=Quser::where('id',$id)->firstOrFail();
        $quser->display_name=$displayName;
        $quser->save();
    }

    public function setProfile(int $id, string|null $profile):void{
        $quser=Quser::where('id',$id)->firstOrFail();
        $quser->profile=$profile;
        $quser->save();
    }

    public function setProfileImageId(int $id, int $profileImageId):void{
        $quser=Quser::where('id',$id)->firstOrFail();
        $quser->profile_image_id=$profileImageId;
        $quser->save();
    }
}