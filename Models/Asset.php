<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function owner()
    {
        return $this->hasOne(Owner::class,'id','owner_id');
    }
}
