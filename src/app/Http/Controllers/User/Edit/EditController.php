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
        $quser=$quserService->getUserByUserName($userName)->resource;
        if(Auth::user()->cannot('update',$quser)) abort(403);
        return response()->json([
            'id'=>$quser->id,
            'user_name'=>$quser->user_name,
            'display_name'=>$quser->display_name,
            'profile'=>$quser->profile,
            'profile_image_id'=>$quser->profile_image_id,
        ],200);
    }
}
