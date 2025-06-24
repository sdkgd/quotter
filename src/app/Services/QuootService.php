<?php

namespace App\Services;
use App\Models\Quoot;
use App\Services\FollowsService;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\QuootResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QuootService{
    private $followsService;

    public function __construct(FollowsService $followsService){
        $this->followsService=$followsService;
    }

    public function createQuoot(int $userId, string $content):void{
        $quoot=new Quoot;
        $quoot->user_id=$userId;
        $quoot->content=$content;
        $quoot->save();
    }

    public function getQuootById(int $id):QuootResource{
        $quoot=Quoot::where('id',$id)->FirstOrFail();
        return new QuootResource($quoot);
    }

    public function updateQuoot(int $id, string $content):void{
        $quoot=Quoot::where('id',$id)->first();
        $quoot->content=$content;
        $quoot->save();
    }

    public function deleteQuoot(int $id):void{
        $quoot=Quoot::where('id',$id)->first();
        $quoot->delete();
    }

    public function getAllQuoots():AnonymousResourceCollection{
        $quoots=Quoot::with('quser.image')->orderBy('created_at','DESC')->get();
        return QuootResource::collection($quoots);
    }

    public function getFollowsQuoots(int $userId):AnonymousResourceCollection{
        $users=$this->followsService->getFollows($userId);
        $followUserIds=array(
            0=>(int)$userId,
        );
        foreach($users as $user){
            $followUserIds[]=$user->followed_user_id;
        }
        $quoots=Quoot::with('quser.image')->whereIn('user_id',$followUserIds)->orderBy('created_at','DESC')->get();
        return QuootResource::collection($quoots);
    }

    public function getUserQuoots(int $userId):AnonymousResourceCollection{
        $quoots=Quoot::with('quser.image')->where('user_id',$userId)->orderBy('created_at','DESC')->get();
        return QuootResource::collection($quoots);
    }
}