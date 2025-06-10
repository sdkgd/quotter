<?php

namespace App\Http\Controllers\User\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quser;

class EditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $userName)
    {
        $quser=Quser::where('user_name',$userName)->firstOrFail();
        if(Auth::id()===$quser->id){
            return view('user.edit')->with([
                'userName'=>$quser->user_name,
                'displayName'=>$quser->display_name,
                'profile'=>$quser->profile
            ]);
        }else{
            abort(403);
        }
    }
}
