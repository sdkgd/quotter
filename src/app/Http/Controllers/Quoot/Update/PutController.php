<?php

namespace App\Http\Controllers\Quoot\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Quoot\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\QuootService;

class PutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        UpdateRequest $request,
        QuootService $quootService,
    )
    {
        $quoot=$quootService->getQuootById($request->getId())->resource;
        if(Auth::user()->cannot('update',$quoot)) abort(403);
        $quootService->updateQuoot($request->getId(),$request->getQuoot());
        return response()->noContent();
    }
}
