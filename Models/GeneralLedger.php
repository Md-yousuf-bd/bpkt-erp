<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GeneralLedger extends Model
{
    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
