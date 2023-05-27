<?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>
<table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
    <tr>
        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                <span> aigwel@police.gov.bd</span> <br>
                <span> Contact No: 01321142060, 01321142063</span> <br>
                <span> <strong> Asset Allotment </strong> </span>


            </p>
        </td>
    </tr>



</table>
<table  class="table table-bordered " style="font-size: 14px; width:100%;">
    <thead>
    <tr>
        <td style="width: 3%;">S.L.</td>

        <td >Floor Name</td>
        <td>Total Shop</td>
        <td>Allotted & Open</td>
        <td>Allotted & Closed</td>
        <td>Un-allotted </td>


    </tr>
    </thead>
    <tbody>

    @php
        $total=0;
        $open=0;
        $close=0;
        $unallotted=0;

    @endphp
    @foreach($customer as $row)
        @php
        $total += $row['open']+$row['close']+$row['unalotted'];
        $open += $row['open'];
        $close += $row['close'];
        $unallotted += $row['unalotted'];

        @endphp
        <tr>


            <td style="text-align: center"> {{ $i++ }} </td>

            <td style="text-align: center"> {{ $row['floor_name'] }}  </td>
            <td style="text-align: center"> {{ $row['open']+$row['close']+$row['unalotted'] }} </td>

            <td style="text-align: center"> {{ $row['open'] }} </td>
            <td style="text-align: center"> {{ $row['close'] }} </td>
            <td style="text-align: center"> {{ $row['unalotted'] }} </td>





            </tr>
    @endforeach
    <tr>
        <td colspan="2" style="text-align: right;">
            <strong> Total </strong>
        </td>
        <td style="text-align: center">
            <strong>   {{ $total }}  </strong>
        </td>
        <td style="text-align: center">
            <strong>   {{ $open }}  </strong>
        </td>
        <td style="text-align: center">
            <strong>   {{ $close }}  </strong>
        </td>
        <td style="text-align: center">
            <strong>   {{ $unallotted }}  </strong>
        </td>
    </tr>
    </tbody>
</table>

