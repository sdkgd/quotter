<?php

namespace App\Http\Controllers\Quoot\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\QuootService;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        QuootService $quootService,
    )
    {
        $quootId=(int)$request->route('quootId');
        $quoot=$quootService->getQuootById($quootId);
        if(Auth::user()->cannot('update',$quoot)) abort(403);
        return view('quoot.update')->with('quoot',$quoot);
    }
}
