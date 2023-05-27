


{{--                    <div class="card-header" style="padding-bottom: 0px;padding-top: 0px !important;">--}}
{{--                        <h5 class="card-title">Collection Statement Report</h5>--}}

{{--                    </div>--}}
                    <div class="card-body card-block" style="padding-top: 0px;width: 100% !important;">
                        <div class="table-responsive" style="font-size: 14px;width: 100% !important; background: white; padding: 10px;  overflow: auto;">
                            <table style="width: 100% !important;border: none;"  class="table  table-borderless" valign="top">
                                <tr style="display: none">
                                    <td  style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                        <br> <strong> Accounts Department</strong>
                                    </td>
                                </tr>


                            </table>


                            <div > <strong> For the period of {{ $date }} </strong> </div>
                            <table class="table table-bordered" style="font-size: 14px; width: 100% !important;">
                                <tr style="background: #d9d9d9;color:#000;">
                                    <td colspan="2" rowspan="2" style=""><strong>Category</strong></td>
                                    <td colspan="3" style="text-align: center"><strong>Current Selected Period</strong></td>
                                    <td rowspan="2" style=""><strong>Collectio From <br>Old Dues(B)</strong></td>
                                    <td rowspan="2" style=""><strong>Total Collection<br>Amount(A+B)</strong></td>

                                </tr>
                                <tr style="background: #d9d9d9;color:#000;">
                                    <td> <strong>Invoice Value </strong> </td>
                                    <td> <strong>Collection Amount(A) </strong> </td>
                                    <td> <strong>Due Amount </strong> </td>

                                </tr>

                                <?php
                                    $toalAdvance=0;
                                    $securityDeposit=0;
                                    $rent=0;
                                    $sv_actual=0;
                                    $electricity=0;
                                    $food_court=0;
                                    $sp_service=0;
                                    $advertisement=0;
                                    $total_sub =0;
                                    $total_interest =0;
                                    $total_vat =0;
                                    $amount = ($collection['Rent']['oldcollection']??0)+($collection['Rent']['collection']??0);
                                    $samount = ($collection['Service Charge']['oldcollection']??0)+($collection['Service Charge']['collection']??0);
                                    $fsamount = ($collection['Service Charge']['oldfinecollection']??0)+($collection['Service Charge']['finecollection']??0);
                                    $total_sci = $collection['Service Charge']['invoice']+$collection['Service Charge']['fine'];
                                    $total_scc = $collection['Service Charge']['collection']+$collection['Service Charge']['finecollection'];
                                    $total_solc = $collection['Service Charge']['oldfinecollection']+$collection['Service Charge']['oldcollection'];
                                    $total_sd1 = $collection['Service Charge']['invoice']-$collection['Service Charge']['collection'];
                                    $total_sd2 = $collection['Service Charge']['fine']-$collection['Service Charge']['finecollection'];
                                    $eamount = ($collection['Electricity']['oldcollection']??0)+($collection['Electricity']['collection']??0);
                                    $feamount = ($collection['Electricity']['oldfinecollection']??0)+($collection['Electricity']['finecollection']??0);

                                    $total_ecc = $collection['Electricity']['fine']+$collection['Electricity']['invoice'];
                                    $total_elc = $collection['Electricity']['finecollection']+$collection['Electricity']['collection'];
                                    $total_ed1 = $collection['Electricity']['invoice']-$collection['Electricity']['collection'];
                                    $total_ed2 = $collection['Electricity']['fine']-$collection['Electricity']['finecollection'];

                                    $edue = ($collection['Electricity']['invoice']??0)-($collection['Electricity']['collection']??0);
                                    $spamount = ($collection['Special Service Charge']['oldcollection']??0)+($collection['Special Service Charge']['collection']??0);
                                    $spdue = ($collection['Special Service Charge']['invoice']??0)-($collection['Special Service Charge']['collection']??0);
                                    $fdamount = ($collection['Food Court Service Charge']['oldcollection']??0)+($collection['Food Court Service Charge']['collection']??0);
                                    $fddue = ($collection['Food Court Service Charge']['invoice']??0)-($collection['Food Court Service Charge']['collection']??0);
                                    $adamount = ($collection['Advertisement']['oldcollection']??0)+($collection['Advertisement']['collection']??0);
                                    $addue = ($collection['Advertisement']['invoice']??0)-($collection['Advertisement']['collection']??0);

                                ?>



                                    <tr>
                                        <td colspan="2">Rent</td>
                                        <td style="text-align: right;">{{number_format($collection['Rent']['invoice']??"00",2)}}</td>
                                        <td style="text-align: right;">{{number_format($collection['Rent']['collection']??"0",2)}}</td>
                                        <td style="text-align: right;"> {{number_format($collection['Rent']['invoice']-$collection['Rent']['collection'],2)}} </td>
                                        <td style="text-align: right;">{{number_format($collection['Rent']['oldcollection']??0,2)}}</td>

                                        <td style="text-align: right;"><strong> {{ number_format($amount,2) }}</strong> </td>
                                    </tr>
                                <tr>
                                    <td rowspan="3" style="">Service Charge</td>
                                    <td  style="">Principle</td>

                                    <td style="text-align: right;">{{number_format($collection['Service Charge']['invoice']??"00",2)}}</td>
                                    <td style="text-align: right;">{{number_format($collection['Service Charge']['collection']??"0",2)}}</td>
                                    <td style="text-align: right;"> {{number_format($collection['Service Charge']['invoice']-$collection['Service Charge']['collection'],2)}} </td>
                                    <td style="text-align: right;">{{number_format($collection['Service Charge']['oldcollection']??0,2)}}</td>

                                    <td style="text-align: right;"> {{ number_format($samount,2) }} </td>
                                </tr>
                                <tr>
                                    <td style="">Fine</td>
                                    <td style="text-align: right;">{{number_format($collection['Service Charge']['fine']??"00",2)}}</td>
                                    <td style="text-align: right;">{{number_format($collection['Service Charge']['finecollection']??"0",2)}}</td>
                                    <td style="text-align: right;"> {{number_format($collection['Service Charge']['fine']-$collection['Service Charge']['finecollection'],2)}} </td>
                                    <td style="text-align: right;">{{number_format($collection['Service Charge']['oldfinecollection']??0,2)}}</td>

                                    <td style="text-align: right;"> {{ number_format($fsamount,2) }} </td>
                                </tr>
                                <tr>
                                    <td style="">Total S. Charge</td>
                                    <td style="text-align: right;"><strong>{{number_format($total_sci,2)}}</strong></td>
                                    <td style="text-align: right;"><strong>{{number_format($total_scc,2)}}</strong></td>
                                    <td style="text-align: right;"><strong>{{number_format($total_sd1+$total_sd2,2)}}</strong></td>
                                    <td style="text-align: right;"><strong> {{number_format($total_solc,2)}} </strong></td>
                                    <td style="text-align: right;"><strong> {{number_format($fsamount+$samount,2)}} </strong></td>
                                </tr>
                                <tr>
                                    <td rowspan="3" style="">Electricity Bill</td>
                                    <td  style="">Principle</td>


                                    <td style="text-align: right;">{{number_format($collection['Electricity']['invoice']??"00",2)}}</td>
                                    <td style="text-align: right;">{{number_format($collection['Electricity']['collection']??"0",2)}}</td>
                                    <td style="text-align: right;"> {{number_format($collection['Electricity']['invoice']-$collection['Electricity']['collection'],2)}} </td>
                                    <td style="text-align: right;">{{number_format($collection['Electricity']['oldcollection']??0,2)}}</td>

                                    <td style="text-align: right;"> {{ number_format($eamount,2) }} </td>
                                </tr>
                                <tr>
                                    <td style="">Fine</td>
                                    <td style="text-align: right;">{{number_format($collection['Electricity']['fine']??"00",2)}}</td>
                                    <td style="text-align: right;">{{number_format($collection['Electricity']['finecollection']??"0",2)}}</td>
                                    <td style="text-align: right;"> {{number_format($collection['Electricity']['fine']-$collection['Electricity']['finecollection'],2)}} </td>
                                    <td style="text-align: right;">{{number_format($collection['Electricity']['oldfinecollection']??0,2)}}</td>

                                    <td style="text-align: right;"> {{ number_format($feamount,2) }} </td>
                                </tr>
                                <tr>
                                    <td style="">Total Electric Bill</td>
                                    <td style="text-align: right;"><strong>{{number_format($total_ecc,2)}}</strong></td>
                                    <td style="text-align: right;"><strong>{{number_format($total_elc,2)}}</strong></td>
                                    <td style="text-align: right;"><strong>{{number_format($total_ed1+$total_ed2,2)}}</strong></td>
                                    <td style="text-align: right;"> <strong>{{number_format($total_solc,2)}}</strong> </td>
                                    <td style="text-align: right;"><strong> {{number_format($feamount+$eamount,2)}} </strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Special Service Charge</td>
                                    <td style="text-align: right;">{{number_format($collection['Special Service Charge']['invoice'],2)}}</td>
                                    <td style="text-align: right;">{{number_format($collection['Special Service Charge']['collection'],2)}}</td>
                                    <td style="text-align: right;"> {{ number_format($spdue,2) }} </td>
                                    <td style="text-align: right;">{{number_format($collection['Special Service Charge']['oldcollection'],2)}}</td>
                                    <td style="text-align: right;"><strong>{{number_format($spamount,2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Food Court S. Charge</td>
                                    <td style="text-align: right;">{{number_format($collection['Food Court Service Charge']['invoice'],2)}}</td>
                                    <td style="text-align: right;">{{number_format($collection['Food Court Service Charge']['collection'],2)}}</td>
                                    <td style="text-align: right;"> {{ number_format($fddue,2) }} </td>
                                    <td style="text-align: right;">{{number_format($collection['Food Court Service Charge']['oldcollection'],2)}}</td>
                                    <td style="text-align: right;"><strong>{{number_format($fdamount,2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Advertisement</td>
                                    <td style="text-align: right;">{{number_format($collection['Advertisement']['invoice'],2)}}</td>
                                    <td style="text-align: right;">{{number_format($collection['Advertisement']['collection'],2)}}</td>
                                    <td style="text-align: right;"> {{ number_format($addue,2) }} </td>
                                    <td style="text-align: right;">{{number_format($collection['Advertisement']['oldcollection'],2)}}</td>
                                    <td style="text-align: right;"><strong>{{number_format($adamount,2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2">  <strong>Total </strong> </td>
                                    <td style="text-align: right;"><strong>{{number_format((
                                        $collection['Special Service Charge']['invoice']+
                                        $collection['Advertisement']['invoice']+
                                        $collection['Food Court Service Charge']['invoice']+
                                        $collection['Electricity']['invoice']+$collection['Electricity']['fine']+
                                        $collection['Service Charge']['invoice']+$collection['Service Charge']['fine']+
                                        $collection['Rent']['invoice']
                                        ),2)}}</strong></td>
                                    <td style="text-align: right;"><strong>{{number_format((
                                        $collection['Special Service Charge']['collection']+
                                        $collection['Advertisement']['collection']+
                                        $collection['Food Court Service Charge']['collection']+
                                        $collection['Electricity']['collection']+
                                        $collection['Service Charge']['collection']+$collection['Service Charge']['finecollection']+
                                        $collection['Rent']['collection']
                                        ),2)}}</strong></td>
                                    <td style="text-align: right;"> <strong>{{
    number_format((
                                ($collection['Rent']['invoice']-$collection['Rent']['collection'])+($collection['Service Charge']['invoice']-$collection['Service Charge']['collection'])+($collection['Service Charge']['fine']-$collection['Service Charge']['finecollection'])+($collection['Electricity']['invoice']-$collection['Electricity']['collection'])+($collection['Electricity']['fine']-$collection['Electricity']['finecollection'])+$spdue+$addue
                                        ),2)
 }}</strong></td>
                                    <td style="text-align: right;"><strong>{{number_format((
                                        $collection['Special Service Charge']['oldcollection']+
                                        $collection['Advertisement']['oldcollection']+
                                        $collection['Food Court Service Charge']['oldcollection']+
                                        $collection['Electricity']['oldcollection']+
                                        $collection['Service Charge']['oldcollection']+
                                        $collection['Rent']['oldcollection']
                                        ),2)}}</strong></td>
                                    <td style="text-align: right;"><strong>{{number_format((
   $amount+ $fsamount+$samount+$feamount+$eamount+$fdamount+$spamount+$adamount)
    ,2)}}</strong></td>

                                </tr>


                            </table>
                            <p></p>


                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <table style="width: 100% ; display: none;">
                                <tr>
                                    <td  style=" width:15%;text-align: left;">Authorized Signatory  </td>
                                    <td style="width:10px">:</td>
                                    <td style=" text-align: left;"></td>
                                </tr>
                                <tr>
                                    <td colspan="3"> &nbsp;</td>
                                </tr>
                                <tr>
                                    <td  style=" width:10%;text-align: left;"> Printed by </td>
                                    <td style="width:10px">:</td>
                                    <td style=" text-align: left;">{{\Illuminate\Support\Facades\Auth::user()->name ?? ''}}</td>
                                </tr>
                                <tr>
                                    <td  style=" width:10%;text-align: left;"> Printed Date  </td>
                                    <td style="width:10px">:</td>
                                    <td style=" text-align: left;">{{date('Y-m-d h:i A')}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

    <!-- .content -->


                    <?php
                    function calculateDate($sdate, $edate) {
                        $earlier = new DateTime("2010-07-06");
                        $later = new DateTime("2010-07-09");

                        $abs_diff = $later->diff($earlier)->format("%a"); //3
                    }
                    function daysBetween($dt1, $dt2) {


                        $dt1 = $dt2;
                        $dt2 = date('Y-m-d');
                        $day =  date_diff(
                            date_create($dt1),
                            date_create($dt2)
                        )->format('%R%a');
                        if($day> 0){
                            return $day;
                        }else{
                            return 0;
                        }
                    }

                    ?>
