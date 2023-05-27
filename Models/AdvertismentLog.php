<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvertismentLog extends Model
{
    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
}
