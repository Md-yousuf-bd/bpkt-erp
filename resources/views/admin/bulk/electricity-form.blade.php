<?php  $i=1;$j=$oppset; $bill_amount = 0; $due_amount=0;$moth=0; ?>

<table id="assetInfo" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
    <thead>
    <tr>
        <td style="width: 3%;">S.L.</td>
        <td style="text-align: center;">Select all <br></br>
        <input type="checkbox" id="checkAllss"  onchange="Bulk.checkAllnput()" checked>
        </td>
        <td >Status</td>
        <td >Asset No</td>
        <td>Tenant Name</td>
        <td>Meter No</td>
        <td>Previous Readings</td>
        <td>Current Readings</td>
        <td>KWH</td>
        <td>Rate</td>
        <td> Amount</td>

        <td>Vat(@ {{ $electrcity->vat??0 }}%)</td>
{{--        <td>Total Receivable</td>--}}
        <td>Total Bill</td>
    </tr>
    <input type="hidden" value="{{$electrcity->vat??0}}" id="vat_rate" name="vat_rate">
    </thead>
    <tbody>
    @if(count($customer)==0)
        <tr>
            <td style="color: red;text-align: center" colspan="10">
                No Record Found
            </td>
        </tr>
    @endif
@php

    @endphp
@foreach($customer as $row)
    <tr>
        @php
            $interest=0;$total=0;
                $bill_amount = $bill_amount+ $row->amount ;
        $due_amount +=($row->amount -  $row->payment_amount); $moth =  $row->month;
                $interest +=round(($electrcity->rate*$row->area)*$month);
                $total +=$electrcity->rate*$row->area+$fixed_fine;
                $i++;

        @endphp

            <td> {{ $j++ }} </td>
             <td style="text-align: center">
@if(in_array($row->meter_no,$allReadyBillGenerate))
    <span style="    background: green;
    padding: 4px;
    color: #fff;"> Bill Done</span>
@else
                     <input id="chk_{{$i}}" class="checkIs"
                            checked
                            type="checkbox" name="chk[]" value="{{  $i-2 }}"> </td>
    @endif


<td> {{ $row->status }} </td>
<td> {{ $row->asset_no }} <input type="hidden"  name="asset_no[]" value="{{$row->asset_no}}"> </td>
<td> {{ $row->shop_name }} <input type="hidden"  name="customer_name[]" value="{{$row->shop_name}}">
<input type="hidden"  name="customer_id[]" value="{{$row->customer_id}}"> </td>
<td> {{ $row->meter_no }}  <input type="hidden"  name="meter[]" value="{{$row->meter_no}}">
<td id="pre_reading_t{{$i}}" style="text-align: right"> {{ number_format($row->pre_month) }}  <input type="hidden" id="pre_reading_{{$i}}" name="pre_reading[]" value="{{$row->pre_month}}">
</td>
<td>  <input type="text" id="cur_reading_{{$i}}" size="6" onkeypress="return filterKeyNumber(this,event,'r_a')" onblur="Bulk.currentReading({{$i}})" name="cur_reading[]" >
<span id="r_a"></span>
</td>


<td > <span id="kwt_t{{$i}}"> </span>
<input type="hidden" id="kwt_{{$i}}"  name="kwt[]" value="">
</td>
<td style="text-align: right" id="rate_t{{$i}}"> {{ $electrcity->rate }}
   <input type="hidden" id="rate_{{$i}}"  name="rate[]" value="{{$electrcity->rate}}"> </td>

<td style="text-align: right"> <span id="amount_t{{$i}}"> </span>
   <input type="hidden"  name="amount[]" id="amount_{{$i}}"  value="">
</td>

<td style="text-align: right" > <span id="interest_t_{{$i}}"> </span>
   <input type="hidden" id="interest_{{$i}}" name="interest[]" value="">
</td>
<td style="text-align: right" > <span id="total_t{{$i}}"> </span>
<input type="hidden" id="total_{{$i}}" name="total[]" value="">
</td>
{{--                <td style="text-align: right"> {{ number_format($total+$month,2) }}--}}
{{--                    <input type="hidden"  name="total[]" value="{{round($total+$month,2)}}">--}}
{{--                </td>--}}


</tr>
@endforeach
<input type="hidden" id="oppset_id" value="{{$j}}" >
</tbody>
</table>

