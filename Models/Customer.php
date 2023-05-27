<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
//    protected $fillable=['id','name','region','created_by','updated_by'];
    public function regionName()
    {
        return $this->hasOne(Lookup::class,'id','region');
    }
    public function owner()
    {
        return $this->hasOne(Owner::class,'id','contact_owner_name');
    }

}
