<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{
    //
    protected $fillable=['parent_id','name','description','priority','status','updated_by'];

    public function parent()
    {
        return $this->hasOne(self::class,'id','parent_id');
    }
    public function groupName()
    {
        return $this->hasOne(self::class,'id','group_id');
    }
    public function category()
    {
        return $this->hasOne(self::class,'id','category_id');
    }
    public function user()
    {
        return $this->hasOne(User::class,'id','updated_by');
    }
    public function child()
    {
        return $this->hasOne(self::class,'id','child_id');
    }
    public function childTwo()
{
    return $this->hasOne(self::class,'id','child_id_2');
}
}
