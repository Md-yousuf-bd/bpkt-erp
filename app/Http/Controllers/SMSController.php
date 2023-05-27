<?php

namespace App\Http\Controllers;

use App\Models\SMS;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return bool|\Illuminate\Http\Response|string
     */
    public static function send($number,$sms_purpose,$message='This is a test SMS.',$sms_type='System Generated',$sender_id='8809601000500',$cost=0.25,$bulk_company='Elitbuzz')
    {
        if($bulk_company=='Elitbuzz')
        {

            $api_key = "C20044085dc31630191244.78966809";
            $contacts = '88' . $number;
            $URL = "http://bangladeshsms.com/smsapi?api_key=".urlencode($api_key)."&type=text&contacts=".urlencode($contacts)."&senderid=".urlencode($sender_id)."&msg=".urlencode($message);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,$URL);

            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

            curl_setopt($ch, CURLOPT_POST, 0);

            try{

                $output = $content=curl_exec($ch);

                // print_r($output);

            }catch(Exception $ex){

                $output = "-100";

            }

            $sms_length=strlen($message)/160;
            $round_sms_length=intval(round(strlen($message),2,PHP_ROUND_HALF_UP )/160);
            if($sms_length>$round_sms_length){
                $round_sms_length=$round_sms_length+1;
            }

            $sms=new SMS();
            $sms->bulk_company=$bulk_company;
            $sms->sender_id=$sender_id;
            $sms->phone_number=$number;
            $sms->content=$message;
            $sms->cost_per_sms=$cost;
            $sms->sms_counted=$round_sms_length;
            $sms->sms_type=$sms_type;
            $sms->sms_purpose=$sms_purpose;
            if($output==1012)
            {
                $sms->cost_per_sms=0;
                $status='danger';
                $status_text=$output.': Number is not Valid.';
            }
            elseif (($output>=1002 && $output<=1006)||($output>=1008 && $output<=1011))
            {
                $sms->cost_per_sms=0;
                $status='danger';
                $status_text=$output.': Found Error from Company Side while sending SMS.';
            }
            elseif ($output==1007)
            {
                $sms->cost_per_sms=0;
                $status='danger';
                $status_text=$output.': Insufficient balance in SMS Bulk Account.';
            }
            else
            {
                $status='success';
                $status_text= $output;
                $output='success';
            }
            $sms->total_cost=$round_sms_length*$sms->cost_per_sms;
            $sms->status=$status;
            $sms->status_text=$status_text;
            $sms->save();

            return $output;
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function show(SMS $sMS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function edit(SMS $sMS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SMS $sMS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function destroy(SMS $sMS)
    {
        //
    }
}
