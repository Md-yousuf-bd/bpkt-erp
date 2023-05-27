<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckController extends Controller
{
    //
    public static function check_unique($table,$column,$value,$except_column=null,$except_value=null,$return_json=false,$column2=null,$value2=null){
        if(isset($column2)&&isset($value2)){
            if(isset($except_column)){
                $data = DB::select('select count(id) as total from ' . $table . ' where '.$column.' = ? and '.$column2.' = ? and '.$except_column.' != ?', [$value,$value2,$except_value]);
            }else {
                $data = DB::select('select count(id) as total from ' . $table . ' where '.$column.' = ? and '.$column2.' = ?', [$value,$value2]);
            }
        }
        else{
            if(isset($except_column)){
                $data = DB::select('select count(id) as total from ' . $table . ' where '.$column.' = ? and '.$except_column.' != ?', [$value,$except_value]);
            }else {
                $data = DB::select('select count(id) as total from ' . $table . ' where '.$column.' = ?', [$value]);
            }
        }

        if($data[0]->total > 0){
            if($return_json==true){
                echo 'false';
            }
            else{
                return false;
            }
        }
        else{
            if($return_json==true){
                echo 'true';
            }
            else{
                return true;
            }
        }
    }

    public function check_unique_post(Request $request){
        $table=$request->input('table');
        $column=$request->input('column');
        $value=$request->input('value');
        $except_column=$request->input('except_column');
        $except_value=$request->input('except_value');
        $return_json=$request->input('return_json');
        $column2=($request->has('column2'))?$request->input('column2'):null;
        $value2=($request->has('value2'))?$request->input('value2'):null;
        self::check_unique($table,$column,$value,$except_column,$except_value,$return_json,$column2,$value2);
    }
}
