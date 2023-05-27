<?php

namespace App\Http\Controllers;


use App\helpers\pdfHtmlView;
use App\Http\PigeonHelpers\otherHelper;
use App\Models\Asset;
use App\Models\AssetLog;
use App\Models\CashCollection;
use App\Models\CashCollectionDetail;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\CustomerLog;
use App\Models\Income;
use App\Models\Billing;
use App\Models\IncomeDetail;
use App\Models\BillingDetail;
use App\Models\Journal;
use App\Models\Lookup;
use App\Models\Meter;
use App\Models\Owner;
use App\Models\RateInfo;
use App\Models\SecurityDeposit;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\LogController as Logs;
use Illuminate\Support\Facades\Auth;
use App\helpers\pdfView;
use DB;
use DateTime;
use PDF;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generalLedger(Request $request)
    {

        $data['page_name'] = "General Ledger";
        $data['customer'] = Customer::orderBy('shop_name', 'ASC')->get();
        $data['asset'] = Asset::orderBy('asset_no', 'ASC')->get();
        $data['vendor'] = Vendor::orderBy('vendor_name', 'ASC')->get();
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('General Ledger', 'active'),
            array('Report', 'active')
        );
        return view('admin.reports.gl', $data);
    }

    public function generalLedgerShow(Request $request)
    {

        $checkData = $request->all();
        $customer = $checkData['customer'] != 'None' ? $checkData['customer'] : '';
        $creaditors = $checkData['creaditors'] != 'None' ? $checkData['creaditors'] : '';
        $shop_no = $checkData['shop_no'] != '' ? $checkData['shop_no'] : '';
        $journal = Journal::where('ledger_id', '=', $checkData['ledger'])
            ->where('effective_date', '>=', $checkData['date_s'])
            ->where('effective_date', '<=', $checkData['date_e'])
            ->when($customer != '', function ($query) use ($customer) {
                return $query->where('customer_name', $customer);
            })
            ->when($shop_no != '', function ($query) use ($shop_no) {
                return $query->where('shop_no', $shop_no);
            })
            ->when($creaditors != '', function ($query) use ($creaditors) {
                return $query->where('customer_name', $creaditors);
            })
            ->groupBy('ref_id', 'ref_module')
            ->orderBy('effective_date', 'desc')
            ->get();
        $openingBalance = $this->getOpeningBalance($checkData);
        $opositResult = array();
        foreach ($journal as $row) {
            $ar = $this->indivisualTransaction($row, $checkData['ledger']);
            foreach ($ar as $r) {
                array_push($opositResult, $r);
            }
        }

        $data['page_name'] = "General Ledger";
        $data['account_head'] = $checkData['ledger_head'];
        $data['type'] = $checkData['type'];
        $data['openingBalance'] = $openingBalance;
        $data['journal'] = $opositResult;
        $data['date'] = date('d M Y', strtotime($checkData['date_s'])) . ' to ' . date('d M Y', strtotime($checkData['date_e']));
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('General Ledger', 'report.gl'),
            array('Report', 'active')
        );
        $returnHTML = view('admin.reports.gl_print', $data)->render();
        return response()->json(array('success' => true, 'result' => $returnHTML));
    }

    public function ledger($type)
    {
        $data['ledger'] = ChartOfAccount::where('type', $type)->groupBy('head')->orderBy('head', 'asc')->get();
        echo json_encode($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indivisualTransaction($arr, $head)
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
            if ($r['ledger_id'] == $head) {
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

        $dr = $this->checkMoreDebit($arr, $head, $debit, $credit);


        foreach ($journal as $row) {
            $sub = array();
            $sub['effective_date'] = $row['effective_date'];
            $sub['customer_name'] = $row['customer_name'];
            $sub['shop_no'] = $row['shop_no'];
            $sub['payment_ref'] = $row['payment_ref'];
            $sub['voucher_no'] = $row['voucher_no'];
            if ($debit != 0) {
                if ($row['ledger_id'] == $head) {
                    continue;
                }
                if (count($result['credit']) > 1) {
                    $sub['debit'] = $row['credit'];
                    $sub['credit'] = $row['debit'];
                } else {
                    $sub['debit'] = $debit;
                    $sub['credit'] = 0.00;
                }

//                if ((int)$arr['debit'] != 0) {
//                    if ($row['debit'] != 0) {
//                        $sub['debit'] = $row['debit'];
//                    } else {
//                        $sub['debit'] = $row['credit'];
//                    }
//                } else if ((int)$arr['credit'] != 0) {
//                    if ($row['debit'] != 0) {
//                        $sub['credit'] = $row['debit'];
//                    } else {
//                        $sub['credit'] = $row['credit'];
//                    }
//                }
//                if (isset($dr[$row['ledger_head']])) {
//                    $sub['debit'] = 0.00;
//                    $sub['credit'] = $dr[$row['ledger_head']];
//                }
                $sub['ledger_head'] = $row->ledger->head ?? "";//$row['ledger_head'];
                array_push($array, $sub);
            } else if ($credit != 0) {
                if ($row['ledger_id'] == $head) {

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

//                if((int)$arr['debit']!=0){
//                    if($row['debit']!=0){
//                        $sub['debit'] =$row['debit'];
//                    }else{
//                        $sub['debit'] =$row['credit'];
//                    }
//                }
//                else if((int)$arr['credit']!=0){
//                    if($row['debit']!=0){
//                        $sub['credit'] =$row['debit'];
//                    }else{
//                        $sub['credit'] =$row['credit'];
//                    }
//                }
//                if(isset($dr[$row['ledger_head']])){
//                    $sub['debit'] =0.00;
//                    $sub['credit'] =$dr[$row['ledger_head']];
//                }


                $sub['ledger_head'] = $row->ledger->head ?? "";//['ledger_head'] ;

                array_push($array, $sub);
            }

            /*

            if ($head == 'Accounts Receivable') {

                if ($row['ledger_head'] == 'Accounts Receivable') {
                    continue;
                }
                $sub['debit'] = 0.00;
                $sub['credit'] = 0.00;
                if ((int)$arr['debit'] != 0) {
                    if ($row['debit'] != 0) {
                        $sub['debit'] = $row['debit'];
                    } else {
                        $sub['debit'] = $row['credit'];
                    }
                } else if ((int)$arr['credit'] != 0) {
                    if ($row['debit'] != 0) {
                        $sub['credit'] = $row['debit'];
                    } else {
                        $sub['credit'] = $row['credit'];
                    }
                }
                if (isset($dr[$row['ledger_head']])) {
                    $sub['debit'] = 0.00;
                    $sub['credit'] = $dr[$row['ledger_head']];
                }
                $sub['ledger_head'] = $row['ledger_head'];
                array_push($array, $sub);
            } else {
                if ($row['ledger_head'] != $head)
                    continue;
                $sub['debit'] = 0.00;
                $sub['credit'] = 0.00;
                if ((int)$arr['debit'] != 0) {
                    if ($row['debit'] != 0) {
                        $sub['debit'] = $row['debit'];
                    } else {
                        $sub['debit'] = $row['credit'];
                    }

                } else if ((int)$arr['credit'] != 0) {
                    if ($row['debit'] != 0) {
                        $sub['credit'] = $row['credit'];
                    } else {
                        $sub['credit'] = $row['credit'];
                    }

                }
                $sub['ledger_head'] = $row['ledger_head'];
                array_push($array, $sub);
            }

*/
        }


        /*
                    if($row['ledger_head']==$head){
                        $debit=$row['debit'];
                        $credit=$row['credit'];
                        continue;
                    }
                    $sub['debit'] =0.00;
                    $sub['credit'] =0.00;
                    if((int)$arr['debit']!=0){
                        if($row['debit']!=0){
                            $sub['debit'] =$row['debit'];
                        }else{
                            $sub['debit'] =$row['credit'];
                        }
                    }
                    else if((int)$arr['credit']!=0){
                        if($row['debit']!=0){
                            $sub['credit'] =$row['debit'];
                        }else{
                            $sub['credit'] =$row['credit'];
                        }
                    }
                    if(isset($dr[$row['ledger_head']])){
                        $sub['debit'] =0.00;
                        $sub['credit'] =$dr[$row['ledger_head']];
                    }

                    if($debit > 0 && ){
                        $sub['ledger_head'] = $row['ledger_head'] ;
                    }
                    array_push($array,$sub);
                }*/
        return $array;

    }

    public function checkMoreDebit($arr, $head, $debit, $credit)
    {
        $journal = Journal::where('ref_id', $arr['ref_id'])
            ->where('ref_module', $arr['ref_module'])
            ->get();
        $res = array();
        foreach ($journal as $row) {
            $res[$row['ledger_id']] = $row['debit'];
        }
        return $res;
    }

    public function getOpeningBalance($data)
    {
//        return $data;
        $date1 = date_create($data['date_s']);
        $date2 = date_create($data['date_e']);
        $diff = date_diff($date1, $date2);
        $customer = '';
        if (isset($data['customer']) && $data['customer'] != 'None') {
            $customer = $data['customer'];
        }
        $creaditors = '';
        if (isset($data['creaditors']) && $data['creaditors'] != 'None') {
            $creaditors = $data['creaditors'];
        }

        // $creaditors = isset($data['creaditors']) ? $data['creaditors'] : '';
        $day = $diff->format("%r%a");
        $p_date_e = date('Y-m-d', strtotime("$data[date_s] -1 day"));
        $journal = Journal::where('ledger_id', $data['ledger'])
            ->where('effective_date', '<=', $p_date_e)
            ->when($customer != '', function ($query) use ($customer) {
                return $query->where('customer_name', $customer);
            })
            ->when($creaditors != '', function ($query) use ($creaditors) {
                return $query->where('customer_name', $creaditors);
            })
            ->groupBy('ref_id')
            ->get();
        $opening = 0;
        foreach ($journal as $row) {
            $ar = $this->indivisualTransaction($row, $data['ledger']);
            foreach ($ar as $r) {
                $opening += ($r['debit'] - $r['credit']);
            }


        }
        return abs($opening);

    }

    /**
     * trial blance display form
     */
    public function trialBalance(Request $request)
    {
        $data['page_name'] = "Trial Balance";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Trial Balance', 'report.tb'),
            array('Report', 'active')
        );

        if ($request->isMethod('post')) {
            $checkData = $request->all();
            $journal = Journal::where('effective_date', '>=', $checkData['date_s'])
                ->where('effective_date', '<=', $checkData['date_e'])
                ->groupBy('ledger_id')
                ->orderBy('ledger_type', 'asc')
                ->get();
            $opositResult = array();
            foreach ($journal as $row) {
                $balance = $this->groupLedgerShow($row, $checkData);
                if ($row['ledger_type'] == 'Asset' || $row['ledger_type'] == 'Expense') {
                    if ($balance > 0) {
                        $row['debit'] = $balance;
                        $row['credit'] = 0.00;
                    } else {
                        $row['debit'] = 0.00;
                        $row['credit'] = abs($balance);
                    }
                } else if ($row['ledger_type'] == 'Income' || trim($row['ledger_type']) == 'Liability') {
                    if ($balance > 0) {
                        $row['credit'] = $balance;
                        $row['debit'] = 0.00;
                    } else {
                        $row['credit'] = 0.00;
                        $row['debit'] = abs($balance);
                    }
                }
                $row['ledger_head'] = $row->ledger->head ?? "";
                array_push($opositResult, $row);

            }

            $data['trialBalance'] = $opositResult;
            $data['date'] = date('d M Y', strtotime($checkData['date_s'])) . ' to ' . date('d M Y', strtotime($checkData['date_e']));


            $returnHTML = view('admin.reports.tb_print', $data)->render();
            return response()->json(array('success' => true, 'result' => $returnHTML));

        } else {
            return view('admin.reports.tb', $data);
        }

    }

    public function groupLedgerShow($data, $postData)
    {
        $journal = Journal::where('effective_date', '>=', $postData['date_s'])
            ->where('effective_date', '<=', $postData['date_e'])
            ->where('ledger_id', '=', $data['ledger_id'])
            ->get();
        $groupResult = 0;
        foreach ($journal as $row) {
            if ($row['ledger_type'] == 'Asset') {
                $groupResult += $row['debit'] - $row['credit'];
            } elseif ($row['ledger_type'] == 'Expense') {
                $groupResult += $row['debit'] - $row['credit'];
            } elseif ($row['ledger_type'] == 'Income') {
                $groupResult += ($row['credit'] - $row['debit']);
            } elseif ($row['ledger_type'] == 'Liability') {
                $groupResult += ($row['credit'] - $row['debit']);
            }

        }
        return $groupResult;

    }

    /**
     * Receivable Statement Report.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function receivableStatement(Request $request)
    {
        $data['page_name'] = "Receivable Statement";

        $data['customer'] = Customer::all();
        $data['shop_no'] = Asset::groupBy('asset_no')->orderBy('asset_no', 'asc')->get();
        $data['type'] = 0;
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Receivable Statement', 'report.rs'),
            array('Report', 'active')
        );
        if ($request->isMethod('post')) {
            try {
                $checkData = $request->all();
                $selected_customer = '';
                // $shop_no = array();
                $date_s = '';
                $date_e = '';
                $off_type = '';
                $bill_type = '';
                if ($checkData['customer'] != '') {
                    $selected_customer = $checkData['customer'];
                }
                // if ($checkData['shop_no'] != '') {
                //     $shop_no = $checkData['shop_no'];
                // }
                if ($checkData['date_s'] != '') {
                    $date_s = $checkData['date_s'];
                }
                if ($checkData['date_e'] != '') {
                    $date_e = $checkData['date_e'];
                }
                if ($checkData['service'] != '') {
                    $off_type = $checkData['service'];
                }
                if ($checkData['bill_type'] != '') {
                    $bill_type = $checkData['bill_type'];
                }
                $paidDues = $checkData['type'] == 1 ? 0 : 1;

                $data['type'] = $checkData['type'];
                
                 if($checkData['shop_no']!=''){
                    $shop_no = explode(',',$checkData['shop_no']);
                }else{
                    $shop_no = array();
                }
                 $array = array();
                 $array1 = array();
                foreach ($shop_no as $s) {
                     $ar1 = explode('@@@',$s);
                     array_push($array, $ar1[0]);
                     array_push($array1, $ar1[1]);
                }
                 if($selected_customer!=''){
                   array_push($array1,$selected_customer);
                 }
                $result = Billing:: where('billings.payment_status', '=', $paidDues)
                     ->when(count($array1)>0, function ($query) use ($array1) {
                        return $query->whereIn('billings.customer_id',  $array1);
                    })
                      ->when(count($array)>0, function ($query) use ($array) {
                           return $query->whereIn('billings.shop_no',$array);
                       })
                    ->when($bill_type != '', function ($query) use ($bill_type) {
                        return $query->where('billings.bill_type', $bill_type);
                    })
                    ->when($off_type != '', function ($query) use ($off_type) {
                        return $query->where('billings.off_type', $off_type);
                    })
                    ->when($date_s != '', function ($query) use ($date_s) {
                        return $query->where('billings.journal_date', '>=', $date_s);
                    })
                    ->when($date_e != '', function ($query) use ($date_e) {
                        return $query->where('billings.journal_date', '<=', $date_e);
                    })
                    ->select('billings.*',
                        'cash_collections.money_receipt_no',
                        'cash_collections.payment_mode',
                        'cash_collections.cheque_no',
                        'owners.name as owner_name',
                        'cash_collections.created_at as payment_date')
                    ->leftJoin('cash_collections', 'billings.id', '=', 'cash_collections.income_id')
                    ->leftJoin('owners', 'billings.owner_id', '=', 'owners.id')
                    ->groupBy('id')
                    ->orderBy('invoice_no', 'desc')
                    ->get();
                $incomeResult = array();
                foreach ($result as $row) {
                    $subR = BillingDetail::billingDetail($row['id'], $row);
//                $subR['sc_fine'] = $row['fine_amount'];
//                $subR['sc_fixed_fine'] = $row['fixed_fine'];
                    $row['ledger'] = $subR;
                    $row['month'] = $subR['month'];
                    array_push($incomeResult, $row);
                }
                $data['rsResult'] = $incomeResult;
                if ($checkData['date_s'] != '' && $checkData['date_e'] != '') {
                    $data['date'] = date('d M Y', strtotime($checkData['date_s'])) . ' to ' . date('d M Y', strtotime($checkData['date_e']));

                } else {
                    $data['date'] = ' As on ' . date(' d M  Y');

                }
                if($checkData['t']=='pdf') {
                    $returnHTML = view('admin.reports.rs_print', $data)->render();
//                $data = ['title' => 'Welcome to Pakainfo.com'];
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->set_paper('A4', 'landscape');
                    $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                    $pdf->loadHTML($d);
                    return $pdf->stream();
                }


                $returnHTML = view('admin.reports.rs_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
               // return view('admin.reports.rs', $data);

            }catch (\Exception $e){
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }

        } else {
              $data['asset'] = Billing::leftjoin('customers', 'customers.id', '=', 'billings.customer_id')->SelectRaw('billings.*,customers.shop_name as name')
                ->orderBy('billings.shop_no', 'ASC')
                ->groupBy('billings.customer_id')
                ->groupBy('billings.shop_no')
                ->get();
            return view('admin.reports.rs', $data);
        }
    }

    /**
     * Income statement
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function incomeStatement(Request $request)
    {
        $data['page_name'] = "Income Statement";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Income Statement', 'active'),
            array('Report', 'active')
        );

        if ($request->isMethod('post')) {
            $checkData = $request->all();
            $data['date'] = date('d M Y', strtotime($checkData['date_s'])) . ' to ' . date('d M Y', strtotime($checkData['date_e']));
            $journal = Journal::where('effective_date', '>=', $checkData['date_s'])
                ->where('effective_date', '<=', $checkData['date_e'])
                ->where(function ($query) {
                    $query->where('ledger_code', 'LIKE', "I%")
                        ->orWhere('ledger_code', 'LIKE', "E%");
                })
                ->selectRaw('journals.*, sum(debit) as debit,sum(credit) as credit')
                ->groupBy('ledger_id', 'effective_date')
                ->get();
            $dates = array();
            $sdate = strtotime($checkData['date_s']);
            $edate = strtotime($checkData['date_e']);
            while ($sdate <= $edate) {
                $dates[date('m', $sdate)] = date('M-y', $sdate);
                $sdate = strtotime('+1 day', $sdate);

            }
//            $incomeStatement = $this->incomeStatementResultData($checkData,$journal);
////             var_dump($incomeStatement);
//            $data['incomeStatement'] = $incomeStatement;
//            $data['dynamicCols'] = $dates;
//            $returnHTML = view('admin.reports.is_print',$data)->render();
//            return response()->json(array('success' => true, 'result'=>$returnHTML));
            try {

                $incomeStatement = $this->incomeStatementResultData($checkData, $journal, 1);
//             var_dump($incomeStatement);
                $data['incomeStatement'] = $incomeStatement;
                $data['dynamicCols'] = $dates;
                $returnHTML = view('admin.reports.is_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));

            } catch (\Exception $e) {

                return response()->json(array('success' => true, 'result' => '<spam style="padding: 10px;"> Data Showing Error </spam>'));
            }

        } else {
            return view('admin.reports.is', $data);
        }


    }

    /**
     * Balance Sheet
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function balanceSheet(Request $request)
    {
        $data['page_name'] = "Balance Sheet";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Balance Sheet', 'active'),
            array('Report', 'active')
        );
        if ($request->isMethod('post')) {

            $checkData = $request->all();
            $data['date'] = date('d M Y', strtotime($checkData['date_s'])) . ' to ' . date('d M Y', strtotime($checkData['date_e']));

            try {

                $openingAsset = $this->getBalanceSheetOpening($checkData);
                $journal = Journal::where('effective_date', '>=', $checkData['date_s'])
                    ->where('effective_date', '<=', $checkData['date_e'])
                    ->where(function ($query) {
                        $query->where('ledger_code', 'LIKE', "A%")
                            ->orWhere('ledger_code', 'LIKE', "L%");
                    })
                    ->leftjoin('chart_of_accounts', 'chart_of_accounts.id', '=', 'journals.ledger_id')
                    ->selectRaw('journals.*,group_concat(journals.id) as concat,sum(debit) as debit,sum(credit) as credit,chart_of_accounts.group_id,chart_of_accounts.group_name')
                    ->groupBy('journals.ledger_id', 'chart_of_accounts.group_id', 'effective_date')
                    ->get();

                $incomeStatement['asset'] = array();
                $incomeStatement['liability'] = array();
                $incomeStatement['total_a'] = array();
                $incomeStatement['total_l'] = array();

                $dates = array();
                $sdate = strtotime($checkData['date_s']);
                $edate = strtotime($checkData['date_e']);
                $firstMonth = date('m', $sdate);
                while ($sdate <= $edate) {
                    $dates[date('m', $sdate)] = date('M-y', $sdate);
                    $sdate = strtotime('+1 day', $sdate);
                }

                if (!empty($openingAsset)) {
                    if (isset($openingAsset['asset'])) {
                        foreach ($openingAsset['asset'] as $key => $row) {
                            foreach ($row as $keys => $r) {
                                $incomeStatement['total_a'][$key][$firstMonth] = $openingAsset['total_a'][$key][$firstMonth];
                                $incomeStatement['asset'][$key][$keys][$firstMonth] = $openingAsset['asset'][$key][$keys];

                            }
                        }

                    }
                    if (isset($openingAsset['liability'])) {
                        foreach ($openingAsset['liability'] as $key => $row) {
                            foreach ($row as $keys => $r) {
                                $incomeStatement['total_l'][$key][$firstMonth] = $openingAsset['total_l'][$key][$firstMonth];
                                $incomeStatement['liability'][$key][$keys][$firstMonth] = $openingAsset['liability'][$key][$keys];
                            }
                        }

                    }
                }

//            var_dump($incomeStatement['total_l']);

                $dd = array();
                foreach ($journal as $row) {
                    $m = date('m', strtotime($row['effective_date']));
                    if ($row['group_name'] == '')
                        continue;
                    if ($row['ledger_type'] == 'Asset') {
                        if (isset($incomeStatement['asset'][$row['group_id']][$row['ledger_head']][$m])) {
                            $incomeStatement['asset'][$row['group_id']][$row['ledger_head']][$m] += $row['debit'] - $row['credit'];

                        } else {
                            $incomeStatement['asset'][$row['group_id']][$row['ledger_head']][$m] = $row['debit'] - $row['credit'];

                        }

                        if (isset($incomeStatement['total_a'][$row['group_id']][$m])) {
                            $incomeStatement['total_a'][$row['group_id']][$m] += $row['debit'] - $row['credit'];
                        } else {
                            $incomeStatement['total_a'][$row['group_id']][$m] = $row['debit'] - $row['credit'];
                        }


                    } else if ($row['ledger_type'] == 'Liability') {
                        if (isset($incomeStatement['liability'][$row['group_id']][$row['ledger_head']][$m])) {
                            $incomeStatement['liability'][$row['group_id']][$row['ledger_head']][$m] += $row['credit'] - $row['debit'];
                        } else {
                            $incomeStatement['liability'][$row['group_id']][$row['ledger_head']][$m] = $row['credit'] - $row['debit'];
                        }
                        if ($row['group_id'] == 42) {
                            continue;
                        }
                        if (isset($incomeStatement['total_l'][$row['group_id']][$m])) {
                            $incomeStatement['total_l'][$row['group_id']][$m] += $row['credit'] - $row['debit'];
                        } else {
                            $incomeStatement['total_l'][$row['group_id']][$m] = $row['credit'] - $row['debit'];
                        }
                    }
                }
                $retainErningOpening = $this->getRetainigOpening($checkData);


                $retainErning = Journal::where('effective_date', '>=', $checkData['date_s'])
                    ->where('effective_date', '<=', $checkData['date_e'])
                    ->where('ledger_code', 'LIKE', "%I%")
                    ->orWhere('ledger_code', 'LIKE', "%E%")
                    ->selectRaw('journals.*, sum(debit) as debit,sum(credit) as credit')
                    ->groupBy('ledger_id', 'effective_date')
                    ->get();

                $retainErningResult = $this->incomeStatementResultData($checkData, $retainErning, 0);

//             var_dump($retainErningResult);
//             var_dump($incomeStatement['total_l']);

                if (isset($retainErningResult['total_i'][$firstMonth])) {
                    $retainErningResult['total_i'][$firstMonth] += $retainErningOpening['total_i'];
                } else {
                    $retainErningResult['total_i'][$firstMonth] = $retainErningOpening['total_i'];
                }
                if (isset($retainErningResult['total_e'][$firstMonth])) {
                    $retainErningResult['total_e'][$firstMonth] += $retainErningOpening['total_e'];
                } else {
                    $retainErningResult['total_e'][$firstMonth] = $retainErningOpening['total_e'];
                }

                $data['bsStatement'] = $incomeStatement;
                $data['isStatement'] = $retainErningResult;
                $data['firstMonth'] = $firstMonth;
                $data['retainErningOpening'] = $retainErningOpening;
                $data['dynamicCols'] = $dates;
                $returnHTML = view('admin.reports.bs_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            } catch (\Exception $e) {

                return response()->json(array('success' => true, 'result' => '<div style="padding: 10px;"> ' . $e->getCode() . " " . $e->getFile() . " " . $e->getMessage() . ' ' . $e->getLine() . '</div>'));
            }
        } else {
            return view('admin.reports.bs', $data);
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getBalanceSheetOpening($checkData)
    {
        $assetGroup = array(6, 22);
        $liabilityGroup = array(42, 47);
        $preDate = date('Y-m-d', strtotime('-1 days', strtotime($checkData['date_s'])));
        $journal = Journal::where('effective_date', '<=', $preDate)
            ->where(function ($query) {
                $query->where('ledger_code', 'LIKE', "A%")
                    ->orWhere('ledger_code', 'LIKE', "L%");
            })
            ->leftjoin('chart_of_accounts', 'chart_of_accounts.id', '=', 'journals.ledger_id')
            ->selectRaw("journals.ledger_head,sum(debit) as debit,sum(credit) as credit,group_id,chart_of_accounts.group_name,DATE_FORMAT(effective_date,'%m') as month")
            ->groupBy('chart_of_accounts.group_id', 'journals.ledger_id')
            ->get();
        $result = array();
        $month = date('m', strtotime($checkData['date_s']));
        foreach ($journal as $row) {

            if (in_array($row['group_id'], $assetGroup)) {
                $result['asset'][$row['group_id']][$row['ledger_head']] = $row['debit'] - $row['credit'];

                if (isset($result['total_a'][$row['group_id']][$month])) {
                    $result['total_a'][$row['group_id']][$month] += $row['debit'] - $row['credit'];
                } else {
                    $result['total_a'][$row['group_id']][$month] = $row['debit'] - $row['credit'];

                }
            } else if (in_array($row['group_id'], $liabilityGroup)) {
                $result['liability'][$row['group_id']][$row['ledger_head']] = $row['credit'] - $row['debit'];

                if (isset($result['total_l'][$row['group_id']][$month])) {
                    $result['total_l'][$row['group_id']][$month] += $row['credit'] - $row['debit'];
                } else {
                    $result['total_l'][$row['group_id']][$month] = $row['credit'] - $row['debit'];
                }
            }
        }
        return $result;
    }

    public function getRetainigOpening($checkData)
    {
        $preDate = date('Y-m-d', strtotime('-1 days', strtotime($checkData['date_s'])));
        $month = date('m', strtotime($checkData['date_s']));

        $data = Journal::where('effective_date', '<=', $preDate)
            ->where(function ($query) {
                $query->where('ledger_code', 'LIKE', "I%")
                    ->orWhere('ledger_code', 'LIKE', "E%");
            })
            ->selectRaw('GROUP_CONCAT( DISTINCT ledger_code) AS code, sum(debit) as debit,sum(credit) as credit')
            ->groupBy('ledger_id')
            ->get();
        $result = array();
        $result['total_i'] = 0;
        $result['total_e'] = 0;
        foreach ($data as $row) {
            if (str_contains($row['code'], 'I')) {
                $result['total_i'] += $row['credit'] - $row['debit'];
            } else if (str_contains($row['code'], 'E')) {
                $result['total_e'] += $row['debit'] - $row['credit'];
            }


        }
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function incomeStatementResultData($checkData, $journal, $flag)
    {
        $incomeStatement['income'] = array();
        $incomeStatement['expense'] = array();
        $incomeStatement['total_i'] = array();
        $incomeStatement['total_e'] = array();
        $incomeStatement['ledger'] = array();
        $incomeStatement['net'] = array();


        foreach ($journal as $row) {
            $incomeStatement['ledger'][$row['ledger_id']] = $row['ledger_head'];
            $m = date('m', strtotime($row['effective_date']));
            if ($row['ledger_type'] == 'Income') {
                if (isset($incomeStatement['income'][$row['ledger_id']][$m])) {
                    $incomeStatement['income'][$row['ledger_id']][$m] += $row['credit'] - $row['debit'];

                } else {
                    $incomeStatement['income'][$row['ledger_id']][$m] = $row['credit'] - $row['debit'];

                }
                if (isset($incomeStatement['total_i'][$m])) {
                    $incomeStatement['total_i'][$m] += $row['credit'] - $row['debit'];
                } else {
                    $incomeStatement['total_i'][$m] = $row['credit'] - $row['debit'];
                }


            } else if ($row['ledger_type'] == 'Expense') {
//                $incomeStatement['expense'][$row['ledger_id']][$m] = $row;

                if (isset($incomeStatement['expense'][$row['ledger_id']][$m])) {
                    $incomeStatement['expense'][$row['ledger_id']][$m] += $row['debit'] - $row['credit'];
                } else {
                    $incomeStatement['expense'][$row['ledger_id']][$m] = $row['debit'] - $row['credit'];

                }
                if ($row['ledger_id'] == 45 && $flag) {
                    continue;
                }
                if (isset($incomeStatement['total_e'][$m])) {
                    $incomeStatement['total_e'][$m] += $row['debit'] - $row['credit'];
                } else {
                    $incomeStatement['total_e'][$m] = $row['debit'] - $row['credit'];
                }
            }
        }
        $dates = array();
        $sdate = strtotime($checkData['date_s']);
        $edate = strtotime($checkData['date_e']);
        while ($sdate <= $edate) {
            $dates[date('m', $sdate)] = date('M-y', $sdate);
            $sdate = strtotime('+1 day', $sdate);

        }
        foreach ($dates as $m => $r) {
            $total = 0;
            $total = ($incomeStatement['total_i'][$m] ?? 0) - ($incomeStatement['total_e'][$m] ?? 0);
            $incomeStatement['net'][$m] = $total - ($incomeStatement['expense'][45][$m] ?? 0);
        }
        return $incomeStatement;
    }

    public function meterReading(Request $request)
    {
        $checkData = $request->all();
        $data['page_name'] = "Meter Reading";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Meter', 'active'),
            array('Report', 'active')
        );
        if ($request->isMethod('post')) {
            try {
                $date = date("Y-m-01", strtotime($checkData['month']));
                $ar = Meter::leftjoin('customers', 'customers.id', '=', 'meters.customer_id')
                    ->where('customers.status', 1)
                    ->where('meters.off_type', $checkData['off_type'])
                    ->where('meters.status', '<>', 'Un-allotted')
                    ->selectRaw('customers.shop_name, meters.*')
                    ->OrderBy('asset_no', 'ASC')
                    ->get();
                $data['customer'] = $ar;
                $data['electrcity'] = RateInfo::where('type', '=', 33)->where('effective_date', '<=', $date)->where('off_type', '=', $checkData['off_type'])->OrderBy('id', 'DESC')->first();
                $m = date('d-m-Y', strtotime($checkData['month']));
                $day = date('M Y', strtotime("-1 months", strtotime($m)));
                $result = array();
                foreach ($ar as $row) {
                    $previous_month = BillingDetail::leftjoin('billings', 'billings.id', '=', 'billing_details.billing_id')
//                    ->where('billing_details.month','=',$day)
                        // ->where('billings.customer_id', '=', $row->customer_id)
                        ->where('billings.shop_no', '=', $row->asset_no)
                        ->where('billings.meter_no', '=', $row->meter_no)
                        ->where('billings.bill_type',  'Electricity')
                        ->where('billings.module',  'Bulk Entry')
                        ->selectRaw('billing_details.*,billings.meter_reading_date')
                        ->orderBy('billing_details.id', 'DESC')
                        ->first();

                    $row['pre_month'] = $previous_month != null ? $previous_month->current_reading : $row->opening_reading;
                    $row['pre_date'] = $previous_month != null ? otherHelper::ymd2dmy($previous_month->meter_reading_date) : otherHelper::ymd2dmy($row->date_s);
                    $row['datas'] = $previous_month;
                    $row['kwt'] = $previous_month->kwt??"";
                    array_push($result, $row);
                }
//            return $result;
//            if($data['electrcity']==null){
//                return response()->json(array('success' => false, 'html'=>''));
//            }
                $data['customer'] = $result;
                $data['month'] = $checkData['month'];
                if($checkData['t']=='pdf') {
                    $returnHTML = view('admin.reports.electricity_print', $data)->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->set_paper('A4', 'p');
                    $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                    $pdf->loadHTML($d);
                    return $pdf->stream();
                }
                $returnHTML = view('admin.reports.electricity_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            }catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }

        } else {
            return view('admin.reports.electricity_form', $data);
        }

    }

    public function meterReadingStatement(Request $request)
    {
        $checkData = $request->all();
        $data['page_name'] = "Electricity Billing Statement";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Report', 'active')
        );
        if ($request->isMethod('post')) {
            $date = date("Y-m-01", strtotime($checkData['month']));
            $ar = Billing::where('billings.bill_type', 'Electricity')
                ->leftjoin('billing_details', 'billings.id', '=', 'billing_details.billing_id')
                ->where('billing_details.month', '=', $checkData['month'])
                ->selectRaw('billings.bill_type , billings.shop_name,billings.shop_no as asset_no, billing_details.*')
                ->OrderBy('shop_name', 'ASC')
                ->get();
            $data['customer'] = $ar;
            $m = date('d-m-Y', strtotime($checkData['month']));
            $day = date('M Y', strtotime("-1 months", strtotime($m)));
            $result = array();
            foreach ($ar as $row) {
                $previous_month = BillingDetail::leftjoin('billings', 'billings.id', '=', 'billing_details.billing_id')
                    ->where('billing_details.month', '=', $day)
                    ->where('billings.bill_type', '=', 'Electricity')
                    ->where('billings.customer_id', '=', $row->customer_id)
                    ->select('billing_details.*')->first();
                $row['pre_month'] = $previous_month != null ? $previous_month->current_reading : $row->opening_reading;
                $row['pre_date'] = $previous_month != null ? otherHelper::ymd2dmy($previous_month->created_at) : '';
                $row['datas'] = $previous_month;
                array_push($result, $row);
            }
//            return $result;
//            if($data['electrcity']==null){
//                return response()->json(array('success' => false, 'html'=>''));
//            }
            $data['customer'] = $result;
            $data['month'] = $checkData['month'];
               if($checkData['t']=='pdf') {
                $returnHTML = view('admin.reports.el_reading_print', $data)->render();
//                $data = ['title' => 'Welcome to Pakainfo.com'];
                $pdf = \App::make('dompdf.wrapper');
                $pdf->set_paper('A4', 'p');
                $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                $pdf->loadHTML($d);
                return $pdf->stream();
            }
            
            $returnHTML = view('admin.reports.el_reading_print', $data)->render();
            return response()->json(array('success' => true, 'result' => $returnHTML));
        } else {
            return view('admin.reports.el_reading', $data);
        }

    }

    public function assetAllotmentReport(Request $request)
    {
        $checkData = $request->all();
        $data['page_name'] = "Asset Allotment Report";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Report', 'active')
        );
        if ($request->isMethod('post')) {
            try {


            $date_s = $checkData['date_s'];
            $date = date("Y-m-01", strtotime($date_s)); //date('Y-m-d');
//            $date_e = $checkData['date_e'];
            //$date= date("Y-m-01",strtotime($checkData['month'])); //date('Y-m-d');
//            $ar = Asset::leftjoin('customers','customers.id','=','assets.customer_id')
//                ->where('customers.status',1)
//                ->where('assets.off_type',$checkData['off_type'])
//                ->where('assets.date_s','<=',$date_s)
//                ->where('assets.date_e','>=',$date_s)
//
//                ->where('assets.status','<>','Un-allotted')
//                ->where('assets.rate','<>',0)
//                ->selectRaw('customers.*, assets.meter_no,assets.opening_reading ,assets.asset_no,floor_name,assets.area_sft as area,assets.rate as rate')
//                ->OrderBy('assets.asset_no','ASC')
//                ->take(100)
//                ->get();

            $ar = Asset::
            when($date != '', function ($query) use ($date) {
                return $query->where('date_s', '<=', $date);
            })
           ->where(function ($query) use ($date) {
                    $query->where('date_e', '>=', $date)
                        ->orWhere('assets.date_e', '=', '0000-00-00')
                        ->orWhere('assets.date_e', '=', '');
                })
                ->groupBy('id')
                ->selectRaw('assets.*,GROUP_CONCAT( DISTINCT assets.`floor_name`) as floor_name,GROUP_CONCAT( DISTINCT assets.`rate`) as rate,
                GROUP_CONCAT(STATUS) as alloted, COUNT(assets.`asset_no`) as total_shop')
                ->OrderBy('asset_no', 'ASC')
                ->get();
            $data['customer'] = $ar;

            $result = array();
            foreach ($ar as $row) {
                $alloted = explode(',', $row['alloted']);
                $rate = explode(',', $row['rate']);
                $row['open'] = 0;
                $row['close'] = 0;
                $row['unalotted'] = 0;
//                foreach ($alloted as $key=>$r) {
                    if ('Allotted & Open' == $row['alloted'] && $row['rate']>0) {

                        if(isset($result[$row['floor_name']]['open'])){
                            $result[$row['floor_name']]['open']  += 1;
                        }else{
                            $result[$row['floor_name']]['open']  =1;
                        }
                    } else if ('Allotted & Closed' == $row['alloted'] || $row['rate']==0 ) {

                        if(isset($result[$row['floor_name']]['close'])){
                            $result[$row['floor_name']]['close'] += 1;
                        }else{
                            $result[$row['floor_name']]['close'] = 1;
                        }
                    } else if ('Un-allotted' == $row['alloted']) {

                        if(isset($result[$row['floor_name']]['unalotted'])){
                            $result[$row['floor_name']]['unalotted'] += 1;
                        }else{
                            $result[$row['floor_name']]['unalotted'] = 1;
                        }
                    }
//
//                }
//                $result[$row['floor_name']]['open'] =
//                array_push($result, $row);
            }
//return $result;
                $ar = array();
                foreach ($result as $key=>$r){
                    $r['unalotted'] = isset($result[$key]['unalotted'])?$result[$key]['unalotted']:0;
                    $r['open'] = isset($result[$key]['open'])?$result[$key]['open']:0;
                    $r['close'] = isset($result[$key]['close'])?$result[$key]['close']:0;
                    $r['floor_name'] = $key;
                    array_push($ar, $r);
                }
            $data['customer'] = $ar;
            $returnHTML = view('admin.reports.asset_allortment_r_print', $data)->render();
            return response()->json(array('success' => true, 'result' => $returnHTML));
            }catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }
        } else {
            return view('admin.reports.asset_allotment_r', $data);
        }

    }

    public function duesReport(Request $request)
    {
        $checkData = $request->all();
        $data['page_name'] = "Dues Statement";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Dues Statement', 'report.dr'),
            array('Report', 'active')
        );
         $data['owner']= Owner::orderBy('name','ASC')->get();
        $data['period'] = "As on " . date('d M Y');
        if ($request->isMethod('post')) {

            $dateFilter = array();
            if ($checkData['month_from'] != '' && $checkData['month_to'] != '') {
                $date1 = new DateTime($checkData['month_from']);
                $date2 = new DateTime($checkData['month_to']);
                $interval = $date1->diff($date2);
                $year = $interval->y;
                $month = $interval->m;
                $month += $year * 12;
                $dateFilter[] = $checkData['month_from'];
                for ($i = 1; $i <= $month; $i++) {
                    $dateFilter[] = date("M Y", strtotime("+" . $i . " months", strtotime($checkData['month_from'])));
                }
                $data['period'] = "Month " . $checkData['month_from'] . " to " . $checkData['month_to'];
            }

            //  return $dateFilter;
            $array = array();
            $array1 = array();
            // $shop_no = explode(',',$checkData['shop_no']);
              if($checkData['shop_no']!=''){
                $shop_no = explode(',',$checkData['shop_no']);
            }else{
                $shop_no = array();
            }
            $bill_type = $checkData['bill_type']!=''?$checkData['bill_type']:"";
            $service = $checkData['service']!=''?$checkData['service']:"";
            $owner = $checkData['owner']!=''?$checkData['owner']:"";
            foreach ($shop_no as $s) {
                $ar1 = explode('@@@',$s);

                array_push($array, $ar1[0]);
                array_push($array1, $ar1[1]);
            }

            try {
                $ar = Billing::
                 when(count($array)>0, function ($query) use ($array) {
                        return $query->whereIn('billings.shop_no',$array);
                    })
                    ->when($bill_type!='', function ($query) use ($bill_type) {
                        return $query->where('billings.bill_type', '=', $bill_type);
                    })
                    ->when($service!='', function ($query) use ($service) {
                        return $query->where('billings.off_type', '=', $service);
                    })
                    ->when(count($array1)>0, function ($query) use ($array1) {
                        return $query->whereIn('billings.customer_id',  $array1);
                    })
                     ->when($owner!='', function ($query) use ($owner) {
                        return $query->where('billings.owner_id',  $owner);
                    })
                    ->where('billings.payment_status',0)
                    ->selectRaw('billings.*,
                    cash_collections.money_receipt_no,
                    cash_collections.payment_mode,
                    cash_collections.cheque_no,
                    billing_details.month,
                    billing_details.ledger_id,
                    group_concat(billings.id) as ids,
                    billing_details.amount as bill_amount,
                    sum(billing_details.fine) as fine,
                    sum(billing_details.interest) as interest,
                    sum(cash_collections.payment_amount) as paid_amount,
                    sum(cash_collections.paid_vat_amount) as paid_vatamount,
                    sum(cash_collections.paid_fine_amount) as paid_fine_amount,
                    sum(cash_collections.paid_fixed_fine) as paid_fixed_fine,
                    cash_collections.created_at as payment_date')
                    ->leftJoin('billing_details', 'billings.id', '=', 'billing_details.billing_id')
                    ->leftJoin('cash_collections', 'billings.id', '=', 'cash_collections.income_id')
                    ->when(count($dateFilter) > 0, function ($query) use ($dateFilter) {
                        return $query->whereIn('billing_details.month', $dateFilter);
                    })
                    ->where('billings.payment_status',0)
                    ->groupBy('billings.id')
                    ->orderByRaw("CASE
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jan'
    THEN 1
    WHEN SUBSTRING(billing_details.month,1,3) = 'Feb'
    THEN 2
    WHEN SUBSTRING(billing_details.month,1,3) = 'Mar'
    THEN 3
    WHEN SUBSTRING(billing_details.month,1,3) = 'Apr'
    THEN 4
    WHEN SUBSTRING(billing_details.month,1,3) = 'May'
    THEN 5
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jun'
    THEN 6
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jul'
    THEN 7
    WHEN SUBSTRING(billing_details.month,1,3) = 'Aug'
    THEN 8
    WHEN SUBSTRING(billing_details.month,1,3) = 'Sep'
    THEN 9
    WHEN SUBSTRING(billing_details.month,1,3) = 'Oct'
    THEN 10
    WHEN SUBSTRING(billing_details.month,1,3) = 'Nov'
    THEN 11
    WHEN SUBSTRING(billing_details.month,1,3) = 'Dec'
    THEN 12
  END, MONTH ASC")
                    ->get();
                $result = array();
                $shop = array();
                foreach ($ar as $row) {
                    $shop['customer_name'] = $row['shop_name'];
                    $shop['shop_no'] = $row['shop_no'];

                    if (isset($result[$row['month']])) {
                        $result[$row['month']]['month'] = $row['month'];
                        $result[$row['month']]['ids'] = $row['ids'];
                        $result[$row['month']]['rent'] += $row['bill_type'] == 'Rent' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['sc'] += $row['bill_type'] == 'Service Charge' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['fine_amount'] += $row['bill_type'] == 'Service Charge' ? $row['fine_amount']- $row['paid_fine_amount'] : 0;
                        $result[$row['month']]['fixed_fine'] += $row['bill_type'] == 'Service Charge' ? $row['fixed_fine']-$row['paid_fixed_fine'] : 0;
                        $result[$row['month']]['el'] += $row['bill_type'] == 'Electricity' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['fcsc'] += $row['ledger_id'] == 34 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['sc_fine'] += $row['bill_type'] == 'Service Charge' ? $row['fine'] - $row['paid_fine_amount']: 0;
                        $result[$row['month']]['interest'] += $row['bill_type'] == 'Electricity' ? $row['interest'] : 0;
                        $result[$row['month']]['advertisement'] += $row['ledger_id'] == 44 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['spsc'] += $row['ledger_id'] == 43 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['el_fine'] += $row['bill_type'] == 'Electricity' ? $row['fine_amount']- $row['paid_fine_amount'] : 0;
                        $result[$row['month']]['other_income'] += $row['ledger_id'] == 42 ? $row['bill_amount'] - $row['paid_amount'] : 0;

                    } else {
                        $result[$row['month']]['month'] = $row['month'];
                        $result[$row['month']]['ids'] = $row['ids'];
                        $result[$row['month']]['rent'] = $row['bill_type'] == 'Rent' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['sc'] = $row['bill_type'] == 'Service Charge' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['fine_amount'] = $row['bill_type'] == 'Service Charge' ? $row['fine_amount']- $row['paid_fine_amount'] : 0;
                        $result[$row['month']]['fixed_fine'] = $row['bill_type'] == 'Service Charge' ? $row['fixed_fine']-$row['paid_fixed_fine'] : 0;
                        $result[$row['month']]['el'] = $row['bill_type'] == 'Electricity' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['el_fine'] = $row['bill_type'] == 'Electricity' ? $row['fine_amount']- $row['paid_fine_amount'] : 0;
                        $result[$row['month']]['fcsc'] = trim($row['ledger_id']) == 34 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['sc_fine'] = $row['bill_type'] == 'Service Charge' ? $row['fine']- $row['paid_fine_amount'] : 0;
                        $result[$row['month']]['interest'] = $row['ledger_id'] == 34 ? $row['interest'] : 0;
                        $result[$row['month']]['advertisement'] = $row['ledger_id'] == 44 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['spsc'] = $row['ledger_id'] == 43 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['month']]['other_income'] = $row['ledger_id'] == 42 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                    }

                }

                $data['customer'] = $result;
//                $data['month'] = $month;
                $data['shop'] = $shop;
                if($checkData['t']=='pdf') {
                    $returnHTML = view('admin.reports.dr_print', $data)->render();
//                $data = ['title' => 'Welcome to Pakainfo.com'];
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->set_paper('A4', 'p');
                    $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                    $pdf->loadHTML($d);
                    return $pdf->stream();
                }

                $returnHTML = view('admin.reports.dr_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            } catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }

        } else {
            $data['asset'] = Billing::leftjoin('customers', 'customers.id', '=', 'billings.customer_id')->SelectRaw('billings.*,customers.shop_name as name')
                ->orderBy('billings.shop_no', 'ASC')
                ->groupBy('billings.customer_id')
                ->groupBy('billings.shop_no')
                ->get();
                //  $data['owner'] = Owner::all();
            //$data['asset'] = Asset::orderBy('customer_id','ASC')->get();
            return view('admin.reports.dr', $data);
        }

    }

    public function receivableCollectionStatementReport(Request $request)
    {
        $checkData = $request->all();
        $data['page_name'] = "Receivable & Collection Summary Report";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Report', 'active'),
            array('Receivable & Collection Summary Report', 'active')
        );
        $data['period'] = "As on " . date('d M Y');
        if ($request->isMethod('post')) {
            $dateFilter = array();
            $yearsSort = array();
            if ($checkData['month_from'] != '' && $checkData['month_to'] != '') {

                $date1 = new DateTime($checkData['month_from']);
                $date2 = new DateTime($checkData['month_to']);
                $interval = $date1->diff($date2);
                $year = $interval->y;
                $month = $interval->m;
                $month += $year * 12;
                $dateFilter[] = $checkData['month_from'];
                $y = date("Y", strtotime($checkData['month_from']));
                 $yearsSort = array();
                $yearsSort[$y][] = date("M Y", strtotime($checkData['month_from']));

                for ($i = 1; $i <= $month; $i++) {
                    $y = date("Y", strtotime("+" . $i . " months", strtotime($checkData['month_from'])));
                    $yearsSort[$y][] = date("M Y", strtotime("+" . $i . " months", strtotime($checkData['month_from'])));
                    $dateFilter[] = date("M Y", strtotime("+" . $i . " months", strtotime($checkData['month_from'])));
                }
                $data['period'] = "Month " . $checkData['month_from'] . " to " . $checkData['month_to'];
            }
            if (!in_array($checkData['month_to'], $dateFilter) && $checkData['month_to'] != '') {
                $dateFilter[] = $checkData['month_to'];
                $y = date("Y", strtotime($checkData['month_to']));
                $yearsSort[$y][] = date("M Y", strtotime($checkData['month_to']));
            }
         
            $category = $checkData['category'] != '' ? $checkData['category'] : "";
            $type = $checkData['type'] != '' ? $checkData['type'] : "";
            $data['category'] = $category == '' ? 'cat' : $category;
            $data['type'] = $type == '' ? 'type' : $type;
         
//            $date_s = $checkData['month_from']!=''?date('Y-m-01',strtotime($checkData['month_from'])):"";
//            $date_e = $checkData['month_to']!=''?date('Y-m-01',strtotime($checkData['month_to'])):"";

            try {
                $ar = Billing::selectRaw(' billing_details.month,billings.*,
                    cash_collections.money_receipt_no,
                    cash_collections.payment_mode,
                    cash_collections.cheque_no,
                    billing_details.ledger_id,
                    group_concat(billings.id) as ids,
                    billing_details.amount as bill_amount,
                    sum(billing_details.vat_amount) as vat_amount,
                    sum(billing_details.fine) as fine,
                    sum(billing_details.interest) as interest,
                    sum(cash_collections.payment_amount) as paid_amount,
                    sum(cash_collections.paid_vat_amount) as paid_vatamount,
                    sum(cash_collections.paid_fine_amount) as paid_fine_amount,
                    sum(cash_collections.paid_fixed_fine) as paid_fixed_fine,
                    cash_collections.created_at as payment_date')
                    ->leftJoin('billing_details', 'billings.id', '=', 'billing_details.billing_id')
                    ->leftJoin('cash_collections', 'billings.id', '=', 'cash_collections.income_id')
                    ->when(count($dateFilter) > 0, function ($query) use ($dateFilter) {
                        return $query->whereIn('billing_details.month', $dateFilter);
                    })
                    ->when($category != '', function ($query) use ($category) {
                        return $query->where('billings.bill_type', $category);
                    })
                    ->when($type != '', function ($query) use ($type) {
                        return $query->where('billings.off_type', $type);
                    })
                    ->groupBy('billings.id')
                    ->orderByRaw("CASE
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jan'
    THEN 1
    WHEN SUBSTRING(billing_details.month,1,3) = 'Feb'
    THEN 2
    WHEN SUBSTRING(billing_details.month,1,3) = 'Mar'
    THEN 3
    WHEN SUBSTRING(billing_details.month,1,3) = 'Apr'
    THEN 4
    WHEN SUBSTRING(billing_details.month,1,3) = 'May'
    THEN 5
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jun'
    THEN 6
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jul'
    THEN 7
    WHEN SUBSTRING(billing_details.month,1,3) = 'Aug'
    THEN 8
    WHEN SUBSTRING(billing_details.month,1,3) = 'Sep'
    THEN 9
    WHEN SUBSTRING(billing_details.month,1,3) = 'Oct'
    THEN 10
    WHEN SUBSTRING(billing_details.month,1,3) = 'Nov'
    THEN 11
    WHEN SUBSTRING(billing_details.month,1,3) = 'Dec'
    THEN 12
  END, MONTH ASC")
                    ->get();
                $result = array();
                $shop = array();
                 $flag=0;
                if(empty($yearsSort)){
                    $flag=1;
                }
                foreach ($ar as $row) {
                    $shop['customer_name'] = $row['shop_name'];
                    $shop['shop_no'] = $row['shop_no'];
                    if($flag){
                        $y = date("Y", strtotime($row['month']));
                        $m = date("M Y", strtotime($row['month']));
                        $yearsSort[$y][$m] = date("M Y", strtotime($row['month']));

                    }
                    if (isset($result[$row['month']])) {
                        $result[$row['month']]['month'] = $row['month'];
                        $result[$row['month']]['ids'] = $row['ids'];
                        $result[$row['month']]['receivable'] += $row['bill_amount'] + $row['vat_amount'];
                        $result[$row['month']]['collection'] += $row['paid_amount'] + $row['paid_vatamount'];


                    } else {
                        $result[$row['month']]['month'] = $row['month'];
                        $result[$row['month']]['ids'] = $row['ids'];
                        $result[$row['month']]['receivable'] = $row['bill_amount'] + $row['vat_amount'];
                        $result[$row['month']]['collection'] = $row['paid_amount'] + $row['paid_vatamount'];
                    }

                }
                if($flag){
                    $data1 = array();
                    foreach ($yearsSort as $key=>$rd){
                        foreach($rd as $r){
                             $data1[$key][] = $r;
                        }
                       
                    }
                    $yearsSort =  $data1;
                }
                $data['customer'] = $result;
//                $data['month'] = $month;
                $data['shop'] = $shop;

                $data['yearsSort'] = $yearsSort;
                $returnHTML = view('admin.reports.arc_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            } catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode() . " " . $e->getFile();
                return response()->json(array('success' => true, 'result' => $msg));
            }
        } else {
            $data['asset'] = Asset::orderBy('customer_id', 'ASC')->get();
            return view('admin.reports.arc', $data);
        }
    }

    public function receivableCollectionStatementReportDetails($month, $category, $type)
    {
        $data['page_name'] = "Dues Statement";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Dues Statement', 'report.dr'),
            array('Report', 'active')
        );
        $ar = Billing::where('billing_details.month', $month)
            ->when($category != 'cat', function ($query) use ($category) {
                return $query->where('billings.bill_type', $category);
            })
            ->when($type != 'type', function ($query) use ($type) {
                return $query->where('billings.off_type', $type);
            })
            ->selectRaw('billings.*,
                    cash_collections.money_receipt_no,
                    cash_collections.payment_mode,
                    cash_collections.cheque_no,
                    billing_details.ledger_name,
                    billing_details.month,
                    billings.shop_no,
                    billings.shop_name,
                    billings.meter_no,
                    billing_details.ledger_id,
                    group_concat(billings.id) as ids,
                    billing_details.amount as bill_amount,
                    sum(billing_details.vat_amount) as vat_amount,
                    sum(billing_details.fine) as fine,
                    sum(billing_details.interest) as interest,
                    sum(cash_collections.payment_amount) as paid_amount,
                    sum(cash_collections.paid_vat_amount) as paid_vatamount,
                    sum(cash_collections.paid_fine_amount) as paid_fine_amount,
                    sum(cash_collections.paid_fixed_fine) as paid_fixed_fine,
                    cash_collections.created_at as payment_date')
            ->leftJoin('billing_details', 'billings.id', '=', 'billing_details.billing_id')
            ->leftJoin('cash_collections', 'billings.id', '=', 'cash_collections.income_id')
            ->groupBy('billings.id')
             ->orderBy('billings.shop_no','asc')
             ->get();
        $data['customer'] = $ar;
        $data['month'] = $month;
        $data['period'] = "Month " . $month;
        return view('admin.reports.arc_details', $data);
    }

    public function CollectionStatementReport(Request $request)
    {

        $data['page_name'] = "Collection Statement";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Collection Statement', 'report.cs'),
            array('Report', 'active')
        );
        if ($request->isMethod('post')) {
            try {
                $checkData = $request->all();
                $collectionResult = Billing::
                leftjoin('billing_details', 'billing_details.billing_id', '=', 'billings.id')
                    ->where('billings.journal_date', '>=', $checkData['date_s'])
                    ->where('billings.journal_date', '<=', $checkData['date_e'])
                    ->selectRaw('billings.*,billing_details.month,billing_details.ledger_id as item_head_id')
                    ->groupBy('billings.id')
                    ->get();

                $collection['Rent']['invoice'] = 0;
                $collection['Rent']['collection'] = 0;
                $collection['Rent']['oldcollection'] = 0;
                $collection['Electricity']['invoice'] = 0;
                $collection['Electricity']['collection'] = 0;
                $collection['Electricity']['oldcollection'] = 0;
                $collection['Electricity']['fine'] = 0;
                $collection['Electricity']['finecollection'] = 0;
                $collection['Electricity']['oldfinecollection'] = 0;
                $collection['Advertisement']['invoice'] = 0;
                $collection['Advertisement']['collection'] = 0;
                $collection['Advertisement']['oldcollection'] = 0;
                $collection['Service Charge']['invoice'] = 0;
                $collection['Service Charge']['collection'] = 0;
                $collection['Service Charge']['oldcollection'] = 0;
                $collection['Service Charge']['fine'] = 0;
                $collection['Service Charge']['finecollection'] = 0;
                $collection['Service Charge']['oldfinecollection'] = 0;
                $collection['Advertisement']['invoice'] = 0;
                $collection['Advertisement']['collection'] = 0;
                $collection['Advertisement']['oldcollection'] = 0;
                $collection['Food Court Service Charge']['invoice'] = 0;
                $collection['Food Court Service Charge']['collection'] = 0;
                $collection['Food Court Service Charge']['oldcollection'] = 0;
                $collection['Special Service Charge']['oldcollection'] = 0;
                $collection['Special Service Charge']['invoice'] = 0;
                $collection['Special Service Charge']['collection'] = 0;

                foreach ($collectionResult as $row) {
                    $date = date('M Y', strtotime($row['collection_date']));
                    // $result  = $this->getCollection($row['id'],$checkData,$row['journal_date']);


                    if (isset($collection["$row[bill_type]"]['invoice'])) {
                        $collection["$row[bill_type]"]['invoice'] += $row['grand_total'];
                    } else {
                        $collection["$row[bill_type]"]['invoice'] = $row['grand_total'];
                    }

                    if (isset($collection["$row[bill_type]"]['fine'])) {
                        $collection["$row[bill_type]"]['fine'] += $row['fine_amount'] + $row['fixed_fine'];
                    } else {
                        $collection["$row[bill_type]"]['fine'] = $row['fine_amount'] + $row['fixed_fine'];
                    }
                    if (isset($collection["$row[bill_type]"]['finecollection'])) {
                        $collection["$row[bill_type]"]['www'][$row['id']] = $row['paid_fine_amount'] + $row['paid_fixed_fine'];
                        $collection["$row[bill_type]"]['finecollection'] += $row['paid_fine_amount'] + $row['paid_fixed_fine'];
                    } else {
                        $collection["$row[bill_type]"]['finecollection'] = $row['paid_fine_amount'] + $row['paid_fixed_fine'];
                        $collection["$row[bill_type]"]['www'][$row['id']] = $row['paid_fine_amount'] + $row['paid_fixed_fine'];

                    }

                    /*
                    if($date==$row['month']){
                        if(isset($collection[$row['item_head_id']]['invoice'])){
                            $collection[$row['item_head_id']]['invoice'] += $row['grand_total'];
                        }else{
                            $collection[$row['item_head_id']]['invoice'] = $row['grand_total'];
                        }
                        if(isset($collection[$row['item_head_id']]['collection'])){
                            $collection[$row['item_head_id']]['collection'] += $row['payment_amount'];
                        }else{
                            $collection[$row['item_head_id']]['collection'] = $row['payment_amount'];
                        }
                        if(isset($collection[$row['item_head_id']]['fine'])){
                            $collection[$row['item_head_id']]['fine'] += $row['fine_amount'];
                        }else{
                            $collection[$row['item_head_id']]['fine'] = $row['fine_amount'];
                        }
                        $collection[33]['finecollection'] += $row['paid_fine_amount'];
                    }else{
                        if(isset($collection[$row['item_head_id']]['oldcollection'])){
                            $collection[$row['item_head_id']]['oldcollection'] += $row['payment_amount'];
                        }else{
                            $collection[$row['item_head_id']]['oldcollection'] = $row['payment_amount'];
                        }
                        $collection[33]['oldfinecollection'] += $row['paid_fine_amount'];
                    }
                    */

                }
                $collectionResult1 = CashCollection::
                leftjoin('cash_collection_details', 'cash_collection_details.ref_id', '=', 'cash_collections.id')
                    ->where('collection_date', '>=', $checkData['date_s'])
                    ->where('collection_date', '<=', $checkData['date_e'])
                    ->selectRaw('cash_collections.*,cash_collection_details.month,cash_collection_details.item_head_id,cash_collection_details.payment_amount')
                    ->get();

                foreach ($collectionResult1 as $row) {
                    $date = date('M Y', strtotime($row['collection_date']));
                    $journal_date = $this->getJournalDate($row['income_id']);
                    if ($date != date('M Y', strtotime($journal_date))) {
                        if (isset($collection["$row[bill_type]"]['oldcollection'])) {
                            $collection["$row[bill_type]"]['oldcollection'] += $row['payment_amount'];
                        } else {
                            $collection["$row[bill_type]"]['oldcollection'] = $row['payment_amount'];
                        }
                        if (isset($collection["$row[bill_type]"]['oldfinecollection'])) {
                            $collection["$row[bill_type]"]['oldfinecollection'] += $row['paid_fine_amount'] + $row['paid_fixed_fine'];
                        } else {
                            $collection["$row[bill_type]"]['oldfinecollection'] = $row['paid_fine_amount'] + $row['paid_fixed_fine'];
                        }

                    } else {
                        if ($row['collection_date'] >= $checkData['date_s'] && $row['collection_date'] <= $checkData['date_e']) {
                            if (isset($collection["$row[bill_type]"]['collection'])) {
                                $collection["$row[bill_type]"]['collection'] += $row['payment_amount'];
                            } else {
                                if ($row['collection_date'] >= $checkData['date_s'] && $row['collection_date'] <= $checkData['date_e']) {
                                    $collection["$row[bill_type]"]['collection'] = $row['payment_amount'];
                                }
                            }
                        }
                    }


                }


                $data['collection'] = $collection;
                $data['date'] = date('d M Y', strtotime($checkData['date_s'])) . " to " . date('d M Y', strtotime($checkData['date_e']));

                if($checkData['t']=='pdf') {
                    $returnHTML = view('admin.reports.cr_print', $data)->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->set_paper('A4', 'p');
//                    $pdf->set_option('isHtml5ParserEnabled', true);
                    $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                    $pdf->loadHTML($d);
                    return $pdf->stream();
                }
                $returnHTML = view('admin.reports.cr_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));

            }catch (\Exception $e){
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));

            }


        } else {
            return view('admin.reports.cr', $data);
        }

    }

    public function getCollection($id, $checkData, $journal_date)
    {
        $array[29]['invoice'] = 0;
        $array[29]['collection'] = 0;
        $array[29]['oldcollection'] = 0;
        $array[33]['invoice'] = 0;
        $array[33]['collection'] = 0;
        $array[33]['oldcollection'] = 0;
        $array[33]['fine'] = 0;
        $array[33]['finecollection'] = 0;
        $array[33]['oldfinecollection'] = 0;
        $array[44]['invoice'] = 0;
        $array[44]['collection'] = 0;
        $array[44]['oldcollection'] = 0;
        $array[31]['invoice'] = 0;
        $array[31]['collection'] = 0;
        $array[31]['oldcollection'] = 0;
        $array[31]['fine'] = 0;
        $array[31]['finecollection'] = 0;
        $array[31]['oldfinecollection'] = 0;
        $array[43]['invoice'] = 0;
        $array[43]['collection'] = 0;
        $array[43]['oldcollection'] = 0;
        $array[34]['invoice'] = 0;
        $array[34]['collection'] = 0;
        $array[34]['oldcollection'] = 0;
        $collectionResult = CashCollection::
        leftjoin('cash_collection_details', 'cash_collection_details.ref_id', '=', 'cash_collections.id')
            ->where('collection_date', '>=', $checkData['date_s'])
            ->where('collection_date', '<=', $checkData['date_e'])
            ->where('income_id', '=', $id)
            ->selectRaw('cash_collections.*,cash_collection_details.month,cash_collection_details.item_head_id,cash_collection_details.payment_amount')
            ->get();

        foreach ($collectionResult as $row) {
            $date = date('M Y', strtotime($row['collection_date']));
            if ($date != date('M Y', strtotime($journal_date))) {
                if (isset($array[$row['item_head_id']]['oldcollection'])) {
                    $array[$row['item_head_id']]['oldcollection'] += $row['payment_amount'];
                } else {
                    $array[$row['item_head_id']]['oldcollection'] = $row['payment_amount'];
                }
                if (isset($array[33]['oldfinecollection'])) {
                    $array[33]['oldfinecollection'] += $row['paid_fine_amount'];
                } else {
                    $array[33]['oldfinecollection'] = $row['paid_fine_amount'];
                }

            }
            if ($row['collection_date'] >= $checkData['date_s'] && $row['collection_date'] <= $checkData['date_e']) {
                if (isset($array[$row['item_head_id']]['collection'])) {
                    $array[$row['item_head_id']]['collection'] += $row['payment_amount'];
                } else {
                    if ($row['collection_date'] >= $checkData['date_s'] && $row['collection_date'] <= $checkData['date_e']) {
                        $array[$row['item_head_id']]['collection'] = $row['payment_amount'];
                    }
                }
            }

        }

        return $array;

    }

    public function getJournalDate($id)
    {
        $date = Billing::find($id);
        return $date['journal_date'];
    }

    public function AssetStatementReport(Request $request)
    {
        $data['page_name'] = "Asset List Report";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Asset List Report', 'report.asset-report'),
            array('Report', 'active')
        );
        $floor = Lookup::where('name', 'Building Floor')->first();
        $data['floor'] = Lookup::where('parent_id', $floor->id)->get();

        if ($request->isMethod('post')) {

            $checkData = $request->all();
            $category = $checkData['category'];
            $status = $checkData['status'];
            $owner_id = $checkData['owner_id'];
            $floor_name = $checkData['floor_name'];
            $ar = Asset::
            leftjoin('owners', 'owners.id', '=', 'assets.owner_id')
                ->leftjoin('customers', 'customers.id', '=', 'assets.customer_id')
                ->when($category != '', function ($query) use ($category) {
                    return $query->where('assets.off_type', '=', $category);
                })
                ->when($status != '', function ($query) use ($status) {
                    return $query->where('assets.status', '=', $status);
                })
                ->when($owner_id != '', function ($query) use ($owner_id) {
                    return $query->where('assets.owner_id', '=', $owner_id);
                })
                ->when($floor_name != '', function ($query) use ($floor_name) {
                    return $query->where('floor_name', '=', $floor_name);
                })
                ->groupBy('assets.asset_no')
                ->selectRaw('assets.*,owners.name,customers.shop_name')
                ->orderBy('assets.asset_no', 'ASC')
                ->orderBy('floor_name', 'ASC')
                ->get();
            $array = array();
            foreach ($ar as $row) {
                $array[$row['floor_name']][] = $row;

            }

            $data['assets'] = $array;
            $returnHTML = view('admin.reports.asset_report_print', $data)->render();
            return response()->json(array('success' => true, 'result' => $returnHTML));
        } else {
            return view('admin.reports.asset_report_f', $data);
        }

    }

    public function DailyCollectionReport(Request $request)
    {
        $data['page_name'] = "Daily Collection Report";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Report', 'active'),
            array('Daily Collection Report', 'active')
        );
        $data['shops'] = Billing::orderBy('shop_no', 'ASC')->groupBy('shop_no')->get();
        $data['customer'] = Billing::orderBy('shop_name', 'ASC')->groupBy('customer_id')->get();


        if ($request->isMethod('post')) {

            try {
                $filter = $checkData = $request->all();
                $shop_no = $filter['shop_no'] != '' ? $filter['shop_no'] : "";
                $date_s = $filter['date_s'] != '' ? $filter['date_s'] : "";
                $date_e = $filter['date_e'] != '' ? $filter['date_e'] : "";
                $customer_name = $filter['customer_name'] != '' ? $filter['customer_name'] : "";
                $payment_mode = $filter['payment_mode'] != '' ? $filter['payment_mode'] : "";
                $service = $filter['service'] != '' ? $filter['service'] : "";

                $collecton = CashCollection::
                leftjoin('cash_collection_details', 'cash_collection_details.ref_id', '=', 'cash_collections.id')
                    ->leftjoin('billings', 'billings.id', '=', 'cash_collections.income_id')
                    ->when($date_s != '', function ($query) use ($date_s) {
                        return $query->where('collection_date', '>=', $date_s);
                    })
                    ->when($date_e != '', function ($query) use ($date_e) {
                        return $query->where('collection_date', '<=', $date_e);
                    })
                    ->when($shop_no != '', function ($query) use ($shop_no) {
                        return $query->where('cash_collections.shop_no', '=', $shop_no);
                    })
                    ->when($customer_name != '', function ($query) use ($customer_name) {
                        return $query->where('cash_collections.customer_id', '=', $customer_name);
                    })
                    ->when($payment_mode != '', function ($query) use ($payment_mode) {
                        return $query->where('payment_mode', '=', $payment_mode);
                    })
                    ->when($service != '', function ($query) use ($service) {
                        return $query->where('cash_collections.bill_type', '=', $service);
                    })
                    ->SelectRaw('cash_collections.cheque_no,cash_collections.shop_no,cash_collections.shop_name,sum(cash_collections.discount) as discount,
                       payment_mode,billings.meter_no,billings.bill_type,cash_collection_details.month,sum(cash_collection_details.payment_amount+cash_collection_details.paid_vat_amount+cash_collection_details.paid_fine_amount+cash_collection_details.paid_fixed_fine) as total')
                    ->groupBy('billings.invoice_no')
                    ->orderBy('billings.shop_no', 'ASC')
                    ->get();
                $data['collecton'] = $collecton;
                 $t1='Date Between ';
                $t1 .= $date_s!=''?date('d-m-Y',strtotime($date_s)):"";
                $t1 .= ' to '.($date_e!=''?date('d-m-Y',strtotime($date_e)):"");
                 $data['date_from'] = $t1;
                if($checkData['t']=='pdf') {
                    $returnHTML = view('admin.reports.csr_print', $data)->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->set_paper('A4', 'p');
//                    $pdf->set_option('isHtml5ParserEnabled', true);
                    $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                    $pdf->loadHTML($d);
                    return $pdf->stream();
                }
                $returnHTML = view('admin.reports.csr_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            } catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }
        }
        return view('admin.reports.csr', $data);
    }

    public function BillWiseCustomerReport(Request $request)
    {

        $data['page_name'] = "Bill wise Customer Report";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Report', 'active'),
            array('Bill wise Customer Report', 'active')
        );
        $data['shops'] = Billing::orderBy('shop_no', 'ASC')->groupBy('shop_no')->get();
        $data['customer'] = Billing::orderBy('shop_name', 'ASC')->groupBy('customer_id')->get();


        if ($request->isMethod('post')) {
            $checkData =$request->all();
            $typeArr[33]['Shop'] = 33;
            $typeArr[33]['Office'] = 26;
            $typeArr[33]['Others'] = 116;
            $typeArr[29]['Shop'] = 29;
            $typeArr[29]['Office'] = 73;
            $typeArr[29]['Others'] = 118;
            $typeArr[31]['Shop'] = 31;
            $typeArr[31]['Office'] = 32;
            $typeArr[31]['Others'] = 119;
            $typeArr[34]['Shop'] = 31;
            $typeArr[34]['Office'] = 32;
            $typeArr[34]['Others'] = 119;


            try {
                $filter = $request->all();
                $shop_no = $filter['shop_no'] != '' ? $filter['shop_no'] : "";
                $date_s = $filter['date_s'] != '' ? $filter['date_s'] : "";
                $date_e = $filter['date_e'] != '' ? $filter['date_e'] : "";
                $customer_name = $filter['customer_name'] != '' ? $filter['customer_name'] : "";
                $service = $filter['service'] != '' ? $filter['service'] : "";
                $type = $filter['type'] != '' ? $filter['type'] : "";
                if($type!=''){
                    $service = $typeArr[$service][$type]??"";
                }

                $billings = Billing::
                leftjoin('billing_details', 'billing_details.billing_id', '=', 'billings.id')
                    ->leftjoin('cash_collections', 'cash_collections.income_id', '=', 'billings.id')
                    ->when($date_s != '', function ($query) use ($date_s) {
                        return $query->where('journal_date', '>=', $date_s);
                    })
                    ->when($date_e != '', function ($query) use ($date_e) {
                        return $query->where('journal_date', '<=', $date_e);
                    })
                    ->when($shop_no != '', function ($query) use ($shop_no) {
                        return $query->where('billings.shop_no', '=', $shop_no);
                    })
                    ->when($customer_name != '', function ($query) use ($customer_name) {
                        return $query->where('billings.customer_id', '=', $customer_name);
                    })
                    ->when($type != '', function ($query) use ($type) {
                        return $query->where('billings.off_type', '=', $type);
                    })
                    ->when($service != '', function ($query) use ($service) {
                        return $query->where('billing_details.ledger_id', '=', $service);
                    })
                    ->SelectRaw('billings.shop_no,billings.shop_name,billings.invoice_no,
                       billings.meter_no,billings.bill_type,billing_details.month,billing_details.total,sum(cash_collections.payment_amount) as payment_amount,cash_collections.bill_remarks')
                    ->groupBy('billings.invoice_no')
                    ->orderBy('billings.shop_no', 'ASC')
                    ->get();
                $data['collecton'] = $billings;
                if($checkData['t']=='pdf') {
                    $returnHTML = view('admin.reports.bwcr_print', $data)->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->set_paper('A4', 'landscape');
//                    $pdf->set_option('isHtml5ParserEnabled', true);
                    $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                    $pdf->loadHTML($d);
                    return $pdf->stream();
                }
                $returnHTML = view('admin.reports.bwcr_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            } catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }
        }
        return view('admin.reports.bwcr', $data);
    }

    public function SecurityDeositReport(Request $request)
    {
        $data['page_name'] = "Advance & Security Deposit Report";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Advance & Security Deposit', 'active'),
            array('Report', 'active')
        );
        $data['shops'] = Asset::orderBy('asset_no', 'ASC')->groupBy('asset_no')->get();
        $data['customer'] = Customer::orderBy('shop_name', 'ASC')->groupBy('id')->get();


        if ($request->isMethod('post')) {

            try {
                $filter = $request->all();
                $shop_no = $filter['shop_no'] != '' ? $filter['shop_no'] : "";
                $date_s = $filter['date_s'] != '' ? $filter['date_s'] : "";
                $date_e = $filter['date_e'] != '' ? $filter['date_e'] : "";
                $customer_name = $filter['customer_name'] != '' ? $filter['customer_name'] : "";
                $service = $filter['service'] != '' ? $filter['service'] : "";
                $deposit_re = $filter['deposit_re'] != '' ? $filter['deposit_re'] : "";
                if($deposit_re==1){
                    $billings = Asset::
                        when($shop_no != '', function ($query) use ($shop_no) {
                            return $query->where('asset_no', '=', $shop_no);
                        })
                        ->when($customer_name != '', function ($query) use ($customer_name) {
                            return $query->where('customer_id', '=', $customer_name);
                        })
                         ->leftjoin('customers','customers.id','=','assets.customer_id')
                         ->orderBy('asset_no','asc')
                         ->groupBy('asset_no')
                         ->SelectRaw('assets.*,customers.shop_name as customer_name')
                         ->get();
                    $data['journals'] = $billings;
                    $data['service'] = $service;
                    $data['deposit_re'] = $deposit_re;
                    $data['filter'] = $filter;
                    $data['filter'] = $filter;
                    $returnHTML = view('admin.reports.security_deposit_print', $data)->render();
                    return response()->json(array('success' => true, 'result' => $returnHTML));
                }else {
                    $billings = Journal::when($shop_no != '', function ($query) use ($shop_no) {
                            return $query->where('shop_no', '=', $shop_no);
                        })
                        ->when($customer_name != '', function ($query) use ($customer_name) {
                            return $query->where('customer_id', '=', $customer_name);
                        })
                        ->when($service != '', function ($query) use ($service) {
                            return $query->where('ledger_id', '=', $service);
                        })
                         ->selectRaw("journals.*,SUM( IF(`effective_date` >= '$date_s' AND `effective_date` <= '$date_e',credit, 0 )) as  credit,
                        SUM(IF(journals.`effective_date` < '$date_s',credit, 0 )) as openig_deposit")
                        ->groupBy('journals.shop_no')
                        ->orderBy('journals.shop_no', 'ASC')
                        ->get();
                    $result = array();
                    foreach ($billings as $row) {
                        $securityDeposit=0;
                        // if($filter['date_s']!=''){
                        //     $securityDeposit = $this->getSecurityDepositOpening($row, $filter);
                        // }


                        $deduction = $this->getAdvanceDepositRequired($row, $filter);
                        $rent = $this->getRent($row, $filter);
                        $row['advanceDeposit'] = $service == 115 ? $rent['security_deposit'] : $rent['advance_deposit'];
                        $row['securityDeposit'] = $rent['security_deposit'];
                        // $row['openig_deposit'] = $securityDeposit;
                        $row['year_diposit'] = $row['credit'];
                        $row['deduction'] = $deduction;
                        $row['rent'] = $rent['rent'];
                        array_push($result, $row);
                    }
                    $data['journals'] = $result;
                    $data['service'] = $service;
                    $data['filter'] = $filter;
                    $data['deposit_re'] = $deposit_re;
                    $returnHTML = view('admin.reports.security_deposit_print', $data)->render();
                    return response()->json(array('success' => true, 'result' => $returnHTML));
                }
            } catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }

        }
        return view('admin.reports.security_deposit', $data);
    }

    public function getAdvanceDepositRequired($row, $filter)
    {
        return $result = Journal::where('ledger_id', $filter['service'])
            ->where('shop_no', $row['shop_no'])
            ->where('ref_module', 'Cash Collection')
            ->sum('debit');
    }

    public function getSecurityDepositOpening($row, $filter)
    {
        $shop_no = $row['shop_no'] != '' ? $row['shop_no'] : "";
        $p_date_e = date('Y-m-d', strtotime("$filter[date_s] -1 day"));
        return Journal::where('ledger_id', $filter['service'])
            ->where('effective_date', '<=', $p_date_e)
            ->when($shop_no != '', function ($query) use ($shop_no) {
                return $query->where('shop_no', '=', $shop_no);
            })
            ->sum('credit');
    }

    public function getRent($row, $filter)
    {
        $assets = Asset::where('asset_no', $row['shop_no'])->first();
        return array('rent' => round($assets['area_sft'] * $assets['rate']), 'security_deposit' => $assets['security_deposit'], 'advance_deposit' => $assets['advance_deposit']);
    }

    public function showAdvanceDeductionDetails($ledger_id, $shop_no)
    {
        $data['page_name'] = "Advance & Security Deposit Report";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Advance & Security Deposit', 'report.security-deposit'),
            array('Report', 'active')
        );
        $result = Journal::where('ledger_id', $ledger_id)
            ->where('shop_no', $shop_no)
            ->where('ref_module', 'Cash Collection')
            ->selectRaw('sum(debit) as debit,effective_date')
            ->groupBy('effective_date')
            ->get();
        $data['result'] = $result;
        return view('admin.reports.security_deposit_details', $data);

    }

    public function RateHisoryReport(Request $request)
    {
        $data['page_name'] = "Rate History";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Rate History', 'report.rate-history'),
            array('Report', 'active')
        );
        $data['shops'] = Asset::orderBy('asset_no', 'ASC')->groupBy('asset_no')->get();
        $data['customer'] = Customer::orderBy('shop_name', 'ASC')->groupBy('id')->get();


        if ($request->isMethod('post')) {

            try {
                $filter = $request->all();
                $customer = $filter['customer'] != '' ? $filter['customer'] : "";
                $shop_no = $filter['shop_no'] != '' ? $filter['shop_no'] : "";
                $category = $filter['category'] != '' ? $filter['category'] : "";


                $asset = Asset::leftjoin('customers', 'customers.id', '=', 'assets.customer_id')
                    ->leftjoin('owners', 'owners.id', '=', 'assets.owner_id')
                    ->when($category != '', function ($query) use ($category) {
                        return $query->where('off_type', '=', $category);
                    })
                    ->when($customer != '', function ($query) use ($customer) {
                        return $query->where('customer_id', '=', $customer);
                    })
                    ->when($shop_no != '', function ($query) use ($shop_no) {
                        return $query->where('asset_no', '=', $shop_no);
                    })
                    ->selectRaw('assets.*,owners.name as owner_type,customers.shop_name,customers.owner_contact,customers.contact_person_phone,customers.status as cus_status')
                    ->orderBy('assets.contact_s_date','asc')
                    ->get();
                $arResult = array();
                foreach ($asset as $r){

                  $billings = AssetLog::where('asset_no', '=', $r['asset_no'])
                        ->when($customer != '', function ($query) use ($customer) {
                            return $query->where('customer_id', '=', $customer);
                        })
                      ->when($category != '', function ($query) use ($category) {
                          return $query->where('off_type', '=', $category);
                      })
                        ->leftjoin('customers', 'customers.id', '=', 'asset_logs.customer_id')
                        ->leftjoin('owners', 'owners.id', '=', 'asset_logs.owner_id')
                        ->selectRaw('asset_logs.*,owners.name as owner_type,customers.shop_name,customers.owner_contact,customers.contact_person_phone,customers.status as cus_status')
                        ->orderBy('asset_logs.contact_s_date','asc')
                        ->get();
                  foreach ($billings as $row){
                     array_push($arResult,$row);
                  }
                    array_push($arResult,$r);
                }
                $data['result'] = $arResult;
                $returnHTML = view('admin.reports.rate_history_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            } catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }
        }
        return view('admin.reports.rate_history', $data);
    }

    public function ReceiptPaymentReport(Request $request)
    {
        $data['page_name'] = "Receipt & Payment Statement";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Receipt & Payment Statement', 'report.rate-history'),
            array('Report', 'active')
        );

        $cashAtBbankLEdger = ChartOfAccount::where('category', 'Bank Accounts')
            ->get();
        $data['ledger'] =  $cashAtBbankLEdger;
        if ($request->isMethod('post')) {

            try {
                $checkData = $request->all();
                $openingAmount = $this->getReceiptPaymentOpeningBalance($checkData);
                $shop_no = '';
                $leader = $checkData['ledger']!=''?$checkData['ledger']: '';
                $cashAtBbankLEdger = ChartOfAccount::where('category', 'Bank Accounts')
                ->get();
                $operatingIncomeLEdger = ChartOfAccount::where('category', 'Non-operating Income')
                    ->get();

                $result['operatingIncome'] = array();
                $result['revenuePayment'] = array();
                $result['capitalReceipt'] = array();
                $result['capitalPayment'] = array();


                $bakLedgerId  = array();
                $bakLedgerIds  = array(6);
                foreach ($cashAtBbankLEdger as $r){
                    array_push($bakLedgerId,$r['id']);
                    array_push($bakLedgerIds,$r['id']);
                }
                foreach ($operatingIncomeLEdger as $r){
                    array_push($bakLedgerIds,$r['id']);
                }

                $IncomeItem = CashCollection::
                leftjoin('cash_collection_details','cash_collection_details.ref_id','=','cash_collections.id')
                    ->leftjoin('billings','billings.id','=','cash_collections.income_id')
                    ->where('collection_date', '>=', $checkData['date_s'])
                    ->where('collection_date', '<=', $checkData['date_e'])
                    ->when($leader != '', function ($query) use ($leader) {
                        return $query->where('cash_collections.ledger_id', '=', $leader);
                    })
                    ->whereIn('cash_collections.ledger_id',  $bakLedgerIds)
                    ->selectRaw('cash_collection_details.item_head_id ,cash_collections.ledger_id,sum(cash_collections.payment_amount) as credit,billings.bill_type,sum(cash_collections.paid_fixed_fine) as paid_fixed_fine,
                     sum(cash_collections.paid_fine_amount) as paid_fine_amount,sum(cash_collections.discount) as discount,cash_collection_details.item_head as ledger_head')
                    ->orderBy('collection_date', 'desc')
                    ->groupBy('cash_collection_details.ref_id')
                    ->get();
//for income head
                $filterLedger = array();
                $refID= array();

                foreach ( $IncomeItem as $row){
                    $row['credit'] = $row['credit']-$row['discount'];
                    $result['operatingIncome'][$row['item_head_id']][] =  $row;
                    $filterLedger[] = $row['ledger_id'];
                }

                $journal = Journal::where('effective_date', '>=', $checkData['date_s'])
                    ->where('effective_date', '<=', $checkData['date_e'])
                    ->selectRaw('sum(credit) as credit, sum(debit) as debit,ref_id,ledger_id, ledger_head,group_name,ref_module')
                    ->orderBy('effective_date', 'desc')
                    ->groupBy('ledger_id','ref_id')
                    ->get();
                $cashLedger=array();
                foreach ($journal as $r) {
                    if($leader==$r['ledger_id']){
                        $cashLedger[] = $r['ref_id'];
                    }

                }

                foreach ($journal as $r) {
                    if (trim($r['group_name']) == 'Non-operating Income' ||
                        trim($r['group_name']) == 'Interest Income'
                    ) {
                        if ($r['credit'] != 0  && $r['ref_module']=='Manual Journal') {
                            if($leader!='' ){
                                if(in_array($r['ref_id'],$cashLedger)){
                                    $r['credit'] = $r['credit']-$r['discount'];
                                    $result['operatingIncome'][$r['ledger_id']][] =  $r;
                                }
                            }else{
                                $r['credit'] = $r['credit']-$r['discount'];
                                $result['operatingIncome'][$r['ledger_id']][] =  $r;
//                                $filterLedger[] = $row['ledger_id'];
                            }


                        }

                    }
                    if (trim($r['group_name']) == 'Administrative Expense' ||
                        trim($r['group_name']) == 'Cost of Services' ||
                        trim($r['group_name']) == 'Misc. Expenses' ||
                        trim($r['group_name']) == 'Promotional Expense' ||
                        trim($r['group_name']) == 'Current Liabilities' ||
                        trim($r['group_name']) == 'Salary & Allowances'
                    ) {
                        if ($r['debit'] != 0 &&  $r['ledger_id']!=120 ) {
                            if($leader!='' ){
                                if(in_array($r['ref_id'],$cashLedger)){
                                    $result['revenuePayment'][$r['ledger_id']][] = $r;
                                }

                            }else{
                                $result['revenuePayment'][$r['ledger_id']][] = $r;
                            }


                        }

                    }
//                    capital recepit payment
                    if (
                        trim($r['group_name']) == 'Equity' ||
                        trim($r['group_name']) == 'Non-Current Assets' ||
                        trim($r['group_name']) == 'Current Liability' ||
                        trim($r['group_name']) == 'Current Assets'
                    ) {
                        if($leader!=''){
                            if($r['ledger_id']==$leader){
                                continue;
                            }
                            $ar = $this->getOpositLedgerCashReceipt($r,$leader);
                            $credit = 0;
                            foreach ($ar as $rd) {
                                $credit += $rd['credit'];
                                $r['ledger_head'] = $rd['ledger_head'];
                            }

                            $r['ref'] = $credit;
                            $r['credit'] = $credit;

                            if($credit!=0 &&  $r['ledger_id']!=6 && $r['ledger_id']!=36){
                                $refID[] = $r['ref_id'];
                                $result['capitalReceipt'][$r['ledger_id']][] = $r;
                            }

                        }
                        else if ($r['credit'] != 0  && $r['ledger_id']!=6 && $r['ledger_id']!=36 && !in_array($r['ledger_id'],$bakLedgerId)) {
                                      $result['capitalReceipt'][$r['ledger_id']][] = $r;

                        }

                    }
                    if (trim($r['group_name']) == 'Equity' ||
                        trim($r['group_name']) == 'Non-Current Assets' ||
                        trim($r['group_name']) == 'Current Liability' ||
                        trim($r['group_name']) == 'Current Assets'
                    ) {
                        if($leader!=''){
//                            if($r['ledger_id']==$leader){
//                                continue;
//                            }
                            $ar = $this->getOpositLedgerCash($r,$leader);
                            $credit = 0;
                            foreach ($ar as $rd) {
                                $credit += $rd['debit'];
                                $r['ledger_head'] = $rd['ledger_head'];
                            }
                            $r['debit'] = $credit;
                            if($credit!=0 ){
                                $result['capitalPayment'][$r['ledger_id']][] = $r;
                            }

                        }
                        else  if ($r['debit'] != 0 && $r['ledger_id']!=6 && $r['ledger_id']!=36 && !in_array($r['ledger_id'],$bakLedgerId)) {

                                $result['capitalPayment'][$r['ledger_id']][] = $r;



                        }

                    }
                }


                $checkData['creaditors'] = 'None';
                $checkData['customer'] = 'None';
                $cashInHand = 0;
                $cashAtBank = 0;
                $cashDebit = 0;
                $cashCredit = 0;

                if($leader=='' || $leader==6){
                    $checkData['ledger'] = 6;
                    $cashInHand = $this->getOpeningBalance($checkData);

                    $journalR = Journal::where('ledger_id', '=', 6)
                        ->where('effective_date', '>=', $checkData['date_s'])
                        ->where('effective_date', '<=', $checkData['date_e'])
                        ->groupBy('ref_id', 'ref_module')
                        ->orderBy('effective_date', 'desc')
                        ->get();


                    foreach ($journalR as $row) {
                        $ar = $this->indivisualTransaction($row, $row['ledger_id']);
                        foreach ($ar as $r) {
                            $cashDebit += $r['debit'];
                            $cashCredit += $r['credit'];
                        }
                    }
                }



//                cash transaction

                $opositResult = array();
                $bankDebit = 0;
                $bankCredit = 0;
                if($leader=='' || $leader!=6 ){
                    if($leader!=''){
                        $checkData['ledger'] = $leader;
                        $checkData['creaditors'] = 'None';
                        $checkData['customer'] = 'None';
                        $cashAtBank += $this->getOpeningBalance($checkData);
                    }else{
                        foreach ($cashAtBbankLEdger as $r) {
                            $checkData['ledger'] = $r['id'];
                            $checkData['creaditors'] = 'None';
                            $checkData['customer'] = 'None';
                            $cashAtBank += $this->getOpeningBalance($checkData);
                        }
                    }


                    $journalBank = Journal::where('effective_date', '>=', $checkData['date_s'])
                        ->where('effective_date', '<=', $checkData['date_e'])
                        ->when($leader != '', function ($query) use ($leader) {
                            return $query->where('ledger_id', '=', $leader);
                        })
                        ->whereIn('ledger_id',  $bakLedgerId)
                        ->orderBy('effective_date', 'desc')
                        ->groupBy('ref_id', 'ref_module')
                        ->get();
                    foreach ($journalBank as $row) {
                        $ar = $this->indivisualTransaction($row, $row['ledger_id']);
                        foreach ($ar as $r) {
                            $bankDebit += $r['debit'];
                            $bankCredit += $r['credit'];
                        }
                    }
                }
//return $refID;
                //   $value = $this->getOpositLedgerCash(array('ref_id'=>2,'ref_module'=>'Manual Journal'),6);
//                $data['value'] =    $value;
                $data['cashInHand'] = $openingAmount['cashOpening'];
                $data['cashAtBank'] = $openingAmount['bankOpening'];
                $data['cashDebit'] = $cashDebit;
                $data['cashCredit'] = $cashCredit;
                $data['bankDebit'] = $bankDebit;
                $data['bankCredit'] = $bankCredit;
                $data['operatingIncome'] = $result['operatingIncome'];

                $data['revenuePayment'] = $result['revenuePayment'];
                $data['capitalReceipt'] = $result['capitalReceipt'];
                $data['capitalPayment'] = $result['capitalPayment'];
                if($checkData['t']=='pdf') {
                    $returnHTML = view('admin.reports.receipt_payment_print', $data)->render();
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->set_paper('A4', 'landscape');
//                    $pdf->set_option('isHtml5ParserEnabled', true);
                    $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                    $pdf->loadHTML($d);
                    return $pdf->stream();
                }
                $returnHTML = view('admin.reports.receipt_payment_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            } catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }
        }
        return view('admin.reports.receipt_payment', $data);
    }

    public function getCashOpossitLedger($r)
    {
        $journal = Journal::where('ref_id', '=', $r['ref_id'])
            ->where('ledger_type', '=', 'Income')
            ->selectRaw('sum(credit) as credit, sum(debit) as debit, ledger_head,group_name')
            ->orderBy('effective_date', 'desc')
            ->groupBy('ledger_id')
            ->get();
        return $journal;
    }
    public function getOpositLedgerCashOrBak($ar,$ledger=0){

        $journal = Journal::where('ref_id', $ar['ref_id'])
            ->where('ref_module', $ar['ref_module'])
            ->orderBy('effective_date', 'desc')
            ->get();
        $debit = 0;
        $credit = 0;

        foreach ($journal as $r) {
            if ($r['ledger_id'] == $ledger) {
                $debit = $r['debit'];
                $credit = $r['credit'];
            }

        }
        $array = array();


        foreach ($journal as $row) {
            $sub = array();
            if ($debit != 0) {
                if ($row['ledger_id'] == $ledger) {
                    continue;
                }
                if ($row['ledger_id'] == 36) {
                    continue;
                }
                if (trim($row['group_name']) == 'Equity' ||
                    trim($row['group_name']) == 'Non-Current Assets' ||
                    trim($row['group_name']) == 'Current Liability' ||
                    trim($row['group_name']) == 'Current Assets'
                ) {
                    $sub['debit'] = $debit;
                    $sub['credit'] = 0.00;

                    $sub['ledger_head'] = $row->ledger_head ?? "";//['ledger_head'] ;

                    array_push($array, $sub);
                }

            } else if ($credit != 0) {
                if ($row['debit'] == 0) {
                    continue;

                }
                if (trim($row['group_name']) == 'Interest Income' ||
                    trim($row['group_name']) == 'Equity' ||
                    trim($row['group_name']) == 'Non-Current Assets' ||
                    trim($row['group_name']) == 'Current Liability' ||
                    trim($row['group_name']) == 'Current Assets'
                ){
                    if ($row['ledger_id'] == $ledger) {
                        continue;
                    }
                    $sub['debit'] = 0.00;
                    $sub['credit'] = $credit;
                    $sub['ledger_head'] = $row->ledger_head ?? "";
                    array_push($array, $sub);
                }

            }

        }



        return $array;


    }
    public function getOpositLedgerCash($ar,$ledger=0){
        $journal = Journal::where('ref_id', $ar['ref_id'])
            ->where('ref_module', $ar['ref_module'])
            ->orderBy('effective_date', 'desc')
            ->get();
        $debit = 0;
        $credit = 0;

        foreach ($journal as $r) {
            if ($r['ledger_id'] == $ledger) {
                $debit = $r['debit'];
                $credit = $r['credit'];

            }

        }
        $array = array();
        $sub = array();
        foreach ($journal as $r) {
            if($debit!=0){
                if ($r['ledger_id'] == $ledger) {
                    continue;
                }
//                $journals = CashCollectionDetail::where('ref_id', $ar['ref_id'])
//                    ->first();
                $sub['debit'] = 0.00;
                $sub['credit'] = $debit;
                $sub['ledger_head'] = $r['ledger_head'] ?? "";
                array_push($array, $sub);

            }
            if($credit!=0){
                if ($r['ledger_id'] == $ledger) {
                    continue;
                }
                $sub['debit'] = $credit;
                $sub['credit'] = 0.00;
                $sub['ledger_head'] = $r['ledger_head'] ?? "";
                array_push($array, $sub);

            }
        }

        return $array;
    }
    public function getOpositLedgerCashReceipt($ar,$ledger=0){
        $journal = Journal::where('ref_id', $ar['ref_id'])
            ->where('ref_module', $ar['ref_module'])
            ->orderBy('effective_date', 'desc')
            ->get();
        $debit = 0;
        $credit = 0;

        foreach ($journal as $r) {
            if ($r['ledger_id'] == $ledger) {
                $debit = $r['debit'];
                $credit = $r['credit'];

            }
        }
        $array = array();
        $sub = array();
        $flag=0;
        foreach ($journal as $r) {
            if($debit!=0){
                if ($r['ledger_id'] == $ledger) {
                    continue;
                }

                if ($r['ledger_id'] == 36) {
                    continue;
                }
//                $journals = CashCollectionDetail::where('ref_id', $ar['ref_id'])
//                    ->first();
                $sub['debit'] = 0.00;
                $sub['credit'] = $debit;
                $sub['ledger_head'] = $r['ledger_head'] ?? "";
                array_push($array, $sub);

            }
            else if($credit!=0){

                if ($r['ledger_id'] == 6) {
                    $flag=1;
                }

                $sub['debit'] = $credit;
                $sub['credit'] = 0.00;
                $sub['ledger_head'] = $r['ledger_head'] ?? "";
                array_push($array, $sub);

            }
        }

        return $flag==0?$array:array();
    }
    public function generatePDF()
    {
        $data = ['title' => 'Welcome to Pakainfo.com'];
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Welcome to CodexWorld.com</h1>');
        return $pdf->stream();
        // Load HTML content
        $dompdf->PDFloadHtml('<h1>Welcome to CodexWorld.com</h1>');

// (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
        $dompdf->render();

// Output the generated PDF to Browser
        $dompdf->stream();
//        $returnHTML = view('admin.reports.rs_print', $data)->render();
//        $pdf = PDF::loadView('admin.reports.rs_print', $data);
//
//        return $pdf->download('pakainfo.pdf');
    }
    public function getReceiptPaymentOpeningBalance($checkData) {

        $p_date_e = $checkData['date_s'] ;//date('Y-m-d', strtotime("$checkData[date_s] -1 day"));
        $shop_no = '';
        $leader = $checkData['ledger'] != '' ? $checkData['ledger'] : '';
        $cashAtBbankLEdger = ChartOfAccount::where('category', 'Bank Accounts')
            ->get();

        $result['operatingIncome'] = array();
        $result['revenuePayment'] = array();
        $result['capitalReceipt'] = array();
        $result['capitalPayment'] = array();

        $bakLedgerId = array();
        $bakLedgerIds = array(6);
        foreach ($cashAtBbankLEdger as $r) {
            array_push($bakLedgerId, $r['id']);
            array_push($bakLedgerIds, $r['id']);
        }
//for income head
        $filterLedger = array();
        $refID = array();

        $checkData['creaditors'] = 'None';
        $checkData['customer'] = 'None';
        $cashInHand = 0;
        $cashAtBank = 0;
        $cashDebit = 0;
        $cashCredit = 0;

        if ($leader == '' || $leader == 6) {
            $checkData['ledger'] = 6;
            $cashInHand = $this->getOpeningBalance($checkData);
        }

        $opositResult = array();
        $bankDebit = 0;
        $bankCredit = 0;
        if ($leader == '' || $leader != 6) {
            if ($leader != '') {
                $checkData['ledger'] = $leader;
                $checkData['creaditors'] = 'None';
                $checkData['customer'] = 'None';
                $cashAtBank += $this->getOpeningBalance($checkData);
            } else{
                $journalBank = Journal::where('effective_date', '<', $p_date_e)
                    ->when($leader != '', function ($query) use ($leader) {
                        return $query->where('ledger_id', '=', $leader);
                    })
                    ->whereIn('ledger_id', $bakLedgerId)
                    ->orderBy('effective_date', 'desc')
                    ->groupBy('ledger_id')
                    ->get();
                foreach ($journalBank as $r) {
                    $checkData['ledger'] = $r['ledger_id'];
                    $checkData['creaditors'] = 'None';
                    $checkData['customer'] = 'None';
                    $cashAtBank += $this->getOpeningBalance($checkData);
                }
            }
        }
        return array('cashOpening'=>($cashInHand),'bankOpening'=>($cashAtBank));
    }
     public function  DueStatementCustomerWiseReport(Request  $request){
        $checkData = $request->all();
        $data['page_name'] = "Dues Statement Customer Wise";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Dues Statement Customer Wise', 'report.due-statement-customer'),
            array('Report', 'active')
        );
         $data['owner']= Owner::orderBy('name','ASC')->get();
        $data['period'] = "As on " . date('d M Y');
        if ($request->isMethod('post')) {

            $dateFilter = array();
            if ($checkData['month_from'] != '' && $checkData['month_to'] != '') {
                $date1 = new DateTime($checkData['month_from']);
                $date2 = new DateTime($checkData['month_to']);
                $interval = $date1->diff($date2);
                $year = $interval->y;
                $month = $interval->m;
                $month += $year * 12;
                $dateFilter[] = $checkData['month_from'];
                for ($i = 1; $i <= $month; $i++) {
                    $dateFilter[] = date("M Y", strtotime("+" . $i . " months", strtotime($checkData['month_from'])));
                }
                $data['period'] = "Month " . $checkData['month_from'] . " to " . $checkData['month_to'];
            }

            //  return $dateFilter;
            $array = array();
            $array1 = array();
            // $shop_no = explode(',',$checkData['shop_no']);
            if($checkData['shop_no']!=''){
                $shop_no = explode(',',$checkData['shop_no']);
            }else{
                $shop_no = array();
            }
            $bill_type = $checkData['bill_type']!=''?$checkData['bill_type']:"";
            $service = $checkData['service']!=''?$checkData['service']:"";
            $owner = $checkData['owner']!=''?$checkData['owner']:"";
            foreach ($shop_no as $s) {
                $ar1 = explode('@@@',$s);

                array_push($array, $ar1[0]);
                array_push($array1, $ar1[1]);
            }

            try {
                $ar = Billing::
                when(count($array)>0, function ($query) use ($array) {
                    return $query->whereIn('billings.shop_no',$array);
                })
                    ->when($bill_type!='', function ($query) use ($bill_type) {
                        return $query->where('billings.bill_type', '=', $bill_type);
                    })
                    ->when($service!='', function ($query) use ($service) {
                        return $query->where('billings.off_type', '=', $service);
                    })
                    ->when(count($array1)>0, function ($query) use ($array1) {
                        return $query->whereIn('billings.customer_id',  $array1);
                    })
                    ->when($owner!='', function ($query) use ($owner) {
                        return $query->where('billings.owner_id',  $owner);
                    })
                    ->where('billings.payment_status',0)
                    ->selectRaw('billings.*,
                    cash_collections.money_receipt_no,
                    cash_collections.payment_mode,
                    cash_collections.cheque_no,
                    billing_details.month,
                    billing_details.ledger_id,
                    group_concat(billings.id) as ids,
                    billing_details.amount as bill_amount,
                    sum(billing_details.fine) as fine,
                    sum(billing_details.interest) as interest,
                    sum(cash_collections.payment_amount) as paid_amount,
                    sum(cash_collections.paid_vat_amount) as paid_vatamount,
                    sum(cash_collections.paid_fine_amount) as paid_fine_amount,
                    sum(cash_collections.paid_fixed_fine) as paid_fixed_fine,
                    cash_collections.created_at as payment_date')
                    ->leftJoin('billing_details', 'billings.id', '=', 'billing_details.billing_id')
                    ->leftJoin('cash_collections', 'billings.id', '=', 'cash_collections.income_id')
                    ->when(count($dateFilter) > 0, function ($query) use ($dateFilter) {
                        return $query->whereIn('billing_details.month', $dateFilter);
                    })
                    
                    ->groupBy('billings.id')
                    ->orderByRaw("CASE
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jan'
    THEN 1
    WHEN SUBSTRING(billing_details.month,1,3) = 'Feb'
    THEN 2
    WHEN SUBSTRING(billing_details.month,1,3) = 'Mar'
    THEN 3
    WHEN SUBSTRING(billing_details.month,1,3) = 'Apr'
    THEN 4
    WHEN SUBSTRING(billing_details.month,1,3) = 'May'
    THEN 5
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jun'
    THEN 6
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jul'
    THEN 7
    WHEN SUBSTRING(billing_details.month,1,3) = 'Aug'
    THEN 8
    WHEN SUBSTRING(billing_details.month,1,3) = 'Sep'
    THEN 9
    WHEN SUBSTRING(billing_details.month,1,3) = 'Oct'
    THEN 10
    WHEN SUBSTRING(billing_details.month,1,3) = 'Nov'
    THEN 11
    WHEN SUBSTRING(billing_details.month,1,3) = 'Dec'
    THEN 12
  END, MONTH ASC")
                    ->get();
                $result = array();
                $result1 = array();
                $shop = array();
                foreach ($ar as $row) {
                    $row['bill_amount'] =  $row['bill_amount'] - $row['paid_amount'];
                    $row['fine_amount'] =  $row['fine_amount'] - $row['paid_fine_amount'];
                    $row['fixed_fine'] =  $row['fixed_fine'] - $row['paid_fixed_fine'];
                    $shop['customer_name'] = $row['shop_name'];
                    $result1[$row['bill_type']] = $row['bill_type'];
                    $result[$row['shop_no']]['bill2'] = Asset::where('asset_no',$row['shop_no'])->first();
                    $result[$row['shop_no']]['bill'][] = $row;
                }
//                    $result[$row['shop_no']]['ids'] = $row['ids'];
//                    $result[$row['shop_no']]['rent'] = $row['bill_type'] == 'Rent' ? $row['bill_amount'] - $row['paid_amount'] : 0;
//                    $result[$row['shop_no']]['sc'] = $row['bill_type'] == 'Service Charge' ? $row['bill_amount'] - $row['paid_amount'] : 0;
//                    $result[$row['shop_no']]['fine_amount'] = $row['bill_type'] == 'Service Charge' ? $row['fine_amount'] : 0;
//                    $result[$row['shop_no']]['fixed_fine'] = $row['bill_type'] == 'Service Charge' ? $row['fixed_fine'] : 0;
//                    $result[$row['shop_no']]['el'] = $row['bill_type'] == 'Electricity' ? $row['bill_amount'] - $row['paid_amount'] : 0;
//                    $result[$row['shop_no']]['el_fine'] = $row['bill_type'] == 'Electricity' ? $row['fine_amount'] : 0;
//                    $result[$row['shop_no']]['fcsc'] = trim($row['ledger_id']) == 34 ? $row['bill_amount'] - $row['paid_amount'] : 0;
//                    $result[$row['shop_no']]['sc_fine'] = $row['bill_type'] == 'Service Charge' ? $row['fine'] : 0;
//                    $result[$row['shop_no']]['interest'] = $row['ledger_id'] == 34 ? $row['interest'] : 0;
//                    $result[$row['shop_no']]['advertisement'] = $row['ledger_id'] == 44 ? $row['bill_amount'] - $row['paid_amount'] : 0;
//                    $result[$row['shop_no']]['spsc'] = $row['ledger_id'] == 43 ? $row['bill_amount'] - $row['paid_amount'] : 0;
//                    $result[$row['shop_no']]['other_income'] = $row['ledger_id'] == 42 ? $row['bill_amount'] - $row['paid_amount'] : 0;

                    $data['customer'] = $result;
                    $data['customer1'] = $result1;
//                $data['month'] = $month;
                $data['shop'] = $shop;
                if($checkData['t']=='pdf') {
                    $returnHTML = view('admin.reports.due_statement_customer_print', $data)->render();
//                $data = ['title' => 'Welcome to Pakainfo.com'];
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->set_paper('A4', 'p');
                    $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                    $pdf->loadHTML($d);
                    return $pdf->stream();
                }

                $returnHTML = view('admin.reports.due_statement_customer_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            } catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }

        } else {
            $data['asset'] = Billing::leftjoin('customers', 'customers.id', '=', 'billings.customer_id')->SelectRaw('billings.*,customers.shop_name as name')
                ->orderBy('billings.shop_no', 'ASC')
                ->groupBy('billings.customer_id')
                ->groupBy('billings.shop_no')
                ->get();
                 $data['owner'] = Owner::all();
            //$data['asset'] = Asset::orderBy('customer_id','ASC')->get();
            return view('admin.reports.due_statement_customer', $data);
        }
    }
     public function  DueStatementShopWiseReport(Request  $request){
        $checkData = $request->all();
        $data['page_name'] = "Dues Statement Shop Wise";
        $data['breadcumb'] = array(
            array('Home', 'home'),
            array('Dues Statement Shop Wise', 'report.due-statement-shop'),
            array('Report', 'active')
        );
        $data['period'] = "As on " . date('d M Y');
        if ($request->isMethod('post')) {

            $dateFilter = array();
            if ($checkData['month_from'] != '' && $checkData['month_to'] != '') {
                $date1 = new DateTime($checkData['month_from']);
                $date2 = new DateTime($checkData['month_to']);
                $interval = $date1->diff($date2);
                $year = $interval->y;
                $month = $interval->m;
                $month += $year * 12;
                $dateFilter[] = $checkData['month_from'];
                for ($i = 1; $i <= $month; $i++) {
                    $dateFilter[] = date("M Y", strtotime("+" . $i . " months", strtotime($checkData['month_from'])));
                }
                $data['period'] = "Month " . $checkData['month_from'] . " to " . $checkData['month_to'];
            }

            //  return $dateFilter;
            $array = array();
            $array1 = array();
            // $shop_no = explode(',',$checkData['shop_no']);
            if($checkData['shop_no']!=''){
                $shop_no = explode(',',$checkData['shop_no']);
            }else{
                $shop_no = array();
            }
            $bill_type = $checkData['bill_type']!=''?$checkData['bill_type']:"";
            $service = $checkData['service']!=''?$checkData['service']:"";
            $owner = $checkData['owner']!=''?$checkData['owner']:"";
            foreach ($shop_no as $s) {
                $ar1 = explode('@@@',$s);

                array_push($array, $ar1[0]);
                array_push($array1, $ar1[1]);
            }

            try {
                $ar = Billing::
                when(count($array)>0, function ($query) use ($array) {
                    return $query->whereIn('billings.shop_no',$array);
                })
                    ->when($bill_type!='', function ($query) use ($bill_type) {
                        return $query->where('billings.bill_type', '=', $bill_type);
                    })
                    ->when($service!='', function ($query) use ($service) {
                        return $query->where('billings.off_type', '=', $service);
                    })
                    ->when(count($array1)>0, function ($query) use ($array1) {
                        return $query->whereIn('billings.customer_id',  $array1);
                    })
                    ->when($owner!='', function ($query) use ($owner) {
                        return $query->where('billings.owner_id',  $owner);
                    })
                    ->selectRaw('billings.*,
                    cash_collections.money_receipt_no,
                    cash_collections.payment_mode,
                    cash_collections.cheque_no,
                    billing_details.month,
                    billing_details.ledger_id,
                    group_concat(billings.id) as ids,
                    billing_details.amount as bill_amount,
                    sum(billing_details.fine) as fine,
                    sum(billing_details.interest) as interest,
                    sum(cash_collections.payment_amount) as paid_amount,
                    sum(cash_collections.paid_vat_amount) as paid_vatamount,
                    sum(cash_collections.paid_fine_amount) as paid_fine_amount,
                    sum(cash_collections.paid_fixed_fine) as paid_fixed_fine,
                    cash_collections.created_at as payment_date')
                    ->leftJoin('billing_details', 'billings.id', '=', 'billing_details.billing_id')
                    ->leftJoin('cash_collections', 'billings.id', '=', 'cash_collections.income_id')
                    ->when(count($dateFilter) > 0, function ($query) use ($dateFilter) {
                        return $query->whereIn('billing_details.month', $dateFilter);
                    })
                    ->where('billings.payment_status',0)
                    ->groupBy('billings.id')
                    ->orderByRaw("CASE
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jan'
    THEN 1
    WHEN SUBSTRING(billing_details.month,1,3) = 'Feb'
    THEN 2
    WHEN SUBSTRING(billing_details.month,1,3) = 'Mar'
    THEN 3
    WHEN SUBSTRING(billing_details.month,1,3) = 'Apr'
    THEN 4
    WHEN SUBSTRING(billing_details.month,1,3) = 'May'
    THEN 5
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jun'
    THEN 6
    WHEN SUBSTRING(billing_details.month,1,3) = 'Jul'
    THEN 7
    WHEN SUBSTRING(billing_details.month,1,3) = 'Aug'
    THEN 8
    WHEN SUBSTRING(billing_details.month,1,3) = 'Sep'
    THEN 9
    WHEN SUBSTRING(billing_details.month,1,3) = 'Oct'
    THEN 10
    WHEN SUBSTRING(billing_details.month,1,3) = 'Nov'
    THEN 11
    WHEN SUBSTRING(billing_details.month,1,3) = 'Dec'
    THEN 12
  END, MONTH ASC")
                    ->get();
                $result = array();
                $result1 = array();
                $shop = array();
                $result = array();
                $shop = array();
                foreach ($ar as $row) {
                    $shop['customer_name'] = $row['shop_name'];
                    $shop['shop_no'] = $row['shop_no'];

                    if (isset($result[$row['shop_no']])) {
                        $result[$row['shop_no']]['rent'] += $row['bill_type'] == 'Rent' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['sc'] += $row['bill_type'] == 'Service Charge' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['fine_amount'] += $row['bill_type'] == 'Service Charge' ? $row['fine_amount'] - $row['paid_fine_amount'] : 0;
                        $result[$row['shop_no']]['fixed_fine'] += $row['bill_type'] == 'Service Charge' ? $row['fixed_fine']-$row['paid_fixed_fine'] : 0;
                        $result[$row['shop_no']]['el'] += $row['bill_type'] == 'Electricity' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['fcsc'] += $row['ledger_id'] == 34 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['sc_fine'] += $row['bill_type'] == 'Service Charge' ? $row['fine'] - $row['paid_fine_amount'] : 0;
                        $result[$row['shop_no']]['interest'] += $row['bill_type'] == 'Electricity' ? $row['interest'] : 0;
                        $result[$row['shop_no']]['advertisement'] += $row['ledger_id'] == 44 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['spsc'] += $row['ledger_id'] == 43 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['el_fine'] += $row['bill_type'] == 'Electricity' ? $row['fine_amount']-$row['paid_fine_amount'] : 0;
                        $result[$row['shop_no']]['other_income'] += $row['ledger_id'] == 42 ? $row['bill_amount'] - $row['paid_amount'] : 0;

                    } else {
                        $result[$row['shop_no']]['rent'] = $row['bill_type'] == 'Rent' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['sc'] = $row['bill_type'] == 'Service Charge' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['fine_amount'] = $row['bill_type'] == 'Service Charge' ? $row['fine_amount']- $row['paid_fine_amount'] : 0;
                        $result[$row['shop_no']]['fixed_fine'] = $row['bill_type'] == 'Service Charge' ? $row['fixed_fine']-$row['paid_fixed_fine'] : 0;
                        $result[$row['shop_no']]['el'] = $row['bill_type'] == 'Electricity' ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['el_fine'] = $row['bill_type'] == 'Electricity' ? $row['fine_amount']-$row['paid_fine_amount'] : 0;
                        $result[$row['shop_no']]['fcsc'] = trim($row['ledger_id']) == 34 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['sc_fine'] = $row['bill_type'] == 'Service Charge' ? $row['fine']- $row['paid_fine_amount'] : 0;
                        $result[$row['shop_no']]['interest'] = $row['ledger_id'] == 34 ? $row['interest'] : 0;
                        $result[$row['shop_no']]['advertisement'] = $row['ledger_id'] == 44 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['spsc'] = $row['ledger_id'] == 43 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                        $result[$row['shop_no']]['other_income'] = $row['ledger_id'] == 42 ? $row['bill_amount'] - $row['paid_amount'] : 0;
                    }

                }

                $data['customer'] = $result;
                if($checkData['t']=='pdf') {
                    $returnHTML = view('admin.reports.due_statement_shop_print', $data)->render();
//                $data = ['title' => 'Welcome to Pakainfo.com'];
                    $pdf = \App::make('dompdf.wrapper');
                    $pdf->set_paper('A4', 'p');
                    $d = pdfHtmlView::pdfView(array('body'=>$returnHTML));
                    $pdf->loadHTML($d);
                    return $pdf->stream();
                }

                $returnHTML = view('admin.reports.due_statement_shop_print', $data)->render();
                return response()->json(array('success' => true, 'result' => $returnHTML));
            } catch (\Exception $e) {
                $msg = $e->getLine() . " " . $e->getMessage() . " " . $e->getCode();
                return response()->json(array('success' => true, 'result' => $msg));
            }

        } else {
            $data['asset'] = Billing::leftjoin('customers', 'customers.id', '=', 'billings.customer_id')->SelectRaw('billings.*,customers.shop_name as name')
                ->orderBy('billings.shop_no', 'ASC')
                ->groupBy('billings.customer_id')
                ->groupBy('billings.shop_no')
                ->get();
            $data['owner'] = Owner::all();
            //$data['asset'] = Asset::orderBy('customer_id','ASC')->get();
            return view('admin.reports.due_statement_shop', $data);
        }
    }
}
