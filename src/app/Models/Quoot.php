<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quoot extends Model
{
    /** @use HasFactory<\Database\Factories\QuootFactory> */
    use HasFactory;

    public function quser()
    {
        return $this->belongsTo(Quser::class,'user_id');
    }

    public function getDisplayName()
    {
        return $this->quser->display_name;
    }

    public function getUserName()
    {
       return $this->quser->user_name;
    }
}
