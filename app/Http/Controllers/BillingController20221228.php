<?php

namespace App\Http\Controllers;

use App\Models\Advertisment;
use App\Models\Asset;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Billing;
use App\Models\CashCollection;
use App\Models\BillingDetail;
use App\Models\Employee;
use App\Models\Journal;
use App\Models\Meter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Billing List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Billing','active'),
            array('List','active')
        );
        return view('admin.billing.index',$data);
    }
    /**
     * list data
     */
    public function listData(Request $request){
        $income=Billing::query();
        return DataTables::eloquent($income)
            ->addIndexColumn()
            ->setRowId(function($row){
                return 'row_'.$row->id;
            })
            ->setRowData([
                'shop_no' => function($row) {
                    return $row->shop_no ?? '';
                },

                'shop_name' => function($row) {
                    return $row->shop_name ?? '';
                },
                'invoice_no' => function($row) {
                    return $row->invoice_no ?? '0';
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
                if(auth()->user()->can('edit-billing')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('billing.edit',[$row->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if(auth()->user()->can('read-billing')){
                    $option .='<div style="padding-left:5px;padding-right:5px;float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="'.route('billing.show',[$row->id]).'"  ><span class="fa fa-edit">  Invoice</i></a></div>';
                }
                if (auth()->user()->can('delete-billing')){
                    $option .= '<div style="padding-left:5px; ">
                                    <form action="'.route('billing.destroy',[$row->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                if(auth()->user()->can('read-billing')){
                    $option .='<div style="margin-top: 3px; float: left"><a style="color:#fff !important;" class="btn btn-xs btn-warning text-white text-sm" href="'.route('billing.journal',[$row->id]).'"  > View JV</i></a></div>';
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
        $data['page_name']="Add Billing";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Billing','billing.index'),
            array('Add','active')
        );
        $m = date('d-m-Y');
        $day = date('M Y',strtotime("-1 months"));
        $previous_month = BillingDetail::where('month','=',$day)->first();
        $data['pre_month']= $previous_month !=null?$previous_month->pre_reading:"";
        $data['customer']= Asset::orderBy('asset_no','ASC')->get();
        $data['advertisement']= Advertisment::all();
        $data['employee']= Employee::all();
        $data['income_head']= ChartOfAccount::where('type','=','Income')->orderBy('head','ASC')->get();
        return view('admin.billing.create',$data);
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
        $assetId = Asset::find($request->input('customer_id'));
        $customer = Customer::find($assetId->customer_id);
        $invoice_no = date('my');
      //  $invoice_no .= '-'.$assetId->asset_no;
        $count = Billing::count();
        $count++;
        $voucher_no = "SV/".date('y')."/".date('m').'/'.$count;
        $customer_id = $assetId->customer_id;// $request->input('customer_id');
        $checkData['customer_id'] = $customer_id;
        $check = Billing::where('shop_no','=',$assetId->asset_no)->count();
        $customer['shop_no'] = $assetId->asset_no;
        $check = $check+1;
         if($check >= 0 && $check < 10){
             $invoice_no .= '-0000'.$check;
         }elseif ($check >= 10 && $check < 100){
             $invoice_no .= '-000'.$check;
         }elseif ($check >= 100 && $check < 1000){
             $invoice_no .= '-00'.$check;
         }elseif ($check >= 1000 && $check < 10000){
             $invoice_no .= '-0'.$check;
         }else{
             $invoice_no .= '-'.$check;
         }

        if($count >= 0 && $count < 10){
            $invoice_no .= '-0000'.$count;
        }elseif ($count >= 10 && $count < 100){
            $invoice_no .= '-000'.$count;
        }elseif ($count >= 100 && $count < 1000){
            $invoice_no .= '-00'.$count;
        }elseif ($count >= 1000 && $count < 10000){
            $invoice_no .= '-0'.$count;
        }else{
            $invoice_no .= '-'.$count;
        }
        $issue_date = $request->input('issue_date');
        $journal_date = $request->input('journal_date');
        $total = $request->input('total');
        $vat_amount = $request->input('vat_amount_total');
        $billing_period_manual = $request->input('billing_period_manual');
        if($request->input('bill_type')=='Income'){
            $income = $this->insertIncomeEntry($checkData,$invoice_no,$customer,$voucher_no,0);

        }
        else if($request->input('bill_type')=='Electricity'){
            $income =  $this->insertElectricityEntry($checkData,$invoice_no,$customer,$voucher_no,0);

        } else if($request->input('bill_type')=='Food Court SC'){
            $income =  $this->insertFoodCourtService($checkData,$invoice_no,$customer,$voucher_no,0);

        } else if($request->input('bill_type')=='Special Service Charge'){
            $income =  $this->insertSCService($checkData,$invoice_no,$customer,$voucher_no,0);

        }else if($request->input('bill_type')=='Advertisement'){
            $income =  $this->insertAdvertisementEntry($checkData,$invoice_no,$customer,$voucher_no,0);

        }else{
//            insert billing form
            $income = new Billing();
            $income->customer_id = $customer_id;
            $income->bill_type = $request->input('bill_type');
            $income->shop_no = $customer->shop_no;
            $income->shop_name = $customer->shop_name;
            $income->person_id = $request->input('person_id');
            $income->issue_date = $issue_date;
            $income->journal_date = $journal_date;
            $income->billing_period_manual = $billing_period_manual;
            $income->due_date = $request->input('due_date');
            $income->credit_period = $request->input('credit_period');
            $income->invoice_no = $invoice_no;
            $income->voucher_no = $voucher_no;
            $income->vat = $request->input('vat');
            $income->vat_amount = $vat_amount ;
            $income->post_date = date('Y-m-d') ;
            $income->total = $total;
            $income->grand_total = $request->input('grand_total');
            $income->created_by = Auth::user()->id;

            $income->save();
            $income_id = $income->id;
            $data = json_decode($checkData['accountRecord'],true);
            $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

            $jv = array(
            );
            $file_amount=0;
            foreach ($data as $r){
                $details = new BillingDetail();
                $details->billing_id = $income_id;
                $details->ledger_name = $r['income_head'];
                $details->ledger_id = $r['income_head_id'];
                $details->month = $r['month'];
                $file_amount += $r['fine_amount'] ;

                $details->amount = $r['amount'];
                $details->fine = $r['fine_amount'];
                $details->area_sft = $r['area'];
                $details->rate_sft = $r['rate'];
                $details->vat = $r['vat'];
                $details->vat_amount = $r['vat_amt'];
                $details->total = $r['total'];
                $details->current_reading = '';
                $details->pre_reading = '';
                $details->kwt = '';
                $details->kwt_rate = '';

                $effective = $journal_date;

                $a = $r['area']* $r['rate'];
                if($request->input('bill_type')=='Service Charge'){
                    $remarks = "Service Charge for Shop No# $customer->shop_no for the month of $r[month] for $r[area] sft @ Tk. $r[rate]";

                }else{
                    $remarks = "Rent for Shop No# $customer->shop_no for the month of $r[month] for $r[area] sft @ Tk. $r[rate]";

                }
                $details->remarks = $remarks;
                $details->effective_date = $effective;
                $details->save();
                $coa = ChartOfAccount::getLedger($r['income_head_id']);
                $ledger_type= $coa->type;
                $ledger_code= $coa->system_code;
                $ledger_id= $coa->id;
                $group_name= $coa->group_name;
                $sub = array('ref_id'=>$income_id, 'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                    'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
                    'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                    'customer_name'=>$customer->shop_name,'remarks'=>$remarks,'ledger_head'=>$r['income_head']
                ,'date'=>$issue_date,'debit'=>0,'credit'=>$r['amount'],'voucher_no'=>$voucher_no,
                'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
                    'ref_module'=>'Billing','created_by'=>Auth::user()->id);
                array_push($jv,$sub);
            }

            $remarks = "$vat_amount Rent vat @ ";
            $coa = ChartOfAccount::getLedger(38); // Sales VAT Payable A/C
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $group_name= $coa->group_name;
            $sub = array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code, 'post_date'=>date('Y-m-d'),
                'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Sales VAT Payable A/C',
                'date'=>$issue_date,'debit'=>0,'credit'=>$vat_amount,'voucher_no'=>$voucher_no,
                'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
                'ref_module'=>'Billing','created_by'=>Auth::user()->id);
            if($vat_amount!=0){
                array_push($jv,$sub);
            }
            if($request->input('bill_type')=='Service Charge'){
                $coa = ChartOfAccount::getLedger(75); //Service Charge Fixed Fine
                $ledger_type= $coa->type;
                $ledger_code= $coa->system_code;
                $ledger_id= $coa->id;
                $ledger_name= $coa->head;
                $group_name= $coa->group_name;
                $sub = array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                    'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code, 'post_date'=>date('Y-m-d'),
                    'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                    'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>$ledger_name,
                    'date'=>$issue_date,'debit'=>0,'credit'=>$file_amount,'voucher_no'=>$voucher_no,
                    'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
                    'ref_module'=>'Billing','created_by'=>Auth::user()->id);
                if($file_amount!=0){
                    array_push($jv,$sub);
                }

            }else{
                $coa = ChartOfAccount::getLedger(28); // Rent Fine
                $ledger_type= $coa->type;
                $ledger_code= $coa->system_code;
                $ledger_id= $coa->id;
                $ledger_name= $coa->head;
                $group_name= $coa->group_name;
                $sub = array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                    'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code, 'post_date'=>date('Y-m-d'),
                    'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                    'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>$ledger_name,
                    'date'=>$issue_date,'debit'=>0,'credit'=>$file_amount,'voucher_no'=>$voucher_no,
                    'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
                    'ref_module'=>'Billing','created_by'=>Auth::user()->id);
                if($file_amount!=0){
                    array_push($jv,$sub);
                }

            }

            $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
            $ledger_type = $coa->type;
            $ledger_code = $coa->system_code;
            $ledger_id = $coa->id;
            $group_name= $coa->group_name;
            $sub =  array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
                'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                'customer_name'=>$customer->shop_name,'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,'remarks'=>'','ledger_head'=>'Accounts Receivable',
                'date'=>$issue_date,'debit'=>($total+$vat_amount+$file_amount),'credit'=>0,'voucher_no'=>$voucher_no,
                'ref_module'=>'Billing','created_by'=>Auth::user()->id);
            array_push($jv,$sub);
//            return $jv;
            Journal::insert($jv);
        }
        Logs::store(Auth::user()->name.'New Billing has been created successfull ','Add','success',Auth::user()->id,$income->id,'Billing');
        return redirect()->route('billing.index')->with('success','Billing has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['page_name']="Billing Invoice";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Billing','billing.index'),
            array('Show','active')
        );
        $billing = Billing::find($id);
        $totalAmount=0;
        $totalAmount = Billing::where('customer_id',$billing['customer_id'])
            ->where('id','!=',$id)
            ->where('bill_type','=',$billing['bill_type'])
            ->where('shop_no','=',$billing['shop_no'])
            ->sum('grand_total');
        $details = CashCollection::join('cash_collection_details','cash_collections.id' ,'=','cash_collection_details.ref_id')
            ->leftjoin('billings','cash_collections.income_id','=','billings.id')
            ->where('cash_collections.customer_id',$billing['customer_id'])
            ->where('cash_collections.income_id','!=',$id)
            ->where('billings.bill_type','=',$billing['bill_type'])
            ->where('billings.shop_no','=',$billing['shop_no'])
            ->selectRaw('sum(cash_collection_details.payment_amount) as payment')->first();
        $due = $totalAmount-$details->payment;

        $data['due']= $due;
        $data['income']= $billing;
        $data['assetShop'] = Asset::where('asset_no',$billing->shop_no)->first();
        $data['details']= BillingDetail::where('billing_id',$id)->get();
        return view('admin.billing.details',$data);
    }
    /**
     * show journal
     * @param int $id
     */
    public function journal($id){
        $data['page_name']="Show Journal";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Billing','billing.index'),
            array('Show','active')
        );
        $data['journal']= Billing::find($id);
        $data['details']= BillingDetail::where('billing_id',$id)->get();
        $data['effective_date']= $data['details'][0]['effective_date'];
        return view('admin.billing.journal',$data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Billing";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Billing','billing.index'),
            array('Edit','active')
        );
        $check =  CashCollection::where('income_id',$id)->get();
        if (count($check) > 0){
            return back()->with('warning',  'Can  not edit data, Already payment this bill!');
        }
        $row = BillingDetail::where('billing_id','=',$id)->select('ledger_id as income_head_id','pre_reading','current_reading','kwt','kwt_rate','ledger_name as income_head','vat','vat_amount as vat_amt','area_sft as area','rate_sft as rate','total','month','remarks','amount')->get();
        $m = date('d-m-Y');
        $bill = Billing::find($id);
        $array = array();
        foreach ($row as $r){
            $r['bill_type'] = $bill->bill_type;
            array_push($array,$r );
        }

        $day = date('M Y',strtotime("-1 months"));
        $previous_month = BillingDetail::where('month','=',$day)->first();
        $data['pre_month']= $previous_month !=null?$previous_month->pre_reading:"";
        $data['customer']= Asset::orderBy('asset_no','ASC')->get();
        $data['employee']= Employee::all();
        $data['income_head']= ChartOfAccount::where('type','=','Income')->orderBy('head','ASC')->get();
        $data['details'] = json_encode($array);
        $data['editData']= $bill;
        return view('admin.billing.edit',$data);
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
        $assetId = Asset::find($request->input('customer_id'));
        $check =  CashCollection::where('income_id',$id)->get();
        if (count($check) > 0){
            return back()->with('warning',  'Can not edit data, Already  payment this bill!');
        }
        $income = Billing::find($id);
        $checkData = $request->all();
        $checkData['customer_id'] = $assetId->customer_id;
        $customer = Customer::find($assetId->customer_id);
        $invoice_no = $income->invoice_no;
        $voucher_no = $income->voucher_no;
        $customer_id = $assetId->customer_id;
        $shop_no = $assetId->asset_no;
        $check = Billing::where('shop_no','=',$assetId->asset_no)->count();
        $check = $check+1;
        $issue_date = $request->input('issue_date');
        $journal_date = $request->input('journal_date');
        $total = $request->input('total');
        $vat_amount = $request->input('vat_amount_total');
        if($request->input('bill_type')=='Income'){
            $income = $this->insertIncomeEntry($checkData,$invoice_no,$customer,$voucher_no,$id);
        }
        else if($request->input('bill_type')=='Electricity'){
              $income =  $this->insertElectricityEntry($checkData,$invoice_no,$customer,$voucher_no,$id);
        }else{
//            insert billing form

            $income->customer_id = $customer_id;
            $income->bill_type = $request->input('bill_type');
            $income->shop_no = $shop_no;
            $income->shop_name = $customer->shop_name;
            $income->person_id = $request->input('person_id');
            $income->issue_date = $issue_date;
            $income->journal_date = $journal_date;
            $income->due_date = $request->input('due_date');
            $income->credit_period = $request->input('credit_period');
            $income->invoice_no = $invoice_no;
            $income->voucher_no = $voucher_no;
            $income->vat = $request->input('vat');
            $income->vat_amount = $vat_amount ;
            $income->post_date = date('Y-m-d') ;
            $income->total = $total;
            $income->grand_total = $request->input('grand_total');
            $income->created_by = Auth::user()->id;
            $income->save();
            $income_id = $income->id;
            $data = json_decode($checkData['accountRecord'],true);
            $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
            $jv = array(
            );
            BillingDetail::where('billing_id',$id)->delete();
            Journal::where('ref_id',$income_id)->where('ref_module','=','Billing')->delete();
            foreach ($data as $r){
                $details = new BillingDetail();
                $details->billing_id = $income_id;
                $details->ledger_name = $r['income_head'];
                $details->ledger_id = $r['income_head_id'];
                $details->month = $r['month'];

                $details->amount = $r['amount'];
                $details->area_sft = $r['area'];
                $details->rate_sft = $r['rate'];
                $details->vat = $r['vat'];
                $details->vat_amount = $r['vat_amt'];
                $details->total = $r['total'];
                $details->current_reading = '';
                $details->pre_reading = '';
                $details->kwt = '';
                $details->kwt_rate = '';

                $effective = $journal_date;

                $a = $r['area']* $r['rate'];
                if($request->input('bill_type')=='Service Charge'){
                    $remarks = "Service Charge for Shop No# $shop_no for the month of $r[month] for $r[area] sft @ Tk. $r[rate]";

                }else{
                    $remarks = "Rent for Shop No# $shop_no for the month of $r[month] for $r[area] sft @ Tk. $r[rate]";

                }
                $details->remarks = $remarks;
                $details->effective_date = $effective;
                $details->save();
                $coa = ChartOfAccount::getLedger($r['income_head_id']);
                $ledger_type= $coa->type;
                $ledger_code= $coa->system_code;
                $ledger_id= $coa->id;
                $group_name= $coa->group_name;
                $sub = array('ref_id'=>$income_id, 'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                    'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
                    'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                    'customer_name'=>$customer->shop_name,'remarks'=>$remarks,'ledger_head'=>$r['income_head'],
                    'date'=>$issue_date,'debit'=>0,'credit'=>$r['amount'],'voucher_no'=>$voucher_no,
                    'shop_no'=>$shop_no,'customer_id'=>$customer_id,
                    'ref_module'=>'Billing','created_by'=>Auth::user()->id);
                array_push($jv,$sub);
            }

            $remarks = "$vat_amount Rent vat @ ";
            $coa = ChartOfAccount::getLedger(38); // Sales VAT Payable A/C
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $group_name= $coa->group_name;
            $sub = array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code, 'post_date'=>date('Y-m-d'),
                'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Sales VAT Payable A/C',
                'date'=>$issue_date,'debit'=>0,'credit'=>$vat_amount,'voucher_no'=>$voucher_no,
                'shop_no'=>$shop_no,'customer_id'=>$customer_id,
                'ref_module'=>'Billing','created_by'=>Auth::user()->id);
            if($vat_amount!=0){
                array_push($jv,$sub);
            }
            $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
            $ledger_type = $coa->type;
            $ledger_code = $coa->system_code;
            $ledger_id = $coa->id;
            $group_name= $coa->group_name;
            $sub =  array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
                'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
                'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Accounts Receivable',
                'date'=>$issue_date,'debit'=>($total+$vat_amount),'credit'=>0,'voucher_no'=>$voucher_no,
                'shop_no'=>$shop_no,'customer_id'=>$customer_id,
                'ref_module'=>'Billing','created_by'=>Auth::user()->id);
            array_push($jv,$sub);
