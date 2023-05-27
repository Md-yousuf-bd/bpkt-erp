<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
