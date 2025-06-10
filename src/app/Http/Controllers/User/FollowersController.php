<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quser;
use App\Models\Follows;

class FollowersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $userName)
    {
        $quser=Quser::where('user_name',$userName)->firstOrFail();
        $relations=Follows::where('followed_user_id',$quser->id)->get();
        $users=array();
        foreach($relations as $relation){
            $user=Quser::where('id',$relation->following_user_id)->first();
            array_push($users,$user);
        }
        return view('user.followers')->with([
            'displayName'=>$quser->display_name,
            'users'=>$users,
        ]);
    }
}
