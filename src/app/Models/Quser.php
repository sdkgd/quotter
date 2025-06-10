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
}
