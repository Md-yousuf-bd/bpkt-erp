<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{

    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function cashCollection(){

    }
    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }

}
