<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class CashCollection extends Model
{
    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class,'id','customer_id');
    }
}
