<?php

namespace App\Http\Controllers\Quoot\Create;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('quoot.create');
    }
}
