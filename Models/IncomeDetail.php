<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeDetail extends Model
{
    public static function incomeDetails($id){

        $ledger = array(
            'service_charge'=>0,
            'food_court'=>0,
            'electricity'=>0,
            'sp_service'=>0,
            'advertisement'=>0,
            'total'=>0,
            'rent'=>0,
            'interest'=>0,
            'month'=>'',
        );
        $res =  IncomeDetail::where('income_id',$id)->get();
        $total = 0;
        foreach ($res as $row){
            $ledger['month']=$row['month'];
            if($row['income_head_id'] == 29){
                $ledger['rent']=$row['amount'];
            }
            else if($row['income_head_id'] == 30){
                $ledger['interest']=$row['amount'];
            }
            else if($row['income_head_id'] == 31){ //Service Charge Revenue
                $ledger['service_charge']=$row['amount'];
                $total = $total+$row['amount'];
            }elseif ($row['income_head_id'] == 34){ //'Food Court SC Income'
                $ledger['food_court']=$row['amount'];
                $total = $total+$row['amount'];

            }elseif ($row['income_head_id'] == 33 ){ // Utility-Electricity Income
                $ledger['electricity']=$row['amount'];
                $total = $total+$row['amount'];
            }elseif ($row['income_head_id'] == 43){ //'Special Service Charge Revenue'
                $ledger['sp_service']=$row['amount'];
                $total = $total+$row['amount'];
            }elseif ($row['income_head_id'] == 44){ //'Advertisement Revenue'
                $ledger['advertisement']=$row['amount'];
                $total = $total+$row['amount'];
            }
        }
        $ledger['total']=$total;
        return $ledger;
    }
}
