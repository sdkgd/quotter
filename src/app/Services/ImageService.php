<?php

namespace App\Services;
use Illuminate\Http\UploadedFile;

interface ImageService{

    public function createImage(UploadedFile $file):int;
    public function deleteImage(int $id):void;
    
}