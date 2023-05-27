<?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>
<table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
    <tr>
        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                <span> Contact No: 01321142060, 01321142063</span> <br>
                <span> <strong>Bill wise Customer Report</strong> </span>


            </p>
        </td>
    </tr>



</table>
<table  class="table table-bordered " style="font-size: 14px; width:100%;">
    <thead>
    <tr>
        <td style="width: 3%;text-align: center;font-weight: bold;">S.L.</td>

        <td style="text-align: center;font-weight: bold;">Shop No.</td>
        <td style="text-align: center;font-weight: bold;">Meter No.</td>
        <td style="text-align: center;font-weight: bold;">Customer Name</td>
        <td style="text-align: center;font-weight: bold;">Service Name</td>
        <td style="text-align: center;font-weight: bold;">Billing Period</td>
        <!--<td style="text-align: center;font-weight: bold;">Invoice No.</td>-->
     
        <td style="text-align: center;font-weight: bold;">Bill <br> Amount (Tk.) </td>
        <td style="text-align: center;font-weight: bold;">Collection <br> Amount (Tk.) </td>
        <td style="text-align: center;font-weight: bold;"> Due (Tk.) </td>
           <td style="text-align: center;font-weight: bold;">Remarks</td>
    </tr>
    </thead>
    <tbody>

    @php
        $total=0;
        $due=0;
        $collection=0;
    @endphp
    @foreach($collecton as $row)
        @php
        $total += $row->total;
        $collection += $row->payment_amount;
        $due += $row->total-$row->payment_amount;
        @endphp
        <tr>


            <td style="text-align: center"> {{ $i++ }} </td>

            <td style="text-align: center"> {{ $row->shop_no }}  </td>
            <td> {{ $row->meter_no }} </td>

            <td style="width: 80px !important;"> <?=wordwrap($row->shop_name ,25,"<br>") ?> </td>

            <td style="text-align: center;padding-left:5px;padding-right:2px;"> <?=wordwrap($row->bill_type ,17,"<br>") ?> </td>
            <td style="text-align: center;width:15px !important;"> <?=wordwrap($row->month ,17,"<br>") ?> </td>
            <!--<td style="text-align: center"> {{ $row->invoice_no }}  </td>-->
         
            <td style="text-align: right" id="pre_reading_t{{$i}}"> {{ number_format($row->total,2) }}  </td>
            <td style="text-align: right" id="pre_reading_t{{$i}}"> {{ number_format($row->payment_amount,2) }}  </td>
            <td style="text-align: right" id="pre_reading_t{{$i}}"> {{ number_format($row->total-$row->payment_amount,2) }}  </td>
               <td style="text-align: center"> {{ $row->bill_remarks }}  </td>

            </tr>
    @endforeach
    <tr>
        <td colspan="6" style="text-align: right;">
            <strong> Total </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($total,2) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($collection,2) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($due,2) }}  </strong>
        </td>
    </tr>
    </tbody>
</table>

