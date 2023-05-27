<?php

namespace App\Http\Controllers;

use App\Http\PigeonHelpers\otherHelper;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Owner;
use Illuminate\Http\Request;
use App\Models\Journal;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Journal List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Journal','active'),
            array('List','active')
        );
        $data['customer']= Customer::orderBy('shop_name','ASC')->get();
        $data['ledger']= ChartOfAccount::orderBy('head','ASC')->get();
        return view('admin.journal.index',$data);
    }
    /**
     * list data
     */
    public function listData(Request $request){

        $columns = array(
            0 =>'id',
            1 =>'effective_date',
            2 =>'date',
            3 =>'transaction_type',
            4 =>'invoice_no',
            5 =>'customer_name',
            6 =>'shop_no',
            7 =>'remarks',
            8 =>'remarks',
            9 =>'ledger_head',
            10 =>'debit',
            11 =>'credit',

        );


        $in  = $request->all();

        $date_from=array();
        $date_to=array();
        $customer_id = '';
        $ledger_id = '';
        $shop_no = '';
        $date_type = 1;
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
                }else if($in['data'][$i]['name']=="customer_id"){
                    if($in['data'][$i]['value']!=''){
                        $customer_id =  $in['data'][$i]['value'];
                    }
                }else if($in['data'][$i]['name']=="ledger"){
                    if($in['data'][$i]['value']!=''){
                        $ledger_id =  $in['data'][$i]['value'];
                    }
                }else if($in['data'][$i]['name']=="shop_no"){
                    if($in['data'][$i]['value']!=''){
                        $shop_no =  $in['data'][$i]['value'];
                    }
                }else if($in['data'][$i]['name']=="date_type"){
                    if($in['data'][$i]['value']!=''){
                        $date_type =  $in['data'][$i]['value'];
                    }
                }
            }
            $order = 'id';// $columns[$request->input('order.0.column')];
            $dir ='desc';// $request->input('order.0.dir');
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
            $order = 'id';
            $dir ='desc';
        }


        $order = 'effective_date';
        $dir ='desc';
