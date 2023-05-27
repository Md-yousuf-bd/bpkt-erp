<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingDetail extends Model
{
    public static function billingDetail($id,$d){

        $ledger = array(
            'service_charge'=>0,
            'sc_fine'=>0,
            'el_fine'=>0,
            'sc_fixed_fine'=>0,
            'food_court'=>0,
            'electricity'=>0,
            'sp_service'=>0,
            'advertisement'=>0,
            'total'=>0,
            'rent'=>0,
            'interest'=>0,
            'month'=>'',
        );
        $res =  BillingDetail::where('billing_id',$id)->get();
        $total = 0;
        foreach ($res as $row){
            $ledger['month']=$row['month'];
            if($row['ledger_id'] == 29){
                $ledger['rent']=$row['amount'];
            }
            else if($row['ledger_id'] == 30){
                $ledger['interest']=$row['amount'];
            }
            else if($row['ledger_id'] == 31 || $row['ledger_id'] == 32 || $row['ledger_id'] == 119){ //Service Charge Revenue
                $ledger['service_charge']=$row['amount'];
                $ledger['sc_fine']=$d['fine_amount'];
                $ledger['sc_fixed_fine']=$d['fixed_fine'];
                $total = $total+$row['amount'];
            }elseif ($row['ledger_id'] == 34){ //'Food Court SC Income'
                $ledger['food_court']=$row['amount'];
                $total = $total+$row['amount'];

            }elseif ($row['ledger_id'] == 33 ){ // Utility-Electricity Income
                $ledger['electricity']=$row['amount'];
                $ledger['el_fine']=$d['fine_amount'];
                $total = $total+$row['amount'];
            }elseif ($row['ledger_id'] == 43){ //'Special Service Charge Revenue'
                $ledger['sp_service']=$row['amount'];
                $total = $total+$row['amount'];
            }elseif ($row['ledger_id'] == 44){ //'Advertisement Revenue'
                $ledger['advertisement']=$row['amount'];
                $total = $total+$row['amount'];
            }
        }
        $ledger['total']=$total;
        return $ledger;
    }
    public function accCoa(){
        return $this->hasOne(ChartOfAccount::class,'id','ledger_id');
    }

}
