<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quser;
use App\Models\Follows;

class FollowsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $userName)
    {
        $quser=Quser::where('user_name',$userName)->firstOrFail();
        $follows=Follows::where('following_user_id',$quser->id)->get();
        $followusers=array();
        foreach($follows as $follow){
            $fuser=Quser::where('id',$follow->followed_user_id)->first();
            array_push($followusers,$fuser);
        }
        return view('user.follows')->with([
            'displayName'=>$quser->display_name,
            'followusers'=>$followusers,
        ]);
    }
}
