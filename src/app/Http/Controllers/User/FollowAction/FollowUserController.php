<?php

namespace App\Http\Controllers\User\FollowAction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FollowsService;
use App\Services\QuserService;

class FollowUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request, 
        string $userName,
        FollowsService $followsService,
        QuserService $quserService,
    )
    {
        $following=Auth::id();
        $follower=$quserService->getUserByUserName($userName)->resource->id;
        $followsService->createFollow($following,$follower);
        return response()->json([],201);
    }
}
