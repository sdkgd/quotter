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
        $quser=$quserService->getUserByUserName($userName)->resource;
        $users=$quserService->getFollowersProfiles($quser->id);
        return response()->json([
            'displayName'=>$quser->display_name,
            'users'=>$users,
        ],200);
    }
}
