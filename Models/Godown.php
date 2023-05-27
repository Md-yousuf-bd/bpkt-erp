<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Godown extends Model
{
    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
