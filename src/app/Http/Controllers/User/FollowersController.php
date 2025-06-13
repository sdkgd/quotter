<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\QuserService;

class FollowersController extends Controller
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
        $users=$quserService->getFollowersProfiles($quser->id);
        return view('user.followers')->with([
            'displayName'=>$quser->display_name,
            'users'=>$users,
        ]);
    }
}
