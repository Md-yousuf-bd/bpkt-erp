<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\GeneralLedger;
use App\Models\GeneralLedgerDetails;
use App\Models\SecurityDeposit;
use App\Models\SecurityDepositDetails;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Journal;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class SecurityDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Security Deposit List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Security Deposit','active'),
            array('List','active')
        );

        return view('admin.security-deposit.index',$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Security Deposit";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Security Deposit','security-deposit.index'),
            array('Add','active')
        );

        $data['income_head']= ChartOfAccount::orderBy('head','ASC')->get();
        $data['vendor']= Vendor::orderBy('vendor_name','ASC')->get();
        $data['customer']= Asset::orderBy('asset_no','ASC')
            ->leftjoin('customers','customers.id','=','assets.customer_id')
            ->SelectRaw('assets.*,customers.shop_name')->get();

        $data['employee']= Employee::orderBy('name','ASC')->get();
        return view('admin.security-deposit.create',$data);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listData(Request $request){
        $journal=SecurityDeposit::query();
        return DataTables::eloquent($journal)
            ->addIndexColumn()
            ->setRowId(function($row){
                return 'row_'.$row->id;
            })
            ->setRowData([
                'journal_dates' => function($row) {
                    return  otherHelper::ymd2dmy($row->journal_date?? '');
                },
                'vourcher_no' => function($row) {
                    return $row->vourcher_no ?? '';
                },
                'shop_no' => function($row) {
                    return $row->shop_no ?? '';
                }, 'shop_name' => function($row) {
                    return $row->shop_name ?? '';
                },
                'amount' => function($row) {
                    return $row->amount ?? '';
                },
                'data-created_by' => function($row) {
                    return $row->user->name ?? '';
                },
                'data-updated_at' => function($row) {
                    return otherHelper::change_date_format($row->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('action',  function($row) {
                $option='<div style="width:170px;">';
                if(auth()->user()->can('edit-security-deposit')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('security-deposit.edit',[$row->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if(auth()->user()->can('read-security-deposit')){
                    $option .='<div style="padding-left:5px;float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="'.route('security-deposit.show',[$row->id]).'"  ><span class="fa fa-edit">  View JV</i></a></div>';
                }
                if(auth()->user()->can('read-security-deposit')){
                    $option .='<div style="margin-right:5px;float: left;   margin-top: 5px;"><a style="color:#fff !important; " class="btn btn-xs btn-warning text-white text-sm" href="'.route('security-deposit.mr',[$row->id]).'"  ><span class="fa fa-edit">  MR View </i></a></div>';
                }
                if (auth()->user()->can('delete-security-deposit')){
                    $option .= '<div style=" float: right;        margin-top: 5px;">
                                    <form action="'.route('security-deposit.destroy',[$row->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                }
                else{
                    $option .= '<div style=" "></div>';
                }
                $option .='</div>';
                return $option;
            })
            ->rawColumns(['action'])
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
        $checkData = $request->all();
        $count = SecurityDeposit::count();
        $count++;
        $voucher_no = "SD/SV/".date('y')."/".date('m').'/'.$count;
        $money_receipt_no = "SD/".date('y')."/".date('m').'/'.$count;
        $issue_date = date('Y-m-d');
        $journal_date = $request->input('journal_date');
        $total_debit = $request->input('total_debit');
        $total_credit = $request->input('total_credit');

        $gl = new SecurityDeposit();
        $gl->issue_date = $issue_date;
        $gl->journal_date = $journal_date;
        $gl->amount = $total_credit;
        $gl->money_receipt_no = $money_receipt_no;
        $gl->voucher_no = $voucher_no;
        $gl->created_by = Auth::user()->id;
        $gl->save();

            $gl_id = $gl->id;
            $data = json_decode($checkData['accountRecord'],true);
            $jv = array();

            $total=0;
            foreach ($data as $r){
                $vendor_id = $r['customer'];
                $company_type = "Customer";
                $category_id = $r["category"];
                $vendor_name = $r['customer_name'];
                $shop_no = '';
                if($company_type=='Customer'){
                    $customerData = Asset::orderBy('asset_no','ASC')
                        ->leftjoin('customers','customers.id','=','assets.customer_id')
                        ->SelectRaw('assets.*,customers.shop_name')->where('asset_no',$vendor_id)->first();
                    $vendor_id = $customerData['customer_id'];
                    $vendor_name = $customerData['shop_name'];
                    $shop_no = $customerData['asset_no'];
                }
                $main = SecurityDeposit::find($gl_id);
                $main->shop_no = $shop_no;
                $main->shop_name = $vendor_name;
                $main->category =  $category_id;
                $main->save();
                $total += $r['credit'];

                $details = new SecurityDepositDetails();
                $details->ref_id = $gl_id;
                $details->ledger_name = trim($r['ledger_name']) ;
                $details->ledger_id = $r['ledger_id'];
                $details->company_type = $company_type;
                $details->shop_no = $shop_no;
                $details->vendor_name =  trim($vendor_name);
                $details->vendor_id =  $vendor_id;
                $details->credit = $r['credit'];
                $details->journal_date =$journal_date;
                $details->voucher_no =$voucher_no;
                $effective = $journal_date;
                $remarks = $r['remarks'];
                $payment_ref = $r['payment_ref'];
                $details->remarks = $remarks;
                $details->payment_ref = $payment_ref;
                $details->created_by = Auth::user()->id;
                $details->issue_date = $issue_date;
                $coa = ChartOfAccount::getLedger($r['ledger_id']);
                $ledger_type= $coa->type;
                $ledger_code= $coa->system_code;
                $ledger_id= $coa->id;
                $ledger_name= $coa->head;
                $group_name= $coa->group_name;
                $details->ledger_type = $ledger_type;
                if($gl_id!=0){
                    $details->save();
                }
                $sub = array('ref_id'=>$gl_id, 'payment_ref'=>$payment_ref, 'staff_name'=>"", 'group_name'=>$group_name,
                    'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'shop_no'=>$shop_no,
                    'post_date'=>date('Y-m-d'),'effective_date'=>$effective, 'transaction_type'=> 'Security Deposit',
                    'invoice_no'=>'','customer_name'=>trim($vendor_name)??"",'remarks'=>$remarks,'customer_id'=>trim($vendor_id)??"",
                    'ledger_head'=>trim($ledger_name),'date'=>$issue_date,'debit'=>$r['credit'],'company_type'=>$company_type,
                    'credit'=>0,'voucher_no'=>$voucher_no,'ref_module'=>'Security Deposit','created_by'=>Auth::user()->id);
                array_push($jv,$sub);
            }

        $coa = ChartOfAccount::getLedger($category_id); // Security Deposit;
        $ledger_type= $coa->type;
        $ledger_code= $coa->system_code;
        $ledger_id= $coa->id;
        $group_name= $coa->group_name;
        $ledger_name= $coa->head;
        $details->ledger_type = $ledger_type;
        $sub = array('ref_id'=>$gl_id, 'payment_ref'=>$payment_ref, 'staff_name'=>"", 'group_name'=>$group_name,
            'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'shop_no'=>$shop_no,
            'post_date'=>date('Y-m-d'),'effective_date'=>$effective, 'transaction_type'=> 'Security Deposit',
            'invoice_no'=>'','customer_name'=>trim($vendor_name)??"",'remarks'=>$remarks,'customer_id'=>trim($vendor_id)??"",
            'ledger_head'=>trim($ledger_name),'date'=>$issue_date,'debit'=>0,'company_type'=>$company_type,
            'credit'=>$total,'voucher_no'=>$voucher_no,'ref_module'=>'Security Deposit','created_by'=>Auth::user()->id);
        array_push($jv,$sub);
            if(!empty($jv)){
                Journal::insert($jv);
            }
        Logs::store(Auth::user()->name.'New Security Deposit has been created successfull ','Add','success',Auth::user()->id,$gl_id,'Security Deposit');

        return redirect()->route('security-deposit.index')->with('success','Security Deposit has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['page_name']="Show Journal";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Security Deposit','security-deposit.index'),
            array('Show','active')
        );
        $data['journal']= SecurityDeposit::find($id);
        $data['details']= Journal::where('ref_id',$id)->where('ref_module','Security Deposit')->orderBy('debit','desc')->get();
        return view('admin.security-deposit.journal',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Security Deposit";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Security Deposit','security-deposit.index'),
            array('Edit','active')
        );

        $data['income_head']= ChartOfAccount::orderBy('head','ASC')->get();
        $data['vendor']= Vendor::orderBy('vendor_name','ASC')->get();
        $data['employee']= Employee::orderBy('name','ASC')->get();
        $data['customer']= Asset::orderBy('asset_no','ASC')
            ->leftjoin('customers','customers.id','=','assets.customer_id')
            ->SelectRaw('assets.*,customers.shop_name')->get();

        $data['editData']= SecurityDeposit::find($id);
        $d = SecurityDepositDetails::where('ref_id',$id)->get();
        $array = array();
        foreach ($d as $r){
            if($r['company_type']=='Vendor'){
                $r['vendor'] = $r['vendor_id'];
                $r['vendor_name'] = $r['vendor_name'];
                $r['customer_name'] = '';
                $r['customer'] ='';
            }else{
                $r['asset_no'] = $r['shop_no'];
                $r['customer'] = $r['shop_no'];
                $r['customer_name'] = $r['shop_no'].' - '.$r['vendor_name'];
                $r['vendor_name'] = '';
                $r['vendor'] = '';
            }
            array_push($array,$r);
        }
        $data['details']= json_encode($array);
        return view('admin.security-deposit.edit',$data);
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

        $journal_date = $checkData['journal_date'];
        $issue_date = date('Y-m-d');//$checkData['issue_date'];
        $total_debit = $checkData['total_debit'];
        $total_credit = $checkData['total_credit'];
        $gl = GeneralLedger::find($id);
        $voucher_no = $gl->voucher_no;
        $gl->journal_date = $journal_date;
        $gl->issue_date = $issue_date;
        $gl->total_debit = $total_debit;
        $gl->total_credit = $total_credit;
        $gl->updated_by = Auth::user()->id;
        $gl->updated_at = date('Y-m-d H:i:s');
        $gl->save();

        $gl_id = $id;

        $data = json_decode($checkData['accountRecord'],true);
        $jv = array();
        $issue_date = date('Y-m-d');
        $backup = GeneralLedgerDetails::where('ref_id',$id)->get();
        $msg = json_encode($backup);
        GeneralLedgerDetails::where('ref_id',$id)->delete();
        foreach ($data as $r){
            $vendor_id = $r['vendor']!=''?$r['vendor']:$r['customer'];
            $company_type = $r['vendor']!=''?"Vendor":"Customer";
            $vendor_name = $r['vendor']!=''?$r['vendor_name']:$r['customer_name'];
            $shop_no = '';
            if($company_type=='Customer'){
                $customerData = Asset::orderBy('asset_no','ASC')
                    ->leftjoin('customers','customers.id','=','assets.customer_id')
                    ->SelectRaw('assets.*,customers.shop_name')->where('asset_no',$vendor_id)->first();
                $vendor_id = $customerData['customer_id'];
                $vendor_name = $customerData['shop_name'];
                $shop_no = $customerData['asset_no'];
            }
            $details = new GeneralLedgerDetails();
            $details->ref_id = $gl_id;
            $details->ledger_name = trim($r['ledger_name']) ;
            $details->ledger_id = $r['ledger_id'];
            $details->company_type = $company_type;
            $details->shop_no = $shop_no;
            $details->vendor_name =  trim($vendor_name);
            $details->vendor_id =  $vendor_id;
            $details->staff_name = isset($r['staff_name'])?$r['staff_name']:"";
            $details->staff_id = $r['staff_id']??"";
            $details->debit = $r['debit'];
            $details->credit = $r['credit'];
            $details->journal_date =$journal_date;
            $details->voucher_no =$voucher_no;
            $effective = $journal_date;
            $remarks = $r['remarks'];
            $payment_ref = $r['payment_ref'];
            $details->remarks = $remarks;
            $details->payment_ref = $payment_ref;
            $details->created_by = Auth::user()->id;
            $details->issue_date = $issue_date;
            $coa = ChartOfAccount::getLedger($r['ledger_id']);
            $ledger_type= $coa->type;
            $ledger_code= $coa->system_code;
            $ledger_id= $coa->id;
            $group_name= $coa->group_name;
            $details->ledger_type = $ledger_type;
            if($gl_id!=0){
                $details->save();
            }
            $sub = array('ref_id'=>$gl_id, 'payment_ref'=>$payment_ref, 'staff_name'=>trim($r['staff_name'])??"", 'group_name'=>$group_name,
                'ledger_id'=>$ledger_id,'ledger_type'=>$ledger_type,'ledger_code'=>$ledger_code,'shop_no'=>$shop_no,
                'post_date'=>date('Y-m-d'),'effective_date'=>$effective, 'transaction_type'=> 'Security Deposit',
                'invoice_no'=>'','customer_name'=>trim($vendor_name)??"",'remarks'=>$remarks,'customer_id'=>trim($vendor_id)??"",
                'ledger_head'=>trim($r['ledger_name']),'date'=>$issue_date,'debit'=>$r['debit'],'company_type'=>$company_type,
                'credit'=>$r['credit'],'voucher_no'=>$voucher_no,'ref_module'=>'Security Deposit','created_by'=>Auth::user()->id);
            array_push($jv,$sub);

        }
        if(!empty($jv)){
            Journal::where('ref_id',$gl_id)->where('ref_module','Security Deposit')->delete();
            Journal::insert($jv);
        }
        Logs::store(Auth::user()->name.'Security Deposit has been updated successfull '.$msg,'Add','success',Auth::user()->id,$gl_id,'Security Deposit');

        return redirect()->route('security-deposit.index')->with('success','Security Deposit has been updated successfully.');

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = SecurityDeposit::find($id);
        $details = SecurityDepositDetails::where('ref_id',$id)->delete();
        $income->delete();
        Journal::where('ref_id',$id)->where('ref_module','Security Deposit')->delete();
        Logs::store(Auth::user()->name.'Security Deposit has been delete successfull ','Delete','success',Auth::user()->id,$income->id,'Security Deposit');
        return redirect()->route('security-deposit.index')->with('success','Security Deposit has been delete successfully.');

    }
    public function getMrView($id){
        $data['page_name']="Show Journal";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Security Deposit','security-deposit.index'),
            array('Show','active')
        );
        $data['journal']= SecurityDeposit::find($id);
           $asset = Asset::where('asset_no',$data['journal']->shop_no)->first();
        $data['customer'] = Customer::find($asset->customer_id);
        $data['details']= SecurityDepositDetails::where('ref_id',$id)->orderBy('debit','desc')->get();
        return view('admin.security-deposit.mr',$data);
    }
}
