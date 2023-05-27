<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Billing;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\BillingDetail;
use App\Models\Journal;
use App\Models\Lookup;
use App\Models\Meter;
use App\Models\Owner;
use App\Models\RateInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\PigeonHelpers\otherHelper;
use Excel;
use DB;
use DateTime;

ini_set('max_input_vars', '10000');

class BulkEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['page_name'] = "Bulk Entry List";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Bulk Entry', 'active'),
            array('List', 'active')
        );
        $data['customer'] = Customer::orderBy('shop_name', 'ASC')->get();
        return view('admin.bulk.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_name'] = "Bulk Entry";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Bulk Entry', 'bulk.index'),
            array('Add', 'active')
        );
        $data['customer'] = Customer::orderBy('shop_name', 'ASC')->get();
        $data['owner'] = Owner::orderBy('name', 'ASC')->get();
        $floor = Lookup::where('name', 'Building Floor')->first();
        $data['floor'] = Lookup::where('parent_id', $floor->id)->get();
        $data['shopList'] = Asset::orderBy('asset_no', 'ASC')->get();
        $data['meterList'] = Meter::orderBy('meter_no', 'ASC')->get();

        return view('admin.bulk.create', $data);
    }

    public function listData(Request $request)
    {
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
            10 => 'created_by'
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

                }else if ($in['data'][$i]['name'] == "bill_type") {
                    if ($in['data'][$i]['value'] != '') {
                        $bill_type = $in['data'][$i]['value'];
                    }

                }
                else if ($in['data'][$i]['name'] == "service") {
                    if ($in['data'][$i]['value'] != '') {
                        $service = $in['data'][$i]['value'];
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

        } else {
            $limit = $request->input('length');
            $start = $request->input('start');
            $data['selected_shops'] = array();
            $data['quantity_assign'] = '<';
            $data['selected_quantity'] = '';
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
        }


//        $order = 'id';
//        $dir ='desc';

        $limit = $request->input('length');
        $start = $request->input('start');

        if (empty($request->input('search.value'))) {
            $totalData = Billing::query()
                ->when(count($date_from) > 0 && $date_type == 1, function ($query) use ($date_from) {
                    return $query->where('due_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 1, function ($query) use ($date_to) {
                    return $query->where('due_date', '<=', $date_to);
                })
                ->when(count($date_from) > 0 && $date_type == 2, function ($query) use ($date_from) {
                    return $query->where('issue_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 2, function ($query) use ($date_to) {
                    return $query->where('issue_date', '<=', $date_to);
                })
                ->when(count($date_from) > 0 && $date_type == 3, function ($query) use ($date_from) {
                    return $query->where('journal_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 3, function ($query) use ($date_to) {
                    return $query->where('journal_date', '<=', $date_to);
                })
                ->when(count($shop_no) > 0, function ($query) use ($shop_no) {
                    return $query->where('shop_no', '=', $shop_no);
                })
                ->when(count($shop_name) > 0, function ($query) use ($shop_name) {
                    return $query->where('customer_id', '=', $shop_name);
                })
                ->when($invoice_no != '', function ($query) use ($invoice_no) {
                    return $query->where('invoice_no', '=', $invoice_no);
                })
                ->when($service != '', function ($query) use ($service) {
                    return $query->where('off_type', '=', $service);
                })
                ->when($bill_type != '', function ($query) use ($bill_type) {
                    return $query->where('bill_type', '=', $bill_type);
                })
                ->where('module', 'Bulk Entry')
                ->count();
            $totalFiltered = $totalData;
            $journal = Billing::query()
                ->when(count($date_from) > 0 && $date_type == 1, function ($query) use ($date_from) {
                    return $query->where('due_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 1, function ($query) use ($date_to) {
                    return $query->where('due_date', '<=', $date_to);
                })
                ->when(count($date_from) > 0 && $date_type == 2, function ($query) use ($date_from) {
                    return $query->where('issue_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 2, function ($query) use ($date_to) {
                    return $query->where('issue_date', '<=', $date_to);
                })
                ->when(count($date_from) > 0 && $date_type == 3, function ($query) use ($date_from) {
                    return $query->where('journal_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 3, function ($query) use ($date_to) {
                    return $query->where('journal_date', '<=', $date_to);
                })
                ->when(count($shop_no) > 0, function ($query) use ($shop_no) {
                    return $query->where('shop_no', '=', $shop_no);
                })
                ->when(count($shop_name) > 0, function ($query) use ($shop_name) {
                    return $query->where('customer_id', '=', $shop_name);
                })
                ->when($invoice_no != '', function ($query) use ($invoice_no) {
                    return $query->where('invoice_no', '=', $invoice_no);
                })
                ->when($service != '', function ($query) use ($service) {
                    return $query->where('off_type', '=', $service);
                })
                ->when($bill_type != '', function ($query) use ($bill_type) {
                    return $query->where('bill_type', '=', $bill_type);
                })
                ->leftjoin('billing_details', 'billing_details.billing_id', '=', 'billings.id')
                ->selectRaw('billings.*,billing_details.month')
                ->where('module', 'Bulk Entry')
                ->orderBy($order, $dir)
                ->offset($start)
                ->limit($limit)
                ->get();
        } else {
            $search = $request->input('search.value');
            $toltalRecord = Billing::query()
                ->when(count($date_from) > 0 && $date_type == 1, function ($query) use ($date_from) {
                    return $query->where('due_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 1, function ($query) use ($date_to) {
                    return $query->where('due_date', '<=', $date_to);
                })
                ->when(count($date_from) > 0 && $date_type == 2, function ($query) use ($date_from) {
                    return $query->where('issue_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 2, function ($query) use ($date_to) {
                    return $query->where('issue_date', '<=', $date_to);
                })
                ->when(count($date_from) > 0 && $date_type == 3, function ($query) use ($date_from) {
                    return $query->where('journal_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 3, function ($query) use ($date_to) {
                    return $query->where('journal_date', '<=', $date_to);
                })
                ->when(count($date_from) > 0, function ($query) use ($date_from) {
                    return $query->where('effective_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0, function ($query) use ($date_to) {
                    return $query->where('effective_date', '<=', $date_to);
                })
                ->when(count($shop_no) > 0, function ($query) use ($shop_no) {
                    return $query->where('shop_no', '=', $shop_no);
                })
                ->when(count($shop_name) > 0, function ($query) use ($shop_name) {
                    return $query->where('customer_id', '=', $shop_name);
                })
                ->when($invoice_no != '', function ($query) use ($invoice_no) {
                    return $query->where('invoice_no', '=', $invoice_no);
                })
                ->when($service != '', function ($query) use ($service) {
                    return $query->where('off_type', '=', $service);
                })
                ->when($bill_type != '', function ($query) use ($bill_type) {
                    return $query->where('bill_type', '=', $bill_type);
                })
                ->where('module', 'Bulk Entry')
                ->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orwhere('shop_no', 'LIKE', "%{$search}%")
                        ->orWhere('shop_name', 'LIKE', "%{$search}%")
                        ->orWhere('invoice_no', 'LIKE', "%{$search}%");
                })
                ->leftjoin('billing_details', 'billing_details.billing_id', '=', 'billings.id')
                ->selectRaw('billings.*,billing_details.month')

//                ->where('created_by',Auth::user()->id)
                ->get();

            $totalFiltered = $totalData = sizeof($toltalRecord);

            $journal = Billing::query()
                ->when(count($date_from) > 0 && $date_type == 1, function ($query) use ($date_from) {
                    return $query->where('due_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 1, function ($query) use ($date_to) {
                    return $query->where('due_date', '<=', $date_to);
                })
                ->when(count($date_from) > 0 && $date_type == 2, function ($query) use ($date_from) {
                    return $query->where('issue_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 2, function ($query) use ($date_to) {
                    return $query->where('issue_date', '<=', $date_to);
                })
                ->when(count($date_from) > 0 && $date_type == 3, function ($query) use ($date_from) {
                    return $query->where('journal_date', '>=', $date_from);
                })
                ->when(count($date_to) > 0 && $date_type == 3, function ($query) use ($date_to) {
                    return $query->where('journal_date', '<=', $date_to);
                })
                ->when(count($shop_no) > 0, function ($query) use ($shop_no) {
                    return $query->where('shop_no', '=', $shop_no);
                })
                ->when(count($shop_name) > 0, function ($query) use ($shop_name) {
                    return $query->where('customer_id', '=', $shop_name);
                })
                ->when($invoice_no != '', function ($query) use ($invoice_no) {
                    return $query->where('invoice_no', '=', $invoice_no);
                })
                ->when($service != '', function ($query) use ($service) {
                    return $query->where('off_type', '=', $service);
                })
                ->when($bill_type != '', function ($query) use ($bill_type) {
                    return $query->where('bill_type', '=', $bill_type);
                })
                ->where('module', 'Bulk Entry')
                ->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%{$search}%")
                        ->orwhere('shop_no', 'LIKE', "%{$search}%")
                        ->orWhere('shop_name', 'LIKE', "%{$search}%")
                        ->orWhere('invoice_no', 'LIKE', "%{$search}%");
                })
//                ->where('created_by',Auth::user()->id)
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
                $nestedData['month'] = $product->month;
                $nestedData['bill_type'] = $product->bill_type;
                $nestedData['shop_no'] = $product->shop_no;
                $nestedData['shop_name'] = $product->shop_name;
                $nestedData['invoice_no'] = $product->invoice_no ?? '';
                $nestedData['meter_no'] = $product->meter_no ?? "";
                $nestedData['created_by'] = $product->user->name ?? "";
                $nestedData['issue_date'] = otherHelper::ymd2dmy($product->issue_date);
                $nestedData['due_date'] = otherHelper::ymd2dmy($product->due_date);
                $nestedData['amount'] = number_format($product->grand_total ?? 0, 2);
                $nestedData['action'] = '<div style=" float: left; margin-bottom: -7px;margin-right: 5px;">
<div style="padding-left:5px;padding-right:5px;float: left;"><a target="_blank" class="btn btn-xs btn-success text-white text-sm" href="' . route('billing.show', [$product->id]) . '"  ><span class="fa fa-edit">  Invoice</i></a>
                                                <a target="_blank" style="color:#fff !important;" class="btn btn-xs btn-warning text-white text-sm" href="' . route('billing.journal', [$product->id]) . '"  > View JV</i></a></div>
                                                </div>';
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_input_vars', '500000');
        $checkData = $request->all();
        $chk = explode(",", $request->input('chk_box'));
        $customer_name = $request->input('customer_name');
        $customer_id = $request->input('customer_id');
        $asset_no = $request->input('asset_no');
        $off_type = $request->input('type');

        $fine_applicable = $request->input('fine_applicable');
        $area = $request->input('area') ?? array();
        $rate = $request->input('rate') ?? array();
        $amount = $request->input('amount') ?? array();
        if ($request->input('category') == 29) {
            $vat = $request->input('vat');
        } else {
            $vat = $request->input('interest') ?? array();
        }
        $total = $request->input('total') ?? array();
        foreach ($chk as $key) {
            $checkAmt =  $amount[$key] ?? 0;
            if($checkAmt<=0){
                continue;
            }
            $customer = Customer::find($customer_id[$key]);
            $customer_ids = $customer_id[$key];
            $shop_no = $asset_no[$key];
            $invoice_no = date('my');
//            $invoice_no .= '-' . $shop_no;
            $results = DB::select( DB::raw("SELECT `billings`.`invoice_no` FROM  billings ORDER BY CAST( SUBSTRING_INDEX(invoice_no, '-', -1) AS UNSIGNED  ) DESC LIMIT 1 ") );
            $temp = (array)$results[0];
            $intMax = explode('-',$temp['invoice_no']);
            if(isset($intMax[2])){
                $count = $intMax[2] + 1;
            }else{
                $count = Billing::count();
                $count = $count+1;
            }

            $voucher_no = "SV/" . date('y') . "/" . date('m') . '/' . $count;

            $check = Billing::where('shop_no', '=', $shop_no)->count();
            $check = $check + 1;
            if ($check >= 0 && $check < 10) {
                $invoice_no .= '-0000' . $check;
            } elseif ($check >= 10 && $check < 100) {
                $invoice_no .= '-000' . $check;
            } elseif ($check >= 100 && $check < 1000) {
                $invoice_no .= '-00' . $check;
            } elseif ($check >= 1000 && $check < 10000) {
                $invoice_no .= '-0' . $check;
            } else {
                $invoice_no .= '-' . $check;
            }

            if ($count >= 0 && $count < 10) {
                $invoice_no .= '-0000' . $count;
            } elseif ($count >= 10 && $count < 100) {
                $invoice_no .= '-000' . $count;
            } elseif ($count >= 100 && $count < 1000) {
                $invoice_no .= '-00' . $count;
            } elseif ($count >= 1000 && $count < 10000) {
                $invoice_no .= '-0' . $count;
            } else {
                $invoice_no .= '-' . $count;
            }

            $issue_date = $request->input('issue_date');
            $journal_date = $request->input('journal_date');
            $grand_total = $total[$key] ?? 0;
            $vat_amount = $vat[$key] ?? 0;
            if ($request->input('category') == 31) {
                $income = $this->serviceChargeEntry($checkData, $invoice_no, $customer, $voucher_no, $key);
            } else if ($request->input('category') == '34') {
                $income = $this->insertFoodCourtService($checkData, $invoice_no, $customer, $voucher_no, $key);

            } else if ($request->input('category') == 43) {
                $income = $this->insertSCService($checkData, $invoice_no, $customer, $voucher_no, $key);

            } else if ($request->input('category') == 33) {
                $cur_reading = $checkData['cur_reading'];
                $pre_reading = $checkData['pre_reading'];
                if($cur_reading[$key]>$pre_reading[$key]){
                    $income = $this->insertElectricityEntry($checkData, $invoice_no, $customer, $voucher_no, $key);

                }

            } else {
                $grand_total = $total[$key] ?? 0;
                $bill_amount = $amount[$key] ?? 0;

//            insert billing form
                $assIds = Asset::where('asset_no',$shop_no)->first();
                $owner_id = $assIds->owner_id;
                $income = new Billing();
                $income->customer_id = $customer_id[$key];
                $income->bill_type = $request->input('category') == 29 ? "Rent" : ($request->input('category') == 31 ? "Service Charge" : "");
                $income->shop_no = $shop_no;
                $income->off_type = $off_type;
                $income->shop_name = $customer->shop_name;
                $income->person_id = 0;
                $income->fine_applicable = $fine_applicable;
                $income->issue_date = $issue_date;
                $income->journal_date = $journal_date;
                $income->owner_id =$assIds->owner_id;
                $income->due_date = $request->input('due_date');
                $income->credit_period = $request->input('month');
                $income->invoice_no = $invoice_no;
                $income->voucher_no = $voucher_no;
                $income->vat = $vat[$key] != '' ? 15 : 0;
                $income->vat_amount = $vat[$key];
                $income->post_date = date('Y-m-d');
                $income->total = $bill_amount;
                $income->grand_total = $grand_total;
                $income->created_by = Auth::user()->id;
                $income->module = 'Bulk Entry';

                $income->save();
                $income_id = $income->id;

                $jv = array();
                $month = $request->input('month');
                if ($off_type == 'Shop') {
                    $coa = ChartOfAccount::getLedger($request->input('category'));
                } else if($off_type == 'Others' && $request->input('category') == 29){
                    $coa = ChartOfAccount::getLedger(118);
                } else {
                    if ($request->input('category') == 29) {
                        $coa = ChartOfAccount::getLedger(73);
                    } else if ($request->input('category') == 31) {
                        $coa = ChartOfAccount::getLedger(32);
                    } else if ($request->input('category') == 33) {
                        $coa = ChartOfAccount::getLedger(26);
                    } else {
                        $coa = ChartOfAccount::getLedger($request->input('category'));
                    }

                }

                $ledger_type = $coa->type;
                $ledger_code = $coa->system_code;
                $ledger_id = $coa->id;
                $ledger_name = $coa->head;
                $group_name = $coa->group_name;

                $details = new BillingDetail();
                $details->billing_id = $income_id;
                $details->ledger_name = $ledger_name;
                $details->ledger_id = $ledger_id;
                $details->fine_applicable = $fine_applicable;
                $details->month = $request->input('month');

                $details->amount = $bill_amount;
                $details->area_sft = $area[$key];
                $details->rate_sft = $rate[$key];
                $details->vat = (int)$vat[$key] != 0 ? 15 : 0;
                $details->vat_amount = $vat[$key];
                $details->total = $grand_total;
                $details->current_reading = '';
                $details->pre_reading = '';
                $details->kwt = '';
                $details->kwt_rate = '';
                $effective = $journal_date;
                $a = $amount[$key];
                if ($request->input('category') == 31) {
                    if ($off_type == 'Shop') {
                        $remarks = "Service Charge for Shop No# $shop_no for the month of $month for $area[$key] sft @ Tk. $rate[$key]";
                    } else {
                        if ($request->input('category') == 31) {
                            $remarks = "Service Charge for Office No# $shop_no for the month of $month for $area[$key] sft @ Tk. $rate[$key]";
                        }
                    }
                } else {
                    if ($off_type == 'Shop') {
                        $remarks = "Rent for Shop No# $shop_no for the month of $month for $area[$key] sft @ Tk. $rate[$key]";
                    } else {
                        if ($request->input('category') == 29) {
                            $remarks = "Rent for $off_type No# $shop_no for the month of $month for $area[$key] sft @ Tk. $rate[$key]";
                        }

                    }

                }

                $details->remarks = $remarks;
                $details->effective_date = $effective;
                $details->save();
                $sub = array('ref_id' => $income_id,'owner_id' => $owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
                    'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
                    'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'customer_name' => trim($customer->shop_name),
                    'remarks' => $remarks, 'ledger_head' => $ledger_name, 'date' => $issue_date, 'debit' => 0,
                    'credit' => $amount[$key], 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry',
                    'created_by' => Auth::user()->id);
                array_push($jv, $sub);


                $remarks = "$vat_amount Rent vat @ ";
                $coa = ChartOfAccount::getLedger(38); // Sales VAT Payable A/C
                $ledger_type = $coa->type;
                $ledger_code = $coa->system_code;
                $ledger_id = $coa->id;
                $group_name = $coa->group_name;
                $sub = array('ref_id' => $income_id, 'owner_id' => $owner_id,'group_name' => $group_name, 'ledger_id' => $ledger_id,
                    'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
                    'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
                    'customer_name' => $customer->shop_name, 'remarks' => '', 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'ledger_head' => 'Sales VAT Payable A/C',
                    'date' => $issue_date, 'debit' => 0, 'credit' => $vat_amount, 'voucher_no' => $voucher_no,
                    'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
                if ($vat_amount != 0) {
                    array_push($jv, $sub);
                }
                $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
                $ledger_type = $coa->type;
                $ledger_code = $coa->system_code;
                $ledger_id = $coa->id;
                $group_name = $coa->group_name;
                $sub = array('ref_id' => $income_id, 'owner_id' => $owner_id,'group_name' => $group_name, 'ledger_id' => $ledger_id,
                    'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
                    'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
                    'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
                    'date' => $issue_date, 'debit' => $grand_total, 'credit' => 0, 'voucher_no' => $voucher_no,
                    'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
                array_push($jv, $sub);
//            return $jv;
                Journal::insert($jv);
            }
            Logs::store(Auth::user()->name . 'New Bulk Entry has been created successfull ', 'Add', 'success', Auth::user()->id, $income->id, 'Bulk Entry');
        }

        return redirect()->route('bulk.index')->with('success', 'Bulk Entry has been created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_name'] = "Edit Asset Info";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Asset Info', 'assets.index'),
            array('Edit', 'active')
        );

        $data['customer'] = Customer::orderBy('shop_name', 'ASC')->get();
        $data['owner'] = Owner::orderBy('name', 'ASC')->get();
        $floor = Lookup::where('name', 'Building Floor')->first();
        $data['floor'] = Lookup::where('parent_id', $floor->id)->get();


        $data['editData'] = Asset::where('id', $id)->first();
        return view('admin.asset-info.edit', $data);

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
        $asset = Asset::where('asset_no', '=', $request->input('asset_no'))->where('status', 1)->first();

        if ($request->input('asset_no') == '') {
            return back()->with('warning', 'Asset No Empty Not  Allow');
        }
        if ($asset == null) {
            $asset->asset_no = $request->input('asset_no');
        }
        $asset->customer_id = $request->input('customer_id');
        $asset->owner_id = $request->input('owner_id');
        $asset->asset_no = $request->input('asset_no');
        $asset->floor_name = $request->input('floor_name');
        $asset->area_sft = $request->input('area_sft');
        $asset->status = $request->input('status');
        $asset->created_by = Auth::user()->id;
        $asset->save();
        Logs::store(Auth::user()->name . ' Asset Info has been updated', 'Edit', 'success', Auth::user()->id, $id, 'Asset Info');
        return redirect()->route('assets.index')->with('success', 'Asset Info has been updated  successfully!');

    }

    public function serviceChargeEntry($checkData, $invoice_no, $customer, $voucher_no, $key)
    {
        $customer_id = $checkData['customer_id'];
        $off_type = $checkData['type'];
        if ($off_type == 'Shop') {
            $category = $checkData['category'];
        }else if($off_type == 'Others'){
            $category = 119;
        } else {
            if ($checkData['category'] == 31) {
                $category = 32;
            }
        }

        $vat = $checkData['vat'];
        $fine_applicable = $checkData['fine_applicable'];
        $vat_rate = $checkData['vat_rate'];
        $amount = $checkData['amount'];
        $total = $checkData['total'];
        $area = $checkData['area'];
        $rate = $checkData['rate'];
        $due_date = $checkData['due_date'];
        $month = $checkData['month'];
        $asset_no = $checkData['asset_no'];
        $shop_no = $asset_no[$key];
        $assIds = Asset::where('asset_no',$shop_no)->first();
        $owner_id = $assIds->owner_id;
        // $interest= $checkData['interest'] ;
        //$fixedAmount= $checkData['fixedAmount'] ;
        // $sub_total= $checkData['sub_total'] ;
        $vat_amount = $vat[$key];
        $journal_date = $checkData['journal_date'];
        $issue_date = $checkData['issue_date'];
        $grand_total = $total[$key];
        $customer_ids = $customer_id[$key];
        $income = new Billing();
        $income->customer_id = $customer_id[$key];
        $income->bill_type = "Service Charge";
        $income->shop_no = $shop_no;
        $income->asset_shop_name = $assIds->shop_name??"";
        $income->off_type = $off_type;
        $income->shop_name = $customer->shop_name;
        $income->person_id = 0;
        $income->fine_applicable = $fine_applicable;
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->due_date = $due_date;
        $income->credit_period = $month;
        $income->invoice_no = $invoice_no;
        $income->voucher_no = $voucher_no;
        $income->owner_id = $assIds->owner_id;
        $income->vat = 3;
        $income->vat_amount = $vat_amount;
        $income->post_date = date('Y-m-d');
        $income->total = $amount[$key];
        $income->grand_total = $total[$key] ?? 0;
        $income->created_by = Auth::user()->id;
        $income->module = 'Bulk Entry';

        $income->save();
        $income_id = $income->id;

        $jv = array();

        $coa = ChartOfAccount::getLedger($category);
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $ledger_name = $coa->head;
        $group_name = $coa->group_name;

        $details = new BillingDetail();
        $details->billing_id = $income_id;
        $details->ledger_name = $ledger_name;
        $details->ledger_id = $ledger_id;
        $details->month = $month;
        $details->fine_applicable = $fine_applicable;
        $details->fine = $fixedAmount[$key] ?? 0;
        $details->interest = 0;// $interest[$key];
        $details->amount = $amount[$key];
        $details->area_sft = $area[$key];
        $details->rate_sft = $rate[$key];
        $details->vat = $vat_rate;
        $details->vat_amount = $vat_amount;
        $details->total = $total[$key] ?? 0;
        $details->current_reading = '';
        $details->pre_reading = '';
        $details->kwt = '';
        $details->kwt_rate = '';
        $effective = $journal_date;
        $a = $amount[$key];
        if ($category == 31) {
            if ($off_type == 'Shop') {
                $remarks = "Service Charge for Shop No# $shop_no for the month of $month for $area[$key] sft @ Tk. $rate[$key]";
            } else {
                $remarks = "Service Charge for Office No# $shop_no for the month of $month for $area[$key] sft @ Tk. $rate[$key]";

            }

        } else {
            $remarks = "Rent for Shop No# $shop_no for the month of $month for $area[$key] sft @ Tk. $rate[$key]";

        }
        $details->remarks = $remarks;
        $details->effective_date = $effective;
        $details->save();
        $sub = array('ref_id' => $income_id,'owner_id' =>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
            'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
            'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($customer->shop_name),
            'remarks' => $remarks, 'ledger_head' => $ledger_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'date' => $issue_date, 'debit' => 0,
            'credit' => $amount[$key], 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry',
            'created_by' => Auth::user()->id);
        array_push($jv, $sub);


        $remarks = "$vat_amount Rent vat @ ";
        $coa = ChartOfAccount::getLedger(38); // Sales VAT Payable A/C
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id,'owner_id' =>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'remarks' => '', 'ledger_head' => 'Sales VAT Payable A/C',
            'date' => $issue_date, 'debit' => 0, 'credit' => $vat_amount, 'voucher_no' => $voucher_no,
            'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
        if ($vat_amount != 0) {
            array_push($jv, $sub);
        }
        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id,'owner_id' =>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
            'date' => $issue_date, 'debit' => $grand_total, 'credit' => 0, 'voucher_no' => $voucher_no,
            'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
        array_push($jv, $sub);
        Journal::insert($jv);
        return $income;
    }

    public function insertElectricityEntry($checkData, $invoice_no, $customer, $voucher_no, $key)
    {

        $customer_id = $checkData['customer_id'];
        $off_type = $checkData['type'];

        if ($off_type == 'Shop') {
            $category = $checkData['category'];
        } else if($off_type == 'Others'){
            $category = 116;
        }else{
            if ($checkData['category'] == 33) {
                $category = 26;
            }
        }

        $vat_rate = $checkData['vat_rate'];
        $meter_reading_date = $checkData['meter_reading_date'];
//        $vat = $checkData['vat'] ;
        $amount = $checkData['amount'];
        $total = $checkData['total'];
//        $area = $checkData['area'] ;
        $meter = $checkData['meter'];
        $asset_no = $checkData['asset_no'];
        $shop_no = $asset_no[$key];
        $meter_no = $meter[$key];
        $due_date = $checkData['due_date'];
        $month = $checkData['month'];
        $vat = $checkData['interest'];
        $fine_applicable = $checkData['fine_applicable'];
        $kwt_rate = $checkData['rate'];
        $kwt = $checkData['kwt'];
        $cur_reading = $checkData['cur_reading'];
        $pre_reading = $checkData['pre_reading'];
        $vat_amount = $vat[$key];
        $journal_date = $checkData['journal_date'];
        $issue_date = $checkData['issue_date'];
        $grand_total = $total[$key];
        $customer_ids = $customer_id[$key];
        $assIds = Meter::where('asset_no',$shop_no)->first();
        $assIds1 = Asset::where('asset_no',$shop_no)->first();
        $owner_id = $assIds->owner_id;
        $income = new Billing();
        $income->customer_id = $customer_id[$key];
        $income->bill_type = "Electricity";
        $income->shop_no = $shop_no;

        $income->asset_shop_name = $assIds1->shop_name??"";
        $income->owner_id = $assIds->owner_id;
        $income->off_type = $off_type;
        $income->shop_name = $customer->shop_name;
        $income->meter_reading_date = $meter_reading_date;
        $income->person_id = 0;
        $income->fine_applicable = $fine_applicable;
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->due_date = $due_date;
        $income->credit_period = $month;
        $income->invoice_no = $invoice_no;
        $income->voucher_no = $voucher_no;
        $income->vat = $vat_rate;
        $income->vat_amount = $vat[$key];
        $income->post_date = date('Y-m-d');
        $income->total = $amount[$key];
        $income->grand_total = $total[$key] ?? 0;
        $income->created_by = Auth::user()->id;
        $income->module = 'Bulk Entry';
        $income->meter_no = $meter_no;
        $income->save();
        $income_id = $income->id;

        $jv = array();;
        $coa = ChartOfAccount::getLedger($category);
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $ledger_name = $coa->head;
        $group_name = $coa->group_name;

        $details = new BillingDetail();
        $details->billing_id = $income_id;
        $details->ledger_name = $ledger_name;
        $details->ledger_id = $ledger_id;
        $details->month = $month;
        $details->fine_applicable = $fine_applicable;
        $details->meter_no = $meter_no;
        $details->fine = $fixedAmount[$key] ?? 0;
        $details->interest = 0;
        $details->amount = $amount[$key];
        $details->area_sft = 0;
        $details->rate_sft = 0;
        $details->vat = 5;
        $details->vat_amount = $vat_amount;
        $details->total = $total[$key] ?? 0;
        $details->current_reading = $cur_reading[$key];
        $details->pre_reading = $pre_reading[$key];
        $details->kwt = $kwt[$key];
        $details->kwt_rate = $kwt_rate[$key];
        $effective = $journal_date;
        $a = $amount[$key];
        if ($off_type == 'Shop') {
            $remarks = "Electricity bill for Shop No# $shop_no for the month of $month for  meter no# " . $meter_no;
        } else {
            $remarks = "Electricity bill for $off_type No# $shop_no for the month of $month for  meter no# " . $meter_no;
        }

        $details->remarks = $remarks;
        $details->effective_date = $effective;
        $details->save();
        $sub = array('ref_id' => $income_id, 'owner_id'=>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
            'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
            'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($customer->shop_name),
            'remarks' => $remarks, 'ledger_head' => $ledger_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'date' => $issue_date, 'debit' => 0,
            'credit' => $amount[$key], 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry',
            'created_by' => Auth::user()->id);
        array_push($jv, $sub);


        $remarks = "$vat_amount Rent vat @ ";
        $coa = ChartOfAccount::getLedger(38); // Sales VAT Payable A/C
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id, 'owner_id'=>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'remarks' => '', 'ledger_head' => 'Sales VAT Payable A/C',
            'date' => $issue_date, 'debit' => 0, 'credit' => $vat_amount, 'voucher_no' => $voucher_no,
            'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
        if ($vat_amount != 0) {
            array_push($jv, $sub);
        }
        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id, 'owner_id'=>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
            'date' => $issue_date, 'debit' => $grand_total, 'credit' => 0, 'voucher_no' => $voucher_no,
            'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
        array_push($jv, $sub);
        Journal::insert($jv);
        return $income;
    }

    public function insertFoodCourtService($checkData, $invoice_no, $customer, $voucher_no, $key)
    {

        $customer_id = $checkData['customer_id'];
        $off_type = $checkData['type'];
        $category = $checkData['category'];
//        $vat = $checkData['vat'] ;
//        $vat_rate = $checkData['vat_rate'] ;
        $amount = $checkData['amount'];
//        $total = $checkData['total'] ;
        $area = $checkData['area'];
        $rate = $checkData['rate'];
        $due_date = $checkData['due_date'];
        $month = $checkData['month'];
        $asset_no = $checkData['asset_no'];
        $shop_no = $asset_no[$key];
        $vat_amount = 0;//$vat[$key];
        $journal_date = $checkData['journal_date'];
        $issue_date = $checkData['issue_date'];
        $fine_applicable = $checkData['fine_applicable'];
        $grand_total = $amount[$key];
        $customer_ids = $customer_id[$key];
        $assIds = Asset::where('asset_no',$shop_no)->first();
        $owner_id = $assIds->owner_id;
        $income = new Billing();
        $income->customer_id = $customer_id[$key];
        $income->bill_type = "Food Court Service Charge";
        $income->shop_no = $shop_no;
        $income->owner_id = $assIds->owner_id;
        $income->asset_shop_name = $assIds->shop_name??"";;
        $income->off_type = $off_type;
        $income->shop_name = $customer->shop_name;
        $income->person_id = 0;
        $income->fine_applicable = $fine_applicable;
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->due_date = $due_date;
        $income->credit_period = $month;
        $income->invoice_no = $invoice_no;
        $income->voucher_no = $voucher_no;
        $income->vat = 0;
        $income->vat_amount = $vat_amount;
        $income->post_date = date('Y-m-d');
        $income->total = $amount[$key];
        $income->grand_total = $amount[$key] ?? 0;
        $income->created_by = Auth::user()->id;
        $income->module = 'Bulk Entry';

        $income->save();
        $income_id = $income->id;

        $jv = array();;
        $coa = ChartOfAccount::getLedger($category);
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $ledger_name = $coa->head;
        $group_name = $coa->group_name;

        $details = new BillingDetail();
        $details->billing_id = $income_id;
        $details->ledger_name = $ledger_name;
        $details->ledger_id = $ledger_id;
        $details->month = $month;
        $details->fine_applicable = $fine_applicable;
        $details->fine = $fixedAmount[$key] ?? 0;
        $details->interest = 0;// $interest[$key];
        $details->amount = $amount[$key];
        $details->area_sft = $area[$key];
        $details->rate_sft = $rate[$key];
        $details->vat = 0;
        $details->vat_amount = $vat_amount;
        $details->total = $amount[$key] ?? 0;
        $details->current_reading = '';
        $details->pre_reading = '';
        $details->kwt = '';
        $details->kwt_rate = '';
        $effective = $journal_date;
        $a = $amount[$key];

        $remarks = "Food Court Service Charge for Shop No# $shop_no for the month of $month  @ Tk. $amount[$key]";

        $details->remarks = $remarks;
        $details->effective_date = $effective;
        $details->save();
        $sub = array('ref_id' => $income_id, 'owner_id' =>$owner_id,'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
            'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
            'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($customer->shop_name),
            'remarks' => $remarks, 'ledger_head' => $ledger_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'date' => $issue_date, 'debit' => 0,
            'credit' => $amount[$key], 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry',
            'created_by' => Auth::user()->id);
        array_push($jv, $sub);


        $remarks = "$vat_amount Rent vat @ ";
        $coa = ChartOfAccount::getLedger(38); // Sales VAT Payable A/C
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id, 'owner_id' =>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'remarks' => '', 'ledger_head' => 'Sales VAT Payable A/C',
            'date' => $issue_date, 'debit' => 0, 'credit' => $vat_amount, 'voucher_no' => $voucher_no,
            'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
        if ($vat_amount != 0) {
            array_push($jv, $sub);
        }
        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id,'owner_id' =>$owner_id,'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
            'date' => $issue_date, 'debit' => $grand_total, 'credit' => 0, 'voucher_no' => $voucher_no,
            'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
        array_push($jv, $sub);
        Journal::insert($jv);
        return $income;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function showCustomer(Request $request)
    {
        try {
            $checkData = $request->all();
            $oppset = $checkData['opsset'];
//       if($checkData['offest_nos']!='-1') {
//           $oppset = 0;
//       }
            if(isset($checkData['limit_s'])){
                $oppset = $checkData['limit_s'];
            }
            $data['oppset'] = $oppset;
            $date = date("Y-m-01", strtotime($checkData['month'])); //date('Y-m-d');
            $allReadyBillGenerate = $this->getBillId($checkData);
            $d = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($checkData['month'])), date('Y', strtotime($checkData['month'])));
            $date_d = date("Y-m-" . $d, strtotime($checkData['month'])); //date('Y-m-d');
            $selected_bill = [];
            $selected_meter = [];
            $allReadyBillMeterGenerate = [];

            if(isset($checkData['specific_shop'])){
                $selected_bill = $checkData['specific_shop'];
            }
            if(isset($checkData['specific_meter_no'])){
                $selected_meter = $checkData['specific_meter_no'];
            }
            if ($checkData['type'] == 33) {
                if ($allReadyBillGenerate['meter_no'][0]  == null) {
                    $allReadyBillMeterGenerate = [];
                } else {
                    $allReadyBillMeterGenerate = explode(";", $allReadyBillGenerate['meter_no'][0]);
                }
                if ($allReadyBillGenerate['shop_no'][0] == null) {
                    $allReadyBillGenerate = [];
                } else {
                    $allReadyBillGenerate = explode(";", $allReadyBillGenerate['shop_no'][0]);
                }

            }else{
                if ($allReadyBillGenerate[0] == null) {
                    $allReadyBillGenerate = [];
                } else {
                    $allReadyBillGenerate = explode(";", $allReadyBillGenerate[0]);
                }
            }

            $dates = array();
            array_push($dates, $date);
            while (strtotime($date) < strtotime($date_d)) {
                $date = date("Y-m-d", strtotime("+1 days", strtotime($date)));
                array_push($dates, $date);
            }
//        echo "<pre>";
//        print_r($dates);
//        die;

            $owner_id = $checkData['owner_id'];
            $ar = Asset::leftjoin('customers', 'customers.id', '=', 'assets.customer_id')
                ->where('customers.status', 1)
                ->whereIn('assets.status', ['Allotted & Open','Allotted & Closed'])
                ->where('assets.off_type', $checkData['off_type'])
                ->where(function ($query) use ($date, $date_d) {
                    $query->where('assets.date_s', '<=', $date);
                })
                ->where(function ($query) use ($date_d, $dates) {
                    $query->whereIn('assets.date_e', $dates)
                        ->orWhere('assets.date_e', '>=', $date_d)
                        ->orWhere('assets.date_e', '=', '0000-00-00')
                        ->orWhere('assets.date_e', '=', '');
                })
//            ->where('assets.date_s', '<=', $date)
//            ->where('assets.date_e', '>=', $date)
                ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                    return $query->whereIn('assets.asset_no', $selected_bill);
                })
                ->when($owner_id > 0, function ($query) use ($owner_id) {
                    return $query->where('assets.owner_id', '=', $owner_id);
                })
//            ->when(count($allReadyBillGenerate) > 0, function ($query) use ($allReadyBillGenerate) {
//                return $query->whereNotIn('assets.asset_no', $allReadyBillGenerate);
//            })
//            ->where('assets.status', '<>', 'Un-allotted')
                ->where('assets.rate', '<>', 0)
                ->where('assets.area_sft', '<>', 0)
                ->selectRaw('customers.* , assets.status as as_sttaus, assets.date_s,assets.date_e,assets.meter_no,assets.opening_reading ,assets.asset_no,
            floor_name,assets.area_sft as area,assets.rate as rate,assets.increment_effective_month,assets.last_increment_date,assets.rent_increment')
                ->OrderBy('assets.asset_no', 'ASC')
                ->skip($oppset)
                ->take(50)
                ->get();
//          var_dump($ar);
            $data['customer'] = $ar;//Customer::where('status',1)->OrderBy('shop_name','ASC')->get();
            $data['rent'] = RateInfo::where('type', '=', 29)->where('effective_date', '<=', $date)->where('off_type', '=', $checkData['off_type'])->OrderBy('id', 'DESC')->first();
            $data['service'] = RateInfo::where('type', '=', 31)->where('effective_date', '<=', $date)->where('off_type', '=', $checkData['off_type'])->OrderBy('id', 'DESC')->first();
            $data['electrcity'] = RateInfo::where('type', '=', 33)->where('effective_date', '<=', $date)->where('off_type', '=', $checkData['off_type'])->OrderBy('id', 'DESC')->first();
            $monthData = Billing::leftjoin('billing_details', 'billing_details.billing_id', '=', 'billings.id')
                ->selectRaw('billings.customer_id,billings.shop_no,billing_details.month')
                ->where('billing_details.month', '=', $checkData['month'])
                ->where('billing_details.ledger_id', '=', $checkData['type'])
                ->get();
            $previousInvoice = array();
            foreach ($monthData as $row) {
                $previousInvoice[$row['shop_no']] = 1;
            }
//         [$allReadyBillGenerate];

            $data['previousInvoice'] = $previousInvoice;
            $data['type'] = $checkData['type'];
            $due_date = $checkData['due_date'];
            if ($checkData['type'] == 31) { // service charge
                $data['fixed_fine'] = 0;
                $data['month'] = 0;
                if(in_array($checkData['off_type'],
                    array(
                        'Motor Pump',
                        'Motor Pump Light House',
                        'Motor Shops',
                        'Officer Mess',
                        'Parking',
                        'Tea Stall',
                        'Top Floor',
                        'Hotel',
                        'Godown',
                        'Foodcourt',
                        'Advertisement',
                        'Others',
                        'Motor Pump Officer Mess')
                )
                ){
                    $data['service'] = RateInfo::where('type', '=', 31)->where('effective_date', '<=', $date)->where('off_type', '=', 'Shop')->OrderBy('id', 'DESC')->first();

                }
//            DB::enableQueryLog();
                $ar2 = Asset::leftjoin('customers', 'customers.id', '=', 'assets.customer_id')
                    ->where('customers.status', 1)
                    ->whereIn('assets.status', ['Allotted & Open','Allotted & Closed'])
                    ->where('assets.off_type', $checkData['off_type'])
                    ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                        return $query->whereIn('assets.asset_no', $selected_bill);
                    })
//                ->where('assets.date_s', '<=', $date)
//                ->where('assets.date_e', '>=', $date_d)
                    ->where(function ($query) use ($date, $date_d) {
                        $query->where('assets.date_s', '<=', $date);
                    })
                    ->where(function ($query) use ($date_d, $dates) {
                        $query->whereIn('assets.date_e', $dates)
                            ->orWhere('assets.date_e', '>=', $date_d)
                            ->orWhere('assets.date_e', '=', '0000-00-00')
                            ->orWhere('assets.date_e', '=', '');
                    })
                    ->where('assets.area_sft', '<>', 0)
                    ->where('assets.sc_rate', '<>', 0)
//                ->when(count($allReadyBillGenerate) > 0, function ($query) use ($allReadyBillGenerate) {
//                    return $query->whereNotIn('assets.asset_no', $allReadyBillGenerate);
//                })
                    ->when($owner_id > 0, function ($query) use ($owner_id) {
                        return $query->where('assets.owner_id', '=', $owner_id);
                    })
                    ->where('assets.service_charge_status', 'Yes')
//                ->whereIn('assets.status', ['Allotted & Closed', 'Allotted & Open'])
                    ->selectRaw('customers.*,assets.meter_no,assets.sc_rate ,assets.opening_reading , assets.asset_no,floor_name,assets.area_sft as area,
                 assets.date_s,assets.date_e,assets.increment_effective_month,assets.last_increment_date,assets.rent_increment')
                    ->OrderBy('asset_no', 'ASC')->skip($oppset)->take(50)->get();

                $data['customer'] = $ar2; //Customer::where('status',1)->OrderBy('shop_name','ASC')->get();
                $to = date_create(date('Y-m-d'));
                $from = date_create($due_date);
                $diff = date_diff($to, $from);
//            print_r($ar);
                if ($diff->days >= 1) {
                    $data['fixed_fine'] = 500;
                    $months = (($diff->y) * 12) + ($diff->m);
                    $months = $months * .03;
                    $data['month'] = $months;
                }
                if ($data['service'] == null) {
                    return response()->json(array('success' => false, 'html' => ''));
                }
                $data['bill_date'] = $date;
                $data['type'] = $checkData['type'];
                $data['allReadyBillGenerate'] = $allReadyBillGenerate;
                $returnHTML = view('admin.bulk.service-form', $data)->render();
            } else if ($checkData['type'] == 33) { // electricity
                if(in_array($checkData['off_type'],
                    array(
                        'Motor Pump',
                        'Motor Pump Light House',
                        'Motor Shops',
                        'Officer Mess',
                        'Parking',
                        'Tea Stall',
                        'Top Floor',
                        'Hotel',
                        'Godown',
                        'Foodcourt',
                        'Advertisement',
                        'Others',
                        'Motor Pump Officer Mess')
                )
                ){

                    $data['electrcity'] = RateInfo::where('type', '=', 33)->where('effective_date', '<=', $date)->where('off_type', '=', 'Shop')->OrderBy('id', 'DESC')->first();
                }

                $ar = Meter::leftjoin('customers', 'customers.id', '=', 'meters.customer_id')
                    ->where('customers.status', 1)

                    ->where('meters.off_type', $checkData['off_type'])
                    ->where(function ($query) use ($date, $date_d) {
                        $query->where('meters.date_s', '<=', $date);
                    })
                    ->when(count($selected_meter) > 0, function ($query) use ($selected_meter) {
                        return $query->whereIn('meters.meter_no', $selected_meter);
                    })
                    ->where(function ($query) use ($date_d, $dates) {
                        $query->whereIn('meters.date_e', $dates)
                            ->orWhere('meters.date_e', '>=', $date_d)
                            ->orWhere('meters.date_e', '=', '0000-00-00')
                            ->orWhere('meters.date_e', '=', '');
                    })
//                ->when(count($allReadyBillGenerate) > 0, function ($query) use ($allReadyBillGenerate) {
//                    return $query->whereNotIn('meters.asset_no', $allReadyBillGenerate);
//                })
//                ->when(count($allReadyBillMeterGenerate) > 0, function ($query) use ($allReadyBillMeterGenerate) {
//                    return $query->whereNotIn('meters.meter_no', $allReadyBillMeterGenerate);
//                })
                    ->selectRaw('customers.shop_name, meters.*')
                    ->OrderBy('meters.asset_no', 'ASC')
                    ->skip($oppset)
                    ->take(50)
                    ->get();

                $data['fixed_fine'] = 0;
                $data['month'] = 0;
                $to = date_create(date('Y-m-d'));
                $from = date_create($due_date);
                $diff = date_diff($to, $from);

                if ($diff->days >= 1) {
                    $data['fixed_fine'] = 500;
                    $months = (($diff->y) * 12) + ($diff->m);
                    $months = $months * .03;
                    $data['month'] = $months;
                }

                $m = date('d-m-Y', strtotime($checkData['month']));
                $day = date('M Y', strtotime("-1 months", strtotime($m)));
                $result = array();
                foreach ($ar as $row) {
                    $previous_month = BillingDetail::leftjoin('billings', 'billings.id', '=', 'billing_details.billing_id')
                        // ->where('billing_details.month', '=', $day)
                        // ->where('billings.customer_id', '=', $row->customer_id)
                        ->where('billings.meter_no', '=', $row->meter_no)
                        ->where('billings.module', '=', 'Bulk Entry')
                        ->select('billing_details.*')->orderBy('billing_details.billing_id','desc')->first();
                    $row['pre_month'] = $previous_month != null ? $previous_month->current_reading : $row->opening_reading;
                    $row['bill_done'] = 1;
                    if(in_array($row['meter_no'],$allReadyBillMeterGenerate)){
                        $row['bill_done'] = 1;
                    }
                    array_push($result, $row);
                }
                if ($data['electrcity'] == null) {
                    return response()->json(array('success' => false, 'html' => ''));
                }
                $data['customer'] = $result;
                $data['allReadyBillGenerate'] = $allReadyBillMeterGenerate;
                $returnHTML = view('admin.bulk.electricity-form', $data)->render();
            } else if ($checkData['type'] == 34) { //Food Court Service Charge
                $data['fixed_fine'] = 0;
                $data['month'] = 0;
                $data['foodCourtS'] = RateInfo::where('type', '=', 34)->where('effective_date', '<=', $date)
                    ->where('off_type', '=', $checkData['off_type'])
                    ->first();
                if ($data['foodCourtS'] == null) {
                    return response()->json(array('success' => false, 'html' => ''));
                }
                $total_area = Asset::leftjoin('customers', 'customers.id', '=', 'assets.customer_id')
                    ->where('customers.status', 1)
                    ->whereIn('assets.status', ['Allotted & Open','Allotted & Closed'])
                    ->where(function ($query) use ($date, $date_d) {
                        $query->where('assets.date_s', '<=', $date);
                    })
                    ->where('off_type', '=', $checkData['off_type'])
                    ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                        return $query->whereIn('assets.asset_no', $selected_bill);
                    })
                    ->where(function ($query) use ($date_d, $dates) {
                        $query->whereIn('assets.date_e', $dates)
                            ->orWhere('assets.date_e', '>=', $date_d)
                            ->orWhere('assets.date_e', '=', '0000-00-00')
                            ->orWhere('assets.date_e', '=', '');
                    })
//                ->when(count($allReadyBillGenerate) > 0, function ($query) use ($allReadyBillGenerate) {
//                    return $query->whereNotIn('assets.asset_no', $allReadyBillGenerate);
//                })
//                ->where('assets.status', '<>', 'Un-allotted')
//                ->where('assets.rate', '<>', 0)
                    ->where('assets.food_court_status', 'Yes')
                    ->selectRaw('sum(assets.area_sft) as total_area')
                    ->first();

                $ar = Asset::leftjoin('customers', 'customers.id', '=', 'assets.customer_id')
                    ->where('customers.status', 1)
                    ->where(function ($query) use ($date, $date_d) {
                        $query->where('assets.food_date_s', '<=', $date);
                    })
                    ->where('off_type', '=', $checkData['off_type'])
                    ->whereIn('assets.status', ['Allotted & Open','Allotted & Closed'])
                    ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                        return $query->whereIn('assets.asset_no', $selected_bill);
                    })
                    ->where(function ($query) use ($date_d, $dates) {
                        $query->whereIn('assets.date_e', $dates)
                            ->orWhere('assets.date_e', '>=', $date_d)
                            ->orWhere('assets.date_e', '=', '0000-00-00')
                            ->orWhere('assets.date_e', '=', '');
                    })

//                ->when(count($allReadyBillGenerate) > 0, function ($query) use ($allReadyBillGenerate) {
//                    return $query->whereNotIn('assets.asset_no', $allReadyBillGenerate);
//                })
                    ->where('assets.status', '<>', 'Un-allotted')
                    ->where('assets.food_court_status', 'Yes')
                    ->selectRaw('customers.*,assets.asset_no,assets.area_sft as total_area ,floor_name,assets.area_sft as area')
                    ->OrderBy('assets.asset_no', 'ASC')
                    ->skip($oppset)
                    ->take(50)
                    ->get();
                $result = array();
                foreach ($ar as $row) {
                    $area = $total_area->total_area * $row['total_area'];

                    $row['amount'] = (($data['foodCourtS']->rate ?? 0) / $total_area->total_area) * ($row['total_area']);
                    $row['rate'] = ($data['foodCourtS']->rate ?? 0) / $total_area->total_area;
                    $row['area'] = $row['total_area'];
                    array_push($result, $row);

                }
                $data['customer'] = $result;
                $data['total_area'] = $total_area;
                // $data['amount'] = ($data['foodCourtS']->rate??0)/($total_areacount($ar);
                $to = date_create(date('Y-m-d'));
                $from = date_create($due_date);
                $diff = date_diff($to, $from);

                $data['allReadyBillGenerate'] = $allReadyBillGenerate;
                $returnHTML = view('admin.bulk.food-form', $data)->render();
            } else if ($checkData['type'] == 43) { // Special Service Charge Revenue
                 $data['fixed_fine'] = 0;
                $data['month'] = 0;
                $data['foodCourtS'] = RateInfo::where('type', '=', 43)->where('effective_date', '<=', $date)
                    ->where('off_type', '=', $checkData['off_type'])
                    ->first();
                if ($data['foodCourtS'] == null) {
                    return response()->json(array('success' => false, 'html' => ''));
                }
                DB::enableQueryLog();
                $ar2 = Asset::leftjoin('customers', 'customers.id', '=', 'assets.customer_id')
                    ->where('customers.status', 1)
                    ->whereIn('assets.status', ['Allotted & Open','Allotted & Closed'])
                    ->where('assets.off_type', $checkData['off_type'])
                    ->when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                        return $query->whereIn('assets.asset_no', $selected_bill);
                    })
//                ->where('assets.date_s', '<=', $date)
//                ->where('assets.date_e', '>=', $date_d)
                    ->where(function ($query) use ($date, $date_d) {
                        $query->where('assets.date_s', '<=', $date);
                    })
                    ->where(function ($query) use ($date_d, $dates) {
                        $query->whereIn('assets.date_e', $dates)
                            ->orWhere('assets.date_e', '>=', $date_d)
                            ->orWhere('assets.date_e', '=', '0000-00-00')
                            ->orWhere('assets.date_e', '=', '');
                    })
                    ->where('assets.area_sft', '<>', 0)
                    ->where('assets.sc_rate', '<>', 0)
                    ->when($owner_id > 0, function ($query) use ($owner_id) {
                        return $query->where('assets.owner_id', '=', $owner_id);
                    })
                    ->where('assets.service_charge_status', 'Yes')
//                ->whereIn('assets.status', ['Allotted & Closed', 'Allotted & Open'])
                    ->selectRaw('customers.*,assets.meter_no,assets.sc_rate ,assets.opening_reading , assets.asset_no,floor_name,assets.area_sft as area,
                 assets.date_s,assets.date_e,assets.increment_effective_month,assets.last_increment_date,assets.rent_increment')
                    ->OrderBy('asset_no', 'ASC')->skip($oppset)->take(50)->get();

                $data['customer'] = $ar2; //Customer::where('status',1)->OrderBy('shop_name','ASC')->get();
                $to = date_create(date('Y-m-d'));
                $from = date_create($due_date);
                $diff = date_diff($to, $from);
                $data['bill_date'] = $date;
                $data['type'] = $checkData['type'];
                $data['allReadyBillGenerate'] = $allReadyBillGenerate;

                $returnHTML = view('admin.bulk.sc-form', $data)->render();
            } else {
                if(in_array($checkData['off_type'],
                    array(
                        'Motor Pump',
                        'Motor Pump Light House',
                        'Motor Shops',
                        'Officer Mess',
                        'Parking',
                        'Tea Stall',
                        'Top Floor',
                        'Hotel',
                        'Godown',
                        'Foodcourt',
                        'Advertisement',
                        'Others',
                        'Motor Pump Officer Mess')
                )
                ){
                    $data['rent'] = RateInfo::where('type', '=', 31)->where('effective_date', '<=', $date)->where('off_type', '=', 'Shop')->OrderBy('id', 'DESC')->first();
                }
                $data['bill_date'] = $date;
                $data['type'] = $checkData['type'];
                $data['allReadyBillGenerate'] = $allReadyBillGenerate;
                $returnHTML = view('admin.bulk.form', $data)->render();
            }
            return response()->json(array('success' => true, 'html' => $returnHTML));
        }catch (\Exception $e){
            $returnHTML = $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        }

    }

    public function dueBillCreate()
    {
        $date = date('Y-m-d');
        $billing = Billing::leftjoin('billing_details', 'billings.id', '=', 'billing_details.billing_id')->where('fine_status', '<>', 1)
            ->where('due_date', '<', $date)
            ->where('payment_status', '<>', 1)
            ->selectRaw('billings.*,billing_details.ledger_id,billing_details.month')
            ->groupBy('billings.id')
            ->get();
        foreach ($billing as $row) {
            if ($row['ledger_id'] == 31) { // service charge
                $data['fixed_fine'] = 0;
                $data['month'] = 0;
                $percent_sc = $row['percent_sc'] + 3;
                $from = date_create($row['due_date']);
//                if($row['next_due_date']!=''){
//                    $from=date_create($row['next_due_date']);
//                }
                $to = date_create(date('Y-m-d'));

                $diff = date_diff($to, $from);

                if ($diff->days >= 1) {
                    $fixed_fine = 500;
                    $months = (($diff->y) * 12) + ($diff->m);
                    $months = $months * .03;
//                    $amount = $row['total']*$months;
                    if ($row['fixed_fine'] != '' && $row['fixed_fine'] != 0) {
                        $m = date('m', strtotime($row['next_due_date']));
                        $y = date('Y', strtotime($row['next_due_date']));

                        if (date('m') > $m && $y == date('Y')) {
                            //  echo "ok";
                            $amount = ($row['total'] + $row['fixed_fine']) * ($percent_sc / 100);
                            $amount = ($amount + $row['fine_amount']);

                            $this->makeJournal($row, $amount, 76);
                            Billing::where('id', $row['id'])->update(
                                ['fine_status' => 0,
                                    'next_due_date' => date('Y-m-d'),
                                    'fixed_fine' => $fixed_fine,
                                    'percent_sc' => $percent_sc,
                                    'fine_amount' => $amount
                                ]
                            );
                        } elseif (date('m') < $m && $y != date('Y')) {

                            $amount = ($row['total'] + $row['fixed_fine']) * ($percent_sc / 100);
                            $amount = ($amount + $row['fine_amount']);
                            $this->makeJournal($row, $amount, 76);
                            //   echo "ok1";
                            Billing::where('id', $row['id'])->update(
                                ['fine_status' => 0,
                                    'next_due_date' => date('Y-m-d'),
                                    'fixed_fine' => $fixed_fine,
                                    'percent_sc' => $percent_sc,
                                    'fine_amount' => $amount
                                ]
                            );
                        }

                    } else {
                        $this->makeJournal($row, 500, 75);
                        Billing::where('id', $row['id'])->update(
                            ['fine_status' => 0,
                                'next_due_date' => date('Y-m-d'),
                                'fixed_fine' => $fixed_fine,
                                'percent_sc' => 0,
                                'fine_amount' => 0
                            ]);
                    }


                }

                //  $returnHTML = view('admin.bulk.service-form',$data)->render();
            } else if ($row['ledger_id'] == 33) { // electricity
                $data['fixed_fine'] = 0;
                $data['month'] = 0;
                $to = date_create(date('Y-m-d'));
                $from = date_create($row['due_date']);
                $diff = date_diff($to, $from);

                if ($diff->days >= 1) {

                    $amount = $row['total'] * .10;
                    Billing::where('id', $row['id'])->update(['fine_status' => 1, 'fine_amount' => $amount]);
                    $this->makeJournal($row, $amount, 30);
                }


            }
        }
    }

    public function makeJournal($row, $amount, $type)
    {


        $coa = ChartOfAccount::getLedger($type); // Sales VAT Payable A/C
        $ledger_type = $coa->type;
        $ledger_code = trim($coa->system_code);
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $ledger_name = $coa->head;
        $income_id = $row['id'];
        $invoice_no = $row['invoice_no'];
        $effective = date('Y-m-d');
        $customer_name = trim($row['shop_name']);
        $issue_date = $row['issue_date'];
        $shop_no = $row['shop_no'];
        $meter_no = $row['meter_no'];
        $month = $row['month'];
        $count = Billing::count();
        $count++;
        $voucher_no = "SV/" . date('y') . "/" . date('m') . '/' . $count;
        if ($row['ledger_id'] == 33) {
            $remarks = "Electricity bill Interest for Shop No# $shop_no for the month of $month for  meter no# " . $meter_no;

        } else {
            $remarks = "Service Charge  bill Interest for Shop No# $shop_no for the month of $month ";

        }
        $jv = array();
        $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
            'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
            'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($customer_name),
            'remarks' => $remarks, 'ledger_head' => $ledger_name, 'date' => $issue_date, 'debit' => 0, 'is_fine' => 1,
            'credit' => $amount, 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry', 'shop_no' => $shop_no,
            'created_by' => Auth::user()->id);
        array_push($jv, $sub);

        // array_push($jv,$sub);

        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => trim($customer_name), 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
            'date' => $issue_date, 'debit' => $amount, 'credit' => 0, 'voucher_no' => $voucher_no, 'is_fine' => 1,
            'shop_no' => $shop_no, 'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
        array_push($jv, $sub);
        return Journal::insert($jv);


    }

    public function printOptions(Request $request)
    {

        $body = $request->all();
        $d = trim($body['data'], '"');
        $selected_bill = array();
        $record = explode(",", $d);

        foreach ($record as $s) {
            array_push($selected_bill, $s);
        }

//        $billing = Billing::whereIn('id',$data)->get();
//        $details = BillingDetail::whereIn('billing_id',[$data])->get();
        $data['page_name'] = "Bulk Entry";
        $data['due'] = 0;
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Bulk Entry', 'active'),
            array('List', 'active')
        );
        try {
            $biling = Billing::when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                return $query->whereIn('id', $selected_bill);
            })->get();
            $details = BillingDetail::when(count($selected_bill) > 0, function ($query) use ($selected_bill) {
                return $query->whereIn('billing_id', $selected_bill);
            })->get();
            $array = array();
            foreach ($biling as $row) {
                $array1 = array();
                foreach ($details as $r) {
                    if ($r->billing_id == $row->id) {
                        array_push($array1, $r);
                    }
                }
                $row['details'] = $array1;
                array_push($array, $row);
            }
            $data['billing'] = $array;
            $returnHTML = view('admin.bulk.invoice-print', $data)->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function getBillId($dates)
    {
        $ledger_id=$dates['type'];
        $data = array();
        if($dates['off_type']=='Office'){
            if($ledger_id==29){
                $ledger_id=73;
            }elseif ($ledger_id==31){
                $ledger_id=32;
            }elseif ($ledger_id==33){ // electricity
                $ledger_id=26;
            }

        }else if (trim($dates['off_type'])=='Others'){
            if ($ledger_id==33){
                $ledger_id=116;
                DB::statement('SET @@session.group_concat_max_len = 1000000;');
                $data = DB::select("SELECT
  GROUP_CONCAT(DISTINCT `billings`.`meter_no` SEPARATOR ';') as meter_no
FROM
  `billings`
  LEFT JOIN `billing_details`
    ON `billing_details`.`billing_id` = billings.id
  LEFT JOIN `meters`
    ON `meters`.`customer_id` = billings.`customer_id`
WHERE `billing_details`.`month` = '$dates[month]'
  AND billing_details.`ledger_id` = '$ledger_id'
  AND meters.`off_type` = '$dates[off_type]'");
                $result = collect($data)->pluck('meter_no')->toArray();
                return array('shop_no'=>$result,'meter_no'=>$result);
            }
        }else if($ledger_id==33 && $dates['off_type']=='Shop'){
            DB::statement('SET @@session.group_concat_max_len = 1000000;');
            $data = DB::select("SELECT
  GROUP_CONCAT(DISTINCT `billings`.`shop_no` SEPARATOR ';') as shop_no,
  GROUP_CONCAT(DISTINCT `billings`.`meter_no` SEPARATOR ';') as meter_no
FROM
  `billings`
  LEFT JOIN `billing_details`
    ON `billing_details`.`billing_id` = billings.id
  LEFT JOIN `meters`
    ON `meters`.`customer_id` = billings.`customer_id`
WHERE `billing_details`.`month` = '$dates[month]'
  AND billing_details.`ledger_id` = '$ledger_id'
  AND meters.`off_type` = '$dates[off_type]'");
            $result = collect($data)->pluck('shop_no')->toArray();
            $result1 = collect($data)->pluck('meter_no')->toArray();
            return array('shop_no'=>$result,'meter_no'=>$result1);
        }
        if($ledger_id==26){
            DB::statement('SET @@session.group_concat_max_len = 1000000;');
            $data = DB::select("SELECT
  GROUP_CONCAT(DISTINCT `billings`.`shop_no` SEPARATOR ';') as shop_no,
  GROUP_CONCAT(DISTINCT `billings`.`meter_no` SEPARATOR ';') as meter_no
FROM
  `billings`
  LEFT JOIN `billing_details`
    ON `billing_details`.`billing_id` = billings.id
  LEFT JOIN `meters`
    ON `meters`.`customer_id` = billings.`customer_id`
WHERE `billing_details`.`month` = '$dates[month]'
  AND billing_details.`ledger_id` = '$ledger_id'
  AND meters.`off_type` = '$dates[off_type]'");
            $result = collect($data)->pluck('shop_no')->toArray();
            $result1 = collect($data)->pluck('meter_no')->toArray();
            return array('shop_no'=>$result,'meter_no'=>$result1);
        }

        DB::statement('SET @@session.group_concat_max_len = 1000000;');
        $data = DB::select("SELECT
  GROUP_CONCAT(DISTINCT `billings`.`shop_no` SEPARATOR ';') as shop_no
FROM
  `billings`
  LEFT JOIN `billing_details`
    ON `billing_details`.`billing_id` = billings.id
  LEFT JOIN `assets`
    ON `assets`.`customer_id` = billings.`customer_id`
WHERE `billing_details`.`month` = '$dates[month]'
  AND billing_details.`ledger_id` = '$ledger_id'
  AND assets.`off_type` = '$dates[off_type]'");
        $result = collect($data)->pluck('shop_no')->toArray();
        return $result;



    }

    public function insertSCService($checkData, $invoice_no, $customer, $voucher_no, $key)
    {

        $customer_id = $checkData['customer_id'];
        $category = $checkData['category'];
        $off_type = $checkData['type'];
//        $vat = $checkData['vat'] ;
//        $vat_rate = $checkData['vat_rate'] ;
        $amount = $checkData['amount'];
//        $total = $checkData['total'] ;
        $area = $checkData['area'];
        $rate = $checkData['rate'];
        $due_date = $checkData['due_date'];
        $month = $checkData['month'];
        $asset_no = $checkData['asset_no'];
        $shop_no = $asset_no[$key];
        $vat_amount = 0;//$vat[$key];
        $journal_date = $checkData['journal_date'];
        $issue_date = $checkData['issue_date'];
        $fine_applicable = $checkData['fine_applicable'];
        $grand_total = $amount[$key];
        $customer_ids = $customer_id[$key];
        $assIds = Asset::where('asset_no',$shop_no)->first();
        $owner_id = $assIds->owner_id;
        $income = new Billing();
        $income->customer_id = $customer_id[$key];
        $income->bill_type = "Special Service Charge";
        $income->shop_no = $shop_no;
        $income->owner_id = $assIds->owner_id;
        $income->asset_shop_name = $assIds->shop_name??"";
        $income->off_type = $off_type;
        $income->shop_name = $customer->shop_name;
        $income->person_id = 0;
        $income->fine_applicable = $fine_applicable;
        $income->issue_date = $issue_date;
        $income->journal_date = $journal_date;
        $income->due_date = $due_date;
        $income->credit_period = $month;
        $income->invoice_no = $invoice_no;
        $income->voucher_no = $voucher_no;
        $income->vat = 0;
        $income->vat_amount = $vat_amount;
        $income->post_date = date('Y-m-d');
        $income->total = $amount[$key];
        $income->grand_total = $amount[$key] ?? 0;
        $income->created_by = Auth::user()->id;
        $income->module = 'Bulk Entry';

        $income->save();
        $income_id = $income->id;

        $jv = array();;
        $coa = ChartOfAccount::getLedger($category);
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $ledger_name = $coa->head;
        $group_name = $coa->group_name;

        $details = new BillingDetail();
        $details->billing_id = $income_id;
        $details->ledger_name = $ledger_name;
        $details->ledger_id = $ledger_id;
        $details->month = $month;
        $details->fine_applicable = $fine_applicable;
        $details->fine = $fixedAmount[$key] ?? 0;
        $details->interest = 0;// $interest[$key];
        $details->amount = $amount[$key];
        $details->area_sft = $area[$key] ?? 0;
        $details->rate_sft = $rate[$key] ?? 0;
        $details->vat = 0;
        $details->vat_amount = $vat_amount;
        $details->total = $amount[$key] ?? 0;
        $details->current_reading = '';
        $details->pre_reading = '';
        $details->kwt = '';
        $details->kwt_rate = '';
        $effective = $journal_date;
        $a = $amount[$key];

        $remarks = "Special Service Charge for Shop No# $shop_no for the month of $month  @ Tk. $amount[$key]";

        $details->remarks = $remarks;
        $details->effective_date = $effective;
        $details->save();
        $sub = array('ref_id' => $income_id,'owner_id'=>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
            'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
            'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($customer->shop_name),
            'remarks' => $remarks, 'ledger_head' => $ledger_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'date' => $issue_date, 'debit' => 0,
            'credit' => $amount[$key], 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry',
            'created_by' => Auth::user()->id);
        array_push($jv, $sub);


        $remarks = "$vat_amount Rent vat @ ";
        $coa = ChartOfAccount::getLedger(38); // Sales VAT Payable A/C
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id,'owner_id'=>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'remarks' => '', 'ledger_head' => 'Sales VAT Payable A/C',
            'date' => $issue_date, 'debit' => 0, 'credit' => $vat_amount, 'voucher_no' => $voucher_no,
            'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
        if ($vat_amount != 0) {
            array_push($jv, $sub);
        }
        $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
        $ledger_type = $coa->type;
        $ledger_code = $coa->system_code;
        $ledger_id = $coa->id;
        $group_name = $coa->group_name;
        $sub = array('ref_id' => $income_id,'owner_id'=>$owner_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
            'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
            'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
            'customer_name' => $customer->shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_ids, 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
            'date' => $issue_date, 'debit' => $grand_total, 'credit' => 0, 'voucher_no' => $voucher_no,
            'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
        array_push($jv, $sub);
        Journal::insert($jv);
        return $income;

    }

    public function makePreviousBill()
    {

        $rows = Excel::toArray([], 'Sample Old Invoice Upload_Test (cased shop)_uploaded_rony mondal-27.10.22.xlsx');
        echo "<pre>";
        print_r($rows[0]);
//         die();
        $customerArray = array();
        foreach ($rows[0] as $key => $r) {
            if ($key == 0) {
                continue;
            }
            $count = Billing::count();
            $count++;
            $voucher_no = "SV/" . date('y') . "/" . date('m') . '/' . $count;
            $effective = trim($r[19]) != '' ? date('Y-m-d', strtotime($r[19])) : '';
            $issue_date = trim($r[22]) != '' ? date('Y-m-d', strtotime($r[22])) : '';
            $shop_no = trim($r[0]);
            $shop_name = trim($r[1]);
            $meter_no = trim($r[3]);
            $invoice_no = trim($r[27]);
            $customer_id = trim($r[2]);
            $amount = round(trim($r[13]));
            $fine = round(trim($r[14]));
            $interest = round(trim($r[15]));
            $grand_total = $fine+$amount+$interest;
            $month = trim($r[24]) != '' ? date('M Y', strtotime($r[24])) : '';
            $bill_type='';
            $ledger_id=0;
            if (trim($r[25]) == 'Rent') {
                $bill_type = "Rent";
                $ledger_id = 29;
                $ledger_name = 'Shop Rental Revenue';
                $remarks = "Rent for Shop No# $shop_no for the month of $month for $r[4] sft @ Tk. $r[12]";
            } else if (trim($r[25]) == 'Service Charge') {
                $bill_type = "Service Charge";
                $ledger_id = 31;
                $ledger_name = 'Service Charge Revenue';
                $remarks = "Service Charge for Shop No# $shop_no for the month of $month for $r[4] sft @ Tk. $r[12]";
                // service charge interest ledger

            } else if (trim($r[25]) == 'Electricity') {
                $bill_type = "Electricity";
                $ledger_id = 33;
                $ledger_name = 'Utility-Electricity Revenue';
                $remarks = "Electricity bill for Shop No# $shop_no for the month of $month for  meter no# " . $meter_no;
            } else if (trim($r[25]) == 'Special SC') {
                $ledger_id = 43;
                $bill_type = "Special Service Charge";
                $ledger_name = 'Special Service Charge';
                $remarks = "Special Service Charge for Shop No# $shop_no for the month of $month  @ Tk. $r[18]";
            } else if (trim($r[25]) == 'Advertisement') {
                $ledger_id = 44;
                $bill_type = "Advertisement";
                $ledger_name = 'Advertisement';
                $remarks = "Advertisement space rent for  $r[6] & Code: $r[5] for month $month";
            } else if (trim($r[25]) == 'Food Court SC') {
                $ledger_id = 34;
                $bill_type = "Food Court Service Charge";
                $ledger_name = 'Food Court SC';
                $remarks = "Food Court Service Charge for Shop No# $shop_no for the month of $month  @ Tk. $r[18]";
            }
            if($ledger_id==0){
                continue;
            }

            $subarray = array(
                'shop_no' => $shop_no,
                'shop_name' => $shop_name,
                'customer_id' => trim($r[2]),
                'total' => trim($r[13]),
                'vat' => trim(0),
                'vat_amount' => trim($r[17]),
                'grand_total' => $grand_total,
                'fine_amount' => $fine,
                'journal_date' => $effective,
                'issue_date' => $issue_date,
                'due_date' => trim($r[26]) != '' ? date('Y-m-d', strtotime($r[26])) : '',
                'fine_applicable' => trim($r[20]),
                'owner_id' => trim($r[23]),
                'bill_type' => $bill_type,
                'off_type' => trim($r[21]),
                'invoice_no' => $invoice_no,
                'voucher_no' => $voucher_no,
                'meter_no' => $meter_no,
                'credit_period' => $month,
                'meter_reading_date' => '',
                'module' => 'Bulk Entry',
                'post_date' => date('Y-m-d'),
                'created_by' => Auth::user()->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $bill = Billing::insert($subarray);
            $productId = $income_id = DB::getPdo()->lastInsertId();


            $sub = array(
                'billing_id' => $productId,
                'ledger_id' => $ledger_id,
                'ledger_name' => $ledger_name,
                'month' => $month,
                'area_sft' => trim($r[4]),
                'rate_sft' => trim($r[12]),
                'amount' => $amount,
                'fine' => $fine,
                'interest' => $interest,
                'fine_applicable' => trim($r[20]),
                'vat' => trim($r[17]),
                'vat_amount' => trim($r[17]),
                'total' => $grand_total,
                'current_reading' => trim($r[9]),
                'pre_reading' => trim($r[10]),
                'kwt' => trim($r[11]),
                'space_name' => trim($r[6]),
                'kwt_rate' => trim($r[12]),
                'meter_no' => $meter_no,
                'payment_amount' => 0,
                'due_amount' => 0,
                'effective_date' => trim($r[19]) != '' ? date('Y-m-d', strtotime($r[19])) : '',
            );

            BillingDetail::insert($sub);
            Logs::store(Auth::user()->name . ' New Bill has been created successfull ', 'Add', 'success', Auth::user()->id, $productId, 'Billing');
            $coa = ChartOfAccount::getLedger($ledger_id); // $ledger_id
            $ledger_type = $coa->type;
            $ledger_code = $coa->system_code;
            $ledger_id = $coa->id;
            $ledger_name = $coa->head;
            $group_name = $coa->group_name;
            $remarks = '';

            $sub = array();
            $jv = array();
            $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
                'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
                'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'shop_no' => $shop_no, 'customer_id' => $customer_id, 'customer_name' => trim($shop_name),
                'remarks' => $remarks, 'ledger_head' => $ledger_name, 'date' => $issue_date, 'debit' => 0,
                'credit' => $amount, 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry',
                'created_by' => Auth::user()->id);
            array_push($jv, $sub);

            $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
            $ledger_type = $coa->type;
            $ledger_code = $coa->system_code;
            $ledger_id = $coa->id;
            $group_name = $coa->group_name;
            $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
                'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
                'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
                'customer_name' => $shop_name, 'shop_no' => $shop_no, 'customer_id' => $customer_id, 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
                'date' => $issue_date, 'debit' => $amount, 'credit' => 0, 'voucher_no' => $voucher_no,
                'ref_module' => 'Bulk Entry', 'created_by' => Auth::user()->id);
            array_push($jv, $sub);
            Journal::insert($jv);

            if (trim($r[25]) == 'Service Charge') {
                $bill_type = "Service Charge";
                $sub = array();
                $jv = array();
                $remarks = "Service Charge  bill Interest for Shop No# $shop_no for the month of $month ";
                $coa = ChartOfAccount::getLedger(76); // Sales VAT Payable A/C
                $ledger_type = $coa->type;
                $ledger_code = trim($coa->system_code);
                $ledger_id = $coa->id;
                $group_name = $coa->group_name;
                $ledger_name = $coa->head;
                $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
                    'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
                    'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($shop_name),
                    'remarks' => $remarks, 'ledger_head' => $ledger_name, 'date' => $issue_date, 'debit' => 0, 'is_fine' => 1,
                    'credit' => $fine, 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry', 'shop_no' => $shop_no,
                    'created_by' => Auth::user()->id,'customer_id' => $customer_id);

                array_push($jv,$sub);

                $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
                $ledger_type = $coa->type;
                $ledger_code = $coa->system_code;
                $ledger_id = $coa->id;
                $group_name = $coa->group_name;
                $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
                    'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
                    'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
                    'customer_name' => trim($shop_name), 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
                    'date' => $issue_date, 'debit' => $fine, 'credit' => 0, 'voucher_no' => $voucher_no, 'is_fine' => 1,
                    'shop_no' => $shop_no, 'ref_module' => 'Bulk Entry','customer_id' => $customer_id, 'created_by' => Auth::user()->id);
                array_push($jv, $sub);
//                Journal::insert($jv);
                Journal::insert($jv);
                // return "ok";

            } else if (trim($r[25]) == 'Electricity') {
                $bill_type = "Electricity";
                $bill_type = "Service Charge";
                $sub = array();
                $jv = array();
                $remarks = "Electricity bill Interest for Shop No# $shop_no for the month of $month for  meter no# " . $meter_no;
                $coa = ChartOfAccount::getLedger(30); // Sales VAT Payable A/C
                $ledger_type = $coa->type;
                $ledger_code = trim($coa->system_code);
                $ledger_id = $coa->id;
                $group_name = $coa->group_name;
                $ledger_name = $coa->head;
                $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id, 'ledger_type' => $ledger_type,
                    'ledger_code' => trim($ledger_code), 'post_date' => date('Y-m-d'), 'effective_date' => $effective,
                    'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($shop_name),
                    'remarks' => $remarks, 'ledger_head' => $ledger_name, 'date' => $issue_date, 'debit' => 0, 'is_fine' => 1,
                    'credit' => $fine, 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry', 'shop_no' => $shop_no,
                    'created_by' => Auth::user()->id,'customer_id' => $customer_id);
                array_push($jv,$sub);

                $coa = ChartOfAccount::getLedger(36); //'Accounts Receivable'
                $ledger_type = $coa->type;
                $ledger_code = $coa->system_code;
                $ledger_id = $coa->id;
                $group_name = $coa->group_name;
                $sub = array('ref_id' => $income_id, 'group_name' => $group_name, 'ledger_id' => $ledger_id,
                    'ledger_type' => $ledger_type, 'ledger_code' => $ledger_code, 'post_date' => date('Y-m-d'),
                    'effective_date' => $effective, 'transaction_type' => 'Billing', 'invoice_no' => $invoice_no,
                    'customer_name' => trim($shop_name), 'remarks' => '', 'ledger_head' => 'Accounts Receivable',
                    'date' => $issue_date, 'debit' => $fine, 'credit' => 0, 'voucher_no' => $voucher_no, 'is_fine' => 1,
                    'shop_no' => $shop_no, 'ref_module' => 'Bulk Entry','customer_id' => $customer_id, 'created_by' => Auth::user()->id);
                array_push($jv, $sub);
                Journal::insert($jv);

            }
//            return $jv;

        }
    }


}
