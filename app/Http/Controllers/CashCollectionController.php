<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Billing;
use App\Models\BillingDetail;
use App\Models\CashCollection;
use App\Models\CashCollectionDetail;
use App\Models\SecurityDeposit;
use App\User;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\Journal;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;
use DB;

class CashCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $cash_collections=CashCollection::all();
//        foreach ($cash_collections as $cash_collection){
//            if(isset($cash_collection->money_receipt_no)&&$cash_collection->money_receipt_no!=''){
//                self::resolve_mr_no($cash_collection);
//            }
//        }
        $data['page_name'] = "Cash Collection";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Cash Collection', 'active'),
            array('List', 'active')
        );
        $data['customer'] = Customer::orderBy('shop_name', 'ASC')->get();
        $data['users'] = User::orderBy('name', 'ASC')->get();
        return view('admin.cash-collection.index', $data);
    }

    private function resolve_mr_no(CashCollection $cashCollection){
        $new_mr_no=otherHelper::change_date_format($cashCollection->created_at,false,'M/y/dHi/');
        $asset=Asset::where('asset_no',$cashCollection->shop_no)->first();
        if(isset($asset->parent_asset)&&$asset->parent_asset!=''){
            $new_mr_no.=$asset->parent_asset;
        }
        else{
            $new_mr_no.=$asset->asset_no;
        }
        $cashCollection->money_receipt_no=$new_mr_no;
        $cashCollection->save();
        $cashCollectionDetails=CashCollectionDetail::where('ref_id',$cashCollection->id)->get();
        foreach ($cashCollectionDetails as $cashCollectionDetail){
            $cashCollectionDetail->mr_no=$new_mr_no;
            $cashCollectionDetail->save();
        }
    }

    /**
     * list data
     */
    public function listData(Request $request)
    {
        $in = $request->all();
        $date_from = array();
        $date_to = array();
        $shop_no = array();
        $shop_name = array();
        $invoice_no = '';
        $date_type = 0;
        $bill_type = '';
        $service = '';
        $created_by = '';
        if (isset($in['data'])) {
            for ($i = 1; $i < sizeof($in['data']); $i++) {
                if ($in['data'][$i]['name'] == 'date_from') {
                    if ($in['data'][$i]['value'] != '') {

                        array_push($date_from, $in['data'][$i]['value']);
                    }
                } else if ($in['data'][$i]['name'] == "date_to") {
                    if ($in['data'][$i]['value'] != '') {
                        array_push($date_to, $in['data'][$i]['value']);
                    }
                } else if ($in['data'][$i]['name'] == "shop_no") {
                    if ($in['data'][$i]['value'] != '') {
                        array_push($shop_no, $in['data'][$i]['value']);
                    }
                } else if ($in['data'][$i]['name'] == "shop_name") {
                    if ($in['data'][$i]['value'] != '') {
                        array_push($shop_name, $in['data'][$i]['value']);
                    }
                } else if ($in['data'][$i]['name'] == "invoice_no") {
                    if ($in['data'][$i]['value'] != '') {
                        $invoice_no = $in['data'][$i]['value'];
                    }

                } else if ($in['data'][$i]['name'] == "date_type") {
                    if ($in['data'][$i]['value'] != '') {
                        $date_type = $in['data'][$i]['value'];
                    }

                } else if ($in['data'][$i]['name'] == "bill_type") {
                    if ($in['data'][$i]['value'] != '') {
                        $bill_type = $in['data'][$i]['value'];
                    }

                } else if ($in['data'][$i]['name'] == "service") {
                    if ($in['data'][$i]['value'] != '') {
                        $service = $in['data'][$i]['value'];
                    }

                }else if ($in['data'][$i]['name'] == "userid") {
                    if ($in['data'][$i]['value'] != '') {
                        $created_by = $in['data'][$i]['value'];
                    }

                }
            }
        }

        $cash = CashCollection::query()->leftjoin('billings', 'cash_collections.income_id', '=', 'billings.id')

            ->when(count($date_from) > 0,  function ($query) use ($date_from) {
                return $query->where('collection_date', '>=', $date_from);
            })
            ->when(count($date_to) > 0 ,function ($query) use ($date_to) {
                return $query->where('collection_date', '<=', $date_to);
            })
            ->when(count($shop_no) > 0, function ($query) use ($shop_no) {
                return $query->where('cash_collections.shop_no', '=', $shop_no);
            })
            ->when(count($shop_name) > 0, function ($query) use ($shop_name) {
                return $query->where('cash_collections.customer_id', '=', $shop_name);
            })
            ->when($invoice_no != '', function ($query) use ($invoice_no) {
                return $query->where('cash_collections.invoice_no', '=', $invoice_no);
            })
            ->when($created_by != '', function ($query) use ($created_by) {
                return $query->where('cash_collections.created_by', '=', $created_by);
            })
            ->when($service != '', function ($query) use ($service) {
                return $query->where('billings.off_type', '=', $service);
            })
            ->when($bill_type != '', function ($query) use ($bill_type) {
                return $query->where('cash_collections.bill_type', '=', $bill_type);
            })->selectRaw('cash_collections.*')->orderBy('cash_collections.id','desc');
        return DataTables::eloquent($cash)
            ->addIndexColumn()
            ->setRowId(function ($row) {
                return 'row_' . $row->id;
            })
            ->setRowData([
                'shop_no' => function ($row) {
                    return $row->shop_no ?? '';
                },

                'shop_name' => function ($row) {
                    return $row->shop_name ?? '';
                },
                'shop_name' => function ($row) {
                    return $row->shop_name ?? '';
                },
                'data-collection_date' => function ($row) {
                    return otherHelper::change_date_format($row->collection_date, true, 'd-M-Y');

                },
                'data-created_at' => function ($row) {
                    return otherHelper::change_date_format($row->created_at, true, 'd-M-Y');
                },
                'invoice_no' => function ($row) {
                    return $row->invoice_no ?? '0';
                },
                'voucher_no' => function ($row) {
                    return $row->voucher_no ?? '0';
                },   'bill_type' => function ($row) {
                    return $row->bill_type ?? '0';
                },
                'id' => function ($row) {
                    return $row->id ?? '0';
                },


                'data-created_by' => function ($row) {
                    if (isset($row->user)) {
                        return $row->user->name;
                    } else {
                        return 'None';
                    }

                },
                'data-updated_at' => function ($row) {
                    return otherHelper::change_date_format($row->updated_at, true, 'd-M-Y h:i A');
                },
            ])
            ->addColumn('action', function ($row) {
                $option = '<div style="width:177px;">';
                if (auth()->user()->can('edit-cash-collection')) {
                    $option .= '<div style="float: left;"><a target="_blank" class="btn btn-xs btn-primary text-white text-sm" href="' . route('cash-collection.edit', [$row->id]) . '"  ><span class="fa fa-edit">  Edit</i></a></div>';
                }
                if (auth()->user()->can('read-cash-collection')) {
                    $option .= '<div style="padding-left:5px;float: left;"><a target="_blank" style="color:#fff !important;" class="btn btn-xs btn-warning text-white text-sm" href="' . route('cash-collection.journal', [$row->id]) . '"  >Voucher</i></a></div>';
                }
                if (auth()->user()->can('read-cash-collection')  && $row['payment_mode']!='Advance Deposit') {
                    $option .= '<div style="padding-left:5px;margin-top:5px;float: left;"><a  target="_blank" style="color:#fff !important;" class="btn btn-xs btn-success text-white text-sm" href="' . route('cash-collection.mr', [$row->id]) . '"  >  &#x1F441; MR</a></div>';
                    $option .= '<div style="padding-left:5px;margin-top:5px;float: left;"><a  target="_blank" style="color:#fff !important;" class="btn btn-xs btn-success text-white text-sm" href="' . route('cash-collection.mr-view', [$row->id]) . '"  >  &#x1F441;MR 2</a></div>';
                }
                if (auth()->user()->can('delete-cash-collection')) {
                    $option .= '<div style=" margin-top:5px;float: right">
                                    <form action="' . route('cash-collection.destroy', [$row->id]) . '" method="post" class="">' . csrf_field() . '<input name="_method" type="hidden" value="DELETE"><button type="submit" class="btn btn-xs btn-danger" value="Delete" onclick="return confirm(\'আপনি কি সত্যি ডিলিট করতে চান? \');"><span class="fa fa-trash"></span> Delete</button></form>
                            </div>';
                } else {
                    $option .= '<div style=" "></div>';
                }
                $option .= '</div>';
                return $option;
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name'] = "Add Cash Collection ";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Cash Collection', 'cash-collection.index'),
            array('Add', 'active')
        );
        $data['customer']= Billing::orderBy('shop_no','ASC')->groupBy('shop_no')->groupBy('shop_name')->get();
        $data['ledger'] = ChartOfAccount::where('sub_category', '=', 'Current Bank Accounts')->where('status',1)->get();
        return view('admin.cash-collection.create', $data);

    }

    public function create_new()
    {
        $data['page_name'] = "New Add Cash Collection";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Cash Collection', 'cash-collection.index'),
            array('Add', 'active')
        );
        $data['customer']= Billing::orderBy('shop_no','ASC')->groupBy('shop_no')->groupBy('shop_name')->get();
        $data['ledger'] = ChartOfAccount::where('sub_category', '=', 'Current Bank Accounts')->where('status', 1)->get();
        return view('admin.cash-collection.create_new', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          set_time_limit(0);
         $checkData = $request->all();
//        if ($checkData['bill_id'] == "") {
//            return back()->with('warning', 'Net Work Problem, Please Refresh Page');
//        }
        if ($checkData['customer_id'] == "") {
            return back()->with('warning', 'Please Select Shop name');
        }
//        if($checkData['invoice_no'] == ""){
//            return back()->with('warning',  'Please Select invoice no');
//        }
        if ($checkData['payment_mode'] == "") {
            return back()->with('warning', 'Please Select payment mode');
        }
        if ($checkData['collection_date'] == '') {
            return back()->with('warning', 'Please Select collection date');
        }

        $asset = Billing::where('id', $request->input('bill_id'))->first();
        $customer_id =  $request->input('customer');
      //  $shop_ids =  $request->input('shop_no');
//        if($shop_ids==''){
//            return "ok";
//        }
       // return "ok1";
        $customer = Customer::find($customer_id);
        $customer_ids = $customer_id;
        $customer_name = $customer->shop_name;
        $count = CashCollection::count();
        $count++;

        $mr_no = date('M/y/dHis/');
        $effective_date = $request->input('collection_date');

        $cheque_no = $request->input('cheque_no');
        //$request->input('money_receipt_no');
        $cheque_date = $request->input('cheque_date');

        $bill_id = $request->input('bill_id');
        $invoiceId = array_unique($bill_id);
        $income_01 = Billing::find($invoiceId[0]);
        $asset=Asset::where('asset_no',$income_01->shop_no)->first();
        if(isset($asset->parent_asset)&&$asset->parent_asset!=''){
            $mr_no.=$asset->parent_asset;
        }
        else{
            $mr_no.=$asset->asset_no;
        }
        if ($request->input('payment_mode') == 'Advance Deposit') {
            $mr_no='';
        }
        $money_receipt_no = $mr_no;
        $ledgers = $request->input('ledger_ids');
        $invoice = $request->input('invoice');
        $bill_remarks = $request->input('bill_remarks');
        $bill_received_amount = $request->input('bill_received_amount');
        $discount_amount = $request->input('discount');
        $shop_name = $request->input('shop_name');
        $shop_no = $request->input('shop_no');
        //$customer_ids = $request->input('customer_id');
        $amountTotal = $request->input('amountTotal');
        $is_settlement = isset($checkData['is_settlement']) ? $checkData['is_settlement'] : 0;
        $security_settalment = $checkData['security_deposit_balance'];
        $due_totals = $checkData['due_total_s'];
        $total_discount = 0;

        foreach ($invoiceId as $key => $row) {
            $payment_amount = 0;
            $discount = 0;
            $fined_amount = 0;
            $paid_vat = 0;
            $paid_fixed_fine = 0;
            $customer = Customer::find($asset->customer_id);
            $counts = CashCollection::count();
            $counts++;
            $voucher_no = "RV/" . date('y') . "/" . date('m') . '/' . $counts;
            $income = Billing::where('id', $row)->first();
            $shop_nos = $income->shop_no;
            if ($income->bill_type == 'Rent') {
                if (isset($ledgers[$key + 2]) && $ledgers[$key + 2] == 28 && $bill_received_amount[$key + 2] != null) {
                    $fined_amount = $bill_received_amount[$key + 2];
                }
                if (isset($bill_received_amount[$key]) && $bill_received_amount[$key] != null) {
                    $payment_amount = $bill_received_amount[$key];
                }
                if (isset($discount_amount[$key]) && $discount_amount[$key] != null) {
                    $discount = $discount_amount[$key]??0;
                    $payment_amount += $discount_amount[$key]??0;
                }
//                if (isset($discount_amount[$key + 2]) && $discount_amount[$key + 2] != null) {
//                    $discount += $discount_amount[$key + 2];
//                }

            } else if ($income->bill_type == 'Service Charge') {
                if (isset($ledgers[$key + 1]) && $ledgers[$key + 1] == 38 && $bill_received_amount[$key + 1] != null) {
                    $paid_vat = 0;//$bill_received_amount[$key+1];
                }
                if (isset($ledgers[$key + 2]) && $ledgers[$key + 2] == 75 && $bill_received_amount[$key + 2] != null) {
                    $paid_fixed_fine = $bill_received_amount[$key + 2];
                }
                if (isset($ledgers[$key + 3]) && $ledgers[$key + 3] == 76 && $bill_received_amount[$key + 3] != null) {
                    $fined_amount = $bill_received_amount[$key + 3];
                }
                if (isset($bill_received_amount[$key]) && $bill_received_amount[$key] != null) {
                    $payment_amount = $bill_received_amount[$key];
                }
                if (isset($discount_amount[$key]) && $discount_amount[$key] != null) {
                    $discount = $discount_amount[$key]??0;
                    $payment_amount +=$discount_amount[$key]??0;
                }
                if (isset($discount_amount[$key + 1]) && $discount_amount[$key + 1] != null) {
                //    $discount += $discount_amount[$key + 1]??0;
                 //   $fined_amount +=$discount_amount[$key + 1]??0;;
                }
                if (isset($discount_amount[$key + 2]) && $discount_amount[$key + 2] != null) {
                    $discount += $discount_amount[$key + 2]??0;
                    $paid_fixed_fine +=$discount_amount[$key + 2]??0;
                }
                if (isset($discount_amount[$key + 3]) && $discount_amount[$key + 3] != null) {
                    $discount += $discount_amount[$key + 3]??0;
                    $fined_amount  +=$discount_amount[$key + 3]??0;
                }

            } else if ($income->bill_type == 'Electricity') {
                if (isset($ledgers[$key + 1]) && $ledgers[$key + 1] == 38 && $bill_received_amount[$key + 1] != null) {
                    $paid_vat = $bill_received_amount[$key + 1];
                }
                if (isset($ledgers[$key + 2]) && $ledgers[$key + 2] == 30 && $bill_received_amount[$key + 2] != null) {
                    $fined_amount = $bill_received_amount[$key + 2];
                }
                if (isset($bill_received_amount[$key]) && $bill_received_amount[$key] != null) {
                    $payment_amount = $bill_received_amount[$key];
                }
                if (isset($discount_amount[$key]) && $discount_amount[$key] != null) {
                    $discount = $discount_amount[$key]??0;
                    $payment_amount += $discount_amount[$key]??0;
                }
                if (isset($discount_amount[$key + 1]) && $discount_amount[$key + 1] != null) {
                    $discount += $discount_amount[$key + 1]??0;
                    $paid_vat += $discount_amount[$key + 1]??0;
                }
                if (isset($discount_amount[$key + 2]) && $discount_amount[$key + 2] != null) {
                    $discount += $discount_amount[$key + 2]??0;
                    $fined_amount += $discount_amount[$key + 2]??0;
                }


            } else if ($income->bill_type == 'Food Court Service Charge') {
                if (isset($bill_received_amount[$key]) && $bill_received_amount[$key] != null) {
                    $payment_amount = $bill_received_amount[$key];
                }
                if (isset($discount_amount[$key]) && $discount_amount[$key] != null) {
                    $discount = $discount_amount[$key];
                    $payment_amount +=  $discount_amount[$key]??0;
                }

            } else if ($income->bill_type == 'Special Service Charge') {
                if (isset($bill_received_amount[$key]) && $bill_received_amount[$key] != null) {
                    $payment_amount = $bill_received_amount[$key];
                }
                if (isset($discount_amount[$key]) && $discount_amount[$key] != null) { // discount amount
                    $discount = $discount_amount[$key]??0;
                    $payment_amount  += $discount_amount[$key]??0;
                }

            } else if ($income->bill_type == 'Advertisement') {
                if (isset($bill_received_amount[$key]) && $bill_received_amount[$key] != null) {
                    $payment_amount = $bill_received_amount[$key];
                }
                if (isset($discount_amount[$key]) && $discount_amount[$key] != null) { // discount amount
                    $discount = $discount_amount[$key]??0;
                    $payment_amount  += $discount_amount[$key]??0;
                }
            }
            else if ($income->bill_type == 'Income') {
                if (isset($bill_received_amount[$key]) && $bill_received_amount[$key] != null) {
                    $payment_amount = $bill_received_amount[$key];
                }
                if (isset($discount_amount[$key]) && $discount_amount[$key] != null) { // discount amount
                    $discount = $discount_amount[$key]??0;
                    $payment_amount  += $discount_amount[$key]??0;
                }
            }
            if (isset($bill_received_amount[$key]) && $bill_received_amount[$key] == null && isset($discount_amount[$key]) && $discount_amount[$key] == 0) {
                continue;
            }
            if (($payment_amount+$fined_amount+$paid_fixed_fine) == 0 && $discount==0) {
                continue;
            }
            //return $bill_received_amount;
            $cash = new CashCollection();
            $cash->customer_id = $income->customer_id;
            $cash->shop_no = $income->shop_no;
            $cash->shop_name = $income->shop_name;
            $cash->bill_type = $income->bill_type;
            $cash->cheque_no = $cheque_no;
            $cash->payment_mode = $request->input('payment_mode');
            $cash->cheque_date = $cheque_date;
            $cash->money_receipt_no = $money_receipt_no;
            $cash->payment_amount = $payment_amount;
            $cash->collection_date = $effective_date;
            $cash->is_settlement = $is_settlement;
            $cash->security_settalment = $security_settalment;
             $bill_remarks1=$bill_remarks[$key]??"";
            $cash->bill_remarks =$bill_remarks1;
            if ($request->input('payment_mode') == 'Advance Deposit') {
                $coa = ChartOfAccount::getLedger(117); // Advance Deposit for Rent
                $cash->ledger_id = 117;
                $cash->ledger_name = $coa->head;
                $payment_ref = $cheque_no;
            } else if ($request->input('payment_mode') == 'Discount of Sales') {

                $coa = ChartOfAccount::getLedger(120);
                $cash->ledger_id = 120;
                $cash->ledger_name = $coa->head;
                $payment_ref = $cheque_no;
            } else if ($request->input('payment_mode') == 'Cheque') {

                $coa = ChartOfAccount::getLedger($request->input('ledger_id'));
                $cash->ledger_id = $request->input('ledger_id');
                $cash->ledger_name = $coa->head;
                $payment_ref = $cheque_no;
            }  else {
                $cash->ledger_id = $request->input('ledger_id2');
                $coa = ChartOfAccount::getLedger($request->input('ledger_id2'));
                $cash->ledger_name = $coa->head;
                if ($request->input('payment_mode') == 'Cash') {
                    $payment_ref = '';
                } else {
                    $payment_ref = $request->input('tr_challan_no');
                }
            }

            $cash->cheque_bank_name = $request->input('cheque_bank_name');
            $cash->tds_certificate_no = $request->input('tds_certificate_no');
            $cash->certificate_date = $request->input('certificate_date');
            $cash->tr_challan_no = $request->input('tr_challan_no');
            $cash->tr_challan_date = $request->input('tr_challan_date');
            $cash->challan_issuer_bank = $request->input('challan_issuer_bank');
            $cash->invoice_no = $income['invoice_no'];
            $cash->voucher_no = $voucher_no;
            $cash->income_id = $income['id'];
            $cash->vat = $income['vat'];
            $cash->vat_amount = $income['vat_amount'];
            $cash->paid_fixed_fine = $paid_fixed_fine;
            $cash->total = $income['total'];
            $cash->discount = $discount;
            $cash->grand_total = $income['grand_total'];
            $cash->paid_vat_amount = $paid_vat;
            $cash->paid_fine_amount = $fined_amount;
            $cash->created_by = Auth::user()->id;
            // return $cash;
            $cash->save();


            $income->paid_fine_amount= $fined_amount;
            $income->paid_fixed_fine= $paid_fixed_fine;
            $income->save();

            $cash_id = $cash->id;

            $subId = $request->input('bill_received');
            $amount = $payment_amount;// $bill_received_amount[$key];
            $discoun_ac = $discount;
            $jv = array();

            $invoiceDetails = BillingDetail::where('billing_id', $row)->get();
            $invoiceArray = array();

            foreach ($invoiceDetails as $r) {
                $invoiceArray[$r['id']] = $r;
            }
            $credit = 0;
            $debit = 0;
            $gtotal = 0;
            $total_discount = 0;
            foreach ($invoiceArray as $key => $r) {
                $row = $r;
                $paid = 0;
                $disc_amount= 0;
                $inComeD = BillingDetail::find($r['id']);
                $paid += $inComeD['payment_amount'];
                $paid += $amount;
                $total_discount += $discoun_ac;
                $disc_amount += $inComeD['discount'];;
                $disc_amount += $discoun_ac;
                $due = $inComeD ['amount'] - ($paid+$disc_amount);
                $inComeD->payment_amount = $paid;
                $inComeD->due_amount = $due<0?0:$due;
                $inComeD->discount = $disc_amount<0?0:$disc_amount;
                $inComeD->save();
                $gtotal += $row['amount'];
                $details = new CashCollectionDetail();
                $details->ref_id = $cash_id;
                $details->item_head = $row['ledger_name'];
                $details->item_head_id = $row['ledger_id'];
                $details->month = $row['month'];
                $details->remarks = $row['remarks'];
                $details->amount = $row['amount'];
                $details->is_settlement = $is_settlement;
                $details->security_settalment = $security_settalment;
                $details->vat = $row['vat'];
                $details->invoice_no = $income['invoice_no'];
                $details->vat_amount = $row['vat_amount'];
                $details->total = $row['total'];
                $details->payment_amount = $amount;
                $details->discount = $discoun_ac;
                $details->mr_no = $money_receipt_no;
                $details->paid_fixed_fine = $paid_fixed_fine;
                $details->paid_vat_amount = $paid_vat;
                $details->paid_fine_amount = $fined_amount;
                $credit += $amount;
                $details->save();
            }
            $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
            $ledger_type = $coa->type;
            $ledger_code = $coa->system_code;
            $ledger_id = $coa->id;
            $group_name = $coa->group_name;
            $remarks1 = 'Invoice No: ' . $income['invoice_no'];
//        $mr_no = '';
            if ($request->input('payment_mode') == 'Cheque') {
                $remarks = "Cheque collected by  MR No#$money_receipt_no on $effective_date";
            }else if ($request->input('payment_mode') == 'Advance Deposit') {
                $remarks = "Cash collected by MR No# $money_receipt_no on $effective_date";
            }else if ($request->input('payment_mode') == 'Discount of Sales') {
                $remarks = "Cash collected by MR No# $money_receipt_no on $effective_date";
            } else if ($request->input('payment_mode') == 'Cash') {
                $remarks = "Cash collected by MR No# $money_receipt_no on $effective_date";
            } else if ($request->input('payment_mode') == 'TDS Challan') {
                $tds_certificate_no = $request->input('tds_certificate_no');
                $tr_challan_no = $request->input('tr_challan_no');
                $tr_challan_date = $request->input('tr_challan_date');
                $challan_issuer_bank = $request->input('challan_issuer_bank');
                $remarks = "By TDS Certificate No # $tds_certificate_no vide TR Challan No # $tr_challan_no of $challan_issuer_bank $tr_challan_date";

            } else if ($request->input('payment_mode') == 'VDS Challan') {
                $tds_certificate_no = $request->input('tds_certificate_no');
                $tr_challan_no = $request->input('tr_challan_no');
                $tr_challan_date = $request->input('tr_challan_date');
                $challan_issuer_bank = $request->input('challan_issuer_bank');
                $remarks = "By VDS Certificate No # $tds_certificate_no vide TR Challan No # $tr_challan_no of $challan_issuer_bank $challan_issuer_bank";

            }
            $jv = array();
            // for settlment
            if (isset($checkData['is_settlement']) && $checkData['is_settlement'] == 1 && trim($checkData['security_deposit_balance']) > 0) {
                $security_deposit_balance = $checkData['security_deposit_balance'];
                $due_totals = $checkData['due_total_s'];
                $setlment = ($credit + $paid_vat + $paid_fixed_fine + $fined_amount);
                $setlment = $due_totals - ($security_deposit_balance - $setlment);
                $security_deposit = abs($setlment);
                //$security_deposit_ledger=
                if ($security_deposit_balance > $due_totals) {
                    $setlment = round($security_deposit_balance - $due_totals, 2);
                    //  $ledger_id = 115;

                    $coa = ChartOfAccount::getLedger(115); //'Accounts Receivable'
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    //$ledger_id = $coa->id;
                    $group_name = $coa->group_name;
                    $ledger_name = $coa->head;
                    $remarks = " Refund to ABC Shop as final settlement";
                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name, 'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id,
                        'payment_ref' => $cheque_no, 'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date, 'transaction_type' => 'Receipt',
                        'invoice_no' => $income['invoice_no'], 'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks,
                        'ledger_head' => $ledger_name, 'date' => date('Y-m-d'), 'debit' => ($setlment), 'credit' => 0,
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                    array_push($jv, $sub);

                    $coa = ChartOfAccount::getLedger($request->input('ledger_id2')); //'Accounts Receivable'
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_id = $coa->id;
                    $ledger_name = $coa->head;
                    $group_name = $coa->group_name;
                    $remarks1 = 'Adjustment of security deposit as final settlement';

                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name,
                        'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id, 'payment_ref' => $cheque_no,
                        'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date,
                        'transaction_type' => 'Receipt', 'invoice_no' => $income['invoice_no'],
                        'customer_name' => $customer->shop_name ?? "", 'remarks' => '',
                        'ledger_head' => trim($ledger_name),
                        'date' => date('Y-m-d'), 'debit' => 0, 'credit' => ($setlment),
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection',
                        'created_by' => Auth::user()->id);
                    array_push($jv, $sub);
                    $coa = ChartOfAccount::getLedger(115); //'Accounts Receivable'
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_id = $coa->id;
                    $group_name = $coa->group_name;
                    $ledger_name = $coa->head;
                    $remarks = " Refund to ABC Shop as final settlement";
                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name, 'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id,
                        'payment_ref' => $cheque_no, 'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date, 'transaction_type' => 'Receipt',
                        'invoice_no' => $income['invoice_no'], 'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks1,
                        'ledger_head' => $ledger_name, 'date' => date('Y-m-d'), 'debit' => ($due_totals), 'credit' => 0,
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                    array_push($jv, $sub);
                    $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_id = $coa->id;
                    $group_name = $coa->group_name;


                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name,
                        'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id, 'payment_ref' => $cheque_no,
                        'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date,
                        'transaction_type' => 'Receipt', 'invoice_no' => $income['invoice_no'],
                        'customer_name' => $customer->shop_name ?? "", 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
                        'date' => date('Y-m-d'), 'debit' => 0, 'credit' => ($due_totals),
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                    array_push($jv, $sub);
                } else if ($security_deposit_balance < $due_totals) { //
                    if ($request->input('payment_mode') == 'Cheque') {
                        $ledger_id = $request->input('ledger_id');
                    } else {
                        $ledger_id = $request->input('ledger_id2');
                    }
                    $security_deposit = round($due_totals - $security_deposit_balance, 2);
                    $coa = ChartOfAccount::getLedger($ledger_id); //'Accounts Receivable'
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    //$ledger_id = $coa->id;
                    $group_name = $coa->group_name;
                    $ledger_name = $coa->head;
                    $remarks = " Collection from ABC Shop as final settlement";
                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name, 'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id,
                        'payment_ref' => $cheque_no, 'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date, 'transaction_type' => 'Receipt',
                        'invoice_no' => $income['invoice_no'], 'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks,
                        'ledger_head' => $ledger_name, 'date' => date('Y-m-d'), 'debit' => ($security_deposit), 'credit' => 0,
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                    array_push($jv, $sub);
                    $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_id = $coa->id;
                    $ledger_name = $coa->head;
                    $group_name = $coa->group_name;
                    $remarks1 = '';

                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name,
                        'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id, 'payment_ref' => $cheque_no,
                        'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date,
                        'transaction_type' => 'Receipt', 'invoice_no' => $income['invoice_no'],
                        'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks1,
                        'ledger_head' => trim($ledger_name),
                        'date' => date('Y-m-d'), 'debit' => 0, 'credit' => ($security_deposit),
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                    array_push($jv, $sub);
                    //  $ledger_id = 115;
                    $coa = ChartOfAccount::getLedger(115); //'Accounts Receivable'
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_id = $coa->id;
                    $group_name = $coa->group_name;
                    $ledger_name = $coa->head;
                    $remarks = " Adjustment of security deposit as final settlement";
                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name, 'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id,
                        'payment_ref' => $cheque_no, 'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date, 'transaction_type' => 'Receipt',
                        'invoice_no' => $income['invoice_no'], 'customer_name' => $customer->shop_name ?? "",
                        'remarks' => $remarks,
                        'ledger_head' => trim($ledger_name), 'date' => date('Y-m-d'), 'debit' => ($security_deposit_balance), 'credit' => 0,
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                    array_push($jv, $sub);
                    $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_id = $coa->id;
                    $ledger_name = $coa->head;
                    $group_name = $coa->group_name;
                    $remarks1 = '';

                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name,
                        'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id, 'payment_ref' => $cheque_no,
                        'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,'bill_remarks'=>$bill_remarks1,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date,
                        'transaction_type' => 'Receipt', 'invoice_no' => $income['invoice_no'],
                        'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks1,
                        'ledger_head' => trim($ledger_name),
                        'date' => date('Y-m-d'), 'debit' => 0, 'credit' => ($security_deposit_balance),
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                    array_push($jv, $sub);
                } else {
                    $coa = ChartOfAccount::getLedger(115);
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_name = $coa->head;
                    $ledger_id = $coa->id;
                    $group_name = $coa->group_name;

                    $remarks = "Adjustment of security deposit as final settlement";
                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name, 'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id,
                        'payment_ref' => $cheque_no, 'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date, 'transaction_type' => 'Receipt',
                        'invoice_no' => $income['invoice_no'], 'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks,
                        'ledger_head' => $ledger_name, 'date' => date('Y-m-d'), 'debit' => ($credit + $paid_vat + $paid_fixed_fine + $fined_amount), 'credit' => 0,
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                    array_push($jv, $sub);
                    $coa = ChartOfAccount::getLedger(36);
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_name = $coa->head;
                    $ledger_id = $coa->id;
                    $group_name = $coa->group_name;
                    $remarks = '';
                    $sub = array('ref_id' => $cash_id, 'group_name' => $group_name,
                        'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id, 'payment_ref' => $cheque_no,
                        'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,'bill_remarks'=>$bill_remarks1,
                        'shop_no' => $shop_nos, 'customer_id' => $customer_ids,
                        'post_date' => date('Y-m-d'), 'effective_date' => $effective_date,
                        'transaction_type' => 'Receipt', 'invoice_no' => $income['invoice_no'],
                        'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks1, 'ledger_head' => 'Accounts Receivable',
                        'date' => date('Y-m-d'), 'debit' => 0, 'credit' => ($credit + $paid_vat + $paid_fixed_fine + $fined_amount),
                        'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                    array_push($jv, $sub);


                }


            }
            else {
                $coa = ChartOfAccount::getLedger(36);
                $ledger_type = $coa->type;
                $ledger_code = $coa->system_code;
                $ledger_name = $coa->head;
                $ledger_id = $coa->id;
                $group_name = $coa->group_name;
                $sub = array('ref_id' => $cash_id, 'group_name' => $group_name,
                    'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id, 'payment_ref' => $cheque_no,
                    'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                    'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                    'post_date' => date('Y-m-d'), 'effective_date' => $effective_date,
                    'transaction_type' => 'Receipt', 'invoice_no' => $income['invoice_no'],
                    'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks1, 'ledger_head' => 'Accounts Receivable',
                    'date' => date('Y-m-d'), 'debit' => 0, 'credit' => (($credit + $paid_vat + $paid_fixed_fine + $fined_amount)-$total_discount),
                    'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                $ledger_type = '';
                $ledger_code = '';
                $ledger_id = 0;
                $group_name = '';
                if ($request->input(('payment_mode')) == 'Advance Deposit') {
                    $coa = ChartOfAccount::getLedger(117);
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_name = $coa->head;
                    $ledger_id = $coa->id;
                    $group_name = $coa->group_name;
                }else if ($request->input(('payment_mode')) == 'Discount of Sales') {
                    $coa = ChartOfAccount::getLedger(120);
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_name = $coa->head;
                    $ledger_id = $coa->id;
                    $group_name = $coa->group_name;
                }
                else if ($request->input(('payment_mode')) == 'Cheque') {
                    $coa = ChartOfAccount::getLedger($request->input('ledger_id'));
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_name = $coa->head;
                    $ledger_id = $coa->id;
                    $group_name = $coa->group_name;
                } else {
                    $coa = ChartOfAccount::getLedger($request->input('ledger_id2'));
                    $ledger_type = $coa->type;
                    $ledger_code = $coa->system_code;
                    $ledger_name = $coa->head;
                    $ledger_id = $coa->id;
                    $group_name = $coa->group_name;
                }

                array_push($jv, $sub);
                $sub = array('ref_id' => $cash_id, 'group_name' => $group_name, 'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id,
                    'payment_ref' => $cheque_no, 'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                    'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                    'post_date' => date('Y-m-d'), 'effective_date' => $effective_date, 'transaction_type' => 'Receipt',
                    'invoice_no' => $income['invoice_no'], 'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks,
                    'ledger_head' => $ledger_name, 'date' => date('Y-m-d'), 'debit' => (($credit + $paid_vat + $paid_fixed_fine + $fined_amount)-$total_discount), 'credit' => 0,
                    'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                array_push($jv, $sub);

                $coa = ChartOfAccount::getLedger(120); // Discount of Sales
                $ledger_type = $coa->type;
                $ledger_code = $coa->system_code;
                $ledger_name = $coa->head;
                $ledger_id = $coa->id;
                $group_name = $coa->group_name;


                $sub = array('ref_id' => $cash_id, 'group_name' => $group_name, 'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id,
                    'payment_ref' => $cheque_no, 'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                    'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                    'post_date' => date('Y-m-d'), 'effective_date' => $effective_date, 'transaction_type' => 'Receipt',
                    'invoice_no' => $income['invoice_no'], 'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks,
                    'ledger_head' => $ledger_name, 'date' => date('Y-m-d'), 'debit' => ($total_discount), 'credit' => 0,
                    'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                if($total_discount>0){
                    array_push($jv, $sub);
                }

                $coa = ChartOfAccount::getLedger(36); // Accounts Receivable
                $ledger_type = $coa->type;
                $ledger_code = $coa->system_code;
                $ledger_name = $coa->head;
                $ledger_id = $coa->id;
                $group_name = $coa->group_name;
                $sub = array('ref_id' => $cash_id, 'group_name' => $group_name, 'payment_ref' => $payment_ref, 'ledger_id' => $ledger_id,
                    'payment_ref' => $cheque_no, 'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code,
                    'shop_no' => $shop_nos, 'customer_id' => $customer_ids,'bill_remarks'=>$bill_remarks1,
                    'post_date' => date('Y-m-d'), 'effective_date' => $effective_date, 'transaction_type' => 'Receipt',
                    'invoice_no' => $income['invoice_no'], 'customer_name' => $customer->shop_name ?? "", 'remarks' => $remarks,
                    'ledger_head' => $ledger_name, 'date' => date('Y-m-d'), 'debit' => 0, 'credit' => $total_discount,
                    'voucher_no' => $voucher_no, 'ref_module' => 'Cash Collection', 'created_by' => Auth::user()->id);
                if($total_discount>0){
                    array_push($jv, $sub);
                }

            }
            Journal::insert($jv);
              $this->completeStatus($income, $gtotal, $paid_vat, $fined_amount, $paid_fixed_fine,$disc_amount);
            Logs::store(Auth::user()->name . 'New Cash Collection has been created successfull ', 'Add', 'success', Auth::user()->id, $income->id, 'Cash Collection');
        }
        return redirect()->route('cash-collection.index')->with('success', 'Cash Collection has been created successfully.');
    }

    public function completeStatus($data, $amount, $paid_vat, $fined_amount, $paid_fixed_fine,$discounts)
    {
        $incomes = BillingDetail::where('billing_id', $data['id'])->sum('payment_amount');
        $discount = BillingDetail::where('billing_id', $data['id'])->sum('discount');
        $vat = Billing::where('id', $data['id'])
            ->selectRaw('sum(vat_amount) as vat_amount,sum(vat_total_paid) as vat_total_paid,
            sum(grand_total) as grand_total, sum(paid_fine_amount) as paid_fine_amount, sum(paid_fixed_fine) as paid_fixed_fine')
            ->first();
        $paid = $vat['vat_total_paid'] + $vat['paid_fine_amount'] + $vat['paid_fixed_fine'];

        if (round($vat['grand_total']) <= round($paid + $incomes+$discount)) {
            $income = Billing::find($data['id']);
            $income->payment_status = 1;
            $income->vat_total_paid = $vat['vat_total_paid'] + $paid_vat;
            $income->save();

        } else {
            $income = Billing::find($data['id']);
            $income->vat_total_paid = $vat['vat_total_paid'] + $paid_vat;
            $income->save();
        }
        return '';

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name'] = "Edit Cash Collection ";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Cash Collection', 'cash-collection.index'),
            array('Edit', 'active')
        );
        $data['customer'] = Customer::all();
        $data['ledger'] = ChartOfAccount::where('group_name', '=', 'Current Assets')->get();
        $data['editData'] = CashCollection::find($id);
        $data['details'] = CashCollectionDetail::where('ref_id', '=', $id)->get();
        return view('admin.cash-collection.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $income = CashCollection::find($id);
        $details = CashCollectionDetail::where('ref_id', $id)->first();

        $billD = BillingDetail::where('billing_id', $income->income_id)->first();
        $payment = ($billD->payment_amount??0) - ($details->payment_amount??0);
        $billDs = BillingDetail::find($billD->id);
        $billDs->payment_amount = ($payment > 0 ? $payment : 0) ;
        $billDs->save();


        $bill = Billing::find($income->income_id);
        $paid_fine_amount = ($billD->paid_fine_amount??0) - ($details->paid_fine_amount??0);
        $paid_fixed_fine = ($billD->paid_fixed_fine??0) - ($details->paid_fixed_fine??0);
        $bill->paid_fine_amount =  $paid_fine_amount > 0 ? $paid_fine_amount : 0;
        $bill->paid_fixed_fine =  $paid_fixed_fine > 0 ? $paid_fixed_fine : 0;
        $bill->payment_status =  0;
        $bill->save();

        CashCollectionDetail::where('ref_id', $id)->delete();
        $income->delete();
        Journal::where('ref_id', $id)->where('ref_module', 'Cash Collection')->delete();
        Logs::store(Auth::user()->name . 'Cash Collection has been delete successful', 'Add', 'success', Auth::user()->id, $income->id, 'Cash Collection');
        return redirect()->route('cash-collection.index')->with('success', 'Cash Collection has been delete successfully.');
    }

    public function dueInvoice(Request $request)
    {
        // return $id;
        $data =$request->all();
        //dd($data);
//        $selected_bill = $data['id'];
        $customer_id = $data['customer_id'];
        $bill_type = $data['bill_type']!=''?$data['bill_type']:"";
//        $d = trim($id, '"');
//        $selected_bill = array();
//        $record = explode(",", $d);
        $customerIds = [$customer_id];
        $selected_bill = [];
        foreach ($data['id'] as $s) {
            $cus = explode('@@@',$s);
//            array_push($customerIds, $cus[1]);
            array_push($selected_bill, $cus[0]);
        }

        try {
            $income = Billing::leftjoin('billing_details','billing_details.billing_id','=','billings.id')
                ->where('payment_status', '=', 0)
                ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                    return $query->whereIn('shop_no', $selected_bill);
                })
                ->when(count($customerIds) > 0, function ($query) use ($customerIds) {
                    return $query->whereIn('customer_id', $customerIds);
                })
                 ->when($bill_type !='', function ($query) use ($bill_type) {
                    return $query->where('billings.bill_type', $bill_type);
                })
               /* ->where(function ($query) use ($customer_id,$selected_bill) {
                    $query->whereIn('billings.shop_no', $selected_bill)
                        ->orWhere('billings.customer_id', '=', $customer_id);
                })*/
               ->selectRaw('billings.*,billing_details.month')
                ->where('invoice_no', '<>', null)
                ->orderby('id', 'desc')
                ->get();
            $totalAmount = 0;
            $totalAmount = Billing::when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                return $query->whereIn('shop_no', $selected_bill);
            })->when(count($customerIds) > 0, function ($query) use ($customerIds) {
                return $query->whereIn('customer_id', $customerIds);
            })
             ->when($bill_type !='', function ($query) use ($bill_type) {
                    return $query->where('billings.bill_type', $bill_type);
                })
                /* ->where(function ($query) use ($customer_id,$selected_bill) {
                     $query->whereIn('billings.shop_no', $selected_bill)
                         ->orWhere('billings.customer_id', '=', $customer_id);
                 })*/->sum('grand_total');

            $details = CashCollection::join('cash_collection_details', 'cash_collections.id', '=', 'cash_collection_details.ref_id')
                ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                    return $query->whereIn('cash_collections.shop_no', $selected_bill);
                })
                ->when(count($customerIds) > 0, function ($query) use ($customerIds) {
                    return $query->whereIn('cash_collections.customer_id', $customerIds);
                })
                 ->when($bill_type !='', function ($query) use ($bill_type) {
                    return $query->where('cash_collections.bill_type', $bill_type);
                })
                /*
                ->where(function ($query) use ($customer_id,$selected_bill) {
                    $query->whereIn('cash_collections.shop_no', $selected_bill)
                        ->orWhere('cash_collections.customer_id', '=', $customer_id);
                }) */
                ->selectRaw('sum(cash_collection_details.payment_amount) as payment,cash_collection_details.month, sum(cash_collections.paid_vat_amount) as vat')->first();
            $due = $totalAmount - ($details->payment + $details->vat);
            $security_deposit_amount=Journal::selectRaw('(sum(credit)-sum(debit)) as total')
            ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                return $query->whereIn('shop_no', $selected_bill);
            })->where('ledger_id',115)->get()->toArray();
            $advance_deposit_amount=Journal::selectRaw('(sum(credit)-sum(debit)) as total')
                ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                    return $query->whereIn('shop_no', $selected_bill);
                })->where('ledger_id',117)->get()->toArray();
            echo json_encode(array('due' => round($due, 2), 'details' => $due, 'income' => $income,
                'security_deposit_amount'=>$security_deposit_amount[0]['total'] ?? 0,'advance_deposit_amount'=>$advance_deposit_amount[0]['total'] ?? 0));
        }catch (\Exception $e){
            $returnHTML = $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        }


    }

    public function getInvoiceDetails(Request $request)
    {
        $checkData = $request->all();
        $checkData = $checkData['body'];
//
//        $d = trim($checkData[''], '"');
//        $selected_bill = array();
        $selected_bill = [];
        $shop_no =[];
        $customerIds =[$checkData['customer_id']];
        $customer_id = $checkData['customer_id'];
        $bill_type = $checkData['bill_type']!=''?$checkData['bill_type']:"";
try {
        if(isset($checkData['shop_no'])){
           // $record = explode(",", $checkData['shop_no']);


            foreach ($checkData['shop_no'] as $s) {
                $cus = explode('@@@',$s);
                array_push($customerIds, $cus[1]);
                array_push($shop_no, $cus[0]);
            }
            //$shop_no =  $checkData['shop_no'];
        }
        if(isset($checkData['invoice'])){
            $selected_bill = $checkData['invoice'];
//            foreach ($record as $s) {
//                array_push($selected_bill, $s);
//            }
        }




        $invoice = BillingDetail::leftjoin('billings','billings.id','=','billing_details.billing_id')
            ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
            return $query->whereIn('billing_id', $selected_bill);
        })->when(count($shop_no) > 0, function ($query) use ($shop_no) {
        return $query->whereIn('billings.shop_no', $shop_no);
    })->when(count($customerIds) > 0, function ($query) use ($customerIds) {
            return $query->whereIn('billings.customer_id', $customerIds);
        })

            ->where('payment_status', '=', 0)->get();
        $result = Billing::when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
            return $query->whereIn('id', $selected_bill);
        })->when(count($shop_no) > 0, function ($query) use ($shop_no) {
            return $query->whereIn('billings.shop_no', $shop_no);
        })->when(count($customerIds) > 0, function ($query) use ($customerIds) {
            return $query->whereIn('billings.customer_id', $customerIds);
        })->when($bill_type!='', function ($query) use ($bill_type) {
                return $query->where('billings.bill_type', $bill_type);
            })->where('payment_status', '=', 0)->get();
        $billingData = array();
        foreach ($result as $row) {
            $tempData = array();
            $month = '';
            foreach ($invoice as $r) {
                if ($r['billing_id'] == $row['id']) {
                    $month = $r['month'];
                    array_push($tempData, $r);
                }
            }
//            if($row->vat_amount!=0 && $row->vat_amount!=''){
            array_push($tempData, array(
                'ledger_id' => 38,
                'ledger_name' => 'Sales VAT Payable A/C',
                'amount' => $row->vat_amount ?? 0,
                'payment_amount' => $row->vat_total_paid ?? 0,
                'month' => $month,
            ));
//            }

//            if($row->fixed_fine!=0 && $row->fixed_fine!=''){
            if ($row->bill_type == 'Service Charge') {
                array_push($tempData, array(
                    'ledger_id' => 75,
                    'id' => 75,
                    'ledger_name' => 'Service Charge Fixed Fine',
                    'amount' => $row->fixed_fine ?? 0,
                    'payment_amount' => $row->paid_fixed_fine ?? 0,
                    'month' => $month,
                ));
            }

            if ($row->bill_type == 'Rent' && ($row->fine_amount - $row->paid_fine_amount) > 0) {
                array_push($tempData, array(
                    'ledger_id' => 28,
                    'id' => 28,
                    'ledger_name' => 'Service Charge Fine',
                    'amount' => $row->fine_amount ?? 0,
                    'payment_amount' => $row->paid_fine_amount ?? 0,
                    'month' => $month
                ));
            }


//            }
//            if($row->fine_amount!=0 && $row->fine_amount!=''){
            if ($row->bill_type == 'Service Charge' || $row->bill_type == 'Electricity') {
                array_push($tempData, array(
                    'ledger_id' => $row->bill_type == 'Electricity' ? 30 : 76,
                    'ledger_name' => $row->bill_type == 'Electricity' ? 'Electricity bill Interest' : 'Service Charge Interest',
                    'amount' => $row->fine_amount ?? 0,
                    'payment_amount' => $row->paid_fine_amount ?? 0,
                    'month' => $month,
                ));
            }

//            }
            $row['invoice'] = $tempData;
            array_push($billingData, $row);

        }
        $data['billing'] = $billingData;
