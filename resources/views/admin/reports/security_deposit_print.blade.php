<?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>
<table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
    <tr>
        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                <span> Contact No: 01321142060, 01321142063</span> <br>
                <span> <strong>@if($service==115) Security Deposit @else Advance Deposit @endif Report</strong> </span>


            </p>
        </td>
    </tr>



</table>
<table  class="table table-bordered " style="font-size: 14px; width:100%;">
    <thead>
    <tr>
        <td style="width: 3%;text-align: center;font-weight: bold;">S.L.</td>

        <td style="text-align: center;font-weight: bold;">Shop No.</td>
        <td style="text-align: center;font-weight: bold;">Customer Name</td>
        @if($deposit_re==2)
        <td style="text-align: center;font-weight: bold;">Rent per <br>month</td>
        @endif
        <td style="text-align: center;font-weight: bold;"> Deposit  <br>Required</td>
        @if($deposit_re==2)
        <td style="text-align: center;font-weight: bold;">Opening <br>Deposit</td>
        <td style="text-align: center;font-weight: bold;">Deposit during<br> the year</td>
        <td style="text-align: center;font-weight: bold;">Total Deposit </td>
        <td style="text-align: center;font-weight: bold;"> Deposit<br> Receivable </td>
        <td style="text-align: center;font-weight: bold;">No. of months <br>deducted</td>
        <td style="text-align: center;font-weight: bold;"> Deduction <br>Value </td>
        <td style="text-align: center;font-weight: bold;"> Available<br> Balance </td>
        <td style="text-align: center;font-weight: bold;"></td>
            @endif
    </tr>
    </thead>
    <tbody>

    @php
        $opening=0;
        $year_diposit=0;
        $total_deposit=0;
        $deposit=0;
        $duction=0;
        $balance=0;
    @endphp
    @if($deposit_re==1)
        @foreach($journals as $row)
            @if($service == 115 && $row->security_deposit==0)
                @continue;
                @endif
            @if($service == 117 && $row->advance_deposit==0)
                @continue;
            @endif

            @php

                $opening += $row->advance_deposit;
                $year_diposit += $row->security_deposit;

            @endphp
            <tr>
                <td style="text-align: center"> {{ $i++ }} </td>
                <td style="text-align: center;width: 15px;"> {{ $row->asset_no }}  </td>
                <td style="width: 100px;"> {{ $row->customer_name }} </td>

                @if($service == 115)
                    <td style="text-align: right;width: 100px;"> {{  number_format($row->security_deposit) }}  </td>
                @else
                    <td style="text-align: right;width: 100px;"> {{  number_format($row->advance_deposit) }}  </td>
                    @endif


            </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align: right;">
                <strong> Total </strong>
            </td>
            @if($service == 115)
                <td style="text-align: right"> {{  number_format($year_diposit) }}  </td>
            @else
                <td style="text-align: right"> {{  number_format($opening) }}  </td>
            @endif

        </tr>
       @else
        @foreach($journals as $row)
            @php

                $opening += $row->openig_deposit;
                $year_diposit += $row->year_diposit;
                $total_deposit += $row->year_diposit +  $row->openig_deposit;
                $deposit += $row->advanceDeposit - ($row->year_diposit +  $row->openig_deposit);
                $duction += $row->deduction;
                $balance += $row->year_diposit + $row->openig_deposit-$row->deduction;

            @endphp
            <tr>
                <td style="text-align: center"> {{ $i++ }} </td>
                <td style="text-align: center"> {{ $row->shop_no }}  </td>
                <td> {{ $row->customer_name }} </td>
                <td style="text-align: right"> {{ number_format($row->rent) }} </td>
                <td style="text-align: right"> {{ number_format($row->advanceDeposit) }}  </td>
                <td style="text-align: right"> {{ number_format($row->openig_deposit) }}  </td>
                <td style="text-align: right"> {{ number_format($row->year_diposit) }}  </td>
                <td style="text-align: right"> {{ number_format($row->year_diposit +  $row->openig_deposit)}}  </td>
                <td style="text-align: right"> {{ number_format($row->advanceDeposit - ($row->year_diposit +  $row->openig_deposit)  )}}  </td>

                <td style="text-align: right" id="pre_reading_t{{$i}}"> {{ number_format(0 ) }}  </td>
                <td style="text-align: right" id="pre_reading_t{{$i}}"> {{ number_format($row->deduction ) }}  </td>
                <td style="text-align: right" id="pre_reading_t{{$i}}"> {{ number_format($row->year_diposit +  $row->openig_deposit-$row->deduction) }}  </td>
                <td style="text-align: right" id="pre_reading_t{{$i}}" onclick="showSecurityAdvanceDetails('{{ $row->shop_no }}',{{$service}})">  <span class="bi bi-eye blue-color" style="color:green;cursor:pointer; font-size: 25px;"> </span>  </td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" style="text-align: right;">
                <strong> Total </strong>
            </td>
            <td style="text-align: right">
                <strong>   {{ number_format($opening,2) }}  </strong>
            </td>
            <td style="text-align: right">
                <strong>   {{ number_format($year_diposit,2) }}  </strong>
            </td>
            <td style="text-align: right">
                <strong>   {{ number_format($total_deposit,2) }}  </strong>
            </td>
            <td style="text-align: right">
                <strong>   {{ number_format($deposit,2) }}  </strong>
            </td>
            <td></td>
            <td style="text-align: right">
                <strong>   {{ number_format($duction,2) }}  </strong>
            </td>
            <td style="text-align: right">
                <strong>   {{ number_format($balance,2) }}  </strong>
            </td>
        </tr>
    @endif


    </tbody>
</table>

