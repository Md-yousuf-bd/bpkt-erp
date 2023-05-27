<?php $i = 1;
$bill_amount = 0;
$due_amount = 0;
$moth = 0; ?>
<table style="width: 100%;border: none;" class="table  table-borderless" valign="top">
    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;"
         src="{{ URL::asset('images/logos/logo-in.png') }}">
    <tr>
        <td colspan="2" style="text-align: center;font-size:15px;"><strong> Bangladesh Police Kallyan Trust</strong>
            <p><span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                <span> Contact No: 01321142060, 01321142063</span> <br>
                <span> <strong> Receivable & Collection Summary Report </strong> </span>


            </p>
        </td>
    </tr>
    <tr>
        <td style="width: 50%" valign="top">
            <table style="font-size: 14px; width:100% !important; padding-left: 5px;">


                <tr>
                    <td style="width:100px;padding-left: 5px;">{{$period}} </td>
                    <td style="width:10px;"></td>
                    <td></td>
                </tr>

            </table>
        </td>

    </tr>


</table>
<table class="table table-bordered " style="font-size: 14px; width:100%;">
    <thead>
    <tr>
        <td style="width: 3%;">S.L.</td>

        <td>Period</td>
        <td>Receivable</td>
        <td>Collection</td>
        <td>Due</td>
        <td></td>


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
        $due=0;
        $ad=0;


    @endphp


    @foreach($yearsSort as $key=>$monthsAll)
        @foreach($monthsAll as $key=>$rows)
            @foreach($customer as $key=>$row)
                @if($rows!=$key)
                    @continue;
                    @endif
                @php
                    $total += $row['receivable'];
                    $gtotal +=$row['collection'];
                    $due += $row['receivable'] - $row['collection'];

                @endphp
                <tr>
                    <td style="text-align: right"> {{ $i++ }} </td>
                    <td style="text-align: right"> {{  $key  }}  </td>
                    <td style="text-align: right"> {{  number_format($row['receivable'] ,2)}} </td>
                    <td style="text-align: right"> {{  number_format($row['collection'],2) }} </td>
                    <td style="text-align: right"> {{  number_format($row['receivable'] -$row['collection'],2) }}  </td>
                    <td style="text-align: center;" onclick="showDetailsDue('{{$key}}','{{$category}}','{{$type}}')">
                        <span class="bi bi-eye blue-color" style="color:green;font-size: 25px;"> </span></td>


                </tr>

            @endforeach
        @endforeach
    @endforeach
    <tr>
        <td colspan="2" style="text-align: right;">
            <strong> Total </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($total,2) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($gtotal,2) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($due,2) }}  </strong>
        </td>
        <td></td>


    </tr>
    </tbody>
</table>

