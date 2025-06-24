<?php

namespace App\Http\Controllers\Quoot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;
use App\Services\QuootService;
use App\Services\QuserService;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        QuootService $quootService,
        QuserService $quserService,
    )
    {
        if($request->id){
            $loginId=$request->id;
            $quoots=$quootService->getFollowsQuoots($loginId);
            $loginUserName=$quserService->getUserById($loginId)->resource->user_name;
            return response()->json([
                'userName'=>$loginUserName,
                'quoots'=>$quoots,
            ],200);
        }else{
            $quoots=$quootService->getAllQuoots();
            return response()->json([
                'quoots'=>$quoots,
            ],200);
        }
    }
}
