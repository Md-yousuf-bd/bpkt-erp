<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function ledger(){
        return $this->hasOne(ChartOfAccount::class,'id','ledger_id');
    }
}
