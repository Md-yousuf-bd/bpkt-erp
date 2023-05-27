<?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>
<table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
    <tr>
        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                 <span> Contact No: 01321142060, 01321142063</span> <br>
                <span> <strong>  Meter Reading Statement </strong> </span> <br>
                <span> <strong>  Month: {{$month}} </strong> </span>


            </p>
        </td>
    </tr>



</table>
<table id="assetInfo" class="table table-bordered " style="font-size: 14px; width:100%;">
    <thead>
    <tr style="font-weight: bold;text-align: center">
        <td style="width: 3%;">S.L.</td>

        <td >Asset No</td>
        <td>Tenant Name</td>
        <td>Meter No</td>
{{--        <td> Readings Month</td>--}}
        <td>Previous <br> Readings</td>
        <td>Previous  <br> Consume</td>
        <td>Previous <br> Reading Date</td>
        <td>Current Reading</td>
        <td style="width: 16%"> Signature</td>


    </tr>
    </thead>
    <tbody>


    @foreach($customer as $row)
        <tr>


            <td> {{ $i++ }} </td>

            <td> {{ $row->asset_no }}  </td>
            <td> {!! wordwrap($row->shop_name,25,"<br>\n") !!}   </td>

            <td style=""> {!! wordwrap($row->meter_no,25,"<br>\n") !!}  </td>
{{--            <td> {{ $month }} </td>--}}

            <td style="text-align: center" id="pre_reading_t{{$i}}"> {{ $row->pre_month }}  </td>
            <td id="pre_reading_k{{$i}}"> {{ $row['kwt']??"" }}  </td>
            <td id="pre_reading_t{{$i}}"> {{ $row->pre_date }}  </td>
            <td id="pre_reading_t{{$i}}"> </td>
            <td id="pre_reading_s{{$i}}"> </td>



            </tr>
    @endforeach
    </tbody>
</table>

<div style="height: 3%">&nbsp;</div>
<div style="height: 3%">&nbsp;</div>
<div style="height: 3%">&nbsp;</div>
<table style="width: 100%">
    <tr>
        <td  style=" width:15%;text-align: left;"> Signatory & Date </td>
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
