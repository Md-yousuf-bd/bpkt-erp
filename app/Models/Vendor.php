<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    public function division()
    {
        return $this->hasOne(Lookup::class,'id','region_id');
    }
}
