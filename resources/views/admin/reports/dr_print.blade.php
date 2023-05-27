<?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>
<table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
    <tr>
        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                <span> Contact No: 01321142060, 01321142063</span> <br>
                <span> <strong> Dues Statement </strong> </span>


            </p>
        </td>
    </tr>
    <tr>
        <td style="width: 50%" valign="top">
            <table  style="font-size: 14px; width:100% !important; padding-left: 5px;" >


                <tr>
                    <td style="width:100px;padding-left: 5px;">{{$period}} <span id="sp_shop"></span> </td>
                    <td style="width:10px;"></td>
                    <td> </td>
                </tr>
                <!--<tr>-->
                <!--    <td style="width:100px;padding-left: 5px;">Customer  </td>-->
                <!--    <td style="width:10px;">:</td>-->
                <!--    <td>{{ $shop['customer_name']??"" }} </td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--    <td style="width:100px;padding-left: 5px;">Shop No </td>-->
                <!--    <td style="width:10px;">:</td>-->
                <!--    <td>  {{ $shop['shop_no']??"" }} </td>-->
                <!--</tr>-->
            </table>
        </td>

    </tr>


</table>
<table  class="table table-bordered " style="font-size: 14px; width:100%;">
    <thead>
    <tr>
        <td style="width: 3%;" rowspan="2">S.L.</td>

        <td rowspan="2">Period</td>
        <td rowspan="2">Rent</td>
        <td rowspan="2">Service Charge</td>
        <td rowspan="2">SC Fine </td>
        <td rowspan="2">SC with Fine </td>
        <td rowspan="2">Electricity<br> Bill </td>
        <td rowspan="2"> Electricity <br>Fine </td>
        <td rowspan="2"> Electricity <br>Bill With Fine </td>
        <td rowspan="2"> Food Court SC </td>
        <td rowspan="2"> Special SC </td>
        <td rowspan="2"> Advertisement </td>
        <td rowspan="2"> Others </td>
        <td colspan="3" style="text-align: center;"> Total </td>


    </tr>
    <tr>
        <td style="text-align: center;">Actual</td>
        <td style="text-align: center;">Fine</td>
        <td style="text-align: center;">Grand Total</td>
    </tr>
    </thead>
    <tbody>

    @php
        $total=0;
        $gtotal=0;
        $rent=0;
        $sc=0;
        $fixed_fine=0;
        $fine_amount=0;
        $el=0;
        $fcsc=0;
        $spsc=0;
        $ad=0;
        $fine=0;
        $gfine=0;
        $el_fine=0;
        $serviceWithFine=0;
        $totalOtherIncome=0;


    @endphp


    @foreach($customer as $key=>$row)
        @if($row['rent'] + $row['sc']+ $row['el'] +$row['fcsc']+$row['advertisement']+$row['spsc']==0)
            @continue;
        @endif
        @php
            $total= $row['rent'] + $row['sc']+ $row['el'] +$row['fcsc']+$row['advertisement']+$row['spsc'] ;
            $gtotal += $total;
            $rent += $row['rent'];
            $sc += $row['sc'];
            $fixed_fine += $row['fixed_fine'];
            $fine_amount += $row['fine_amount']+$row['fixed_fine'];
            $el += $row['el'];
            $el_fine += $row['el_fine'];
            $fcsc += $row['fcsc'];
            $spsc += $row['spsc'];
            $ad += $row['advertisement'];
            $fine = $row['el_fine'] + $row['fine_amount']+$row['fixed_fine'];
            $gfine +=$fine;
            $serviceWithFine += $row['fine_amount']+$row['sc']+$row['fixed_fine'];
            $totalOtherIncome += $row['other_income'];

        @endphp

        <tr>
            <td style="text-align: right"> {{ $i++ }} </td>
            <td style="text-align: right"> {{  $key  }}  </td>
            <td style="text-align: right"> {{  number_format($row['rent'] )}} </td>
            <td style="text-align: right"> {{  number_format($row['sc']) }} </td>
            <td style="text-align: right"> {{  number_format($row['fine_amount']+$row['fixed_fine']) }}  </td>
            <td style="text-align: right"> {{  number_format($row['fine_amount']+$row['fixed_fine']+$row['sc']) }}  </td>
            <td style="text-align: right"> {{  number_format($row['el']) }}  </td>
            <td style="text-align: right"> {{  number_format($row['el_fine']) }}  </td>
            <td style="text-align: right"> {{  number_format($row['el_fine']+$row['el']) }}  </td>
            <td style="text-align: right"> {{  number_format($row['fcsc']) }}  </td>
            <td style="text-align: right"> {{  number_format($row['spsc']) }}  </td>
            <td style="text-align: right"> {{  number_format($row['advertisement']) }}  </td>
            <td style="text-align: right"> {{  number_format($row['other_income']) }}  </td>

            <td style="text-align: right">  {{ number_format($total) }} </td>
            <td style="text-align: right">  {{ number_format($fine) }} </td>
            <td style="text-align: right">  {{ number_format($total+$fine) }} </td>
        </tr>

    @endforeach
    <tr>
        <td colspan="2" style="text-align: right;">
            <strong> Total </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($rent) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($sc) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($fine_amount) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($serviceWithFine) }}  </strong>
        </td> <td style="text-align: right">
            <strong>   {{ number_format($el) }}  </strong>
        </td> <td style="text-align: right">
            <strong>   {{ number_format($el_fine) }}  </strong>
        </td> <td style="text-align: right">
            <strong>   {{ number_format($el+$el_fine) }}  </strong>
        </td> <td style="text-align: right">
            <strong>   {{ number_format($fcsc) }}  </strong>
        </td> <td style="text-align: right">
            <strong>   {{ number_format($spsc) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($ad) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($totalOtherIncome) }}  </strong>
        </td> <td style="text-align: right">
            <strong>   {{ number_format($gtotal) }}  </strong>
        </td>

        <td style="text-align: right">
            <strong>   {{ number_format($gfine) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($gfine+$gtotal) }}  </strong>
        </td>



    </tr>
    </tbody>
</table>

