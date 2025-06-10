<?php

namespace App\Http\Controllers\Quoot\Create;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quoot;
use App\Http\Requests\Quoot\CreateRequest;

class PostController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateRequest $request)
    {
        $quoot=new Quoot;
        $quoot->content=$request->getQuoot();
        $quoot->user_id=$request->getUserId();
        $quoot->save();
        return redirect()->route('quoot.index');
    }
}
