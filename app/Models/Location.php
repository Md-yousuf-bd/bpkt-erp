<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $fillable=['parent_id','name','type','created_by','updated_by'];

    public function parent()
    {
        return $this->hasOne(self::class,'id','parent_id');
    }

    public function create_user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }

    public function update_user()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
}
