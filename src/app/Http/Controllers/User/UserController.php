<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\QuserService;
use App\Services\QuootService;
use App\Services\FollowsService;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request, 
        string $userName,
        QuserService $quserService,
        QuootService $quootService,
        FollowsService $followsService,
    )
    {
        $quser=$quserService->getUserByUserName($userName)->resource;
        $quoots=$quootService->getUserQuoots($quser->id);
        $isFollowing=false;
        $following=$request->id;
        if($following){
            $follower=$quser->id;
            $isFollowing=$followsService->isFollow($following,$follower);
        }
        $imagePath=null;
        if($quser->profile_image_id){
            $imagePath=$quser->getImagePath();
        }
        return response()->json([
            'id'=>$quser->id,
            'userName'=>$quser->user_name,
            'displayName'=>$quser->display_name,
            'profile'=>$quser->profile,
            'imagePath'=>$imagePath,
            'quoots'=>$quoots,
            'isFollowing'=>$isFollowing,
        ],200);
    }
}
