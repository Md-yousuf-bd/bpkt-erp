<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetLog;
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

class CronJobController extends Controller
{


    public function dueBillCreate()
    {
               $file = 'people.txt';
// Open the file to get existing content
       // $current = file_get_contents($file);
// Append a new person to the file
        $current = "John Smith\n";
// Write the contents back to the file
        file_put_contents($file, $current);
       // return;
        $date = date('Y-m-d');
        $billing = Billing::leftjoin('billing_details', 'billings.id', '=', 'billing_details.billing_id')->where('fine_status', '<>', 1)
            ->where('due_date', '<', $date)
            ->where('payment_status', '<>', 1)
            ->where('billings.fine_applicable', 'Yes')
            ->selectRaw('billings.*,billing_details.ledger_id,billing_details.month')
            ->groupBy('billings.id')
            ->get();
        foreach ($billing as $row) {
            if ($row['ledger_id'] == 31 || $row['ledger_id'] == 32 || $row['ledger_id'] == 119) { // service charge
                $data['fixed_fine'] = 0;
                $data['month'] = 0;
                $percent_sc = $row['percent_sc'] + 3;
                $from = date_create($row['due_date']);
//                if($row['next_due_date']!=''){
//                    $from=date_create($row['next_due_date']);
//                }
                $to = date_create(date('Y-m-d'));

                $diff = date_diff($to, $from);
                $amount1=0;

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
                            $amount1 = round($amount);
                            $amount = round(($amount + $row['fine_amount']));


                            $this->makeJournal($row, $amount1, 76);
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
                            $amount1 = round($amount);
                            $amount = round($amount + $row['fine_amount']);

                            $this->makeJournal($row, $amount1, 76);
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
            } else if ($row['ledger_id'] == 33 || $row['ledger_id'] == 116 || $row['ledger_id'] == 26) { // electricity
                $data['fixed_fine'] = 0;
                $data['month'] = 0;
                $to = date_create(date('Y-m-d'));
                $from = date_create($row['due_date']);
                $diff = date_diff($to, $from);

                if ($diff->days >= 1) {

                    $amount = round($row['total'] * .10);
                    Billing::where('id', $row['id'])->update(['fine_status' => 1, 'fine_amount' => $amount]);
                    $this->makeJournal($row, $amount, 30);
                }


            }
        }
        return '<h2 style="text-align:Center;margin-top: 90px;">Apply auto increment Process Complete.....</h2>';

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
        $customer_id = $row['customer_id'];
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
            'transaction_type' => 'Billing', 'invoice_no' => $invoice_no, 'customer_name' => trim($customer_name), 'customer_id' => trim($customer_id),
            'remarks' => $remarks, 'ledger_head' => $ledger_name, 'date' => $issue_date, 'debit' => 0, 'is_fine' => 1,
            'credit' => $amount, 'voucher_no' => $voucher_no, 'ref_module' => 'Bulk Entry', 'shop_no' => $shop_no,
            'created_by' => '0');
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
            'customer_name' => trim($customer_name),  'customer_id' => trim($customer_id),'remarks' => '', 'ledger_head' => 'Accounts Receivable',
            'date' => $issue_date, 'debit' => $amount, 'credit' => 0, 'voucher_no' => $voucher_no, 'is_fine' => 1,
            'shop_no' => $shop_no, 'ref_module' => 'Bulk Entry', 'created_by' => 0);
        array_push($jv, $sub);
        return Journal::insert($jv);


    }
    public function rentAutoIncrement(){
        $data['page_name']="Apply auto increment";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Billing','active'),
            array('List','active')
        );
        $assets = Asset::where('status','<>','Un-allotted')
            ->where('increment_effective_month','<>',0)
            ->get();
        foreach ($assets as $r){
            if($r['last_increment_date']!=''){
                $date1=   date_create(date('Y-m-d'));
                $date2=   date_create($r['last_increment_date']);
                $interval = date_diff($date2, $date1);// ->diff($date1);
//                echo "<pre>";
//                print_r($interval);
                $month = ($interval->y * 12) + $interval->m ;
               // echo $month. " ".$r['asset_no'] ."<br>";
                if($month >= $r['increment_effective_month']){
                    echo $month. " - ".$r['asset_no']."<br>";
                    $increment = round(($r['rent_increment']/100)*$r['rate'],3);
                    $total=$r['rate']+$increment;
                    $asst = Asset::find($r['id']);
                    $log = array();
                    $log = $asst;
                    unset($log['id'], $log['updated_at']);
                    $log['updated_at'] = date('Y-m-d H:i:s');
                    $log['ref_id'] = $r['id'];
                    $log['updated_by'] = 0;
                    AssetLog::insert($log->toArray());
                    unset($log['ref_id']);
                    $asst->rate = $total;
                    $asst->last_increment_date = date('Y-m-d');
                    $asst->save();
                }
            }
        }
        return '<h2 style="text-align:Center;margin-top: 90px;">Apply auto increment Process Complete.....</h2>';

    }
    public function rentInterestCreate(){

        $data['page_name']="Apply auto increment";
        $data['breadcumb']=array(
            array('Home','home'),
            array('Billing','active'),
            array('List','active')
        );
//        return view('admin.billing.index',$data);
         $date = date('Y-m-d');
          $rentResult = Billing::where('bill_type','Rent')->where('due_date','<',$date)->get();
        foreach ($rentResult as $row){
            $from = date_create($row['due_date']);
            $to = date_create(date('Y-m-d'));
            $diff = date_diff($to, $from);
            $months = (($diff->y) * 12) + ($diff->m);
            $months = $months * .03;
            $amount = $row['total'] * $months;
            Billing::where('id', $row['id'])->update(
                [
                    'fine_amount' => $amount
                ]
            );
            Journal::where('ref_id',$row['id'])->where('is_fine',1)->whereIn('ref_module',['Bulk Entry','Billing'])->delete();
            $this->makeJournal($row, $amount, 28);
        }
        return '<h2 style="text-align:Center;margin-top: 90px;"> Apply auto Fine & Interest Process Complete.....</h2>';

    }
        public function testMake(){
        DB::table('tests')->insert(
            array('email' => 'john@examplettttt.com')
        );
    }

//    "SELECT
//  journals.ref_id,
//  journals.invoice_no,
//  journals.ref_module,
//  journals.`customer_id`,
//  journals.`customer_name`,
//  billings.id,
//  journals.`ledger_head`
//FROM
//  `journals`
//  LEFT JOIN `billings`
//    ON billings.id = journals.`ref_id`
//    AND journals.`ref_module` = 'Bulk Entry'
//WHERE billings.id IS NULL "
}
