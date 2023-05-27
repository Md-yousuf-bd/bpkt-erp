<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisment extends Model
{
    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
}
