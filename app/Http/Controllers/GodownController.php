<?php

namespace App\Http\Controllers;

use App\Models\Godown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LogController as Logs;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class GodownController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['page_name']="Store info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Store','active'),
            array('List','active')
        );
        return view('admin.godown.index',$data);
    }
    /**
     * show list data
     */

    public function listData(Request $request){

        $result=Godown::query();
        return DataTables::eloquent($result)
            ->addIndexColumn()
            ->setRowId(function($row){
                return 'row_'.$row->id;
            })
            ->setRowData([
                'name' => function($row) {
                    return $coa->name ?? '';
                },
                'address' => function($row) {
                    return $row->address ?? '';
                },
                'contact_person_name' => function($row) {
                    return $row->contact_person_name ?? '';
                },
                'contact_number' => function($row) {
                    return $row->contact_number ?? '';
                },
                'email' => function($row) {
                    return $row->contact_number ?? '';
                },
                'data-created_by' => function($row) {
                if(isset($row->user)){
                    return $row->user->name;
                }else{
                    return 'None';
                }

                },
                'data-updated_at' => function($row) {
                    return otherHelper::change_date_format($row->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('status',  function($row) {
                $status='';
                if($row->status==1){
                    $status = '<span style="color:green"> Active</span>';
                }else{
                    $status = '<span style="color:red"> In-Active</span>';
                }

                return $status;
            })
            ->addColumn('action',  function($row) {
                if(auth()->user()->can('edit-godown')){
                    return '<div style="width:100%; float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('godown.edit',[$row->id]).'" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                else{
                    return '<div style="width:100%; float: left;"></div>';
                }
            })
            ->rawColumns(['action','status'])
            ->toJson();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Store info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Store','active'),
            array('List','active')
        );
        return view('admin.godown.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }
       $store = new Godown();
       $store->name = $request->input('name');
       $store->address = $request->input('address');
       $store->contact_person_name = $request->input('contact_person_name');
       $store->contact_number = $request->input('contact_number');
       $store->email = $request->input('email');
       $store->status = $request->input('status');
       $store->created_by = Auth::user()->id;
       $store->save();
        Logs::store(Auth::user()->name.'New Store has been created successfull ','Add','success',Auth::user()->id,$store->id,'Store Info');
        return redirect()->route('godown.index')->with('success','Godown has been created successfully.');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $data['page_name']="Store Info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Store','active'),
            array('List','active')
        );
        return view('admin.godown.index',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']= "Edit Store Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Store','active'),
            array('List','active')
        );
        $data['editData']= Godown::find($id);
        return view('admin.godown.edit',$data);
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
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }
        $store =  Godown::find($id);
        $store->name = $request->input('name');
        $store->address = $request->input('address');
        $store->contact_person_name = $request->input('contact_person_name');
        $store->contact_number = $request->input('contact_number');
        $store->email = $request->input('email');
        $store->status = $request->input('status');
        $store->created_by = Auth::user()->id;
        $store->save();
        Logs::store(Auth::user()->name.'New Store has been created successfull ','Add','success',Auth::user()->id,$store->id,'Store Info');
        return redirect()->route('godown.index')->with('success','Godown has been created successfully.');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $store = Godown::find($id);
        Logs::store(Auth::user()->name. ' - '.$store->name.' Store has been Deleted successfull ','Delete','success',Auth::user()->id,$id,'Store Info');
        $store->delete();
        return redirect()->route('godown.index')->with('success','Store has been Deleted successfully.');


    }
}
