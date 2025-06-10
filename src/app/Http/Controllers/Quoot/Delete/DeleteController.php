<?php

namespace App\Http\Controllers\Quoot\Delete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quoot;
use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $userId=Auth::id();
        $quootId=(int)$request->route('quootId');
        $quoot=Quoot::where('id',$quootId)->firstOrFail();
        if($userId===$quoot->user_id){
            $quoot->delete();
            return redirect()->route('quoot.index');
        }else{
            abort(403);
        }
    }
}
