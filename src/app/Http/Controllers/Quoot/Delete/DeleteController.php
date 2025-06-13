<?php

namespace App\Http\Controllers\Quoot\Delete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\QuootService;

class DeleteController extends Controller
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
        if(Auth::user()->cannot('delete',$quoot)) abort(403);
        $quootService->deleteQuoot($quootId);
        return redirect()->route('quoot.index');
    }
}
