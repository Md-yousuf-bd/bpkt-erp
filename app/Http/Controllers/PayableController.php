<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\LogController as Logs;
use App\Http\PigeonHelpers\otherHelper;

use App\Models\Payable;
use App\Models\PaymentDetails;
use App\Models\ChartOfAccount;
use App\Models\Journal;
//use App\Models\Payables;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PayableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Payable List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Payable','active'),
            array('List','active')
        );
        return view('admin.payable.index',$data);
    }
    /**
     * list data
     */
    public function listData(Request $request){
        $payment=Payable::query();
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
                'total' => function($row) {
                    return $row->total ?? '0';
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
//                if(auth()->user()->can('edit-payable')){
//                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('payable.edit',[$row->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
//                }
                if($row['paid_amount']==0){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('payable.payment',[$row->id]).'"  ><span class="fa fa-edit">  Payment</i></a></div>';
                }else{
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="#"  ><span class="fa fa-edit">  Payment Complete</i></a></div>';

                }
                if(auth()->user()->can('read-payable')){
                    $option .='<div style="margin-left: 5px; margin-right: 5px; float: left"><a target="_blank" style="color:#fff !important;" class="btn btn-xs btn-warning text-white text-sm" href="'.route('payable.journal',[$row->id]).'"  > View JV</i></a></div>';
                }
                if (auth()->user()->can('delete-payable')){
                    $option .= '<div style="padding-left:5px; ">
                                    <form action="'.route('payable.destroy',[$row->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
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
        $data['page_name']="Add Payable";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Payable','payable.index'),
            array('Add','active')
        );
        $data['customer']= Vendor::all();
        $data['income_head']= ChartOfAccount::where('type','=','Expense')->get();
        $data['ledger'] = ChartOfAccount::where('status',1)->orderBy('head','asc')->get();
        return view('admin.payable.create',$data);
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
            $count = Payable::count();
            $count++;
        $invoice_no = "";
        $voucher_no = "Exp/".date('y')."/".date('m').'/'.$count;

            $issue_date = $request->input('issue_date');
            $vendor_id = $request->input('vendor_id');
            $journal_date = $request->input('journal_date');
            $total = $request->input('amount');
            $vendor = Vendor::find($vendor_id);

            $grand_total = $request->input('amount');
            $payment_reference =$request->input('remarks');
            $ledger_head =$request->input('ledger_id');

            $coa = ChartOfAccount::getLedger($ledger_head); // cash
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $ledger_name= $coa->head;
            $group_name= $coa->group_name;

            $income = new Payable();
            $income->vendor_id = $request->input('vendor_id');
            $income->payment_type = $request->input('payment_type');
            $income->payment_reference = $payment_reference;
            $income->vendor_name = $vendor->vendor_name??"";
            $income->issue_date = $issue_date;
            $income->journal_date = $journal_date;
            $income->ledger_name = $ledger_name;
            $income->ledger_id = $ledger_head;
            $income->voucher_no = $voucher_no;
            $income->vat = 0;
            $income->vat_amount = 0 ;
            $income->post_date = date('Y-m-d') ;
            $income->total = $total??0;
            $income->grand_total = $total??0;
            $income->paid_amount = 0;
            $income->created_by = Auth::user()->id;
            $income->save();
            $income_id = $income->id;
            $jv = array();
            $sub = array('ref_id'=>$income_id,'payment_ref'=>$payment_reference, 'staff_name'=>$r['staff_name']??"",'group_name'=>$group_name,
                'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code,
                'post_date'=>date('Y-m-d'), 'effective_date'=>$journal_date,
                'transaction_type'=> 'Payment','invoice_no'=>$invoice_no,'customer_name'=>$customer->vendor_name??"",
                'remarks'=>$payment_reference,'ledger_head'=>$ledger_name,'date'=>$issue_date,'debit'=>$grand_total,'credit'=>0,
                'voucher_no'=>$voucher_no,'ref_module'=>'Payable','created_by'=>Auth::user()->id);
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
                'voucher_no'=>$voucher_no,'ref_module'=>'Payable','created_by'=>Auth::user()->id);
            if($grand_total!=0){
                array_push($jv,$sub);
            }
            if($income_id!=0){
                Journal::insert($jv);
            }

        Logs::store(Auth::user()->name.'New Payable has been created successfull ','Add','success',Auth::user()->id,$income->id,'Payable');

        return redirect()->route('payable.index')->with('success','Payable has been created successfully.');

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
        $data['page_name']="Add Payable";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Payable','payable.index'),
            array('List','active')
        );
        $data['customer']= Vendor::all();
        $data['income_head']= ChartOfAccount::where('type','=','Expense')->get();
        $data['ledger'] = ChartOfAccount::where('status',1)->orderBy('head','asc')->get();
        $data['payment'] = Payable::find($id);
        return view('admin.payable.payment',$data);
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
        $income = Payable::find($id);
        $income->delete();
        Journal::where('ref_id',$id)->whereIn('ref_module',['Payable','Payable Payment'])->delete();
        Logs::store(Auth::user()->name.'Payable has been delete successfull ','Delete','success',Auth::user()->id,$income->id,'Payable');
        return redirect()->route('payable.index')->with('success','Payable has been delete successfully.');

    }

    /**
     * show journal
     * @param int $id
     */
    public function journal($id){
        $data['page_name']="Show Journal";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Payable','payable.index'),
            array('Show','active')
        );
        $data['journal']= Payable::find($id);
        $data['details']= Journal::where('ref_id',$id)->where('ref_module','Payable')->orderby('id','ASC')->get();
        return view('admin.payable.journal',$data);

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
    public function payment(Request $request){
        $checkData = $request->all();
        $grand_total = $request->input('paid_amount');
        $payment_reference =$request->input('payment_reference');
        $payment_mode =$request->input('payment_mode');
        $cheque_no =$request->input('cheque_no');
        $journal_date =$request->input('journal_date');
        $issue_date =$request->input('issue_date');
        if( $request->input('payment_mode')=='Cash'){
            $ledger_head = 6;
        }else{
            $ledger_head =$request->input('ledger_id');
        }
        $id = $checkData['payment_id'];
        $invoice_no='';
        $coa = ChartOfAccount::getLedger($ledger_head); // cash
        $ledger_type= $coa->type;
        $ledger_code= $coa->system_code;
        $ledger_id= $coa->id;
        $ledger_name= $coa->head;
        $group_name= $coa->group_name;
        $payment = Payable::find($id);
        $voucher_no = $payment->voucher_no;
        $payment->payment_type = $request->input('payment_type');
        $payment->payment_reference = $payment_reference;
        $payment->payment_mode = $request->input('payment_mode');
        $payment->payment_journal_date = $journal_date;
        $payment->payment_issue_date = $issue_date;
        $payment->cheque_no = $cheque_no;
        $payment->cheque_bank_name = $request->input('cheque_bank_name');
        $payment->cheque_date = $request->input('cheque_date');
        $payment->payment_ledger_name = $ledger_name;
        $payment->payment_ledger_id = $ledger_head;
        $payment->vat = 0;
        $payment->vat_amount = 0 ;
        $payment->post_date = date('Y-m-d') ;
        $payment->paid_amount = $grand_total;
        $payment->updated_by = Auth::user()->id;
        $payment->save();
        $income_id = $id;
        $jv = array();
        $sub = array('ref_id'=>$income_id,'payment_ref'=>$payment_reference, 'staff_name'=>$r['staff_name']??"",'group_name'=>$group_name,
            'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code,
            'post_date'=>date('Y-m-d'), 'effective_date'=>$journal_date,
            'transaction_type'=> 'Payment','invoice_no'=>$invoice_no,'customer_name'=>$customer->vendor_name??"",
            'remarks'=>'','ledger_head'=>$ledger_name,'date'=>$issue_date,'debit'=>$grand_total,'credit'=>0,
            'voucher_no'=>$voucher_no,'ref_module'=>'Payable Payment','created_by'=>Auth::user()->id);
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
            'voucher_no'=>$voucher_no,'ref_module'=>'Payable Payment','created_by'=>Auth::user()->id);
        if($grand_total!=0){
            array_push($jv,$sub);
        }
        if($income_id!=0){
            Journal::insert($jv);
        }
        Logs::store(Auth::user()->name.'Payable Payment has been delete successfull ','Add','success',Auth::user()->id,$id,'Payable Payment');
        return redirect()->route('payable.index')->with('success','Payable Payment has been created successfully.');

    }

    public  function getPaymentLdger($id){
        $ids = explode(",",$id);
        $res = ChartOfAccount::whereIn('id',$ids)->get();
        echo json_encode($res) ;
    }
}
