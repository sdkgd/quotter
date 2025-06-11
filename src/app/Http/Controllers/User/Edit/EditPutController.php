<?php

namespace App\Http\Controllers\User\Edit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quser;
use App\Http\Requests\User\EditRequest;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class EditPutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EditRequest $request, string $userName)
    {
        $quser=Quser::where('user_name',$userName)->firstOrFail();
        if(Auth::id()===$quser->id){
            $quser->display_name=$request->getInput1();
            $quser->profile=$request->getInput2();
            
            if($request->getInput3()){
                $str=Storage::disk('public')->putFile('',$request->getInput3());

                $image=new Image;
                $image->path=$str;
                $image->save();

                if($quser->profile_image_id){
                    $oldImage=Image::where('id',$quser->profile_image_id)->first();
                    $quser->profile_image_id=$image->id;
                    $quser->save();
                    Storage::disk('public')->delete($oldImage->path);
                    $oldImage->delete();
                }else{
                    $quser->profile_image_id=$image->id;
                    $quser->save();
                }
                
            }else{
                $quser->save();
            }

            return redirect()->route('user.index',['userName'=>$userName]);
        }else{
            abort(403);
        }
    }
}
