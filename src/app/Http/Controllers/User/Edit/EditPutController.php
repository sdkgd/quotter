<?php

namespace App\Http\Controllers\User\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\EditRequest;
use App\Services\QuserService;
use App\Services\ImageService;

class EditPutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        EditRequest $request, 
        string $userName,
        QuserService $quserService,
        ImageService $imageService,
    )
    {
        $quser=$quserService->getUserByUserName($userName)->resource;
        if(Auth::user()->cannot('update',$quser)) abort(403);

        $quserService->setDisplayName($quser->id,$request->getInput1());
        $quserService->setProfile($quser->id,$request->getInput2());
        
        if($request->getInput3()){
            $newImageId=$imageService->createImage($request->getInput3());
            if($quser->profile_image_id){
                $oldImageId=$quser->profile_image_id;
                $quserService->setProfileImageId($quser->id,$newImageId);
                $imageService->deleteImage($oldImageId);
            }else{
                $quserService->setProfileImageId($quser->id,$newImageId);
            }
        }

        return response()->noContent();
    }
}
