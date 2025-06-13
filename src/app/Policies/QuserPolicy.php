<?php

namespace App\Policies;

use App\Models\Quser;

class QuserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(Quser $quser, Quser $quser2):bool{
        return $quser->id===$quser2->id;
    }
}
