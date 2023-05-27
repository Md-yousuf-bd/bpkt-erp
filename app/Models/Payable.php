<?php


namespace App\Models;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    protected $table = 'payables';
    public function user()
    {
        return $this->hasOne(User::class,'id','created_by');
    }
}
