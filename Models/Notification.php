<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //

    protected $fillable=['description','action','type','status','send_other_devices','created_for'];

    public function user()
    {
        return $this->hasOne(User::class,'id','created_for');
    }
}
