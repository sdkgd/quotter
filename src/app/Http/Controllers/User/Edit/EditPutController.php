<?php

namespace App\Http\Controllers\User\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quser;
use App\Http\Requests\User\EditRequest;

class EditPutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EditRequest $request, string $userName)
    {
        $quser=Quser::where('user_name',$userName)->firstOrFail();
        if(Auth::id()===$quser->id){
            $quser->display_name=$request->getInput1();
            $quser->profile=$request->getInput2();
            $quser->save();
            return redirect()->route('user.index',['userName'=>$userName]);
        }else{
            abort(403);
        }
    }
}
