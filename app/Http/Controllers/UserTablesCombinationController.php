<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserTablesCombination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class UserTablesCombinationController extends Controller
{
    //
    public function getCombination(Request $request){
        $url=$request->input('url');
        $base_url=URL::to('/');
        $url=str_replace($base_url,'',$url);
        $table_id=$request->input('table_id');
        $route = app('router')->getRoutes()->match(app('request')->create($url));
        $route_name=$route->action['as'];
        $combination=UserTablesCombination::where('user_id',Auth::user()->id)->where('route_name',$route_name)->where('table_id',$table_id)->first();
        if(isset($combination)){
            $data['combination']=$combination->combination;
        }
        else {
            $data['combination']=null;
        }

        return response()->json($data);
    }

    public function setCombination(Request $request){
        $url=$request->input('url');
        $base_url=URL::to('/');
        $url=str_replace($base_url,'',$url);
        $combination_str=$request->input('combination_str');
        $table_id=$request->input('table_id');
        $route = app('router')->getRoutes()->match(app('request')->create($url));
        $route_name=$route->action['as'];
        $combination=UserTablesCombination::where('user_id',Auth::user()->id)->where('route_name',$route_name)->where('table_id',$table_id)->first();
        if(isset($combination)){
            $combination->combination=$combination_str;
            $combination->save();
        }
        else {
            $combination=new UserTablesCombination();
            $combination->user_id=Auth::user()->id;
            $combination->route_name=$route_name;
            $combination->table_id=$table_id;
            $combination->combination=$combination_str;
            $combination->save();
        }

        return response()->json(1);
    }
}
