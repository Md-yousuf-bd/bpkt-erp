<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateInfoLog extends Model
{
    protected $fillable=['name','rate','type','status','effective_date','effective_date_to','updated_by','created_by','created_at','updated_at'];
}
