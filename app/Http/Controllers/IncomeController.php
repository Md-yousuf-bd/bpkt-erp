<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\Journal;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Income List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Income','active'),
            array('List','active')
        );
        return view('admin.income.index',$data);
    }
    /**
     * list data
     */
    public function listData(Request $request){
        $income=Income::query();
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
                if(auth()->user()->can('edit-income')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('income.edit',[$row->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if(auth()->user()->can('read-income')){
                    $option .='<div style="padding-left:5px;padding-right:5px;float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="'.route('income.show',[$row->id]).'"  ><span class="fa fa-edit">  Invoice</i></a></div>';
                }
                if (auth()->user()->can('delete-income')){
                    $option .= '<div style="padding-left:5px; ">
                                    <form action="'.route('income.destroy',[$row->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                if(auth()->user()->can('read-income')){
                    $option .='<div style="margin-top: 3px; float: left"><a style="color:#fff !important;" class="btn btn-xs btn-warning text-white text-sm" href="'.route('income.journal',[$row->id]).'"  >Journal Voucher</i></a></div>';
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
        $data['page_name']="Add Income";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Income','income.index'),
            array('Add','active')
        );
        $data['customer']= Customer::all();
        $data['income_head']= ChartOfAccount::where('type','=','Income')->get();
        return view('admin.income.create',$data);
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
        $customer = Customer::find($request->input('customer_id'));
        $invoice_no = date('my');
        $invoice_no .= '-'.$customer->shop_no;
        $count = Income::count();
        $count++;
        $voucher_no = "SV/".date('y')."/".date('m').'/'.$count;

        $check = Income::where('shop_no','=',$customer->shop_no)->get();
        $count = Income::all();
         if(count($check) >= 0 && count($check) < 10){
             $invoice_no .= '-0000'.count($check);
         }elseif (count($check) >= 10 && count($check) < 100){
             $invoice_no .= '-000'.count($check);
         }elseif (count($check) >= 100 && count($check) < 1000){
             $invoice_no .= '-00'.count($check);
         }elseif (count($check) >= 1000 && count($check) < 10000){
             $invoice_no .= '-0'.count($check);
         }else{
             $invoice_no .= '-'.count($check);
         }

        if(count($count) >= 0 && count($count) < 10){
            $invoice_no .= '-0000'.count($count);
        }elseif (count($count) >= 10 && count($count) < 100){
            $invoice_no .= '-000'.count($count);
        }elseif (count($count) >= 100 && count($count) < 1000){
            $invoice_no .= '-00'.count($count);
        }elseif (count($count) >= 1000 && count($count) < 10000){
            $invoice_no .= '-0'.count($count);
        }else{
            $invoice_no .= '-'.count($count);
        }
        $issue_date = $request->input('issue_date');
        $total = $request->input('total');
        $vat_amount = $request->input('vat_amount_total');

        $income = new Income();
        $income->customer_id = $request->input('customer_id');
        $income->shop_no = $customer->shop_no;
        $income->shop_name = $customer->shop_name;
        $income->person_id = $request->input('person_id');
        $income->issue_date = $issue_date;
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

        foreach ($data as $r){
            $details = new IncomeDetail();
            $details->income_id = $income_id;
            $details->income_head = $r['income_head'];
            $details->income_head_id = $r['income_head_id'];
            $details->month = $r['month'];
            $details->remarks = $r['remarks'];
            $details->amount = $r['amount'];
            $details->vat = $r['vat'];
            $details->vat_amount = $r['vat_amt'];
            $details->total = $r['total'];
            $m = explode(" ",$r['month']);
            $i = array_search($m[0],$month);
            $effective = '';
                if($i<10){
                    $effective = $m[1].'-0'.($i+1).'-01';
                }else{
                    $effective   = $m[1].'-'.($i+1).'-01';
                }
            $details->effective_date = $effective;
            $details->save();
            $coa = ChartOfAccount::getCoaType($r['income_head']);
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $sub = array('ref_id'=>$income_id, 'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'),'effective_date'=>$effective, 'transaction_type'=> 'Income','invoice_no'=>$invoice_no,'customer_name'=>$customer->shop_name,'remarks'=>$r['remarks'],'ledger_head'=>$r['income_head'],'date'=>$issue_date,'debit'=>0,'credit'=>$r['amount'],'voucher_no'=>$voucher_no,'ref_module'=>'Income','created_by'=>Auth::user()->id);
            array_push($jv,$sub);
        }

        $coa = ChartOfAccount::getCoaType('Sales VAT Payable A/C');
        $ledger_type= $coa->type;
        $ledger_code= $coa->system_code;
        $ledger_id= $coa->id;
        $sub = array('ref_id'=>$income_id,'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type, 'ledger_code'=>$ledger_code, 'post_date'=>date('Y-m-d'), 'effective_date'=>$effective, 'transaction_type'=> 'Income','invoice_no'=>$invoice_no,'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Sales VAT Payable A/C','date'=>$issue_date,'debit'=>0,'credit'=>$vat_amount,'voucher_no'=>$voucher_no,'ref_module'=>'Income','created_by'=>Auth::user()->id);
        if($vat_amount!=0){
               array_push($jv,$sub);
           }
        $coa = ChartOfAccount::getCoaType('Accounts Receivable');
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $sub =  array('ref_id'=>$income_id,'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'post_date'=>date('Y-m-d'), 'effective_date'=>$effective, 'transaction_type'=> 'Income','invoice_no'=>$invoice_no,'customer_name'=>$customer->shop_name,'remarks'=>'','ledger_head'=>'Accounts Receivable','date'=>$issue_date,'debit'=>($total+$vat_amount),'credit'=>0,'voucher_no'=>$voucher_no,'ref_module'=>'Income','created_by'=>Auth::user()->id);
         array_push($jv,$sub);
        Journal::insert($jv);
        Logs::store(Auth::user()->name.'New Income has been created successfull ','Add','success',Auth::user()->id,$income->id,'Income');
        return redirect()->route('income.index')->with('success','Income has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['page_name']="Income Invoice";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Income','income.index'),
            array('Show','active')
        );
        $data['income']= Income::find($id);
        $data['details']= IncomeDetail::where('income_id',$id)->get();
        return view('admin.income.details',$data);
    }
    /**
     * show journal
     * @param int $id
     */
    public function journal($id){
        $data['page_name']="Show Journal";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Income','income.index'),
            array('Show','active')
        );
        $data['journal']= Income::find($id);
        $data['details']= IncomeDetail::where('income_id',$id)->get();
        $data['effective_date']= $data['details'][0]['effective_date'];
        return view('admin.income.journal',$data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Income";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Income','income.index'),
            array('Edit','active')
        );
        $data['customer']= Customer::all();
        $data['income_head']= ChartOfAccount::where('type','=','Income')->get();
        $row = IncomeDetail::where('income_id','=',$id)->select('income_head_id','income_head','vat','vat_amount as vat_amt','total','month','remarks','amount')->get();
        $data['details'] = json_encode($row);
        $data['editData']= Income::find($id);
        return view('admin.income.edit',$data);
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
        $customer = Customer::find($request->input('customer_id'));
        $income = Income::find($id);
        $issue_date = $request->input('issue_date');
        $total = $request->input('total');
        $vat_amount = $request->input('vat_amount');
        $voucher_no = '';

        $income->customer_id = $request->input('customer_id');
        $income->shop_no = $customer->shop_no;
        $income->shop_name = $customer->shop_name;
        $income->person_id = $request->input('person_id');
        $income->issue_date = $issue_date;
        $income->due_date = $request->input('due_date');
        $income->credit_period = $request->input('credit_period');
        $income->vat = $request->input('vat');
        $income->vat_amount = $vat_amount ;
        $income->total = $total;
        $income->grand_total = $request->input('grand_total');
        $income->updated_by = Auth::user()->id;
        $income->save();

        $income_id = $id;
        $data = json_decode($checkData['accountRecord'],true);
        IncomeDetail::where('income_id',$id)->delete();

        foreach ($data as $r){
            $details = new IncomeDetail();
            $details->income_id = $income_id;
            $details->income_head = $r['income_head'];
            $details->income_head_id = $r['income_head_id'];
            $details->month = $r['month'];
            $details->remarks = $r['remarks'];
            $details->amount = $r['amount'];
            $details->save();
        }

        Logs::store(Auth::user()->name.'New Income has been updated successfull ','Add','success',Auth::user()->id,$income->id,'Income');
        return redirect()->route('income.index')->with('success','Income has been updated successfully.');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = Income::find($id);
        $details = IncomeDetail::where('income_id',$id)->get();
        $details->delete();
        $income->delete();
        Logs::store(Auth::user()->name.'New Income has been delete successfull ','Add','success',Auth::user()->id,$income->id,'Income');
        return redirect()->route('income.index')->with('success','Income has been delete successfully.');

    }
    public function getCustomer($id){
        $customer = Customer::find($id);
        echo json_encode($customer);
    }
}
