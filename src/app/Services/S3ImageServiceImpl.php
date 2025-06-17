<?php

namespace App\Services;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class S3ImageServiceImpl implements ImageService{

    public function createImage(UploadedFile $file):int{
        $path=Storage::disk('s3')->putFile('',$file);
        $image=new Image;
        $image->path=Storage::disk('s3')->url($path);
        $image->save();
        return $image->id;
    }

    public function deleteImage(int $id):void{
        $image=Image::where('id',$id)->firstOrFail();
        $div=preg_split("/\//",$image->path);
        Storage::disk('s3')->delete(end($div));
        $image->delete();
    }
}