<?php

namespace App\Http\Controllers;

use App\Http\PigeonHelpers\otherHelper;
use App\User;
use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */


    public function index()
    {
        //
        $data['page_name']="Logs";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Logs','active')
        );
        $data['users']=User::all();
        $data['user_id']=Auth::user()->id;
        return view('admin.log',$data);
    }

    public function get_index(Request $request)
    {
        $to=$request->input('to');
        $from=$request->input('from');
        $user_id=$request->input('user_id');
        $logs=Log::select('logs.*','users.name')->join('users','logs.created_by','=','users.id')
            ->when($from!=''&&$to!='',function ($q) use($from,$to){
                return $q->where('logs.created_at','>=',$from.' 00:00:00')->where('logs.created_at','<=',$to.' 23:59:59');
            })
            ->when($from!=''&&$to=='',function ($q) use($from){
                return $q->where('logs.created_at','>=',$from.' 00:00:00')->where('logs.created_at','<=',$from.' 23:59:59');
            })
            ->when($from==''&&$to!='',function ($q) use($to){
                return $q->where('logs.created_at','>=',$to.' 00:00:00')->where('logs.created_at','<=',$to.' 23:59:59');
            })
            ->when(count($user_id)>0,function ($q) use($user_id){
                return $q->whereIn('logs.created_by',$user_id);
            })
            ->when(count($user_id)==0,function ($q) use($user_id){
                return $q->where('logs.created_by',auth()->user()->id);
            });

        return DataTables::eloquent($logs)
            ->addIndexColumn()
            ->setRowId(function($log){
                return 'row_'.$log->id;
            })
            ->setRowClass(function ($log) {
                return 'table-'.$log->type;
            })
            ->setRowData([
                'data-created_at' => function($log) {
                    return otherHelper::change_date_format($log->created_at,true,'d-M-Y h:i A');
                },
                'data-created_by' => function($log) {
                    return $log->user->name;
                },
            ])
            ->toJson();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($description,$action,$type='info',$user_id=null,$action_id=0,$module='')
    {
        $log=new Log();
        $log->description = $description;
        $log->action =$action;
        $log->type =$type;
        $log->created_by = $user_id;
        $log->action_id = $action_id;
        $log->module = $module;
        $log->save();
        $user=User::find(auth()->user()->id);
//        broadcast(new \App\Events\AllActivityLogEvent($user_id));
//        broadcast(new \App\Events\ActivityLogEvent($user));
    }

    public function show(Request $request)
    {
        //

       // dd($request);
        $data['page_name']="Logs";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Logs','active')
        );
        $data['users']=User::all();
        $data['from']=$request->input('from');
        $from=$data['from'].' 23:59:59';
        $data['to']=$request->input('to');
        $to=$data['to'].' 00:00:00';
        $data['user_id']=$request->input('user_id');
        $data['logs']=Log::where('created_by',$data['user_id'])->where('updated_at','<=',$from)->where('updated_at','>=',$to)->orderBy('id','DESC')->get();
        return view('admin.log',$data);
    }
}
