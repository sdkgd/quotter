<?php

namespace App\Policies;

use App\Models\Quser;
use App\Models\Quoot;

class QuootPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(Quser $quser, Quoot $quoot):bool{
       return $quoot->user_id===$quser->id;
    }

    public function delete(Quser $quser, Quoot $quoot):bool{
       return $quoot->user_id===$quser->id;
    }
}
