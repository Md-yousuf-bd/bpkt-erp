<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Tax extends Model
{
    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