//        echo " ok".$request->input('order.0.dir');
        if(isset($columns[$request->input('order.0.column')]) && $request->input('order.0.column')!=null){
            $order =  $columns[$request->input('order.0.column')];
        }
        if($request->input('order.0.dir') !=null){
            $dir = $request->input('order.0.dir');
        }

        $limit = $request->input('length');
        $start = $request->input('start');

        if(empty($request->input('search.value')))
        {
            $totalData = Journal::query()
                ->when(count($date_from)>0 && $date_type==1 , function ($query) use ($date_from) {
                    return $query->where('effective_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==1 , function ($query) use ($date_to) {
                    return $query->where('effective_date','<=', $date_to);
                })
                ->when(count($date_from)>0 && $date_type==2 , function ($query) use ($date_from) {
                    return $query->where('date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==2 , function ($query) use ($date_to) {
                    return $query->where('date','<=', $date_to);
                })
                ->when( $customer_id !='' , function ($query) use ($customer_id) {
                    return $query->where('customer_id','=', $customer_id);
                })
                ->when( $ledger_id !='' , function ($query) use ($ledger_id) {
                    return $query->where('ledger_id','=', $ledger_id);
                })
                ->when( $shop_no !='' , function ($query) use ($shop_no) {
                    return $query->where('shop_no','=', $shop_no);
                })
//                ->where('created_by',Auth::user()->id)
                ->count();
            $totalFiltered = $totalData;
            $journal = Journal::query()
                ->when(count($date_from)>0 && $date_type==1 , function ($query) use ($date_from) {
                    return $query->where('effective_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==1 , function ($query) use ($date_to) {
                    return $query->where('effective_date','<=', $date_to);
                })
                ->when(count($date_from)>0 && $date_type==2 , function ($query) use ($date_from) {
                    return $query->where('date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==2 , function ($query) use ($date_to) {
                    return $query->where('date','<=', $date_to);
                })
                ->when( $customer_id !='' , function ($query) use ($customer_id) {
                    return $query->where('customer_id','=', $customer_id);
                })
                ->when( $ledger_id !='' , function ($query) use ($ledger_id) {
                    return $query->where('ledger_id','=', $ledger_id);
                })
                ->when( $shop_no !='' , function ($query) use ($shop_no) {
                    return $query->where('shop_no','=', $shop_no);
                })
//                ->where('created_by',Auth::user()->id)
                ->orderBy($order,$dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        }else{
            $search = $request->input('search.value');
            $toltalRecord = Journal::query()
                ->when(count($date_from)>0 && $date_type==1 , function ($query) use ($date_from) {
                    return $query->where('effective_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==1 , function ($query) use ($date_to) {
                    return $query->where('effective_date','<=', $date_to);
                })
                ->when(count($date_from)>0 && $date_type==2 , function ($query) use ($date_from) {
                    return $query->where('date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==2 , function ($query) use ($date_to) {
                    return $query->where('date','<=', $date_to);
                })
                ->when( $customer_id !='' , function ($query) use ($customer_id) {
                    return $query->where('customer_id','=', $customer_id);
                })
                ->when( $ledger_id !='' , function ($query) use ($ledger_id) {
                    return $query->where('ledger_id','=', $ledger_id);
                })
                ->when( $shop_no !='' , function ($query) use ($shop_no) {
                    return $query->where('shop_no','=', $shop_no);
                })
                ->where('id','LIKE',"%{$search}%")
                ->orwhere('transaction_type','LIKE',"%{$search}%")
                ->orWhere('date','LIKE',"%{$search}%")
                ->orWhere('invoice_no','LIKE',"%{$search}%")
                ->orWhere('ledger_head','LIKE',"%{$search}%")
                ->orWhere('debit','LIKE',"%{$search}%")
                ->orWhere('credit','LIKE',"%{$search}%")
//                ->where('created_by',Auth::user()->id)
                ->get();

            $totalFiltered = $totalData = sizeof($toltalRecord);

            $journal = Journal::query()
                ->when(count($date_from)>0 && $date_type==1 , function ($query) use ($date_from) {
                    return $query->where('effective_date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==1 , function ($query) use ($date_to) {
                    return $query->where('effective_date','<=', $date_to);
                })
                ->when(count($date_from)>0 && $date_type==2 , function ($query) use ($date_from) {
                    return $query->where('date','>=', $date_from);
                })
                ->when(count($date_to)>0 && $date_type==2 , function ($query) use ($date_to) {
                    return $query->where('date','<=', $date_to);
                })
                ->when( $customer_id !='' , function ($query) use ($customer_id) {
                    return $query->where('customer_id','=', $customer_id);
                })
                ->when( $ledger_id !='' , function ($query) use ($ledger_id) {
                    return $query->where('ledger_id','=', $ledger_id);
                })
                ->when( $shop_no !='' , function ($query) use ($shop_no) {
                    return $query->where('shop_no','=', $shop_no);
                })
                ->where('id','LIKE',"%{$search}%")
                ->orwhere('transaction_type','LIKE',"%{$search}%")
                ->orWhere('date','LIKE',"%{$search}%")
                ->orWhere('invoice_no','LIKE',"%{$search}%")
                ->orWhere('ledger_head','LIKE',"%{$search}%")
                ->orWhere('debit','LIKE',"%{$search}%")
                ->orWhere('credit','LIKE',"%{$search}%")
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
                $nestedData['id'] = $i++;
                $nestedData['ref_id'] = $product->ref_id;
                $nestedData['ref_module'] = $product->ref_module;
                $nestedData['date'] = (otherHelper::ymd2dmy($product->date) ?? '');
                $nestedData['effective_date'] = (otherHelper::ymd2dmy($product->effective_date) ?? '');
                $nestedData['transaction_type'] = ($product->transaction_type ?? '');
                $nestedData['invoice_no'] = $product->invoice_no ?? '';
                $nestedData['customer_name'] = $product->customer_name;
                $nestedData['remarks'] =  wordwrap($product->remarks, 40, "<br>") ;
                $nestedData['ledger_head'] = ($product->ledger_head ?? '');
                $nestedData['module'] = ($product->ref_module ?? '');
                $nestedData['shop_no'] = ($product->shop_no ?? '');
                $nestedData['debit'] = number_format($product->debit ??'0.00' ,2);
                $nestedData['credit'] = number_format($product->credit ?? '0.00',2);
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
