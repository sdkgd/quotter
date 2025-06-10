<?php

namespace App\Http\Controllers\Quoot\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Quoot\UpdateRequest;
use App\Models\Quoot;
use Illuminate\Support\Facades\Auth;

class PutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateRequest $request)
    {
        $userId=Auth::id();
        $quoot=Quoot::where('id',$request->getId())->FirstOrFail();
        if($userId===$quoot->user_id){
            $quoot->content=$request->getQuoot();
            $quoot->save();
            return redirect()->route('quoot.index');
        }else{
            abort(403);
        }
    }
}
