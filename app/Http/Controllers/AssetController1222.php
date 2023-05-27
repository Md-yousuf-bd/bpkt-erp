<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\Customer;
use App\Models\GroupAccount;
use App\Models\Lookup;
use App\Models\Owner;
use App\Models\AssetLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;
class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['page_name']="Asset Info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Asset Info','active'),
            array('List','active')
        );
        $data['owner']= Owner::orderBy('name','ASC')->get();
        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        $data['assets']= Asset::orderBy('asset_no','ASC')->get();
        $data['floor']= Asset::orderBy('asset_no','ASC')->groupBy('floor_name')->get();
        return view('admin.asset-info.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Asset Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Asset Info','assets.index'),
            array('Add','active')
        );
        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        $data['owner']= Owner::orderBy('name','ASC')->get();
        $floor = Lookup::where('name','Building Floor')->first();
        $data['floor']= Lookup::where('parent_id',$floor->id)->get();
        $data['assets']= Asset::orderBy('asset_no','ASC')->get();

        return view('admin.asset-info.create',$data);
    }
    public function listData(Request $request){
        $in  = $request->all();
        $customer = '';
        $owner = '';
        $date_type = '';
        $asset_no = '';
        $floor_name = '';

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
                }
            }


        }
        $groups=Asset:: when($owner !='', function ($query) use ($owner) {
            return $query->where('owner_id','=', $owner);
        })->when($customer !='', function ($query) use ($customer) {
            return $query->where('customer_id','=', $customer);
        })->when($date_type !='', function ($query) use ($date_type) {
            return $query->where('off_type','=', $date_type);
        })->when($asset_no !='', function ($query) use ($asset_no) {
            return $query->where('asset_no','=', $asset_no);
        })->when($floor_name !='', function ($query) use ($floor_name) {
            return $query->where('floor_name','=', $floor_name);
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
                'id' => function($coa) {
                    return $coa->id ?? '';
                },
                'floor_name' => function($coa) {
                    return $coa->floor_name ?? '';
                }, 'data-customer-name' => function($coa) {
                    return $coa->customer->shop_name ?? '';
                }, 'data-owner' => function($coa) {
                    return $coa->owner->name ?? '';
                }, 'area_sft' => function($coa) {
                    return $coa->area_sft ?? '';
                },'status'=>function($coa){
                    return $coa->status ?? '';
                },'rate'=>function($coa){
                    return $coa->rate ?? '';
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
                'last_increment_date'=>function($coa){
                    return otherHelper::ymd2dmy($coa->last_increment_date);
                },
                'data-updated_at' => function($coa) {
                    return otherHelper::change_date_format($coa->updated_at,true,'d-M-Y h:i A');
                },
            ])

            ->addColumn('action',  function($coa) {
                $option='<div style="width:140px;">';
                if(auth()->user()->can('edit-assets')){
                    $option .= '<div style=" float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('assets.edit',[$coa->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if (auth()->user()->can('delete-assets')){
                    $option .= '<div style=" float: right">
                                    <form action="'.route('assets.destroy',[$coa->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                else{
                    $option .= '<div style="width:100%; float: left;"></div>';
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
        $check = Asset::where('asset_no','=',$request->input('asset_no'))->where('status','<>','Un-allotted')->first();
        if($check !=null){
            return back()->with('warning',  'Asset No Already Assigned');

        }
        if($request->input('asset_no') == ''){
            return back()->with('warning',  'Asset No Empty Not  Allow');
        }
        $asset = new Asset();
        $asset->asset_no=$request->input('asset_no');
        $asset->customer_id=$request->input('customer_id');
        $asset->owner_id=$request->input('owner_id');
        $asset->asset_no=$request->input('asset_no');
        $asset->sc_rate=$request->input('sc_rate');
        $asset->food_court_rate=$request->input('food_court_rate');
        $asset->floor_name=$request->input('floor_name');
        $asset->area_sft=$request->input('area_sft');
        $asset->status=$request->input('status');
        $asset->meter_no=$request->input('meter_no');
        $asset->opening_reading=$request->input('opening_reading');
        $asset->rate=$request->input('rate');
        $asset->off_type=$request->input('off_type');
        $asset->last_increment_date=$request->input('last_increment_date');
        $asset->vat=$request->input('vat');
        $asset->advance_deposit_date=$request->input('advance_deposit_date');
        $asset->security_deposit=$request->input('security_deposit');
        $asset->advance_deposit=$request->input('advance_deposit');
        $asset->date_s=$request->input('date_s');
        $asset->date_e=$request->input('date_e')!=''?$request->input('date_e'):'0000-00-00';
        $asset->service_charge_status=$request->input('service_charge_status');
        $asset->food_court_status=$request->input('food_court_status');
        $asset->rent_increment=$request->input('rent_increment');
        $asset->parent_asset=$request->input('parent_asset');
        $asset->increment_effective_month=$request->input('increment_effective_month');
        $asset->service_date_s=$request->input('service_date_s')!=''?$request->input('service_date_s'):"0000-00-00";
        $asset->service_date_e=$request->input('service_date_e')!=''?$request->input('service_date_e'):"0000-00-00";
        $asset->food_date_s=$request->input('food_date_s')!=''?$request->input('food_date_s'):"0000-00-00";
        $asset->food_date_e=$request->input('food_date_e')!=''?$request->input('food_date_e'):"0000-00-00";
        $asset->contact_s_date=$request->input('contact_s_date')!=''?$request->input('contact_s_date'):"0000-00-00";

        $asset->created_by= Auth::user()->id;
        $asset->save();

        Logs::store(Auth::user()->name.' Asset Info has been created successfull ','Add','success',Auth::user()->id,$asset->id,'Asset Info');
        return redirect()->route('assets.index')->with('success','Asset Info has been created successfully.');

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
        $data['page_name']="Edit Asset Info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Asset Info','assets.index'),
            array('Edit','active')
        );

        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        $data['owner']= Owner::orderBy('name','ASC')->get();
        $floor = Lookup::where('name','Building Floor')->first();
        $data['floor']= Lookup::where('parent_id',$floor->id)->get();
        $data['assets']= Asset::orderBy('asset_no','ASC')->get();

        $data['editData']= Asset::where('id',$id)->first();
        return view('admin.asset-info.edit',$data);

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
        $assets = Asset::where('asset_no','=',$request->input('asset_no'))->where('status','Un-allotted')->first();
        $asset = Asset::find($id);
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
        AssetLog::insert($log->toArray());
        unset($log['ref_id']);

        $asset->customer_id=$request->input('customer_id');
        $asset->owner_id=$request->input('owner_id');
        $asset->asset_no=$request->input('asset_no');
        $asset->floor_name=$request->input('floor_name');
        $asset->last_increment_date=$request->input('last_increment_date');
        $asset->parent_asset=$request->input('parent_asset');
        $asset->area_sft=$request->input('area_sft');
        $asset->status=$request->input('status');
        $asset->meter_no=$request->input('meter_no');
        $asset->sc_rate=$request->input('sc_rate');
        $asset->food_court_rate=$request->input('food_court_rate');
        $asset->opening_reading=$request->input('opening_reading');
        $asset->rate=$request->input('rate');
        $asset->date_s=$request->input('date_s');
        $asset->date_e=$request->input('date_e')!=''?$request->input('date_e'):'0000-00-00';
        $asset->service_charge_status=$request->input('service_charge_status');
        $asset->food_court_status=$request->input('food_court_status');
        $asset->rent_increment=$request->input('rent_increment');
        $asset->off_type=$request->input('off_type');
        $asset->vat=$request->input('vat');
        $asset->contact_s_date=$request->input('contact_s_date')!=''?$request->input('contact_s_date'):"0000-00-00";
        $asset->advance_deposit_date=$request->input('advance_deposit_date');
        $asset->security_deposit=$request->input('security_deposit');
        $asset->advance_deposit=$request->input('advance_deposit');
        $asset->increment_effective_month=$request->input('increment_effective_month');
        $asset->service_date_s=$request->input('service_date_s')!=''?$request->input('service_date_s'):"0000-00-00";
        $asset->service_date_e=$request->input('service_date_e')!=''?$request->input('service_date_e'):"0000-00-00";
        $asset->food_date_s=$request->input('food_date_s')!=''?$request->input('food_date_s'):"0000-00-00";
        $asset->food_date_e=$request->input('food_date_e')!=''?$request->input('food_date_e'):"0000-00-00";

        $asset->created_by= Auth::user()->id;
        $asset->save();
        Logs::store(Auth::user()->name.' Asset Info has been updated','Edit','success',Auth::user()->id,$id,'Asset Info');
        return redirect()->route('assets.index')->with('success','Asset Info has been updated  successfully!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = Asset::find($id);
        $billings = Billing::where('shop_no',$income->asset_no)->first();

            if($billings !=null){
                return back()->with('warning',  'This asset has some transactions, you can not delete it');
            }
        $income->delete();
        Logs::store(Auth::user()->name.'Asset has been delete successfull ','Delete','success',Auth::user()->id,$id,'Asset Info');
        return redirect()->route('assets.index')->with('success','Assets has been delete successfully.');

    }
    public function getAssetNo($id){
        $assets = Asset::where('customer_id',$id)->get();
        echo json_encode($assets);
    }
    public function checkParent( Request $request){
        $data = $request->all();
        $data = $data['body'];
        $assets = Asset::where('parent_asset',$data['asset_no'])->first();
        echo $assets!=null?json_encode($assets):"";
    }
    public function billSDate(){
        $ar = Asset::all();
        foreach ($ar as $r){
            $d= Asset::find($r['id']);
            $d->date_s =$r['last_increment_date'];
            $d->last_increment_date =$r['date_s'];
            $d->save();
        }
        echo 'done';
    }

}
