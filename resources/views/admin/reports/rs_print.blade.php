


<div class="card-header" style="padding-bottom: 0px;padding-top: 0px !important;">
    <h5 class="card-title"></h5>

</div>
<div class="card-body card-block" style="padding-top: 0px;">
    <div class="table-responsive" style="font-size: 14px; background: white; padding: 10px;  overflow: auto;">
        <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
            <tr >
                <td  style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                    <p> <strong> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br></strong>
                        <strong>   <span> Contact No: 01321142060, 01321142063</span> <br></strong>
                       <strong> Receivable Statement Report</strong>

                    </p>
                </td>
            </tr>


        </table>


        <div > <strong> For the period of {{ $date }} </strong> </div>
        <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
            <tr style="background: #ddd;color:#000;">
                <td rowspan="2" style="width:20px !important;"><strong>S.L.</strong></td>
                <td rowspan="2" style=""><strong>Shop <br> No</strong></td>
                <td rowspan="2" style="width:100px !important;"><strong>Shop Name</strong></td>
                <td rowspan="2" style=""><strong>Owner</strong></td>
                <td rowspan="2" style="display: none;"><strong>30 Years Advance</strong></td>
                <td rowspan="2" style="width:90px !important;display: none;"><strong>Security Deposit</strong></td>
{{--                <td rowspan="2" style=""><strong>Invoice No</strong></td>--}}
                <td rowspan="2" style=""><strong>Service <br> Period</strong></td>
                <td rowspan="2" style=""><strong><nobr>Issue Date</nobr></strong></td>
                <td rowspan="2" style=""><strong><nobr>Due Date</nobr></strong></td>
                @if($type==2)
                    <td rowspan="2" style=""><strong>Payment Date</strong></td>
                    <td rowspan="2" style=""><strong>Payment Reference</strong></td>
                    <td rowspan="2" style=""><strong>Bank Name</strong></td>
                @else
                    <td rowspan="2" style=""><strong>Over <br>Due Days</strong></td>
                @endif

                <td rowspan="2" style=""><strong>Rent</strong></td>
{{--                <td rowspan="2" style=""><strong>Interest</strong></td>--}}
                <td colspan="2" style=""><strong>Service Charge</strong></td>
                <td colspan="2" style=""><strong>Electricity Bill</strong></td>
                <td rowspan="2" style=""><strong>Food <br>Court SC</strong></td>
                <td rowspan="2" style=""><strong>Special <br> SC</strong></td>
                <td rowspan="2" style=""><strong>Advertisement</strong></td>
                <td rowspan="2" style=""><strong>Vat</strong></td>
                <td colspan="2" style=""><strong>Total</strong></td>
            </tr>
            <tr style="background: #ddd;color:#000;">
                <td> <strong>Actual </strong> </td>
                <td> <strong>Fine </strong> </td>
                <td> <strong>Actual </strong> </td>
                <td> <strong>Fine </strong> </td>
                <td> <strong>Actual </strong> </td>
                <td> <strong>Actual<br> With Fine </strong> </td>
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
            $total_fine = 0;
            $sc_fine = 0;
            $el_fine = 0;
            $sc_fixed_fine = 0;
            $i=1;

            ?>
            @foreach($rsResult as $row)
                @php
                    $advance_deposit = 0;

                    if(!empty($row->customer->advance_deposit) ){
                            $advance_deposit = $row->customer->advance_deposit;
                    }
                        $toalAdvance += $advance_deposit;
                        $security_deposit = !empty($row->customer->security_deposit)?$row->customer->security_deposit:0;

                        $securityDeposit += $security_deposit;
                        $monthly_rent = $row['ledger']['rent'];
                        $interest = $row['ledger']['interest'];
                        $rent += $monthly_rent;
                        $total_vat += $row['vat_amount'];
                        $total_interest += $interest;
                        $sv_actual += $row['ledger']['service_charge'];
                        $electricity += $row['ledger']['electricity'];
                        $food_court += $row['ledger']['food_court'];
                        $sp_service += $row['ledger']['sp_service'];
                        $advertisement += $row['ledger']['advertisement'];
                        $total_fine += $interest+$row['vat_amount'];
                        $sc_fine +=  $row['ledger']['sc_fine'];
                        $el_fine +=  $row['ledger']['el_fine'];
                        $sc_fixed_fine +=  $row['ledger']['sc_fixed_fine'];
                        $total_sub += $monthly_rent+
                        $row['ledger']['service_charge'] + $row['ledger']['electricity']+ $row['ledger']['food_court']+
                        $row['ledger']['sp_service']+$row['ledger']['advertisement'];


                @endphp

                <tr>
                    <td style="width:20px !important;"> {{ $i++ }}</td>
                    <td style="width: 10px !important;
