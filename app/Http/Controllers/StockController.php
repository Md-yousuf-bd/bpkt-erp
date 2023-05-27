<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Godown;
use App\Models\Journal;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockInvoice;
use App\Models\Customer;
use App\Models\GroupAccount;
use App\Models\Lookup;
use App\Models\Owner;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Matrix\Exception;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;
class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Stock List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Stock','active'),
            array('List','active')
        );
        return view('admin.stock.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Stock";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Stock','stock.index'),
            array('Add','active')
        );
        $data['vendor']= Vendor::orderBy('vendor_name','ASC')->get();
        $data['godown']= Godown::orderBy('name','ASC')->get();
        $data['unit']= MeasurementUnit::orderBy('short_name','ASC')->get();
        $product = Lookup::where('name','Product')->first();
        $brand = Lookup::where('name','Brand')->first();
        $size = Lookup::where('name','Size')->first();
        $data['product']= Product::orderBy('product_name','ASC')->get();
        $data['brand']= Lookup::where('parent_id',$brand->id)->orderBy('name','ASC')->get();
        $data['size']= Lookup::where('parent_id',$size->id)->orderBy('name','ASC')->get();
        $brand = Lookup::where('name','Product Category')->first();
        $data['category'] = Lookup::where('parent_id',$brand->id)->orderBy('name','asc')->get();
        return view('admin.stock.create',$data);
    }




    public function listData(Request $request){
        $columns = array(
            0 =>'id',
            1=>'action',
            2 =>'id',
            3 =>'product_id',
            4 =>'brand_id',
            5 =>'vendor_name',
            6=>'qty',
            7 =>'size',
            8 =>'total',
            9 =>'created_by'
        );

        $in  = $request->all();
        $date_from=array();
        $date_to=array();
        $shop_no=array();
        $shop_name=array();
        $invoice_no='';
        $date_type=0;
        if (isset($in['data'])) {
            for($i=1;$i< sizeof($in['data']); $i++){
                if($in['data'][$i]['name']=='date_from'){
                    if($in['data'][$i]['value']!=''){

                        array_push($date_from, $in['data'][$i]['value']);
                    }
                }else if($in['data'][$i]['name']=="date_to"){
                    if($in['data'][$i]['value']!=''){
                        array_push($date_to, $in['data'][$i]['value']);
                    }
                }else if($in['data'][$i]['name']=="shop_no"){
                    if($in['data'][$i]['value']!=''){
                        array_push($shop_no, $in['data'][$i]['value']);
                    }
                }else if($in['data'][$i]['name']=="shop_name"){
                    if($in['data'][$i]['value']!=''){
                        array_push($shop_name, $in['data'][$i]['value']);
                    }
                }else if($in['data'][$i]['name']=="invoice_no"){
                    if($in['data'][$i]['value']!=''){
                        $invoice_no = $in['data'][$i]['value'];
                    }

                }else if($in['data'][$i]['name']=="date_type" ){
                    if($in['data'][$i]['value']!=''){
                        $date_type = $in['data'][$i]['value'];
                    }

                }
            }
            $order =  $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $limit=10;
            $start=0;
            if($request->input('length')){
                $limit = $request->input('length');
            }
            if($request->input('start')){
                $start = $request->input('start');
            }

        }
        else
        {
            $limit = $request->input('length');
            $start = $request->input('start');
            $data['selected_shops']=array();
            $data['quantity_assign']='<';
            $data['selected_quantity']='';
            $order =  $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
        }


//        $order = 'id';
//        $dir ='desc';

        $limit = $request->input('length');
        $start = $request->input('start');

        if(empty($request->input('search.value')))
        {
            $totalData = Stock::query()
                ->when(count($date_from)>0 && $date_type==1 , function ($query) use ($date_from) {
                    return $query->where('due_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==1  , function ($query) use ($date_to) {
                    return $query->where('due_date','<=', $date_to);
                })
                ->when(count($date_from)>0 && $date_type==2 , function ($query) use ($date_from) {
                    return $query->where('issue_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==2  , function ($query) use ($date_to) {
                    return $query->where('issue_date','<=', $date_to);
                })
                ->when(count($shop_no)>0 , function ($query) use ($shop_no) {
                    return $query->where('shop_no','=', $shop_no);
                })
                ->when(count($shop_name)>0 , function ($query) use ($shop_name) {
                    return $query->where('customer_id','=', $shop_name);
                })
                ->when($invoice_no!='' , function ($query) use ($invoice_no) {
                    return $query->where('invoice_no','=', $invoice_no);
                })
                ->count();
            $totalFiltered = $totalData;
            $journal = Stock::query()
                ->when(count($date_from)>0 && $date_type==1 , function ($query) use ($date_from) {
                    return $query->where('due_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==1  , function ($query) use ($date_to) {
                    return $query->where('due_date','<=', $date_to);
                })
                ->when(count($date_from)>0 && $date_type==2 , function ($query) use ($date_from) {
                    return $query->where('issue_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==2  , function ($query) use ($date_to) {
                    return $query->where('issue_date','<=', $date_to);
                })
                ->when(count($shop_no)>0 , function ($query) use ($shop_no) {
                    return $query->where('shop_no','=', $shop_no);
                })
                ->when(count($shop_name)>0 , function ($query) use ($shop_name) {
                    return $query->where('customer_id','=', $shop_name);
                })
                ->when($invoice_no!='' , function ($query) use ($invoice_no) {
                    return $query->where('invoice_no','=', $invoice_no);
                })
                ->orderBy($order,$dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        }else{
            $search = $request->input('search.value');
            $toltalRecord = Stock::query()
                ->when(count($date_from)>0 && $date_type==1 , function ($query) use ($date_from) {
                    return $query->where('due_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==1  , function ($query) use ($date_to) {
                    return $query->where('due_date','<=', $date_to);
                })
                ->when(count($date_from)>0 && $date_type==2 , function ($query) use ($date_from) {
                    return $query->where('issue_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==2  , function ($query) use ($date_to) {
                    return $query->where('issue_date','<=', $date_to);
                })
                ->when(count($date_from)>0 , function ($query) use ($date_from) {
                    return $query->where('effective_date','>=', $date_from);
                })
                ->when(count($date_to)>0 , function ($query) use ($date_to) {
                    return $query->where('effective_date','<=', $date_to);
                })
                ->when(count($shop_no)>0 , function ($query) use ($shop_no) {
                    return $query->where('shop_no','=', $shop_no);
                })
                ->when(count($shop_name)>0 , function ($query) use ($shop_name) {
                    return $query->where('customer_id','=', $shop_name);
                })
                ->when($invoice_no!='' , function ($query) use ($invoice_no) {
                    return $query->where('invoice_no','=', $invoice_no);
                })
                ->where('module','Bulk Entry')
                ->where(function($query)  use ($search){
                    $query->where('id','LIKE',"%{$search}%")
                        ->orwhere('shop_no','LIKE',"%{$search}%")
                        ->orWhere('shop_name','LIKE',"%{$search}%")
                        ->orWhere('invoice_no','LIKE',"%{$search}%");
                })
                ->leftjoin('billing_details','billing_details.billing_id','=','billings.id')
                ->selectRaw('billings.*,billing_details.month')

//                ->where('created_by',Auth::user()->id)
                ->get();

            $totalFiltered = $totalData = sizeof($toltalRecord);

            $journal = Stock::query()

                ->when(count($date_from)>0 && $date_type==1 , function ($query) use ($date_from) {
                    return $query->where('due_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==1  , function ($query) use ($date_to) {
                    return $query->where('due_date','<=', $date_to);
                })
                ->when(count($date_from)>0 && $date_type==2 , function ($query) use ($date_from) {
                    return $query->where('issue_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==2  , function ($query) use ($date_to) {
                    return $query->where('issue_date','<=', $date_to);
                })
                ->when(count($shop_no)>0 , function ($query) use ($shop_no) {
                    return $query->where('shop_no','=', $shop_no);
                })
                ->when(count($shop_name)>0 , function ($query) use ($shop_name) {
                    return $query->where('customer_id','=', $shop_name);
                })
                ->when($invoice_no!='' , function ($query) use ($invoice_no) {
                    return $query->where('invoice_no','=', $invoice_no);
                })
                ->where('module','Bulk Entry')
                ->where(function($query)  use ($search){
                    $query->where('id','LIKE',"%{$search}%")
                        ->orwhere('shop_no','LIKE',"%{$search}%")
                        ->orWhere('shop_name','LIKE',"%{$search}%")
                        ->orWhere('invoice_no','LIKE',"%{$search}%");
                })
//                ->where('created_by',Auth::user()->id)
                ->orderBy($order,$dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        }


        $data = array();
        if(!empty($journal))
        {
            $i=$start+1;

            foreach ($journal as $product)
            {

                $nestedData['sl'] = $i++;
                $nestedData['id'] = $product->id;
                $nestedData['vendor_name'] = $product->vendor->vendor_name??"";
                $nestedData['product_name'] = $product->product_name??"";
                $nestedData['brand_name'] = $product->brand_name??"";
                $nestedData['size_name'] = $product->sizes->name??"";
                $nestedData['qty'] = $product->qty??"";
                $nestedData['total_amount'] = $product->total_amount??"";

                // <a target="_blank" class="btn btn-xs btn-success text-white text-sm" href="'.route('stock.edit',[$product->id]).'"  ><span class="fa fa-edit">  Edit</i></a>

                $nestedData['action'] = '<div style=" float: left; margin-bottom: -7px;margin-right: 5px;">
<div style="padding-left:5px;padding-right:5px;float: left;">
                                                <a target="_blank" style="color:#fff !important;" class="btn btn-xs btn-warning text-white text-sm" href="'.route('stock.journal',[$product->id]).'"  > JV View </i></a></div>
                                                </div>';
                $nestedData['data-updated_at'] = otherHelper::change_date_format($product->updated_at,true,'d-M-Y h:i A');
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $checkData = $request->all();
            $sub_array = json_decode($checkData['sub_array'],true);
            $invoice_no=$request->input('purchase_ref_no');
            $journal_date=$request->input('journal_date');

            $cheque_no=$request->input('payment_mode')=='Cash'?$request->input('purchase_ref_no'):$request->input('cheque_no');

            $count = Stock::count();
            $count++;
            $voucher_no = "PV/".date('y')."/".date('m').'/'.$count;

            $stock_invoice_data_due_date = $request->input('due_date');
            $stock_invoice_data_vendor_id = '';
            $stock_invoice_data_brand_id = '';
            $stock_invoice_data_size = '';
            $stock_invoice_data_product_id = '';
            $stock_invoice_data_product_name = '';
            $stock_invoice_data_size_name = '';
            $stock_invoice_data_vendor_name = '';
            $stock_invoice_data_brand_name = '';
            $stock_invoice_data_qty = '';
            $stock_invoice_data_measuring_unit = '';
            $stock_invoice_data_rate = '';
            $stock_invoice_data_vat_rate = '';
            $stock_invoice_data_sub_total = '';
            $stock_invoice_data_total_amount = '';
            $stock_invoice_data_store_name = '';
            $stock_invoice_data_voucher_no = $voucher_no;
            $stock_invoice_data_ledger_id = '';
            $stock_invoice_data_purchase_ref_no = $invoice_no;
            $stock_invoice_data_journal_date = '';
            $stock_invoice_data_payment_mode = '';
            $stock_invoice_data_checque_no = '';

            $stock_invoice_data_invoice_sub_total = 0;
            $stock_invoice_data_invoice_total = 0;

            foreach ($sub_array as $row){
                // $count = Stock::count();
                // $count++;
                $measuring_unit = $row['measuring_unit'];
                // $voucher_no = "SV/".date('y')."/".date('m').'/'.$count;
                $product = Product::find($row['product_id']);
                $stock_invoice_data_product_id = $row['product_id'].','.$stock_invoice_data_product_id;
                $stock_invoice_data_product_name = $product->name.','.$stock_invoice_data_product_name;

//            $brand = Lookup::find($request->input('brand_id'));

                $size = (isset($row['size'])&&$row['size']!='')?$row['size']:0;//Lookup::find($request->input('size'));
                $stock_invoice_data_size = $size.','.$stock_invoice_data_size;

                $brand_name = (isset($row['brand_name'])&&$row['brand_name']!='')?$row['brand_name']:'Non Brand';
                $stock_invoice_data_brand_name = $brand_name.','.$stock_invoice_data_brand_name;

                $size_name = (isset($row['size_name'])&&$row['size_name']!='')?$row['size_name']:'None';
                $stock_invoice_data_size_name = $size_name.','.$stock_invoice_data_size_name;

                $vendor_name = $row['vendor_name'];
                $stock_invoice_data_vendor_name = $vendor_name.','.$stock_invoice_data_vendor_name;

                $vendor_id = $row['vendor_id'];
                $stock_invoice_data_vendor_id = $row['vendor_id'].','.$stock_invoice_data_vendor_id;

                $asset = new Stock();
                $asset->journal_date=$request->input('journal_date');
                $stock_invoice_data_journal_date = $request->input('journal_date').','.$stock_invoice_data_journal_date;

                $asset->payment_mode=$request->input('payment_mode');
                $stock_invoice_data_payment_mode = $request->input('payment_mode').','.$stock_invoice_data_payment_mode;

                $asset->cheque_no=$request->input('cheque_no');
                $stock_invoice_data_checque_no = $request->input('cheque_no').','.$stock_invoice_data_checque_no;

                $asset->ledger_id=$request->input('ledger_id');
                $stock_invoice_data_ledger_id = $request->input('ledger_id').','.$stock_invoice_data_ledger_id;

                $asset->purchase_ref_no=$request->input('purchase_ref_no');
                // $stock_invoice_data_purchase_ref_no = $request->input('purchase_ref_no').','.$stock_invoice_data_purchase_ref_no;

                $asset->vendor_id=$vendor_id;
                $asset->brand_id=0;
                $asset->product_name=$product->product_name;
                $asset->brand_name=$brand_name;
                $asset->size_name=$size_name;
                $asset->vendor_name=$vendor_name;
                $asset->size=$size;
                $asset->product_id=$row['product_id'];

                $asset->qty=$row['qty'];
                $stock_invoice_data_qty = $row['qty'].','.$stock_invoice_data_qty;

                $asset->balance_qty=$row['qty'];

                $asset->rate=$row['rate'];
                $stock_invoice_data_rate = $row['rate'].','.$stock_invoice_data_rate;

                $asset->vat_rate=$row['vat_rate']!=''?$row['vat_rate']:0;

                $temp_vat_rate = $row['vat_rate']!=''?$row['vat_rate']:0;
                $stock_invoice_data_vat_rate = $temp_vat_rate.','.$stock_invoice_data_vat_rate;

                $asset->re_order_label=$row['re_order_label'];

                $asset->measuring_unit=$row['measuring_unit'];
                $stock_invoice_data_store_name = $row['measuring_unit'].','.$stock_invoice_data_store_name;

                $asset->sub_total=$row['sub_total'];
                $stock_invoice_data_sub_total = $row['sub_total'].','.$stock_invoice_data_sub_total;

                $stock_invoice_data_invoice_sub_total = $stock_invoice_data_invoice_sub_total + $row['sub_total'];

                $asset->total_amount=$row['total_amount'];
                $stock_invoice_data_total_amount = $row['total_amount'].','.$stock_invoice_data_total_amount;

                $stock_invoice_data_invoice_total = $stock_invoice_data_invoice_total + $row['total_amount'];

                $asset->post_date=date('Y-m-d');
                $asset->effective_date=date('Y-m-d');
                $asset->voucher_no=$voucher_no;
                $asset->created_by= Auth::user()->id;
                $asset->save();

                Logs::store(Auth::user()->name.' Stock has been created successfull ','Add','success',Auth::user()->id,$asset->id,'Stock');
                $coa = ChartOfAccount::getLedger(25);
                $ledger_type= $coa->type;
                $ledger_code= $coa->system_code;
                $ledger_id= $coa->id;
                $group_name= trim($coa->group_name);
                $income_head = $coa->head;
                $group_id= trim($coa->group_id);
                $customer_id = $vendor_id;
                $qty = $row['qty'];
                $amount = $row['total_amount'];
                $total_amount = $row['total_amount'];
                $jv = array();

                $remarks = "Purchase of $product->product_name, Brand $brand_name , Size $size_name, total $qty $measuring_unit @ Tk. $amount and VAT @5% on ".date('d M Y');
//                $vendor = Vendor::find($request->input('vendor_id'));
//                $vendor_name = $vendor->vendor_name;
                $sub = array('ref_id'=>$asset->id, 'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                    'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
                    'effective_date'=>$journal_date, 'transaction_type'=> 'Purchase','invoice_no'=>$invoice_no,
                    'customer_name'=>$vendor_name,'remarks'=>$remarks,'ledger_head'=>trim($income_head)
                ,'date'=>date('Y-m-d'),'debit'=>$total_amount,'credit'=>0,'voucher_no'=>trim($voucher_no),
                    'payment_ref'=>$cheque_no,'customer_id'=>$vendor_id,'qty'=>$request->input('qty'),
                    'ref_module'=>'Stock','created_by'=>Auth::user()->id);
                array_push($jv,$sub);
                $coa = ChartOfAccount::getLedger($request->input('ledger_id')); // Sundry Creditors
                $ledger_type= $coa->type;
                $ledger_code= $coa->system_code;
                $ledger_id= $coa->id;
                $group_name= trim($coa->group_name);
                $group_id= trim($coa->group_id);
                $income_head = $coa->head;

                $sub = array('ref_id'=>$asset->id, 'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                    'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
                    'effective_date'=>$journal_date, 'transaction_type'=> 'Purchase','invoice_no'=>$invoice_no,
                    'customer_name'=>$vendor_name,'remarks'=>'','ledger_head'=>trim($income_head)
                ,'date'=>date('Y-m-d'),'debit'=>0,'credit'=>$total_amount,'voucher_no'=>trim($voucher_no),
                    'payment_ref'=>$cheque_no,'customer_id'=>$vendor_id,'qty'=>$request->input('qty'),
                    'ref_module'=>'Stock','created_by'=>Auth::user()->id);
                array_push($jv,$sub);
                Journal::insert($jv);
            }
                $StockInvoice = new StockInvoice();
                $StockInvoice->vendor_id = trim($stock_invoice_data_vendor_id,',');
                $StockInvoice->brand_id = trim($stock_invoice_data_brand_id,',');
                $StockInvoice->size = $stock_invoice_data_size;
                $StockInvoice->product_id = $stock_invoice_data_product_id;
                $StockInvoice->product_name = $stock_invoice_data_product_name;
                $StockInvoice->size_name = $stock_invoice_data_size_name;
                $StockInvoice->vendor_name = trim($stock_invoice_data_vendor_name,',');
                $StockInvoice->brand_name = $stock_invoice_data_brand_name;
                $StockInvoice->created_by= Auth::user()->id;
                $StockInvoice->qty = $stock_invoice_data_qty;
                $StockInvoice->measuring_unit = 0;
                $StockInvoice->rate = $stock_invoice_data_rate;
                $StockInvoice->vat_rate = $stock_invoice_data_vat_rate;
                $StockInvoice->sub_total = $stock_invoice_data_sub_total;
                $StockInvoice->total_amount = $stock_invoice_data_total_amount;
                $StockInvoice->post_date = date('Y-m-d');
                $StockInvoice->effective_date = date('Y-m-d');
                $StockInvoice->store_name = $stock_invoice_data_store_name;
                $StockInvoice->voucher_no = $stock_invoice_data_voucher_no;
                $StockInvoice->ledger_id = $stock_invoice_data_ledger_id;
                $StockInvoice->purchase_ref_no = $stock_invoice_data_purchase_ref_no;
                $StockInvoice->journal_date =  $stock_invoice_data_journal_date;
                $StockInvoice->payment_mode = $stock_invoice_data_payment_mode;
                $StockInvoice->checque_no = $stock_invoice_data_checque_no ;
                $StockInvoice->due_date = $stock_invoice_data_due_date;
                $StockInvoice->invoice_sub_total = $stock_invoice_data_invoice_sub_total;
                $StockInvoice->invoice_total = $stock_invoice_data_invoice_total;
                $StockInvoice->due_amount = $stock_invoice_data_invoice_total;
                $StockInvoice->save();

            return redirect()->route('stock-invoice.index')->with('success','Stock has been created successfully.');

        }catch (\Exception $e){
            $msg = $e->getLine() . " " . $e->getFile() . " " . $e->getMessage() . " " . $e->getCode();
            return back()->with('warning',  $msg);
        }

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
        $assets = Asset::where('asset_no','=',$request->input('asset_no'))->where('status','Un-allotted')->first();
        $asset = Asset::find($id);
        if($request->input('asset_no') == ''){
            return back()->with('warning',  'Asset No Empty Not  Allow');
        }
        if($assets == null){
            $asset->asset_no=$request->input('asset_no');
        }
        $asset->customer_id=$request->input('customer_id');
        $asset->owner_id=$request->input('owner_id');
        $asset->asset_no=$request->input('asset_no');
        $asset->floor_name=$request->input('floor_name');
        $asset->area_sft=$request->input('area_sft');
        $asset->status=$request->input('status');
        $asset->meter_no=$request->input('meter_no');
        $asset->opening_reading=$request->input('opening_reading');
        $asset->rate=$request->input('rate');
        $asset->date_s=$request->input('date_s');
        $asset->date_e=$request->input('date_e');
        $asset->service_charge_status=$request->input('service_charge_status');
        $asset->food_court_status=$request->input('food_court_status');
        $asset->rent_increment=$request->input('rent_increment');
        $asset->off_type=$request->input('off_type');
        $asset->vat=$request->input('vat');
        $asset->increment_effective_month=$request->input('increment_effective_month');
        $asset->created_by= Auth::user()->id;
        $asset->save();
        Logs::store(Auth::user()->name.' Asset Info has been updated','Edit','success',Auth::user()->id,$id,'Asset Info');
        return redirect()->route('assets.index')->with('success','Asset Info has been updated  successfully!');

    }
    public function journal($id){
        $data['page_name']="Stock Journal";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Stock','stock.index'),
            array('List','active')
        );
        $data['journal'] = Stock::find($id);
        $data['details'] = Journal::where('ref_id',$id)->where('ref_module','Stock')->get();
        return view('admin.stock.journal',$data);
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

    public function getProductInfo($id,$ref){
        if($ref==1){
            $data['category'] = Product::where('id',$id)->orderBy('product_name','asc')->get();
            return Response($data,200);
        }elseif ($ref==2){
            $size = Lookup::where('name','Brand')->first();
            $data['brand'] = Product::where('parent_id',$size->id)->orderBy('name','asc')->get();
            return Response($data,200);
        }elseif ($ref==3){
            $size = Lookup::where('name','Size')->first();
            $data['size'] = Lookup::where('parent_id',$size->id)->orderBy('name','asc')->get();
            return Response($data,200);
        }
    }
    public function getAllLedger($id){
        if($id=='Cash'){
            $data['ledger'] = ChartOfAccount::where('category','Cash in Hand')->orderBy('head','asc')->get();
            return Response($data,200);
        }elseif ($id=='Bank'){
            $data['ledger'] = ChartOfAccount::where('category','Bank Accounts')->orderBy('head','asc')->get();
            return Response($data,200);
        }elseif ($id=='Creditors'){
            $data['ledger'] = ChartOfAccount::where('id','126')->orderBy('head','asc')->get();
            return Response($data,200);
        }else{
            $data['ledger'] = array();
            return Response($data,200);
        }
    }
    public function getVendorInvoice($id){

        $stock = StockInvoice::where('vendor_id',$id)->where('paid_amount',0)->get();
        return response()->json($stock);
    }
}
