<?php

namespace App\Http\Controllers\User\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\QuserService;

class EditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request, 
        string $userName,
        QuserService $quserService,
    )
    {
        $quser=$quserService->getUserByUserName($userName);
        if(Auth::user()->cannot('update',$quser)) abort(403);
        return view('user.edit')->with([
            'userName'=>$quser->user_name,
            'displayName'=>$quser->display_name,
            'profile'=>$quser->profile
        ]);
    }
}