"><?= wordwrap($row['shop_no'],10,"<br>")?></td>
                    <td style=""><?= wordwrap($row['shop_name'],30,"<br>")?></td>
                    <td style="">{{$row['owner_name']??""}}</td>
                    <td style="display: none;text-align: right">{{number_format($advance_deposit,2)}}</td>
                    <td style="display: none;text-align: right">{{number_format($security_deposit,2)}}</td>
{{--                    <td style="">{{ $row['invoice_no'] }}</td>--}}
                    <td style=""><nobr>{{ $row['month'] }}</nobr></td>
                    <td style=""> <nobr>{{ $row['issue_date'] }}</nobr></td>
                    <td style=""><nobr>{{ $row['due_date'] }}</nobr></td>
                    @if($type==2)
                        <td style="">{{ $row['payment_date'] }}</td>
                        <td style="">{{ $row['cheque_no'] }}</td>
                        <td style="">{{ $row['payment_mode'] }}</td>
                    @else
                        <td style=""></td>

                    @endif
                    <td style="text-align: right">{{number_format($monthly_rent,2)}}</td>
{{--                    <td style="text-align: right">{{number_format($interest,2)}}</td>--}}
                    <td style="text-align: right">{{number_format(($row['ledger']['service_charge']??"00"),2)}}</td>
                    <td style="text-align: right">{{ number_format($row['ledger']['sc_fine']+ $row['ledger']['sc_fixed_fine'],2) }}</td>
                    <td style="text-align: right">{{number_format(($row['ledger']['electricity']??"00"),2)}}</td>
                    <td style="text-align: right">{{ number_format( $row['ledger']['el_fine'],2) }}</td>
                    <td style="text-align: right">{{number_format(($row['ledger']['food_court']??"00"),2)}}</td>
                    <td style="text-align: right">{{number_format(($row['ledger']['sp_service']??"00"),2)}}</td>
                    <td style="text-align: right">{{number_format(($row['ledger']['advertisement']??"00"),2)}}</td>
                    <td style="text-align: right">{{number_format(($row['vat_amount']),2)}}</td>
                    <td style="text-align: right"> <strong>
                            @if(isset($row['ledger']['total']))
                                {{number_format(($row['ledger']['total']+$monthly_rent),2)}}
                            @else
                                0.00
                            @endif
                        </strong>
                    </td>
                    <td style="text-align: right"> <strong>
                            @if(isset($row['ledger']['total']))
                                {{ number_format(($row['vat_amount']+$interest+$row['ledger']['total']+$monthly_rent+$row['ledger']['el_fine']+$row['ledger']['sc_fine']+$row['ledger']['sc_fixed_fine']),2) }}
                            @else
                                {{ number_format(($row['vat_amount']+$interest),2) }}
                            @endif
                        </strong></td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4">  <strong>Total </strong> </td>
                <td style="text-align: right;"> <strong> </strong> </td>
                <td style="text-align: right;"> <strong> </strong> </td>
                <td style="text-align: right;"> <strong> </strong> </td>
{{--                <td style="text-align: right;"> <strong> </strong> </td>--}}
                {{--                                    <td style="text-align: right;"> <strong> </strong> </td>--}}
                {{--                                    <td style="text-align: right;"> <strong> </strong> </td>--}}
                @if($type==2)
                    <td style=""></td>
                    <td style=""></td>
                    <td style=""></td>
                @else
                    <td style=""></td>

                @endif

                <td style="text-align: right;"> <strong> {{ number_format($rent,2) }}</strong> </td>
{{--                <td style="text-align: right;"> <strong> {{ number_format($total_interest,2) }}</strong> </td>--}}
                <td style="text-align: right;"> <strong> {{ number_format($sv_actual,2) }} </strong> </td>
                <td style="text-align: right;"> <strong> {{ number_format($sc_fixed_fine+$sc_fine,2) }} </strong> </td>

                <td style="text-align: right;"> <strong> {{ $electricity }}</strong> </td>
                <td style="text-align: right;"> <strong> {{ number_format($el_fine,2) }} </strong> </td>

                <td style="text-align: right;"> <strong> {{ number_format($food_court,2) }}</strong> </td>
                <td style="text-align: right;"> <strong> {{ number_format($sp_service,2) }}</strong> </td>
                <td style="text-align: right;"> <strong> {{ number_format($advertisement,2) }}</strong> </td>
                <td style="text-align: right;"> <strong> {{ number_format($total_vat,2) }}</strong> </td>
                <td style="text-align: right;"> <strong> {{ number_format($total_sub,2) }}</strong> </td>
                <td style="text-align: right;"> <strong>{{number_format($total_fine+$total_sub+$sc_fixed_fine+$sc_fine+$el_fine,2)}}</strong> </td>

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
//                    function calculateDate1($sdate, $edate) {
//                        $earlier = new DateTime("2010-07-06");
//                        $later = new DateTime("2010-07-09");
//
//                        $abs_diff = $later->diff($earlier)->format("%a"); //3
//                    }
//                    function daysBetween1($dt1, $dt2) {
//
//
//                        $dt1 = $dt2;
//                        $dt2 = date('Y-m-d');
//                        $day =  date_diff(
//                            date_create($dt1),
//                            date_create($dt2)
//                        )->format('%R%a');
//                        if($day> 0){
//                            return $day;
//                        }else{
//                            return 0;
//                        }
//                    }

?>
