<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quser extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'user_name',
        'display_name',
        'email',
        'password',
    ];

    public function quoots()
    {
        return $this->hasMany(Quoot::class);
    }

    public function image()
    {
        return $this->hasOne(Image::class,'id','profile_image_id');
    }

    public function getImagePath()
    {
        if($this->profile_image_id) return $this->image->path;
        else return null;
    }
}
