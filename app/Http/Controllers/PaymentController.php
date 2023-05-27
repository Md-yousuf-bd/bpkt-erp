<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogController as Logs;
use App\Http\PigeonHelpers\otherHelper;
use App\Models\Payment;
use App\Models\PaymentDetails;
use App\Models\ChartOfAccount;
use App\Models\Journal;
use App\Models\StockInvoice;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Payments List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Payment','active'),
            array('List','active')
        );
        return view('admin.payments.index',$data);
    }
    /**
     * list data
     */
    public function listData(Request $request){
        $payment=Payment::query();
        return DataTables::eloquent($payment)
            ->addIndexColumn()
            ->setRowId(function($row){
                return 'row_'.$row->id;
            })
            ->setRowData([


                'vendor_name' => function($row) {
                    return $row->vendor_name ?? '';
                },
                'voucher_no' => function($row) {
                    return $row->voucher_no ?? '';
                },
                'id' => function($row) {
                    return $row->id ?? '0';
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

            ->addColumn('action',  function($row) {
                $option='<div style="width:210px;">';
                if(auth()->user()->can('edit-payment')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('payment.edit',[$row->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if(auth()->user()->can('read-payment')){
                    $option .='<div style="margin-left: 5px; margin-right: 5px; float: left"><a style="color:#fff !important;" class="btn btn-xs btn-warning text-white text-sm" href="'.route('payment.journal',[$row->id]).'"  > View JV</i></a></div>';
                }
                if (auth()->user()->can('delete-payment')){
                    $option .= '<div style="padding-left:5px; ">
                                    <form action="'.route('payment.destroy',[$row->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
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
        $data['page_name']="Add Payments";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Payment','payment.index'),
            array('Add','active')
        );
        $data['customer']= Vendor::all();
        $data['income_head']= ChartOfAccount::where('type','=','Expense')->get();
        $data['ledger'] = ChartOfAccount::where('sub_category', '=', 'Current Bank Accounts')->where('status',1)->get();
        return view('admin.payments.create',$data);
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
        if($checkData['payment_type']=='Vendor Payment'){
            $customer = Vendor::find($request->input('vendor_id'));
            $invoice_no = date('my');
            $invoice_no .= '-'.$customer->id;
        }else{
            $customer = array();
            $invoice_no = '';
        }
        $count = Payment::count();
        $count++;
        $voucher_no = "Exp/".date('y')."/".date('m').'/'.$count;

        $issue_date = $request->input('issue_date');
        $journal_date = $request->input('journal_date');
        $total = $request->input('total');
        $vat_amount = $request->input('vat_amount_total');
        if(
            $request->input('payment_type')=='Inter Transfer' ||
            $request->input('payment_type')=='Advance Tax Payment' ||
            $request->input('payment_type')=='Source Tax Payment' ||
            $request->input('payment_type')=='Source VAT Payment' ||
            $request->input('payment_type')=='Sales VAT Payment' ||
            $request->input('payment_type')=='Corporate Tax Payment' ||
            $request->input('payment_type')=='Others Payment'
         ){
            $income = $this->insertOtherEntry($checkData,$invoice_no,$customer,$voucher_no);

        }
        else{
//            insert billing form

            $grand_total = $request->input('paid_amount');

            $payment_reference =$request->input('payment_reference');
            $payment_mode =$request->input('payment_mode');
            $cheque_no =$request->input('cheque_no');
            if( $request->input('payment_mode')=='Cash'){
                $ledger_head = 6;
            }else{
                $ledger_head =$request->input('ledger_id');
            }

            $coa = ChartOfAccount::getLedger($ledger_head); // cash
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $ledger_name= $coa->head;
            $group_name= $coa->group_name;
            $sum = StockInvoice::whereIn('id',[$request->input('invoice_no')])->sum('total_amount');

            $income = new Payment();
            $income->vendor_id = $request->input('vendor_id');
            $income->payment_type = $request->input('payment_type');
            $income->payment_reference = $payment_reference;
            $income->payment_mode = $request->input('payment_mode');
            $income->vendor_name = $customer->vendor_name??"";
            $income->issue_date = $issue_date;
            $income->journal_date = $journal_date;
            $income->cheque_no = $cheque_no;
            $income->due_date = $request->input('due_date');
            $income->ref_id = $request->input('invoice_no');
            $income->cheque_bank_name = $request->input('cheque_bank_name');
            $income->cheque_date = $request->input('cheque_date');
            $income->ledger_name = $ledger_name;
            $income->ledger_id = $ledger_head;
            $income->voucher_no = $voucher_no;
            $income->vat = 0;
            $income->vat_amount = 0 ;
            $income->post_date = date('Y-m-d') ;
            $income->total = $sum??0;
            $income->grand_total = $sum??0;
            $income->paid_amount = $grand_total;
            $income->created_by = Auth::user()->id;
            $income->save();
            $income_id = $income->id;



            $jv = array();
            $sub = array('ref_id'=>$income_id,'payment_ref'=>$payment_reference, 'staff_name'=>$r['staff_name']??"",'group_name'=>$group_name,
                'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code,
                'post_date'=>date('Y-m-d'), 'effective_date'=>$journal_date,
                'transaction_type'=> 'Payment','invoice_no'=>$invoice_no,'customer_name'=>$customer->vendor_name??"",
                'remarks'=>'','ledger_head'=>$ledger_name,'date'=>$issue_date,'debit'=>$grand_total,'credit'=>0,
                'voucher_no'=>$voucher_no,'ref_module'=>'Payment','created_by'=>Auth::user()->id);
            if($grand_total!=0){
                array_push($jv,$sub);
            }
            $coa = ChartOfAccount::getLedger(126); // Sundry Creditors
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $ledger_name= $coa->head;
            $group_name= $coa->group_name;
            $sub = array('ref_id'=>$income_id,'payment_ref'=>$payment_reference, 'staff_name'=>$r['staff_name']??"",'group_name'=>$group_name,
                'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code,
                'post_date'=>date('Y-m-d'), 'effective_date'=>$journal_date,
                'transaction_type'=> 'Payment','invoice_no'=>$invoice_no,'customer_name'=>$customer->vendor_name??"",
                'remarks'=>'','ledger_head'=>$ledger_name,'date'=>$issue_date,'debit'=>0,'credit'=>$grand_total,
                'voucher_no'=>$voucher_no,'ref_module'=>'Payment','created_by'=>Auth::user()->id);
            if($grand_total!=0){
                array_push($jv,$sub);
            }
            if($income_id!=0){
                Journal::insert($jv);
            }
        }
        Logs::store(Auth::user()->name.'New Payment has been created successfull ','Add','success',Auth::user()->id,$income->id,'Payment');

        return redirect()->route('payment.index')->with('success','Payment has been created successfully.');

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
        $income = Payment::find($id);
        $details = PaymentDetails::where('ref_id',$id)->delete();
        $income->delete();
        Journal::where('ref_id',$id)->where('ref_module','Payment')->delete();
        Logs::store(Auth::user()->name.'Payment has been delete successfull ','Delete','success',Auth::user()->id,$income->id,'Payment');
        return redirect()->route('payment.index')->with('success','Payment has been delete successfully.');

    }

    /**
     * show journal
     * @param int $id
     */
    public function journal($id){
        $data['page_name']="Show Journal";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Payment','payment.index'),
            array('Show','active')
        );
        $data['journal']= Payment::find($id);
        $data['details']= Journal::where('ref_id',$id)->where('ref_module','Payment')->orderby('id','ASC')->get();
        return view('admin.payments.journal',$data);

    }

    public function getCoaList($id){


        if($id=='Cheque'){
            $ledger= ChartOfAccount::where('sub_category','=','Curren Bank Accounts')->get();
            echo json_encode(array('ledger'=>$ledger,'flag'=>1));

        }else if($id=='Cash'){
            $ledger= ChartOfAccount::where('sub_category','=','Cash in Hand')->get();
            echo json_encode(array('ledger'=>$ledger,'flag'=>0));
        }


    }
    public function insertOtherEntry($checkData,$invoice_no,$customer,$voucher_no){
        //            insert other form

        $grand_total = $checkData['ad_total'];

        $payment_reference =$checkData['payment_reference'];
        $payment_mode =$checkData['payment_mode'];
        $cheque_no =$checkData['cheque_no']??"";
        $issue_date =$checkData['issue_date'];
        $journal_date =$checkData['journal_date'];
        $total =$checkData['ad_total'];

        $income = new Payment();
        $income->vendor_id = $checkData['vendor_id'];
        $income->payment_type = $checkData['payment_type'];
        $income->payment_reference = $payment_reference;
        $income->payment_mode = $checkData['payment_mode'];
        $income->vendor_name = $customer->vendor_name??"";
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->cheque_no = $cheque_no;
        $income->voucher_no = $voucher_no;
        $income->vat = 0;
        $income->vat_amount = 0 ;
        $income->post_date = date('Y-m-d') ;
        $income->total = $total;
        $income->grand_total = $grand_total;
        $income->created_by = Auth::user()->id;

        $income->save();
        $income_id = $income->id;
        $data = json_decode($checkData['accountRecord'],true);

        $jv = array(
        );


        foreach ($data as $r){
            $details = new PaymentDetails();
            $details->ref_id = $income_id;
            $details->ledger_name = trim($r['income_head']) ;
            $details->ledger_id = $r['income_head_id'];
            $details->vendor_name = $customer->vendor_name??"";
            $details->staff_name = isset($r['staff_name'])?$r['staff_name']:"";
            $details->staff_id = $r['staff_id']??"";
            $details->amount = $r['amount'];
            $details->vat = 0;
            $details->vat_amount = 0;
            $effective = $journal_date;
            $remarks = $r['remarks'];
            $details->remarks = $remarks;
            $details->effective_date = $effective;
            if($income_id!=0){
                $details->save();
            }
            $coa = ChartOfAccount::getLedger($r['income_head_id']);
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $group_name= $coa->group_name;
            $sub = array('ref_id'=>$income_id, 'payment_ref'=>$payment_reference, 'group_name'=>$group_name,'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),'effective_date'=>$effective, 'transaction_type'=> 'Payment','invoice_no'=>$invoice_no,'customer_name'=>$customer->vendor_name??"",'remarks'=>$remarks,'ledger_head'=>$r['income_head'],'date'=>$issue_date,'debit'=>$r['amount'],'credit'=>0,'voucher_no'=>$voucher_no,'ref_module'=>'Payment','created_by'=>Auth::user()->id);
            array_push($jv,$sub);
        }
        $coa = ChartOfAccount::getLedger($checkData['ledger_head']); // cash
        $ledger_type= $coa->type;
        $ledger_code= $coa->system_code;
        $ledger_id= $coa->id;
        $ledger_name= $coa->head;
        $group_name= $coa->group_name;
        $sub = array('ref_id'=>$income_id,'payment_ref'=>$payment_reference, 'group_name'=>$group_name,'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code, 'post_date'=>date('Y-m-d'), 'effective_date'=>$effective, 'transaction_type'=> 'Payment','invoice_no'=>$invoice_no,'customer_name'=>$customer->vendor_name??"",'remarks'=>'','ledger_head'=>$ledger_name,'date'=>$issue_date,'debit'=>0,'credit'=>$grand_total,'voucher_no'=>$voucher_no,'ref_module'=>'Payment','created_by'=>Auth::user()->id);
        if($grand_total!=0){
            array_push($jv,$sub);
        }
      //  return $grand_total;
        Journal::insert($jv);
    return $income;
}

    public  function getPaymentLdger($id){
        $ids = explode(",",$id);
        $res = ChartOfAccount::whereIn('id',$ids)->get();
        echo json_encode($res) ;
    }
}
