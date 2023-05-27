<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class ChartOfAccount extends Model
{
    public static function getCoaCode($type){
        $query = ChartOfAccount::where('type',$type)
                ->orderByRaw('CAST( SUBSTR(system_code,2,LENGTH(`system_code`)) AS UNSIGNED ) desc')
                ->select(DB::raw("SUBSTR( system_code, 2,LENGTH(`system_code`)) AS last_no"))
                ->limit(1)
                ->first();

        $code='';
        $no = (int)($query['last_no']);
        if($type=='Asset'){
            $code = 'A'.($no+1);
        }elseif ($type=='Liability'){
            $code = 'L'.($no+1);
        }elseif ($type=='Expense'){
            $code = 'E'.($no+1);;
        }elseif ($type=='Income'){
            $code = 'I'.($no+1);;
        }
        return $code;
    }
    public static function getCoaType($type){
        $res = ChartOfAccount::where('head','=',$type)->first();
        return $res;
    }
    public static function getLedger($id){
        $res = ChartOfAccount::where('id','=',$id)->first();
        return $res;
    }
}
