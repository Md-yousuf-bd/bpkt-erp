<?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>
<table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
    <tr>
        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                <span> Contact No: 01321142060, 01321142063</span> <br>
                <span> <strong>Shop List</strong> </span>


            </p>
        </td>
    </tr>



</table>
<table  class="table table-bordered " style="font-size: 14px; width:100%;">
    <thead>
    <tr>

        <td >Floor Name</td>
        <td>Shop/Office No.</td>
        <td>Shop/ Office Name</td>

        <td>Allotment<br> Start Date </td>
         <td>Bill Start<br>  Date </td>
        <td>Owner Name </td>
        <td>Status </td>
        <td>Service Charge<br> Applicable </td>
        <td>Food Court<br> SC Applicable </td>




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


    @foreach($assets as $key=>$rows)
        @php $keys = 0; $keys=count($rows); $total+=count($rows);@endphp
    @foreach($rows as $k=>$row)

        @php
            $total += $row['receivable'];
            $gtotal +=$row['collection'];
            $due += $row['receivable'] - $row['collection'];


        @endphp
        <tr >

                <td style="text-align: left" > {{ $key }} </td>


            <td style="text-align: center"> {{ $row['asset_no']}} </td>
            <td style="text-align: left"> {!! wordwrap($row['shop_name'],25,'<br>',true) !!}  </td>
            <td style="text-align: left"> @if($row['contact_s_date']!='' && $row['contact_s_date']!='0000-00-00') {{ date('d-m-Y',strtotime($row['contact_s_date']))}} @endif </td>
                       <td style="text-align: left"> @if($row['date_s']!='' && $row['date_s']!='0000-00-00') {{ date('d-m-Y',strtotime($row['date_s']))}} @endif </td>

            <td style="text-align: left"> {{ $row['name']}} </td>
            <td style="text-align: left"> {{ $row['status']}} </td>
            <td style="text-align: left"> {{ $row['service_charge_status']}} </td>
            <td style="text-align: left"> {{ $row['food_court_status']}} </td>



        </tr>

    @endforeach
        <tr>
            <td style="text-align: right" ><strong> {{ $key }} Total= </strong></td>
            <td style="text-align: center" > <strong> {{$keys}} </strong></td>
            <td style="text-align: center" colspan="6" > <strong>  </strong></td>
        </tr>
    @endforeach
    <tr>
        <td style="text-align: right" ><strong> Total= </strong></td>
        <td style="text-align: center" > <strong> {{$total}} </strong></td>
        <td style="text-align: center" colspan="6" > <strong>  </strong></td>
    </tr>
    </tbody>
</table>

