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
        $loginId=Auth::id();
        if($loginId){
            $quoots=$quootService->getFollowsQuoots($loginId);
            $loginUserName=$quserService->getUserById($loginId)->user_name;
            return view('quoot.index')->with([
                'userName'=>$loginUserName,
                'quoots'=>$quoots,
            ]);
        }else{
            $quoots=$quootService->getAllQuoots();
            return view('quoot.index')->with([
                'quoots'=>$quoots,
            ]);
        }
    }
}
