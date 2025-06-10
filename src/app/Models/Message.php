<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function quser()
    {
       return $this->belongsTo(Quser::class,'mentioned_user_id');
    }

    public function getDisplayName()
    {
       return $this->quser->display_name;
    }
}
