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
        <td>Area (Sft)</td>
        <td>Rate/Sft</td>
        <td style="">Amount</td>
        <td style="">Vat@ {{ $service->vat??0 }}%</td>
        <td style="display: none;">Fixed fine</td>
        <td>Total Bill</td>
        <td style="display: none;">Interest(@3%)</td>
        <td style="display: none;">Total Receivable</td>

    </tr>
    </thead>
    <tbody>
    <input type="hidden"  name="vat_rate" value="{{$service->vat??0}}">
    @if(count($customer)==0)
        <tr>
            <td style="color: red;text-align: center" colspan="9">
                No Record Found
            </td>
        </tr>
        @endif
@php    @endphp
@foreach($customer as $row)
    <tr>
        @php
            $interest=0;$total=0;
                $bill_amount = $bill_amount+ $row->amount ;
        $due_amount +=($row->amount -  $row->payment_amount); $moth =  $row->month;
                $interest +=round((($electrcity->sc_rate??0)*$row->area??0)*$month);
                $total += ($row->sc_rate??0)*$row->area;
                $i++

        @endphp

            <td> {{ $j++ }} </td>
             <td style="text-align: center">
                 @if(in_array($row['asset_no'],$allReadyBillGenerate))
                     <span style="    background: green;
    padding: 4px;
    color: #fff;"> Bill Done</span>
                 @else
                     <input class="checkIs"

                            checked
                            type="checkbox" name="chk[]" value="{{ $i-2 }}">
                 @endif

                  </td>
            <td> {{ $row->asset_no }} <input type="hidden"  name="asset_no[]" value="{{$row->asset_no}}"> </td>
            <td> {{ $row->shop_name }} <input type="hidden"  name="customer_name[]" value="{{$row->shop_name}}">
                <input type="hidden"  name="customer_id[]" value="{{$row->id}}"> </td>
            <td> {{ $row->area }} <input type="hidden"  name="area[]" value="{{$row->area}}">  </td>



                <td> {{ $row->sc_rate??0 }}  <input type="hidden"  name="rate[]" value="{{$row->sc_rate??0}}">
                </td>
                <td style="text-align: right;"> {{ number_format(($row->sc_rate??0)*$row->area) }}
                    <input type="hidden"  name="amount[]" value="{{ round((($row->sc_rate??0)*$row->area))}}"> </td>
                <td style="text-align: right;">
                    @php $vat = 0; @endphp

                        @php
                        if(isset($service->vat) && $service->vat!=0)
                            $vat = ($row->sc_rate??0)*$row->area*($service->vat/100);
                        @endphp
                        {{$vat}}

                    <input type="hidden"  name="vat[]" value="{{$vat}}">

                </td>
                <td style="text-align: right;display: none;"> {{ number_format($fixed_fine) }}
                    <input type="hidden"  name="fixedAmount[]" value="{{round($fixed_fine)}}">
                </td>
                <td style="text-align: right;display: none;"> {{ number_format((($service->rate??0)*$row->area)) }}
                    <input type="hidden"  name="sub_total[]" value="{{round((($service->rate??0)*$row->area))}}">
                </td>
                <td style="text-align: right;display:none"> {{ number_format($interest) }}
                    <input type="hidden"  name="interest[]" value="{{round($interest)}}">
                </td>
                <td style="text-align: right;"> {{ number_format($total+$vat) }}
                    <input type="hidden"  name="total[]" value="{{round($total+$vat)}}">
                </td>


    </tr>
@endforeach
    <input type="hidden" id="oppset_id" value="{{$j}}" >
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
