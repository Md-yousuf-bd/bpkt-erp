<?php  $i=1;$j=$oppset; $bill_amount = 0; $due_amount=0;$moth=0; ?>

<table id="assetInfo" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
    <thead>
    <tr>
        <td style="width: 3%;">S.L.</td>
        <td style="text-align: center;">Select all <br></br>
        <input type="checkbox" id="checkAllss"  onchange="Bulk.checkAllnput()" checked>
        </td>
        <td >Asset No</td>
        <td>Tenant Name</td>

        <td>Total Amount</td>
{{--        <td>VAT</td>--}}
{{--        <td>Total Bill</td>--}}

    </tr>
    </thead>
    <tbody>
    @if(count($customer)==0)
        <tr>
            <td style="color: red;text-align: center" colspan="5">
                No Record Found
            </td>
        </tr>
    @endif
    @php


    @endphp


@foreach($customer as $row)
    @php
      $i++;
$j++
        @endphp
    <tr>


        <td> {{ $j }} </td>
{{--         <td style="text-align: center"> <input class="checkIs"  @if(isset($previousInvoice[$row['asset_no']]))--}}
{{--             disabled--}}
{{--                                                @else--}}
{{--                                                checked--}}
{{--                                                @endif type="checkbox" name="chk[]" value="{{ $i-2 }}"> </td>--}}

        <td style="text-align: center">
            @if(in_array($row['asset_no'],$allReadyBillGenerate))
                <span style="    background: green;
    padding: 4px;
    color: #fff;"> Bill Done</span>
            @else
                <input class="checkIs"


                       checked   type="checkbox" name="chk[]" value="{{ $i-2 }}">
            @endif
        </td>
        <td> {{ $row->asset_no }} <input type="hidden"  name="asset_no[]" value="{{$row->asset_no}}"> </td>
        <td> {{ $row->shop_name }} <input type="hidden"  name="customer_name[]" value="{{$row->shop_name}}">
            <input type="hidden"  name="customer_id[]" value="{{$row->id}}"> </td>

                <td style="text-align: right"> {{ number_format($row->amount) }}
                    <input type="hidden"  name="amount[]" value="{{ round($row->amount)}}"> </td>
        <input type="hidden"  name="rate[]" value="{{$row->rate}}">
        <input type="hidden"  name="area[]" value="{{$row->area}}">
{{--                <td style="text-align: right"> {{ number_format($amount,2) }}--}}
{{--                    <input type="hidden"  name="total[]" value="{{round($amount,2)}}">--}}
{{--                </td>--}}

{{--                <td> {{ $service->rate }}  <input type="hidden"  name="rate[]" value="{{$service->rate}}">--}}
{{--                </td>--}}
{{--                <td style="text-align: right"> {{ number_format($service->rate*$row->area,2) }}--}}
{{--                    <input type="hidden"  name="amount[]" value="{{$service->rate*$row->area}}"> </td>--}}
{{--                <td>--}}
{{--                    @php $vat = 0; @endphp--}}
{{--                    @if($row->vat_exemption=='No')--}}
{{--                        @php--}}
{{--                            $vat = $service->rate*$row->area*.15;--}}
{{--                        @endphp--}}
{{--                        {{$vat}}--}}
{{--                    @endif--}}
{{--                    <input type="hidden"  name="vat[]" value="{{$vat}}">--}}
{{--                </td>--}}
{{--                <td style="text-align: right"> {{ number_format(($service->rate*$row->area),2) }}--}}
{{--                    <input type="hidden"  name="total[]" value="{{round(($service->rate*$row->area),2)}}">--}}
{{--                </td>--}}


    </tr>
@endforeach
    <input type="hidden" id="oppset_id" value="{{$j-1}}" >
    </tbody>
</table>


{{--<tr>--}}
{{--    <?php--}}
{{--    $bill_amount = $bill_amount + $vat->vat_amount ;--}}
{{--    $due_amount = $due_amount + ($vat->vat_amount - $vat->vat_total_paid) ;--}}
{{--    ?>--}}
{{--    <td colspan="3"><strong></strong></td>--}}
{{--    <td ><strong>{{$bill_amount}}</strong></td>--}}
{{--    <td> <strong>{{$due_amount}}</strong> </td>--}}
{{--    <td> <strong> <span id="sp_total"> 0 </span></strong> </td>--}}
{{--</tr>--}}
