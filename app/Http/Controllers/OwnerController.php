<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Owner;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data['page_name']="Owner Info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('owner','active'),
            array('List','active')
        );

        return view('admin.owner.index',$data);
    }
    public function listData(Request $request){
        $groups=Owner::query();
        return DataTables::eloquent($groups)
            ->addIndexColumn()
            ->setRowId(function($coa){
                return 'row_'.$coa->id;
            })
            ->setRowData([

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
                $option='<div style="width:220px;">';
                if(auth()->user()->can('edit-owner')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('owner.edit',[$coa->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if(auth()->user()->can('read-owner')){
                    $option .='<div style="padding-left:5px;float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="'.route('owner.show',[$coa->id]).'"  ><span class="fa fa-edit">  Details</i></a></div>';
                }
                if (auth()->user()->can('delete-owner')){
                    $option .= '<div style=" float: right">
                                    <form action="'.route('owner.destroy',[$coa->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                else{
                    $option .= '<div style=" "></div>';
                }
                $option .='</div>';
                return $option;
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
        $data['page_name']="Add Owner Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('owner','active'),
            array('Add','active')
        );

        return view('admin.owner.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->input('name')==''){
            return redirect()->back()->with('warning','Please Enter Owner Name');

        }
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }

        $owner = new Owner();
        $owner->name = $request->input('name');
        $owner->type = $request->input('type');
        $owner->address = $request->input('address');
        $owner->phone = $request->input('phone');
        $owner->email = $request->input('email');
        $owner->contact_person_name = $request->input('contact_person_name');
        $owner->contact_person_phone = $request->input('contact_person_phone');
        $owner->status = $request->input('status');
        $owner->created_by = Auth::user()->id;
        $owner->save();
        Logs::store(Auth::user()->name.' New owner has been created successfull ','Add','success',Auth::user()->id,$owner->id,'Owner Info');
        return redirect()->route('owner.index')->with('success','Owner Info has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('owner','active'),
            array('Edit','active')
        );
        $data['editData'] = Owner::find($id);

        return view('admin.owner.edit',$data);
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

        if($request->input('name')==''){
            return redirect()->back()->with('warning','Please Enter Owner Name');

        }
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }
        $owner = Owner::find($id);
        $owner->name = $request->input('name');
        $owner->type = $request->input('type');
        $owner->address = $request->input('address');
        $owner->phone = $request->input('phone');
        $owner->email = $request->input('email');
        $owner->contact_person_name = $request->input('contact_person_name');
        $owner->contact_person_phone = $request->input('contact_person_phone');
        $owner->status = $request->input('status');
        $owner->updated_by = Auth::user()->id;
        $owner->save();
        Logs::store(Auth::user()->name.' Edit owner has been created successfull ','Edit','success',Auth::user()->id,$owner->id,'Owner Info');
        return redirect()->route('owner.index')->with('success','Owner Info has been Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $owner = Owner::find($id);
        $owner->status = 2;
        $owner->save();
        Logs::store(Auth::user()->name.'  owner has been In-active successfull ','delete','success',Auth::user()->id,$owner->id,'Owner Info');
        return redirect()->route('owner.index')->with('success','Owner Info has been In-active successfully.');

    }
}
