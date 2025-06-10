<?php

namespace App\Http\Controllers\Quoot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quoot;
use App\Models\Quser;
use \Illuminate\Support\Facades\Auth;
use App\Models\Follows;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $loginId=Auth::id();
        if($loginId){
            $users=Follows::where('following_user_id',$loginId)->get();
            $followUserIds=array(
                0=>(int)$loginId,
            );
            foreach($users as $user){
                $followUserIds[]=$user->followed_user_id;
            }
            $quoots=Quoot::whereIn('user_id',$followUserIds)->orderBy('created_at','DESC')->get();
            $loginUser=Quser::where('id',$loginId)->first();
            return view('quoot.index')->with([
                'userName'=>$loginUser->user_name,
                'quoots'=>$quoots,
            ]);
        }else{
            $quoots=Quoot::orderBy('created_at','DESC')->get();
            return view('quoot.index')->with([
                'quoots'=>$quoots,
            ]);
        }
    }
}
