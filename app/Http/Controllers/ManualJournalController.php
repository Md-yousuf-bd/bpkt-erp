<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\GeneralLedger;
use App\Models\GeneralLedgerDetails;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Journal;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;

class ManualJournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_name']="Manual Journal List";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Manual Journal','active'),
            array('List','active')
        );

        return view('admin.manual-journal.index',$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name']="Add Manual Journal";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Manual Journal','manual-journal.index'),
            array('Add','active')
        );

        $data['income_head']= ChartOfAccount::orderBy('head','ASC')->get();
        $data['vendor']= Vendor::orderBy('vendor_name','ASC')->get();
        $data['customer']= Asset::orderBy('asset_no','ASC')
            ->leftjoin('customers','customers.id','=','assets.customer_id')
            ->SelectRaw('assets.*,customers.shop_name')->get();

        $data['employee']= Employee::orderBy('name','ASC')->get();
        return view('admin.manual-journal.create',$data);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listData(Request $request){
        $journal=GeneralLedger::query();
        return DataTables::eloquent($journal)
            ->addIndexColumn()
            ->setRowId(function($row){
                return 'row_'.$row->id;
            })
            ->setRowData([
                'journal_date' => function($row) {
                    return $row->journal_date ?? '';
                },

                'vourcher_no' => function($row) {
                    return $row->vourcher_no ?? '';
                },

                'data-created_by' => function($row) {
                    return $row->user->name ?? '';
                },
                'data-updated_at' => function($row) {
                    return otherHelper::change_date_format($row->updated_at,true,'d-M-Y h:i A');
                },
            ])
            ->addColumn('action',  function($row) {
                $option='<div style="width:230px;">';
                if(auth()->user()->can('edit-manual-journal')){
                    $option .='<div style="float: left;"><a class="btn btn-xs btn-primary text-white text-sm" href="'.route('manual-journal.edit',[$row->id]).'"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if(auth()->user()->can('read-manual-journal')){
                    $option .='<div style="padding-left:5px;float: left;"><a class="btn btn-xs btn-success text-white text-sm" href="'.route('manual-journal.show',[$row->id]).'"  ><span class="fa fa-edit">  View JV</i></a></div>';
                }
                if (auth()->user()->can('delete-manual-journal')){
                    $option .= '<div style=" float: right">
                                    <form action="'.route('manual-journal.destroy',[$row->id]).'" method="post" class="">'.csrf_field().'<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
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
        $count = GeneralLedger::count();
        $count++;
        $voucher_no = "JV/".date('y')."/".date('m').'/'.$count;
        $issue_date = date('Y-m-d');
        $journal_date = $request->input('journal_date');
        $total_debit = $request->input('total_debit');
        $total_credit = $request->input('total_credit');

        $gl = new GeneralLedger();
        $gl->issue_date = $issue_date;
        $gl->journal_date = $journal_date;
        $gl->total_debit = $total_debit;
        $gl->total_credit = $total_credit;
        $gl->voucher_no = $voucher_no;
        $gl->created_by = Auth::user()->id;
        $gl->save();

            $gl_id = $gl->id;
            $data = json_decode($checkData['accountRecord'],true);
            $jv = array();

            foreach ($data as $r){
                $vendor_id = $r['vendor']!=''?$r['vendor']:$r['customer'];
                $company_type = $r['vendor']!=''?"vendor":"Customer";
                $vendor_name = $r['vendor']!=''?$r['vendor_name']:$r['customer_name'];
                $shop_no = '';
                if($company_type=='Customer'){
                    $customerData = Asset::orderBy('asset_no','ASC')
                        ->leftjoin('customers','customers.id','=','assets.customer_id')
                        ->SelectRaw('assets.*,customers.shop_name')->where('asset_no',$vendor_id)->first();
                    $vendor_id = $customerData['customer_id']??"";
                    $vendor_name = $customerData['shop_name']??"";
                    $shop_no = $customerData['asset_no']??"";
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
                    'post_date'=>date('Y-m-d'),'effective_date'=>$effective, 'transaction_type'=> 'Manual Journal',
                    'invoice_no'=>'','customer_name'=>trim($vendor_name)??"",'remarks'=>$remarks,'customer_id'=>trim($vendor_id)??"",
                    'ledger_head'=>trim($r['ledger_name']),'date'=>$issue_date,'debit'=>$r['debit'],'company_type'=>$company_type,
                    'credit'=>$r['credit'],'voucher_no'=>$voucher_no,'ref_module'=>'Manual Journal','created_by'=>Auth::user()->id);
                array_push($jv,$sub);
            }
            if(!empty($jv)){
                Journal::insert($jv);
            }
        Logs::store(Auth::user()->name.'New Manual Journal has been created successfull ','Add','success',Auth::user()->id,$gl_id,'Manual Journal');

        return redirect()->route('manual-journal.index')->with('success','Manual Journal has been created successfully.');

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
            array('Manual Journal','manual-journal.index'),
            array('Show','active')
        );
        $data['journal']= GeneralLedger::find($id);
        $data['details']= GeneralLedgerDetails::where('ref_id',$id)->orderBy('debit','desc')->get();
        return view('admin.manual-journal.journal',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name']="Edit Manual Journal";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Manual Journal','manual-journal.index'),
            array('Edit','active')
        );

        $data['income_head']= ChartOfAccount::orderBy('head','ASC')->get();
        $data['vendor']= Vendor::orderBy('vendor_name','ASC')->get();
        $data['employee']= Employee::orderBy('name','ASC')->get();
        $data['customer']= Asset::orderBy('asset_no','ASC')
            ->leftjoin('customers','customers.id','=','assets.customer_id')
            ->SelectRaw('assets.*,customers.shop_name')->get();

        $data['editData']= GeneralLedger::find($id);
        $d = GeneralLedgerDetails::where('ref_id',$id)->get();
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
        return view('admin.manual-journal.edit',$data);
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
                $vendor_id = $customerData['customer_id']??"";
                $vendor_name = $customerData['shop_name']??"";
                $shop_no = $customerData['asset_no']??"";
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
                'post_date'=>date('Y-m-d'),'effective_date'=>$effective, 'transaction_type'=> 'Manual Journal',
                'invoice_no'=>'','customer_name'=>trim($vendor_name)??"",'remarks'=>$remarks,'customer_id'=>trim($vendor_id)??"",
                'ledger_head'=>trim($r['ledger_name']),'date'=>$issue_date,'debit'=>$r['debit'],'company_type'=>$company_type,
                'credit'=>$r['credit'],'voucher_no'=>$voucher_no,'ref_module'=>'Manual Journal','created_by'=>Auth::user()->id);
            array_push($jv,$sub);

        }
        if(!empty($jv)){
            Journal::where('ref_id',$gl_id)->where('ref_module','Manual Journal')->delete();
            Journal::insert($jv);
        }
        Logs::store(Auth::user()->name.'Manual Journal has been updated successfull '.$msg,'Add','success',Auth::user()->id,$gl_id,'Manual Journal');

        return redirect()->route('manual-journal.index')->with('success','Manual Journal has been updated successfully.');

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = GeneralLedger::find($id);
        $details = GeneralLedgerDetails::where('ref_id',$id)->delete();
        $income->delete();
        Journal::where('ref_id',$id)->where('ref_module','Manual Journal')->delete();
        Logs::store(Auth::user()->name.'Manual Journal has been delete successfull ','Delete','success',Auth::user()->id,$income->id,'Manual Journal');
        return redirect()->route('manual-journal.index')->with('success','Manual Journal has been delete successfully.');

    }
}