//            return $jv;
            Journal::insert($jv);
        }

        Logs::store(Auth::user()->name.' Billing has been updated successfull ','Add','success',Auth::user()->id,$income->id,'Billing');
        return redirect()->route('billing.index')->with('success','Billing has been updated successfully.');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Billing = Billing::find($id);
        $details = BillingDetail::where('billing_id',$id)->delete();
        Journal::where('ref_id',$id)->where('ref_module','=',$Billing->module)->delete();
        $Billing->delete();
        Logs::store(Auth::user()->name.'Billing has been delete successfull ','Delete','success',Auth::user()->id,$Billing->id,'Billing');
        return redirect()->route('billing.index')->with('success','Billing has been delete successfully.');

    }
    public function getCustomer($id){
        $assetId = Asset::find($id);
        $meter = Meter::where('asset_no',$assetId->asset_no)->first();
        $customer = Customer::find($assetId->customer_id);
        $previous_month = BillingDetail::leftjoin('billings', 'billings.id', '=', 'billing_details.billing_id')
            ->where('billings.customer_id', '=', $meter->customer_id)
            ->where('billings.meter_no', '=', $meter->meter_no)
            ->select('billing_details.*')
            ->orderBy('billings.id','desc')
            ->first();
        $meter['pre_month'] = $previous_month != null ? $previous_month->current_reading : $meter->opening_reading;

        echo json_encode(array('customer'=>$customer,'assets'=>$assetId,'meter'=>$meter));
    }
    public function insertIncomeEntry($checkData,$invoice_no,$customer,$voucher_no,$flag=0) {
        // Income  Entry form
        $issue_date = $checkData['issue_date'];
        $journal_date = $checkData['journal_date'];
        $total = $checkData['i_total'];
        $customer_id = $checkData['customer_id'];
        $vat_amount = $checkData['vat_amount_total'];
        if($flag) {
            $income =  Billing::find($flag);
            BillingDetail::where('billing_id',$flag)->delete();
            Journal::where('ref_id',$flag)->where('ref_module','=','Billing')->delete();
        }else{
            $income = new Billing();
        }
        $income->customer_id = $checkData['customer_id'];
        $income->bill_type = $checkData['bill_type'];
        $income->shop_no = $customer->shop_no;
        $income->shop_name = $customer->shop_name;
        $income->person_id = $checkData['person_id'];
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->due_date = $checkData['due_date'];
        $income->credit_period = $checkData['credit_period'];
        $income->invoice_no = $invoice_no;
        $income->voucher_no = $voucher_no;
        $income->vat =$checkData['vat'];
        $income->vat_amount = $vat_amount ;
        $income->post_date = date('Y-m-d') ;
        $income->total = $total;
        $income->grand_total = $checkData['grand_total'];
        $income->created_by = Auth::user()->id;

        $income->save();
        $income_id = $income->id;
        $data = json_decode($checkData['accountRecord'],true);
        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        $jv = array(
        );

        foreach ($data as $r){
            $details = new BillingDetail();
            $details->billing_id = $income_id;
            $details->ledger_name = $r['income_head'];
            $details->ledger_id = $r['income_head_id'];
            $details->month = $r['month'];
            $details->amount = $r['amount'];
            $details->vat = $r['vat'];
            $details->vat_amount = $r['vat_amt'];
            $details->total = $r['total'];
            $details->current_reading = '';
            $details->pre_reading = '';
            $details->kwt = '';
            $details->area_sft = '';
            $details->rate_sft = '';
            $details->rate_sft = '';
            $details->kwt_rate = '';
            $effective = $journal_date;
            $remarks = $r['remarks'];
            $details->effective_date = $effective;
            $details->remarks = $remarks;
            $details->save();
            $coa = ChartOfAccount::getLedger($r['income_head_id']);
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $group_name= $coa->group_name;
            $sub = array('ref_id'=>$income_id, 'group_name'=>$group_name, 'ledger_id'=>$ledger_id,
                'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
                'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                'customer_name'=>$customer->shop_name,'remarks'=>$remarks,'ledger_head'=>$r['income_head'],
                'date'=>$issue_date,'debit'=>0,'credit'=>$r['amount'],'voucher_no'=>$voucher_no,
                'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
                'ref_module'=>'Billing','created_by'=>Auth::user()->id);
            array_push($jv,$sub);
        }

        $remarks = "$vat_amount Rent vat @ ";
        $coa = ChartOfAccount::getLedger(38); //Sales VAT Payable A/C
        $ledger_type= $coa->type;
        $ledger_code= $coa->system_code;
        $ledger_id= $coa->id;
        $group_name= $coa->group_name;
        $sub = array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
            'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code, 'post_date'=>date('Y-m-d'),
            'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
            'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Sales VAT Payable A/C',
            'date'=>$issue_date,'debit'=>0,'credit'=>$vat_amount,'voucher_no'=>$voucher_no,
            'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
            'ref_module'=>'Billing','created_by'=>Auth::user()->id);
        if($vat_amount!=0){
            array_push($jv,$sub);
        }
        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name= $coa->group_name;
        $sub =  array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
            'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
            'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
            'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Accounts Receivable',
            'date'=>$issue_date,'debit'=>($total+$vat_amount),'credit'=>0,'voucher_no'=>$voucher_no,
            'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
            'ref_module'=>'Billing','created_by'=>Auth::user()->id);
        if(($total+$vat_amount) > 0){
            array_push($jv,$sub);
        }
        Journal::insert($jv);
        return $income;

    }
    public function insertElectricityEntry($checkData,$invoice_no,$customer,$voucher_no,$flag) {
        // Electricity Income  Entry form
//        return $checkData;
        $issue_date = $checkData['issue_date'];
        $journal_date = $checkData['journal_date'];
        $total = $checkData['e_total'];
        $customer_id = $checkData['customer_id'];
        $vat_amount = $checkData['vat_amount_total'];
        if($flag){

            $income =  Billing::find($flag);
            BillingDetail::where('billing_id',$flag)->delete();
            Journal::where('ref_id',$flag)->where('ref_module','=','Billing')->delete();
        }else{
            $income = new Billing();
        }

        $income->customer_id = $checkData['customer_id'];
        $income->bill_type = $checkData['bill_type'];
        $income->shop_no = $customer->shop_no;
        $income->shop_name = $customer->shop_name;
        $income->person_id = $checkData['person_id'];
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->due_date = $checkData['due_date'];
        $income->credit_period = $checkData['credit_period'];
        $income->invoice_no = $invoice_no;
        $income->voucher_no = $voucher_no;
        $income->vat =$checkData['vat'];
        $income->vat_amount = $vat_amount ;
        $income->post_date = date('Y-m-d') ;
        $income->total = $total;
        $income->grand_total = $checkData['grand_total'];
        $income->created_by = Auth::user()->id;

        $income->save();

            $income_id = $income->id;


        $data = json_decode($checkData['accountRecord'],true);
        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        $jv = array();

        foreach ($data as $r){
            $details = new BillingDetail();
            $details->billing_id = $income_id;
            $details->ledger_name = $r['income_head'];
            $details->ledger_id = $r['income_head_id'];
            $details->month = $r['month'];
            $details->amount = $r['amount'];
            $details->vat = $r['vat'];
            $details->vat_amount = $r['vat_amt'];
            $details->total = $r['total'];
            $details->current_reading = $r['current_reading'];
            $details->pre_reading = $r['pre_reading'];
            $details->kwt = $r['kwt'];
            $details->area_sft = '';
            $details->rate_sft = '';
            $details->rate_sft = '';
            $details->kwt_rate = $r['kwt_rate'];
            $m = explode(" ",$r['month']);
            $effective = $journal_date;
            $remarks = "Electricity bill for Shop No# $customer->shop_no for the month of $r[month] for  meter no# ".($r['meter_no']??'' );
            $details->effective_date = $effective;
            $details->remarks = $remarks;
            $details->save();
            $coa = ChartOfAccount::getLedger($r['income_head_id']);
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $group_name= $coa->group_name;
            $sub = array('ref_id'=>$income_id, 'group_name'=>$group_name, 'ledger_id'=>$ledger_id,
                'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
                'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                'customer_name'=>$customer->shop_name,'remarks'=>$remarks,'ledger_head'=>$r['income_head'],
                'date'=>$issue_date,'debit'=>0,'credit'=>$r['amount'],'voucher_no'=>$voucher_no,
                'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
                'ref_module'=>'Billing','created_by'=>Auth::user()->id);
            array_push($jv,$sub);
        }

        $remarks = "$vat_amount Rent vat @ ";
        $coa = ChartOfAccount::getLedger(38); //Sales VAT Payable A/C
        $ledger_type= $coa->type;
        $ledger_code= $coa->system_code;
        $ledger_id= $coa->id;
        $group_name= $coa->group_name;
        $sub = array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
            'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code, 'post_date'=>date('Y-m-d'),
            'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
            'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Sales VAT Payable A/C',
            'date'=>$issue_date,'debit'=>0,'credit'=>$vat_amount,'voucher_no'=>$voucher_no,
            'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
            'ref_module'=>'Billing','created_by'=>Auth::user()->id);
        if($vat_amount!=0){
            array_push($jv,$sub);
        }
        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name= $coa->group_name;
        $sub =  array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
            'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
            'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
            'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Accounts Receivable',
            'date'=>$issue_date,'debit'=>($total+$vat_amount),'credit'=>0,'voucher_no'=>$voucher_no,
            'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
            'ref_module'=>'Billing','created_by'=>Auth::user()->id);
        if(($total+$vat_amount) > 0){
            array_push($jv,$sub);
        }
        Journal::insert($jv);
        return $income;

    }
    public function insertAdvertisementEntry($checkData,$invoice_no,$customer,$voucher_no,$flag){
        // Income  Entry form
        $issue_date = $checkData['issue_date'];
        $journal_date = $checkData['journal_date'];
        $total = $checkData['ad_total'];
        $customer_id = $checkData['customer_id'];
        $vat_amount = 0;//$checkData['vat_amount_total'];
        if($flag) {
            $income =  Billing::find($flag);
            BillingDetail::where('billing_id',$flag)->delete();
            Journal::where('ref_id',$flag)->where('ref_module','=','Billing')->delete();
        }else{
            $income = new Billing();
        }
        $income->customer_id = $checkData['customer_id'];
        $income->bill_type = $checkData['bill_type'];
        $income->billing_period_manual = $checkData['billing_period_manual'];
        $income->shop_no = $customer->shop_no;
        $income->shop_name = $customer->shop_name;
        $income->person_id = $checkData['person_id'];
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->due_date = $checkData['due_date'];
        $income->credit_period = $checkData['credit_period'];
        $income->invoice_no = $invoice_no;
        $income->voucher_no = $voucher_no;
        $income->vat =$checkData['vat'];
        $income->vat_amount = $vat_amount ;
        $income->post_date = date('Y-m-d') ;
        $income->total = $total;
        $income->grand_total = $checkData['grand_total'];
        $income->created_by = Auth::user()->id;

        $income->save();
        $income_id = $income->id;
        $data = json_decode($checkData['accountRecord'],true);
        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        $jv = array(
        );

        foreach ($data as $r){
            $details = new BillingDetail();
            $coa = ChartOfAccount::getLedger($r['income_head_id']);
            $details->billing_id = $income_id;
            $details->ledger_name = $coa->head;
            $details->ledger_id = $r['income_head_id'];
            $details->month = $r['month'];
            $details->amount = $r['total'];
            $details->vat = 0;
            $details->vat_amount =0;
            $details->total = $r['total'];
            $details->current_reading = '';
            $details->pre_reading = '';
            $details->kwt = '';
            $details->space_name = $r['space_name'];
            $details->code = $r['code'];
            $details->area_sft = $r['area'];
            $details->rate_sft = $r['rate'];
            $details->kwt_rate = '';
            $effective = $journal_date;
            $remarks = "Advertisement space rent for  $r[space_name] & Code: $r[code] for month $r[month]";
            $details->effective_date = $effective;
            $details->remarks = $remarks;
            $details->save();

            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $income_head= $coa->head;
            $group_name= $coa->group_name;
            $sub = array('ref_id'=>$income_id, 'group_name'=>$group_name, 'ledger_id'=>$ledger_id,
                'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
                'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
                'customer_name'=>$customer->shop_name,'remarks'=>$remarks,'ledger_head'=>$income_head,
                'date'=>$issue_date,'debit'=>0,'credit'=>$r['total'],'voucher_no'=>$voucher_no,
                'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
                'ref_module'=>'Billing','created_by'=>Auth::user()->id);
            array_push($jv,$sub);
        }

        $remarks = "$vat_amount Rent vat @ ";
        $coa = ChartOfAccount::getLedger(38); //Sales VAT Payable A/C
        $ledger_type= $coa->type;
        $ledger_code= $coa->system_code;
        $ledger_id= $coa->id;
        $income_head= $coa->head;
        $group_name= $coa->group_name;
        $sub = array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
            'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code, 'post_date'=>date('Y-m-d'),
            'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
            'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Sales VAT Payable A/C',
            'date'=>$issue_date,'debit'=>0,'credit'=>$vat_amount,'voucher_no'=>$voucher_no,
            'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
            'ref_module'=>'Billing','created_by'=>Auth::user()->id);
        if($vat_amount!=0){
            array_push($jv,$sub);
        }
        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name= $coa->group_name;
        $sub =  array('ref_id'=>$income_id,'group_name'=>$group_name,'ledger_id'=>$ledger_id,
            'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),
            'effective_date'=>$effective, 'transaction_type'=> 'Billing','invoice_no'=>$invoice_no,
            'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Accounts Receivable',
            'date'=>$issue_date,'debit'=>($total+$vat_amount),'credit'=>0,'voucher_no'=>$voucher_no,
            'shop_no'=>$customer->shop_no,'customer_id'=>$customer_id,
            'ref_module'=>'Billing','created_by'=>Auth::user()->id);
        if(($total+$vat_amount) > 0){
            array_push($jv,$sub);
        }
        Journal::insert($jv);
        return $income;
    }
    public function insertFoodCourtService($checkData,$invoice_no,$customer,$voucher_no,$flag)
    {

        $customer_id = $checkData['customer_id'];
        $off_type = $checkData['bill_type'];
//        $category = $checkData['category'];
//        $total = $checkData['total'];
//        $vat_amount = $checkData['vat_amount_total'];
//        $area = $checkData['area'];
//        $rate = $checkData['rate'];
        $due_date = $checkData['due_date'];
        $month = $checkData['month'];
//        $asset_no = $checkData['asset_no'];
        $shop_no = $customer->shop_no;
        $vat_amount = $checkData['vat_amount_total'];
        $journal_date = $checkData['journal_date'];
        $issue_date = $checkData['issue_date'];
        $fine_applicable = 'No';
        $total= $checkData['i_total'];
        $assIds = Asset::where('asset_no',$shop_no)->first();
        $income = new Billing();
        $income->bill_type = "Food Court Service Charge";
        $income->customer_id = $checkData['customer_id'];
        $income->billing_period_manual = $checkData['billing_period_manual'];
        $income->shop_no = $customer->shop_no;
        $income->off_type = $assIds->off_type;
        $income->shop_name = $customer->shop_name;
        $income->person_id = $checkData['person_id'];
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->due_date = $checkData['due_date'];
        $income->credit_period = $checkData['credit_period'];
        $income->invoice_no = $invoice_no;
        $income->voucher_no = $voucher_no;
        $income->vat =$checkData['vat'];
        $income->vat_amount = $vat_amount ;
        $income->post_date = date('Y-m-d') ;
        $income->total = $total;
        $income->grand_total = $checkData['grand_total'];
        $income->created_by = Auth::user()->id;
        $income->module = 'Billing';
        $income->save();
        $income_id = $income->id;

        $jv = array();;

        $jv = array(
        );
        $data = json_decode($checkData['accountRecord'],true);
//        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        $grand_total = $checkData['grand_total'];
        foreach ($data as $r){
        $details = new BillingDetail();
            $details->billing_id = $income_id;
            $details->ledger_name = $r['income_head'];
            $details->ledger_id = $r['income_head_id'];
            $details->month = $r['month'];
//            $file_amount += $r['fine_amount'] ;
            $grand_total += $r['amount'] ;
            $month = $r['month'];

            $details->amount = $r['amount'];
            $details->fine = $r['fine_amount']??0;
            $details->area_sft = $assIds['area_sft']??0;
            $details->rate_sft = $assIds['rate']??0;
            $details->vat = $r['vat'];
            $details->vat_amount = $r['vat_amt'];
            $details->total = $r['total'];
            $details->current_reading = '';
            $details->pre_reading = '';
            $details->kwt = '';
            $details->kwt_rate = '';

            $effective = $journal_date;


            $amount = $r['amount'];
            $remarks = "Food Court Service Charge for Shop No# $shop_no for the month of $month  @ Tk. $amount";

            $details->remarks = $remarks;
            $details->effective_date = $effective;
            $details->save();

        $details->remarks = $remarks;
        $details->effective_date = $effective;
        $details->save();
            $coa = ChartOfAccount::getLedger($r['income_head_id']);
            $ledger_type = $coa->type;
            $ledger_code = $coa->system_code;
            $ledger_id = $coa->id;
            $ledger_name = $coa->head;
            $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
            'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
            'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($customer->shop_name),
            'remarks' => $remarks, 'ledger_head' => $ledger_name, 'shop_no' => $shop_no, 'customer_id' => $customer_id, 'date' => $issue_date, 'debit' => 0,
            'credit' => $amount, 'voucher_no' => $voucher_no, 'ref_module' => 'Billing',
            'created_by' => Auth::user()->id);
        array_push($jv, $sub);
        }

        $remarks = "$vat_amount Rent vat @ ";
        $coa = ChartOfAccount::getLedger(38); // Sales VAT Payable A/C
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_id, 'remarks' => '', 'ledger_head' => 'Sales VAT Payable A/C',
            'date' => $issue_date, 'debit' => 0, 'credit' => $vat_amount, 'voucher_no' => $voucher_no,
            'ref_module' => 'Billing', 'created_by' => Auth::user()->id);
        if ($vat_amount != 0) {
            array_push($jv, $sub);
        }
        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_id, 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
            'date' => $issue_date, 'debit' => $grand_total, 'credit' => 0, 'voucher_no' => $voucher_no,
            'ref_module' => 'Billing', 'created_by' => Auth::user()->id);
        array_push($jv, $sub);
        Journal::insert($jv);
        return $income;

    }
    public function insertSCService($checkData,$invoice_no,$customer,$voucher_no,$flag)
    {

        $customer_id = $checkData['customer_id'];
        $off_type = $checkData['bill_type'];
//        $category = $checkData['category'];
//        $total = $checkData['total'];
//        $vat_amount = $checkData['vat_amount_total'];
//        $area = $checkData['area'];
//        $rate = $checkData['rate'];
        $due_date = $checkData['due_date'];
        $month = $checkData['month'];
//        $asset_no = $checkData['asset_no'];
        $shop_no = $customer->shop_no;
        $vat_amount = $checkData['vat_amount_total'];
        $journal_date = $checkData['journal_date'];
        $issue_date = $checkData['issue_date'];
        $fine_applicable = 'No';
        $total= $checkData['i_total'];
        $assIds = Asset::where('asset_no',$shop_no)->first();
        $income = new Billing();
        $income->bill_type = "Special Service Charge";
        $income->customer_id = $checkData['customer_id'];
        $income->billing_period_manual = $checkData['billing_period_manual'];
        $income->shop_no = $customer->shop_no;
        $income->off_type = $assIds->off_type;
        $income->shop_name = $customer->shop_name;
        $income->person_id = $checkData['person_id'];
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->due_date = $checkData['due_date'];
        $income->credit_period = $checkData['credit_period'];
        $income->invoice_no = $invoice_no;
        $income->voucher_no = $voucher_no;
        $income->vat =$checkData['vat'];
        $income->vat_amount = $vat_amount ;
        $income->post_date = date('Y-m-d') ;
        $income->total = $total;
        $income->grand_total = $checkData['grand_total'];
        $income->created_by = Auth::user()->id;
        $income->module = 'Billing';
        $income->save();
        $income_id = $income->id;

        $jv = array();;

        $jv = array(
        );
        $data = json_decode($checkData['accountRecord'],true);
//        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        $grand_total = $checkData['grand_total'];
        foreach ($data as $r){
            $details = new BillingDetail();
            $details->billing_id = $income_id;
            $details->ledger_name = $r['income_head'];
            $details->ledger_id = $r['income_head_id'];
            $details->month = $r['month'];
//            $file_amount += $r['fine_amount'] ;
            $grand_total += $r['amount'] ;
            $month = $r['month'];

            $details->amount = $r['amount'];
            $details->fine = $r['fine_amount']??0;
            $details->area_sft = $assIds['area_sft']??0;
            $details->rate_sft = $assIds['rate']??0;
            $details->vat = $r['vat'];
            $details->vat_amount = $r['vat_amt'];
            $details->total = $r['total'];
            $details->current_reading = '';
            $details->pre_reading = '';
            $details->kwt = '';
            $details->kwt_rate = '';

            $effective = $journal_date;


            $amount = $r['amount'];
            $remarks = "Special Service Charge for Shop No# $shop_no for the month of $month  @ Tk. $amount";

            $details->remarks = $remarks;
            $details->effective_date = $effective;
            $details->save();

            $details->remarks = $remarks;
            $details->effective_date = $effective;
            $details->save();
            $coa = ChartOfAccount::getLedger($r['income_head_id']);
            $ledger_type = $coa->type;
            $ledger_code = $coa->system_code;
            $ledger_id = $coa->id;
            $ledger_name = $coa->head;
            $group_name = $coa->group_name;
            $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
                'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
                'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($customer->shop_name),
                'remarks' => $remarks, 'ledger_head' => $ledger_name, 'shop_no' => $shop_no, 'customer_id' => $customer_id, 'date' => $issue_date, 'debit' => 0,
                'credit' => $amount, 'voucher_no' => $voucher_no, 'ref_module' => 'Billing',
                'created_by' => Auth::user()->id);
            array_push($jv, $sub);
        }

        $remarks = "$vat_amount Rent vat @ ";
        $coa = ChartOfAccount::getLedger(38); // Sales VAT Payable A/C
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_id, 'remarks' => '', 'ledger_head' => 'Sales VAT Payable A/C',
            'date' => $issue_date, 'debit' => 0, 'credit' => $vat_amount, 'voucher_no' => $voucher_no,
            'ref_module' => 'Billing', 'created_by' => Auth::user()->id);
        if ($vat_amount != 0) {
            array_push($jv, $sub);
        }
        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_id, 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
            'date' => $issue_date, 'debit' => $grand_total, 'credit' => 0, 'voucher_no' => $voucher_no,
            'ref_module' => 'Billing', 'created_by' => Auth::user()->id);
        array_push($jv, $sub);
        Journal::insert($jv);
        return $income;
    }




}
