<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\CustomerLog;
use App\Models\Lookup;
use App\Models\Meter;
use App\Models\Owner;
use App\Models\Billing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;
use Excel;
use DB;
use DateTime;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Customer info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('customer','active'),
            array('List','active')
        );
        $data['customer'] = Customer::orderBy('shop_name', 'ASC')->get();
        return view('admin.customer.index',$data);
    }
    public function listData(Request $request){
        $columns = array(
            0 => 'id',
            1 => 'action',
            2 => 'id',
            3 => 'month',
            4 => 'month',
            5 => 'shop_no',
            6 => 'shop_name',
            7 => 'invoice_no',
            8 => 'invoice_no',
            9 => 'meter_no',
            10 => 'created_by',
            11 => 'created_by'
        );
        $in = $request->all();
        $date_from = array();
        $date_to = array();
        $shop_no = array();
        $shop_name = array();
        $invoice_no = '';
        $date_type = 0;
        $bill_type = '';
        $service = '';
        if (isset($in['data'])) {
            for ($i = 1; $i < sizeof($in['data']); $i++) {
                if ($in['data'][$i]['name'] == "shop_name") {
                    if ($in['data'][$i]['value'] != '') {
                        array_push($shop_name, $in['data'][$i]['value']);
                    }
                }
            }
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $limit = 10;
            $start = 0;
            if ($request->input('length')) {
                $limit = $request->input('length');
            }
            if ($request->input('start')) {
                $start = $request->input('start');
            }
        }else {
            $limit = $request->input('length');
            $start = $request->input('start');
            $data['selected_shops'] = array();
            $data['quantity_assign'] = '<';
            $data['selected_quantity'] = '';
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
        }
        $limit = $request->input('length');
           $start = $request->input('start');
        if (empty($request->input('search.value'))) {
            $totalData = Customer::query()
              ->when(count($shop_name) > 0, function ($query) use ($shop_name) {
                    return $query->where('shop_name', '=', $shop_name);
                })
                ->count();
            $totalFiltered = $totalData;
            $journal = Customer::query()
                ->leftJoin('assets','customers.id','=','assets.customer_id')
                ->when(count($shop_name) > 0, function ($query) use ($shop_name) {
                    return $query->where('customers.id', '=', $shop_name);
                })
                ->selectRaw('customers.*,group_concat(assets.asset_no) as asset_no')
                ->groupBy('customers.id')
                ->orderBy($order, $dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        }else{
            $search = $request->input('search.value');
            $totalData = Customer::query()
                ->when(count($shop_name) > 0, function ($query) use ($shop_name) {
                    return $query->where('shop_name', '=', $shop_name);
                })
                ->count();
            $totalFiltered = $totalData;
            $journal = Customer::query()
                ->leftJoin('assets','customers.id','=','assets.customer_id')
                ->when(count($shop_name) > 0, function ($query) use ($shop_name) {
                    return $query->where('customers.id', '=', $shop_name);
                })
                ->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orwhere('shop_no', 'LIKE', "%{$search}%")
                        ->orWhere('shop_name', 'LIKE', "%{$search}%");
                })
                ->selectRaw('customers.*,group_concat(assets.asset_no) as asset_no')
                ->groupBy('customers.id')
                ->orderBy($order, $dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        }


        $data = array();
        if (!empty($journal)) {


                $i = $start + 1;

                foreach ($journal as $product) {

                    $nestedData['sl'] = $i++;
                    $nestedData['id'] = $product->id;
                    $nestedData['action'] = '';
                    $nestedData['shop_name'] = $product->shop_name;
                    $nestedData['shop_no'] = $product->shop_no;
                    $nestedData['owner_name'] = $product->owner_name;
                    $nestedData['owner_contact'] = $product->owner_contact ?? '';
                    $nestedData['email'] = $product->email ?? "";
                    $nestedData['owner_nid'] = $product->owner_nid ?? "";
                    $nestedData['asset_no'] = wordwrap(($product->asset_no ?? ""),'20','<br>\n');
                    $nestedData['contact_person_name'] = $product->contact_person_name ?? "";
                    $nestedData['owner_address'] = $product->owner_address ?? "";
                    $nestedData['etin'] = $product->etin ?? "";
                    $nestedData['contact_person_phone'] = $product->contact_person_phone ?? "";
                    $nestedData['created_by'] = $product->user->name ?? "";
                    if ($product->status == 1) {
                        $status = '<span style="color:green"> Active</span>';
                    } else {
                        $status = '<span style="color:red"> In-Active</span>';
                    }

                    $nestedData['status'] = $status;
                    $option = '<div style="width:220px;">';
                    if (auth()->user()->can('edit-customer') && $product->id) {
                        $option .= '<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="' . route('customer.edit', [$product->id]) . '"  ><span class="fa fa-edit">  Edit</i></a></div>';
                    }
                    if (auth()->user()->can('read-customer') && $product->id) {

                        $option .= '<div style="padding-left:5px;float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="' . route('customer.show', [$product->id]) . '"  ><span class="fa fa-edit">  Details</i></a></div>';
                    }
                    if (auth()->user()->can('delete-customer') && $product->id) {
                        $option .= '<div style=" float: right">
                                    <form action="' . route('customer.destroy', [$product->id]) . '" method="post" class="">' . csrf_field() . '<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                    } else {
                        $option .= '<div style=" "></div>';
                    }
                    $option .= '</div>';

                    $nestedData['action'] = $option;
                    $nestedData['updated_at'] = otherHelper::change_date_format($product->updated_at, true, 'd-M-Y h:i A');

                    $data[] = $nestedData;

                }

        }
            $json_data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            );

            echo json_encode($json_data);


    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Customer info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('customer','customer.index'),
            array('Add','active')
        );

        $division_id = Lookup::where('name','Division')->first();
        $data['division']=Lookup::where('parent_id','=',$division_id['id'])->orderBy('id','desc')->get();
        $data['ownerName'] = Owner::where('type','Owner Info')->get();
        $data['ledger'] = ChartOfAccount::all();
        return view('admin.customer.create',$data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $check = Customer::where('shop_name',$request->input('shop_name'))->first();
        if($check != null){
            return back()->with('warning',  'Data already exits!');
        }
        $checkData = $request->all();
        unset($checkData['_token']);
//        if($checkData['shop_no']==''){
//            return back()->with('warning',  'Please Enter Shop No');
//        }
        if($checkData['shop_name']==''){
            return back()->with('warning',  'Please Enter Shop name');
        }
        if($checkData['owner_name']==''){
            return back()->with('warning',  'Please Enter owner name');
        }
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }
        $customer = new Customer();
        foreach ($checkData as $key => $value) {
            $customer->$key = $value;
        }

        $ledger = ChartOfAccount::getLedger($checkData['ledger']);
        $customer->ledger = $ledger->head??"";
        $customer->ledger_id = $ledger->id??"";
        $customer->created_by= Auth::user()->id;
        $customer->save();
        Logs::store(Auth::user()->name.' New Customer has been created successfull ','Add','success',Auth::user()->id,$customer->id,'Customer Info');
        return redirect()->route('customer.index')->with('success','Customer has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['page_name']="Customer info List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('customer','customer.index'),
            array('List','active')
        );
        $data['details'] = Customer::find($id);

        return view('admin.customer.details',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Customer info";
        $data['breadcumb']=array(
            array('Home','home'),
            array('customer','customer.index'),
            array('Edit','active')
        );

        $division_id = Lookup::where('name','Division')->first();
        $data['division']=Lookup::where('parent_id','=',$division_id['id'])->orderBy('id','desc')->get();
        $data['editData'] = Customer::find($id);
        $data['ledger'] = ChartOfAccount::all();
        $data['ownerName'] = Owner::where('type','Owner Info')->get();
        return view('admin.customer.edit',$data);
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
        if($checkData['shop_name']==''){
            return back()->with('warning',  'Please Enter Shop name');
        }
        if($checkData['owner_name']==''){
            return back()->with('warning',  'Please Enter owner name');
        }
        if($request->input('email')!='' && !filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('warning','Please Enter Valid Email');
        }

        unset($checkData['_token']);
        $customer  = Customer::find($id);
        $log = array();
        $log = $customer;
        unset($log['id'], $log['updated_at']);
        $log['customer_id'] = $id;
        $log['updated_by'] = Auth::user()->id;
        CustomerLog::insert($log->toArray());
        unset($log['customer_id']);
        foreach ($checkData as $key => $value) {
            $customer->$key = $value;
        }
        $ledger = ChartOfAccount::getLedger($checkData['ledger']);
        $customer->ledger = $ledger->head??"";
        $customer->ledger_id = $ledger->id??"";
        $customer->updated_by = Auth::user()->id;
        $customer->save();

        Logs::store(Auth::user()->name.' Customer has been Updated successfull ','Update','success',Auth::user()->id,$customer->id,'Customer Info');
        return redirect()->route('customer.index')->with('success','Customer has been Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);

        $billings = Billing::where('customer_id',$id)->first();

        if($billings !=null){
            return back()->with('warning',  'This customer has some transactions, you can not delete it');
        }
        Logs::store(Auth::user()->name. ' - '.$customer->shop_name.' Customer has been Deleted successfull ','Delete','success',Auth::user()->id,$id,'Customer Info');
        $customer->delete();
        return redirect()->route('customer.index')->with('success','Customer has been Deleted successfully.');


    }
    public function readExcel()
    {

//        $rows = Excel::toArray( [],'Asset List for Upload_12.06.22.xlsx');
        $rows = Excel::toArray( [],'New Meter info upload_03.11.22 (2).xlsx');
                echo "<pre>";
        print_r($rows[0]);
        $customerArray = array();

     foreach ($rows[0]  as $key=>$r) {
            if($key==0) {
                continue;
            }
            $subarray  = array(
                'asset_no'=>trim($r[0]),
                'customer_id'=>trim($r[2]),
                'floor_name'=>trim($r[3]),
                'meter_no'=>trim($r[4]),
                'owner_id'=>trim($r[5]),
                'status'=>trim($r[6]),
                'off_type'=>trim($r[7]),
                'opening_reading'=>trim($r[9]),
                'vat_applicable'=>trim($r[10]),
                'rate'=>0,
                'vat'=>trim($r[8]),
                'date_e'=>'0000-00-00',
                'date_s'=>trim($r[11])!=''?date('Y-m-d',strtotime($r[11])):'',
                'created_by'=> Auth::user()->id,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_by'=>Auth::user()->id,
                'updated_at'=>date('Y-m-d H:i:s'),
            );
            $customer =  Meter::insert($subarray);
            $productId = DB::getPdo()->lastInsertId();
            Logs::store(Auth::user()->name.' New Meter Info has been created successfull ','Add','success',Auth::user()->id,$productId,'Meter Info');

        }


/*
        foreach ($rows[0]  as $key=>$r) {
            if($key==0) {
                continue;
            }
            $subarray  = array(
                'asset_no'=>trim($r[0]),
                'customer_id'=>trim($r[2]),
                'floor_name'=>trim($r[3]),
                'area_sft'=>trim($r[4]),
                'date_s'=>trim($r[5])!=''?date('Y-m-d',strtotime($r[5])):'',
                'date_e'=>trim($r[6])!=''?date('Y-m-d',strtotime($r[6])):'',
                'owner_id'=>trim($r[7]),
                'status'=>trim($r[8]),
                'service_charge_status'=>trim($r[9]),
                'food_court_status'=>trim($r[10]),
                'rate'=>trim($r[11]),
                'rent_increment'=>trim($r[12]),
                'off_type'=>trim($r[13]),
                'vat'=>trim($r[14]),
                'created_by'=> Auth::user()->id,
                'created_at'=>date('Y-m-d H:i:s'),
                'updated_by'=>Auth::user()->id,
                'updated_at'=>date('Y-m-d H:i:s'),
                'increment_effective_month'=>trim($r[15])
            );
            $customer =  Asset::insert($subarray);
            $productId = DB::getPdo()->lastInsertId();
            Logs::store(Auth::user()->name.' New Asset has been created successfull ','Add','success',Auth::user()->id,$productId,'Asset Info');

        }
*/


//        echo "<pre>";
//        print_r($rows[1]);
/*
        $customerArray = array();
        foreach ($rows[0]  as $key=>$r) {
            if($key==0) {
                continue;
            }

            $subarray  = array(
                'shop_no'=>trim($r[0]),
                'shop_name'=>trim($r[1]),
                'owner_name'=>trim($r[2]),
                'owner_contact'=>trim($r[3]),
                'region'=>trim($r[6]),
                'trade_lincese_no'=>trim($r[7]),
                'incorporation_no'=>trim($r[8]),
                'etin'=>trim($r[9]),
                'bin'=>trim($r[10]),
                'email'=>trim($r[11]),
                'contact_person_name'=>trim($r[12]),
                'contact_person_phone'=>trim($r[13]),
                'customer_remarks'=>trim($r[14]),
                'designation'=>trim($r[15]),
                'vat_exemption'=>trim($r[16]),
                'black_listed'=>trim($r[17]),
                'status'=>trim($r[18])=='Un-allotted'?2:1,
                'contact_s_date'=>trim($r[19])!=''?date('Y-m-d',($r[19]-25569)*86400):'',
                'renewal_date'=>trim($r[22])!=''?date('Y-m-d',($r[20]-25569)*86400):''
            );


// print datetime formatted

            $customer =  Customer::insert($subarray);
            $productId = DB::getPdo()->lastInsertId();
//echo echo date('Y-m-d',$xlsx_class->unixstamp($j['IST_Time']));." ";
//            dd($productId); // will spit out product id
//             var_dump($customer->insertGetId());
            Logs::store(Auth::user()->name.' New Customer has been created successfull ','Add','success',Auth::user()->id,$productId,'Customer Info');

        }
*/

//        end read excel

//        create excel
//        Excel::export('Report2016', function($excel) {
//
//            // Set the title
//            $excel->setTitle('My awesome report 2016');
//
//            // Chain the setters
//            $excel->setCreator('Me')->setCompany('Our Code World');
//
//            $excel->setDescription('A demonstration to change the file properties');
//
//            $data = [12,"Hey",123,4234,5632435,"Nope",345,345,345,345];
//
//            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
//                $sheet->setOrientation('landscape');
//                $sheet->fromArray($data, NULL, 'A3');
//            });
//
//        })->download('xlsx');
//        end create excel




    }
}
