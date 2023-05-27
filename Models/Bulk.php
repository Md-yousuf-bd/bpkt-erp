<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Bulk extends Model
{
    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function owner()
    {
        return $this->hasOne(Owner::class,'id','owner_id');
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
