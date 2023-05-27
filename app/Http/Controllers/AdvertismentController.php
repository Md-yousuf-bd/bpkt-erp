<?php

namespace App\Http\Controllers;

use App\Models\Advertisment;
use App\Models\AdvertismentLog;
use App\Models\Asset;
use App\Models\Customer;
use App\Models\GroupAccount;
use App\Models\Lookup;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

use Excel;
use DB;
use DateTime;

class AdvertismentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //
        $data['page_name'] = "Advertisement Space List";
        $data['breadcumb'] = array(
            array('Home','home'),
            array('Asset Info','active'),
            array('List','active')
        );

        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        return view('admin.advertisement-space.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Advertisement Space";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Advertisement Space','advertisement.index'),
            array('Add','active')
        );
        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        $data['shop_no']= Asset::orderBy('asset_no','ASC')->get();

        return view('admin.advertisement-space.create',$data);
    }
    public function listData(Request $request){
        $in  = $request->all();
        $customer = '';
        $code = '';
        $date_type = '';

        if (isset($in['data'])) {

            for($i=1;$i< sizeof($in['data']); $i++){
                if($in['data'][$i]['name']=='code'){
                    if($in['data'][$i]['value']!=''){
                        $code=$in['data'][$i]['code'];
                    }
                }else if($in['data'][$i]['name']=="date_type"){
                    if($in['data'][$i]['value']!=''){
                        $date_type=$in['data'][$i]['value'];
                    }
                }else if($in['data'][$i]['name']=="shop_name"){
                    if($in['data'][$i]['value']!=''){
                        $customer=$in['data'][$i]['value'];
                    }
                }
            }
        }
        $groups=Advertisment:: when($code !='', function ($query) use ($code) {
            return $query->where('code','=', $code);
        })->when($customer !='', function ($query) use ($customer) {
            return $query->where('customer_id','=', $customer);
        })->when($date_type !='', function ($query) use ($date_type) {
            return $query->where('space_name','=', $date_type);
        });
        return DataTables::eloquent($groups)
            ->addIndexColumn()
            ->setRowId(function($coa){
                return 'row_'.$coa->id;
            })
            ->setRowData([
                'code' => function($coa) {
                    return $coa->code ?? '';
                },  'space_name' => function($coa) {
                    return $coa->space_name ?? '';
                },
                 'data-customer-name' => function($coa) {
                    return $coa->customer->shop_name ?? '';
                },  'area' => function($coa) {
                    return $coa->area ?? '';
                }, 'rate' => function($coa) {
                    return $coa->rate ?? '';
                }, 'asset_no' => function($coa) {
                    return $coa->asset_no ?? '';
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
                $option='<div style="width:150px;">';
                if(auth()->user()->can('edit-advertisement')){
                    $option .='<div style="width:70px; float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('advertisement.edit',[$coa->id]).'" target="_blank" ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if (auth()->user()->can('delete-advertisement')){
                    $option .= '<div style="padding-left:5px; ">
                                    <form action="'.route('advertisement.destroy',[$coa->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
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
//        $check = Asset::where('asset_no','=',$request->input('asset_no'))->where('status','<>','Un-allotted')->first();
//        if($check !=null){
//            return back()->with('warning',  'Asset No Already Assigned');
//
//        }
        $checkData = $request->all();
        unset($checkData['_token']);
        $customer = new Advertisment();
        foreach ($checkData as $key => $value) {
            if($key=='asset_no'){
                $ass = Asset::where('asset_no',$value)->first();
                $customer->$key = $value;
                $customer->customer_id = $ass->customer_id??0;
            }else{
                $customer->$key = $value;
            }

        }
        $customer->save();
        Logs::store(Auth::user()->name.'Advertisement Space has been created successfull ','Add','success',Auth::user()->id,$customer->id,'Advertisement Space');
        return redirect()->route('advertisement.index')->with('success','Advertisement Space has been created successfully.');

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
        $data['page_name']="Edit Advertisement Space";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Advertisement Space','advertisement.index'),
            array('Edit','active')
        );

        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        $data['shop_no']= Asset::orderBy('asset_no','ASC')->get();

        $data['editData']= Advertisment::where('id',$id)->first();
        return view('admin.advertisement-space.edit',$data);

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
//        if($checkData['shop_no']==''){
//            return back()->with('warning',  'Please Enter Shop No');
//        }
//        if($checkData['shop_name']==''){
//            return back()->with('warning',  'Please Enter Shop name');
//        }
//        if($checkData['owner_name']==''){
//            return back()->with('warning',  'Please Enter owner name');
//        }
//        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
//            return redirect()->back()->with('warning','Please Enter Valid Email');
//        }

        unset($checkData['_token']);
        $customer  = Advertisment::find($id);
        $log = array();
        $log = $customer;
        unset($log['id'], $log['updated_at']);
//        $log['customer_id'] = $id;
        $log['updated_by'] = Auth::user()->id;

        $log['updated_at'] = date('Y-m-d H:i:s');
        $log['ref_id'] = $id;
        $log['updated_by'] = Auth::user()->id;
        AdvertismentLog::insert($log->toArray());
        unset($log['ref_id']);
        unset($log['customer_id']);
        foreach ($checkData as $key => $value) {
            if($key=='asset_no'){
                $ass = Asset::where('asset_no',$value)->first();
                $customer->$key = $value;
                $customer->customer_id = $ass->customer_id??0;
            }else{
                $customer->$key = $value;
            }

        }

        $customer->updated_by = Auth::user()->id;
        $customer->save();

        Logs::store(Auth::user()->name.'Advertisement Space has been Updated successfull ','Update','success',Auth::user()->id,$customer->id,'Advertisement Space');
        return redirect()->route('advertisement.index')->with('success','Advertisement Space has been Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Advertisment::find($id);
        Logs::store(Auth::user()->name. ' - '.$customer->shop_name.' Advertisement Space has been Deleted successfull ','Delete','success',Auth::user()->id,$id,'Advertisement Space');
        $customer->delete();
        return redirect()->route('advertisement.index')->with('success','Advertisement Space has been Deleted successfully.');

    }
    public function  getAddCode($id){

        $asset = Asset::find($id);
        $data = Advertisment::where('customer_id',$asset->customer_id)->get();
        return json_encode(array('add_code'=>$data));
    }
    public function readExcel()
    {

        $rows = Excel::toArray( [],'Advertisement Space Info.xlsx');
        echo "<pre>";
        print_r($rows[0]);
        $customerArray = array();

        foreach ($rows[0]  as $key=>$r) {
            if($key==0) {
                continue;
            }
            $subarray  = array(
                'customer_id'=>0,
                'code'=>trim($r[2]),
                'space_name'=>trim($r[3]),
                'rate'=>trim($r[4]),
                'area'=>trim($r[5]),
                'date_e'=>'0000-00-00',
                'date_s'=>'0000-00-00',
                'created_by'=> Auth::user()->id,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_by'=>Auth::user()->id,
                'updated_at'=>date('Y-m-d H:i:s'),
            );
            $customer =  Advertisment::insert($subarray);
            $productId = DB::getPdo()->lastInsertId();
            Logs::store(Auth::user()->name.' New Advertisement Info has been created successfull ','Add','success',Auth::user()->id,$productId,'Meter Info');

        }


    }
}
