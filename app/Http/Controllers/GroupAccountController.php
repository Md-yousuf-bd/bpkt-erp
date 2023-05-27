<?php

namespace App\Http\Controllers;

use App\Models\GroupAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;
class GroupAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['page_name']="Group Accounts List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Group Accounts','active'),
            array('List','active')
        );
        return view('admin.group-account.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Group Account";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Group Accounts','active'),
            array('Add','active')
        );

        return view('admin.group-account.create',$data);
    }
    public function listData(Request $request){
        $groups=GroupAccount::query();
        return DataTables::eloquent($groups)
            ->addIndexColumn()
            ->setRowId(function($coa){
                return 'row_'.$coa->id;
            })
            ->setRowData([
                'name' => function($coa) {
                    return $coa->name ?? '';
                },

                'data-updated_at' => function($coa) {
                    return otherHelper::change_date_format($coa->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('status',  function($coa) {
                $status='';
                if($coa->status==1){
                    $status = '<span style="color:green"> Active</span>';
                }else{
                    $status = '<span style="color:red"> In-Active</span>';
                }

                return $status;
            })
            ->addColumn('action',  function($coa) {
                if(auth()->user()->can('edit-group-account')){
                    return '<div style="width:100%; float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('group-account.edit',[$coa->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                else{
                    return '<div style="width:100%; float: left;"></div>';
                }
            })
            ->rawColumns(['action','status'])
            ->toJson();

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name=$request->input('name');
        $status=$request->input('status');
        $res = GroupAccount::create(['name' => $name,'status'=>$status,'created_by'=>Auth::user()->id,'created_at'=>date('Y-m-d H:i:s')]);
        Logs::store(Auth::user()->name.' Group account has been created successfull ','Add','success',Auth::user()->id,$res->id,'Group Accounts');
        return redirect()->route('group-account.index')->with('success','Group account has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Group Account";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Group Accounts','active'),
            array('Edit','active')
        );
        $data['editData']= GroupAccount::where('id',$id)->first();
        return view('admin.group-account.edit',$data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $coa = GroupAccount::find($id);
        $coa->name = $request->input('name');
        $coa->status = $request->input('status');
        $coa->save();
        Logs::store(Auth::user()->name.' Group account has been updated','Edit','success',Auth::user()->id,$id,'Group Accounts');
        return redirect()->route('group-account.index')->with('success','Group account has been updated  successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
