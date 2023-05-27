<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        //
        $data['page_name']="Notifications";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Notifications','active')
        );
        $data['users']=User::all();
        $data['from']=date('Y-m-d');
        $from=$data['from'].' 23:59:59';
        $data['to']=date('Y-m-d', strtotime('-5 day'));
        $to=$data['to'].' 00:00:00';
        $data['user_id']=Auth::user()->id;
        $data['notifications']=Notification::where('created_for',$data['user_id'])->where('updated_at','<=',$from)->where('updated_at','>=',$to)->orderBy('status','ASC')->get();
        Notification::where('created_for',$data['user_id'])->where('updated_at','<=',$from)->where('status','=',0)->where('updated_at','>=',$to)->update(['status'=>1]);
        return view('admin.notification',$data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store($description,$action,$type='info',$user_id=null)
    {
        $notification=new Notification();
        $notification->description = $description;
        $notification->action =$action;
        $notification->type =$type;
        $notification->status ='0';
        $notification->created_for = $user_id;

        $notification->save();
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
        $data['notifications']=Notification::where('created_for',$data['user_id'])->where('updated_at','<=',$from)->where('updated_at','>=',$to)->orderBy('status','ASC')->get();
        if(Auth::user()->id==$data['user_id']){
            Notification::where('created_for',$data['user_id'])->where('updated_at','<=',$from)->where('status','=',0)->where('updated_at','>=',$to)->update(['status'=>1]);
        }
        return view('admin.notification',$data);
    }

    public static function unread()
    {
        $data['notifications']=Notification::where('created_for',Auth::user()->id)->where('status','0')->orderBy('status','DESC')->get();
        return  $data['notifications'];
    }
}
