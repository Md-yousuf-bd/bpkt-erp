<?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>

@foreach($billing as $r)
@foreach($r->invoice as $row)

    @php
        $style='';
    @endphp
    @if(($row['amount'] -  $row['payment_amount'])==0)
{{--        @continue;--}}
        @php $style="display:none"; @endphp
    @endif
    @if($row['amount'] -  $row['payment_amount']<=0)
{{--        @continue;--}}
        @php $style="display:none"; @endphp
    @endif

    <tr style="{{$style}}">
        <?php

        $bill_amount = $bill_amount + round($row['amount']) ;
        $due_amount +=  round($row['amount'] -  $row['payment_amount']);
        $moth =  $row['month'];

        ?>

        <td style="display: none;"> {{ $i }}

            <input type="hidden" name="bill_id[]" value="{{$r->id}}">
            <input type="hidden" name="ledger_ids[]" value="{{$row['ledger_id']}}">
            <input type="hidden" name="invoice[]" value="{{$r->invoice_no}}">
            <input type="hidden" name="shop_no[]" value="{{$r['shop_no']}}">
            <input type="hidden" name="shop_name[]" value="{{$r['shop_name']}}">
            <input type="hidden" name="amountTotal[]" value="{{$row['amount']}}">
        </td>
        <td> {{ $row['ledger_name'] }} <input type="hidden" id="in_{{ $i}}" name="bill_received[]" value="{{$r->id}}"> </td>
        <td> {{ $row['month'] }} </td>
        <td> {{ $r['invoice_no'] }} </td>
        <td style="text-align: right"> {{ number_format($row['amount']) }} </td>
        <td  style="text-align: right;">
            <input type="hidden"id="rd_{{ $i}}" value="{{round(($row['amount'] -  $row['payment_amount']))}}">
            {{ number_format(round($row['amount'] -  $row['payment_amount']))}}
        </td>
            <td style="text-align: right">
                <input type="text" name="discount[]" id="dis_{{ $i}}" value="0"  onblur="checkAmount(this.value,{{$i}})"  size="8">
                <p id="dis_{{ $i}}" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
            </td>

            <td>  <input type="text" id="amt_{{ $i}}" value="{{round(($row['amount'] -  $row['payment_amount']))}}" name="bill_received_amount[]" onblur="checkAmount(this.value,{{ $i}})"  onkeypress="return filterKeyNumber(this,event,'r_{{ $i}}')">
            <p id="r_{{ $i}}" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p></td>
    </tr>
    @php $i++; @endphp
@endforeach
{{--@if($r->vat_amount - $r->vat_total_paid >0)--}}

{{--<tr>--}}
{{--    <td> {{ $i++ }} </td>--}}
{{--    <td style="width:200px !important;">Sales VAT</td>--}}
{{--    <td> {{ $moth }} </td>--}}
{{--    <td> {{ $r->invoice_no }} </td>--}}
{{--    <td style="text-align:right;"> {{ $r->vat_amount  }}</td>--}}
{{--    <td id="rd_38" style="text-align:right;"> {{ $r->vat_amount - $r->vat_total_paid  }}</td>--}}
{{--    <td>  <input type="text" id="paid_vat_amount" name="paid_vat_amount[]" onblur="checkAmount(this.value,38)"  onkeypress="return filterKeyNumber(this,event,'r_38')">--}}
{{--        <p id="r_38" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p></td>--}}
{{--</tr>--}}

{{--@endif--}}
{{--@if($r->fixed_fine - $r->paid_fixed_fine >0)--}}
{{--<tr>--}}
{{--    <td> {{ $i++ }} </td>--}}
{{--    <td style="width:200px !important;">Service Charge Fixed Fine</td>--}}
{{--    <td> {{ $moth }} </td>--}}
{{--    <td> {{ $r->invoice_no }} </td>--}}
{{--    <td style="text-align:right;"> {{ $r->fixed_fine  }}</td>--}}
{{--    <td id="rd_38" style="text-align:right;"> {{ $r->fixed_fine - $r->paid_fixed_fine  }}</td>--}}
{{--    <td>  <input type="text" id="paid_vat_amount" name="paid_vat_amount[]" onblur="checkAmount(this.value,38)"  onkeypress="return filterKeyNumber(this,event,'r_38')">--}}
{{--        <p id="r_38" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p></td>--}}
{{--</tr>--}}
{{--@endif--}}
{{--@if($r->fine_amount - $r->paid_fixed_fine >0)--}}
{{--<tr>--}}
{{--    <td> {{ $i++ }} </td>--}}
{{--    <td style="width:200px !important;">--}}
{{--        @if($r->bill_type=='Electricity')--}}
{{--            Electricity bill Interest--}}
{{--            @else--}}
{{--            Service Charge Interest--}}
{{--        @endif--}}

{{--    </td>--}}
{{--    <td> {{ $moth }} </td>--}}
{{--    <td> {{ $r->invoice_no }} </td>--}}
{{--    <td style="text-align:right;"> {{ $r->fine_amount  }}</td>--}}
{{--    <td id="rd_38" style="text-align:right;"> {{ $r->fine_amount - $r->paid_fixed_fine  }}</td>--}}
{{--    <td>  <input type="text" id="paid_vat_amount" name="paid_vat_amount[]" onblur="checkAmount(this.value,38)"  onkeypress="return filterKeyNumber(this,event,'r_38')">--}}
{{--        <p id="r_38" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p></td>--}}
{{--</tr>--}}
{{--@endif--}}

@endforeach


<tr>
    <?php
    $bill_amount = $bill_amount;
    $due_amount = $due_amount ;
    ?>
    <td colspan="3"><strong></strong></td>
    <td style="text-align:right;"><strong>{{number_format($bill_amount)}}</strong></td>
    <td style="text-align:right;"> <strong>{{number_format($due_amount)}}</strong>
<input type="hidden" name="due_total_s" id="due_total_s" value="{{ round($due_amount) }}">
    </td>
    <td style="text-align:right;"> <strong> <span id="sp_discount_total">  </span></strong> </td>
    <td style="text-align:right;"> <strong> <span id="sp_total"> {{number_format($due_amount)}} </span></strong> </td>
</tr>
