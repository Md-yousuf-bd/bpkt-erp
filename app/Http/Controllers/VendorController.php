<?php

namespace App\Http\Controllers;

use App\Models\MeasurementUnit;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\Lookup;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Vendor info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('vendor','active'),
            array('List','active')
        );

        return view('admin.vendor.index',$data);
    }

    public function listData(Request $request){
        $vendors=Vendor::query();
        return DataTables::eloquent($vendors)
            ->addIndexColumn()
            ->setRowId(function($row){
                return 'row_'.$row->id;
            })
            ->setRowData([
                'vendor_name' => function($row) {
                    return $row->vendor_name ?? '';
                },

                'owner_name' => function($row) {
                    return $row->owner_name ?? '';
                },
                'owner_contact' => function($row) {
                    return $row->owner_contact ?? '';
                },
                'email' => function($row) {
                    return $row->email ?? '';
                },
                'owner_nid' => function($row) {
                    return $row->owner_nid ?? '';
                },
                'contact_person_name' => function($row) {
                    return $row->contact_person_name ?? '';
                },
                'contact_person_phone' => function($row) {
                    return $row->contact_person_phone ?? '';
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
                $option='<div style="width:220px;">';
                if(auth()->user()->can('edit-vendor')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('vendor.edit',[$row->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if(auth()->user()->can('read-vendor')){
                    $option .='<div style="padding-left:5px;float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="'.route('vendor.show',[$row->id]).'"  ><span class="fa fa-edit">  Details</i></a></div>';
                }
                if (auth()->user()->can('delete-vendor')){
                    $option .= '<div style=" float: right">
                                    <form action="'.route('vendor.destroy',[$row->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
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
        $data['page_name']="Add Vendor info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('vendor','active'),
            array('Add','active')
        );
        $division_id = Lookup::where('name','Division')->first();
        $data['measurement'] = MeasurementUnit::all();
        $data['division']=Lookup::where('parent_id','=',$division_id['id'])->orderBy('id','desc')->get();

        return view('admin.vendor.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkData = $request->all();
        unset($checkData['_token']);

        if($checkData['vendor_name']==''){
            return back()->with('warning',  'Please Enter vendor name');
        }
        if($checkData['owner_name']==''){
            return back()->with('warning',  'Please Enter owner name');
        }
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }
        $vendor = new Vendor();
        foreach ($checkData as $key => $value) {
            $vendor->$key = $value;
        }
        $vendor->created_by= Auth::user()->id;
        $vendor->save();
        Logs::store(Auth::user()->name.'New Vendor has been created successfull ','Add','success',Auth::user()->id,$vendor->id,'Vendor Info');
        return redirect()->route('vendor.index')->with('success','Vendor has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['page_name']="Vendor info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('vendor','vendor.index'),
            array('List','active')
        );
        $data['details'] = Vendor::find($id);

        return view('admin.vendor.details',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Vendor Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('vendor','active'),
            array('Edit','active')
        );
        $division_id = Lookup::where('name','Division')->first();
        $data['division']= Lookup::where('parent_id','=',$division_id['id'])->orderBy('id','desc')->get();
        $data['editData']= Vendor::find($id);
        return view('admin.vendor.edit',$data);
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
        $checkData =  $request->all();

        if($checkData['vendor_name']==''){
            return back()->with('warning',  'Please Enter vendor name');
        }
        if($checkData['owner_name']==''){
            return back()->with('warning',  'Please Enter owner name');
        }
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }
        unset($checkData['_token']);
        $vendor  = Vendor::find($id);

        foreach ($checkData as $key => $value) {
            $vendor->$key = $value;
        }
        $vendor->updated_by = Auth::user()->id;
        $vendor->save();

        Logs::store(Auth::user()->name.' Vendor has been Updated successfull ','Update','success',Auth::user()->id,$vendor->id,'Vendor Info');
        return redirect()->route('vendor.index')->with('success','Vendor has been Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = Vendor::find($id);
        Logs::store(Auth::user()->name. ' - '.$vendor->vendor_name.' Vendor has been Deleted successfull ','Delete','success',Auth::user()->id,$id,'Vendor Info');
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success','Vendor has been Deleted successfully.');


    }
}
