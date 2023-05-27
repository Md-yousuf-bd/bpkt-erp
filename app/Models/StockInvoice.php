<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class StockInvoice extends Model
{
    public function vendor(){
        return $this->hasOne(Vendor::class,'id','vendor_id');
    }
    public function user()
    {
        return $this->hasOne(Owner::class,'id','created_by');
    }
    public function product()
    {
        return $this->hasOne(Lookup::class,'id','product_id');
    }
    public function brand()
    {
        return $this->hasOne(Lookup::class,'id','brand_id');
    }
    public function sizes()
    {
        return $this->hasOne(Lookup::class,'id','size');
    }
}
