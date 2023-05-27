<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Customer;
use App\Models\GroupAccount;
use App\Models\Lookup;
use App\Models\Meter;
use App\Models\MeterLog;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;
class MeterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['page_name']="Meter Info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Meter','active'),
            array('List','active')
        );
        $data['owner']= Owner::orderBy('name','ASC')->get();
        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        $data['assets']= Meter::orderBy('asset_no','ASC')->get();
        $data['meter']= Meter::orderBy('meter_no','ASC')->get();
        $data['floor']= Asset::orderBy('asset_no','ASC')->groupBy('floor_name')->get();
        return view('admin.meter-info.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Meter Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Meter','meter.index'),
            array('Add','active')
        );
        $data['asses']= Asset::orderBy('asset_no','ASC')->get();
        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        $data['owner']= Owner::orderBy('name','ASC')->get();
        $floor = Lookup::where('name','Building Floor')->first();
        $data['floor']= Lookup::where('parent_id',$floor->id)->get();

        return view('admin.meter-info.create',$data);
    }
    public function listData(Request $request){

        $in  = $request->all();
        $customer = '';
        $owner = '';
        $date_type = '';
        $asset_no = '';
        $floor_name = '';
        $meter_no = '';

        if (isset($in['data'])) {

            for($i=1;$i< sizeof($in['data']); $i++){
                if($in['data'][$i]['name']=='owner'){
                    if($in['data'][$i]['value']!=''){

                        $owner=$in['data'][$i]['value'];
                    }
                }else if($in['data'][$i]['name']=="date_type"){
                    if($in['data'][$i]['value']!=''){
                        $date_type=$in['data'][$i]['value'];
                    }
                }else if($in['data'][$i]['name']=="shop_name"){
                    if($in['data'][$i]['value']!=''){
                        $customer=$in['data'][$i]['value'];
                    }
                }else if($in['data'][$i]['name']=="shop_no"){
                    if($in['data'][$i]['value']!=''){
                        $asset_no=$in['data'][$i]['value'];
                    }
                }else if($in['data'][$i]['name']=="floor_name"){
                    if($in['data'][$i]['value']!=''){
                        $floor_name=$in['data'][$i]['value'];
                    }
                }else if($in['data'][$i]['name']=="meter_no"){
                    if($in['data'][$i]['value']!=''){
                        $meter_no=$in['data'][$i]['value'];
                    }
                }
            }


        }

        $groups=Meter::when($owner !='', function ($query) use ($owner) {
            return $query->where('owner_id','=', $owner);
        })->when($customer !='', function ($query) use ($customer) {
            return $query->where('customer_id','=', $customer);
        })->when($date_type !='', function ($query) use ($date_type) {
            return $query->where('off_type','=', $date_type);
        })->when($asset_no !='', function ($query) use ($asset_no) {
            return $query->where('asset_no','=', $asset_no);
        })->when($floor_name !='', function ($query) use ($floor_name) {
            return $query->where('floor_name','=', $floor_name);
        })->when($meter_no !='', function ($query) use ($meter_no) {
            return $query->where('meter_no','=', $meter_no);
        });
        return DataTables::eloquent($groups)
            ->addIndexColumn()
            ->setRowId(function($coa){
                return 'row_'.$coa->id;
            })
            ->setRowData([
                'asset_no' => function($coa) {
                    return $coa->asset_no ?? '';
                },
                'meter_no' => function($coa) {
                    return $coa->meter_no ?? '';
                },
                'floor_name' => function($coa) {
                    return $coa->floor_name ?? '';
                }, 'data-customer-name' => function($coa) {
                    return $coa->customer->shop_name ?? '';
                }, 'data-owner' => function($coa) {
                    return $coa->owner->name ?? '';
                }, 'status'=>function($coa){
                    return $coa->status ?? '';
                },
                'off_type'=>function($coa){
                    return $coa->off_type ?? '';
                },
                'date_convert_s'=>function($coa){
                    return otherHelper::ymd2dmy($coa->date_s);
                },
                'date_e'=>function($coa){
                    return otherHelper::ymd2dmy($coa->date_e);
                },
                'data-updated_at' => function($coa) {
                    return otherHelper::change_date_format($coa->updated_at,true,'d-M-Y h:i A');
                },
            ])

            ->addColumn('action',  function($coa) {
                $option='<div style="width:140px;">';
                if(auth()->user()->can('edit-meter')){
                    $option .= '<div style=" float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('meter.edit',[$coa->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if (auth()->user()->can('delete-meter')){
                    $option .= '<div style=" float: right">
                                    <form action="'.route('meter.destroy',[$coa->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                else{
                    return    $option .= '<div style=" "></div>';
                }
                $option .='</div>';
                return $option;
            })
            ->rawColumns(['action'])
            ->orderColumn('id', '-id $1')
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
        $check = Meter::where('meter_no','=',$request->input('meter_no'))->where('status','<>','Un-allotted')->first();
        if($check !=null){
            return back()->with('warning',  'Meter No Already Assigned');

        }
        if($request->input('asset_no') == ''){
            return back()->with('warning',  'Asset No Empty Not  Allow');
        }
        if($request->input('meter_no') == ''){
            return back()->with('warning',  'Meter No Empty Not  Allow');
        }
        $assets = Asset::where('asset_no',$request->input('asset_no'))->first();
        $asset = new Meter();
        $asset->asset_no=$request->input('asset_no');
        $asset->customer_id=$assets->customer_id??0;
        $asset->owner_id=$request->input('owner_id');
        $asset->asset_no=$request->input('asset_no');
        $asset->floor_name=$request->input('floor_name');
        $asset->status=$request->input('status');
        $asset->meter_no=$request->input('meter_no');
        $asset->opening_reading=$request->input('opening_reading');
        $asset->rate=$request->input('rate');
        $asset->off_type=$request->input('off_type');
        $asset->vat=$request->input('vat');
        $asset->vat_applicable=$request->input('vat_applicable');
        $asset->date_s=$request->input('date_s');
        $asset->date_e=$request->input('date_e')!=''?$request->input('date_e'):"0000-00-00";
        $asset->created_by= Auth::user()->id;
        $asset->save();

        Logs::store(Auth::user()->name.' Meter Info has been created successfull ','Add','success',Auth::user()->id,$asset->id,'Meter Info');
        return redirect()->route('meter.index')->with('success','Meter Info has been created successfully.');

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
        $data['page_name']="Edit Meter Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Meter','meter.index'),
            array('Edit','active')
        );
        $data['asses']= Asset::orderBy('asset_no','ASC')->get();
        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        $data['owner']= Owner::orderBy('name','ASC')->get();
        $floor = Lookup::where('name','Building Floor')->first();
        $data['floor']= Lookup::where('parent_id',$floor->id)->get();


        $data['editData']= Meter::where('id',$id)->first();
        return view('admin.meter-info.edit',$data);

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
        $checkData = $request->all();
        $assets = Meter::where('asset_no','=',$request->input('asset_no'))->where('status','Un-allotted')->first();
        $asset = Meter::find($id);
        if($request->input('asset_no') == ''){
            return back()->with('warning',  'Asset No Empty Not  Allow');
        }
        if($assets == null){
            $asset->asset_no=$request->input('asset_no');
        }

        unset($checkData['_token']);
        $log = array();
        $log = $asset;
        unset($log['id'], $log['updated_at']);
        $log['updated_at'] = date('Y-m-d H:i:s');
        $log['ref_id'] = $id;
        $log['updated_by'] = Auth::user()->id;
        MeterLog::insert($log->toArray());
        unset($log['ref_id']);
        $assets = Asset::where('asset_no',$request->input('asset_no'))->first();
        $asset->customer_id=$assets->customer_id??0;
        $asset->owner_id=$request->input('owner_id');
        $asset->asset_no=$request->input('asset_no');
        $asset->floor_name=$request->input('floor_name');
        $asset->status=$request->input('status');
        $asset->meter_no=$request->input('meter_no');
        $asset->opening_reading=$request->input('opening_reading');
        $asset->rate=$request->input('rate');
        $asset->date_s=$request->input('date_s');
        $asset->date_e=$request->input('date_e')!=''?$request->input('date_e'):"0000-00-00";
        $asset->off_type=$request->input('off_type');
        $asset->vat=$request->input('vat');
        $asset->vat_applicable=$request->input('vat_applicable');
        $asset->created_by= Auth::user()->id;
        $asset->save();
        Logs::store(Auth::user()->name.' Meter Info has been updated','Edit','success',Auth::user()->id,$id,'Meter Info');
        return redirect()->route('meter.index')->with('success','Meter Info has been updated  successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $owner = Meter::find($id);
        $owner->delete();
        Logs::store(Auth::user()->name.' Meter Info has been deleted','Delete','success',Auth::user()->id,$id,'Meter Info');
        return redirect()->route('meter.index')->with('success','Meter Info has been deleted  successfully!');
    }
}
