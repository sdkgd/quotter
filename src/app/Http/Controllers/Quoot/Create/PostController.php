<?php

namespace App\Http\Controllers\Quoot\Create;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Quoot\CreateRequest;
use App\Services\QuootService;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        CreateRequest $request,
        QuootService $quootService,
    )
    {
        $quootService->createQuoot($request->getUserId(),$request->getQuoot());
        return response()->json([],201);
    }
}
