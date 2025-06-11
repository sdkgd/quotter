<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quser;
use App\Models\Quoot;
use Illuminate\Support\Facades\Auth;
use App\Models\Follows;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $userName)
    {
        $quser=Quser::where('user_name',$userName)->firstOrFail();
        $quoots=Quoot::where('user_id',$quser->id)->orderBy('created_at','DESC')->get();
        $following=Auth::id();
        $follower=$quser->id;
        $follows=Follows::where([
            ['following_user_id',$following],
            ['followed_user_id',$follower],
        ])->first();
        $isFollowing=false;
        if($follows) $isFollowing=true;
        $imagePath=null;
        if($quser->profile_image_id){
            $imagePath=$quser->getImagePath();
        }
        return view('user.index')->with([
            'id'=>$quser->id,
            'userName'=>$quser->user_name,
            'displayName'=>$quser->display_name,
            'profile'=>$quser->profile,
            'imagePath'=>$imagePath,
            'quoots'=>$quoots,
            'isFollowing'=>$isFollowing,
        ]);
    }
}