//        $data['vat'] = $vat;

            $returnHTML = view('admin.cash-collection.invoice-form', $data)->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        } catch (\Exception $e) {
            $returnHTML = $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        }

    }

    /**
     * show journal
     * @param int $id
     */
    public function journal($id)
    {
        $data['page_name'] = "Show Journal";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Cash Collection', 'cash-collection.index'),
            array('Show', 'active')
        );
        $data['journal'] = CashCollection::find($id);
//        $data['vat']= CashCollection::find($id);
        $data['details'] = Journal::where('ref_id', $id)->where('ref_module', '=', 'Cash Collection')->orderby('id', 'desc')->get();
        return view('admin.cash-collection.journal', $data);

    }

    public function MrView($id)
    {
        $data['page_name'] = "MONEY RECEIPT";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Cash Collection', 'cash-collection.index'),
            array('Show', 'active')
        );
        $data['journal'] = CashCollection::find($id);
        $data['addtionamount'] = CashCollection::find($id);
        $data['details'] = CashCollectionDetail::leftjoin('cash_collections','cash_collections.id','=','cash_collection_details.ref_id')
            ->where('mr_no', $data['journal']->money_receipt_no)
            ->selectRaw('cash_collection_details.*,cash_collections.bill_type,cash_collections.shop_no')
            ->get();
        return view('admin.cash-collection.mr', $data);
    }

    public function MrViewTwo($id)
    {
        $data['page_name'] = "MONEY RECEIPT";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Cash Collection', 'cash-collection.index'),
            array('Show', 'active')
        );
        $data['journal'] = CashCollection::find($id);
        $data['addtionamount'] = CashCollection::find($id);
        $data['details'] = CashCollectionDetail::leftjoin('cash_collections','cash_collections.id','=','cash_collection_details.ref_id')
            ->where('mr_no', $data['journal']->money_receipt_no)
            ->selectRaw('cash_collection_details.*,cash_collections.shop_no,cash_collections.bill_type, cash_collections.shop_name')
            ->get();
        return view('admin.cash-collection.mr2', $data);
    }
    public function getSecuirityDeposit($shop_no)
    {
        $ledger_id = 115;
        $journal = Journal::where('ledger_id', '=', $ledger_id)
            ->when($shop_no != '', function ($query) use ($shop_no) {
                return $query->where('shop_no', $shop_no);
            })
            ->groupBy('ref_id', 'ref_module')
            ->orderBy('effective_date', 'desc')
            ->get();
        $openingBalance = $this->getOpeningBalance($ledger_id, $shop_no);
        $opositResult = array();
        foreach ($journal as $row) {
            $ar = $this->indivisualTransaction($row, $shop_no);
            foreach ($ar as $r) {
                array_push($opositResult, $r);
            }
        }

        $data['page_name'] = "General Ledger";
        $data['account_head'] = $ledger_id;
        $data['openingBalance'] = $openingBalance;
        $data['journal'] = $opositResult;
        echo json_encode(array('openingBalance' => $openingBalance, 'journal' => $opositResult));

    }

    public function getOpeningBalance($shop_no, $ledger_id)
    {

        $journal = Journal::where('ledger_id', $ledger_id)
            ->when($shop_no != '', function ($query) use ($shop_no) {
                return $query->where('shop_no', $shop_no);
            })
            ->groupBy('ref_id')
            ->get();
        $opening = 0;
        foreach ($journal as $row) {
            $ar = $this->indivisualTransaction($row, $ledger_id);
            foreach ($ar as $r) {
                $opening += ($r['debit'] - $r['credit']);
            }


        }
        return abs($opening);

    }

    public function indivisualTransaction($arr, $ledger_id)
    {
        $journal = Journal::where('ref_id', $arr['ref_id'])
            ->where('ref_module', $arr['ref_module'])
            ->orderBy('effective_date', 'desc')
            ->get();
        $debit = 0;
        $credit = 0;
        $result['debit'] = array();
        $result['credit'] = array();
        foreach ($journal as $r) {
            if ($r['ledger_id'] == $ledger_id) {
                $debit = $r['debit'];
                $credit = $r['credit'];
            }
            if ($r['debit'] != 0) {
                $result['debit'][] = $r['debit'];
            }
            if ($r['credit'] != 0) {
                $result['credit'][] = $r['credit'];
            }
        }
        $array = array();

        foreach ($journal as $row) {
            $sub = array();
            $sub['effective_date'] = $row['effective_date'];
            $sub['customer_name'] = $row['customer_name'];
            $sub['payment_ref'] = $row['payment_ref'];
            $sub['voucher_no'] = $row['voucher_no'];
            if ($debit != 0) {
//                if ($row['ledger_id'] == $ledger_id) {
//                    continue;
//                }
                if (count($result['credit']) > 1) {
                    $sub['debit'] = $row['credit'];
                    $sub['credit'] = $row['debit'];
                } else {
                    $sub['debit'] = $debit;
                    $sub['credit'] = 0.00;
                }


                $sub['ledger_head'] = $row->ledger->head ?? "";//$row['ledger_head'];
                array_push($array, $sub);
            } else if ($credit != 0) {
                if ($row['ledger_id'] == $ledger_id) {

                }
                if (count($result['debit']) == 1 && $row['debit'] == 0) {
                    continue;
                }
                if (count($result['debit']) > 1) {
                    $sub['debit'] = $row['credit'];
                    $sub['credit'] = $row['debit'];
                } else {
                    $sub['debit'] = 0.00;
                    $sub['credit'] = $credit;
                }
                $sub['ledger_head'] = $row->ledger->head ?? "";//['ledger_head'] ;
                array_push($array, $sub);
            }
        }
        return $array;
    }
    public function getCustomerInvoice($id){
        $invoice= Asset::where('parent_asset',$id)->groupBy('asset_no')->get();
       echo json_encode($invoice);

    }
}
