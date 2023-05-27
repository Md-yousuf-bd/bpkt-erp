<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    public function employee(){
//        return $this->hasOne(User::class,'id','created_by');

    }
    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function cashCollection(){

    }

}
