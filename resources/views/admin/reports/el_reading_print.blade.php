<?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>
<table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
    <tr>
        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                 <span> Contact No: 01321142060, 01321142063</span> <br>
                <span> <strong> Electricity Billing Statement </strong> </span>


            </p>
        </td>
    </tr>
    <tr>
        <td style="width: 50%" valign="top">
            <table  style="font-size: 14px; width:100% !important; padding-left: 5px;" >


                <tr>
                    <td style="width:100px;padding-left: 5px;">Period </td>
                    <td style="width:10px;">:</td>
                    <td> {{ $month}}</td>
                </tr>

            </table>
        </td>

    </tr>


</table>
<table  class="table table-bordered " style="font-size: 14px; width:100%;">
    <thead>
    <tr>
        <td style="width: 3%;">S.L.</td>

        <td >Asset No</td>
        <td>Tenant Name</td>
        <td>Meter No</td>
        <td>Previous Readings</td>
        <td>Current Reading </td>
        <td>KWT Consumed </td>
        <td> Bill Amount(Tk.) </td>


    </tr>
    </thead>
    <tbody>

    @php
        $total=0;
    @endphp
    @foreach($customer as $row)
        @php
        $total += $row->total;
        @endphp
        <tr>


            <td style="text-align: center"> {{ $i++ }} </td>

            <td style="text-align: center"> {{ $row->asset_no }}  </td>
            <td> {!! wordwrap($row->shop_name,25,'<br>',true)  !!}   </td>

            <td> {{ $row->meter_no }} </td>

            <td style="text-align: center"> {{ $row->pre_reading }}  </td>
            <td style="text-align: center"> {{ $row->current_reading }}  </td>
            <td style="text-align: center"> {{ $row->kwt }}  </td>
            <td style="text-align: right" id="pre_reading_t{{$i}}"> {{ number_format($row->total,2) }}  </td>



            </tr>
    @endforeach
    <tr>
        <td colspan="7" style="text-align: right;">
            <strong> Total </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($total,2) }}  </strong>
        </td>
    </tr>
    </tbody>
</table>

