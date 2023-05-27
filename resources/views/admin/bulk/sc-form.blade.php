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
    @php  @endphp
    @foreach($customer as $row)
        <tr>
            @php

                $interest=0;$total=0;
                $bill_amount = $bill_amount+ $row->amount ;
                $due_amount +=($row->amount -  $row->payment_amount); $moth =  $row->month;
                $interest +=round((($electrcity->sc_rate??0)*$row->area??0)*$month);

                $service_amount=($foodCourtS->rate??0)*$row->area;
                $service_grand_total=$service_amount;
              if ($type == 31) {
                  $d=30;
                  $d1=cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($bill_date)),date('Y',strtotime($bill_date)));
                //  $ass = Asset::where('asset_no',$row->asset_no)->first();
                  if(date('Y-m',strtotime($row->date_s))==date('Y-m',strtotime($bill_date))){
                      $tardate = date('Y-m-'.$d1,strtotime($bill_date));
                      $termiante = date('Y-m-'.$d1,strtotime($bill_date));
                      $cmpdate = $row->date_s;
                      if($row->date_e=='0000-00-00' || $row->date_e==''){
                         $termiante = date('Y-m-'.$d1,strtotime($bill_date));
                      }else{
                          $termiante = $row->date_e;
                      }

                      $date1 = date_create($termiante);
                      $date2 = date_create($tardate);
                      $diff = date_diff($date2, $date1);
                      $days = $diff->format("%R%a");
                      $days = $days+1;
                      if($days>0){
                          $tardate = date('Y-m-'.$d1,strtotime($bill_date));
                          $cmpdate = $row->date_s;
                          $date1 = date_create($tardate);
                          $date2 = date_create($cmpdate);
                          $diff = date_diff($date2, $date1);
                          $days = $diff->format("%R%a");
                          $days = $days+1;
                          if($service_grand_total> 0 ){
                              $service_grand_total = abs(round(($service_grand_total/$d)*$days));
                              $service_amount = abs(round(($service_amount/$d)*$days));
                          }
                      }else{

                          //   $tardate = date('Y-m-30',strtotime($request->input('month')));
                          //$termiante = date('Y-m-30',strtotime($request->input('month')));
                          $cmpdate = $row->date_s;
                          $termiante = $row->date_e;
                          $date1 = date_create($termiante);
                          $date2 = date_create($cmpdate);
                          $diff = date_diff($date2, $date1);
                          $days = $diff->format("%R%a");
                          $days = $days+1;
                          if($service_grand_total> 0 ){
                              $service_grand_total = abs(round(($service_grand_total/$d)*$days));
                              $service_amount = abs(round(($service_amount/$d)*$days));
                          }
                      }
                  }else{
                       $tardate = date('Y-m-01',strtotime($bill_date));
                       $d=30;
                       $d1=cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($bill_date)),date('Y',strtotime($bill_date)));
                       $tardate = $row->date_s;
                       if($row->date_e=='0000-00-00' || $row->date_e==''){
                         $termiante = date('Y-m-'.$d1,strtotime($bill_date));
                          }else{
                              $termiante = $row->date_e;
                       }
                      $date1 = date_create($termiante);
                      $date2 = date_create($tardate);
                      $diff = date_diff($date1, $date2);
                      $days = $diff->format("%R%a");

                      $days = $days+1;
                      if($days >0){
                          $tardate = date('Y-m-01',strtotime($bill_date));
                          $cmpdate = $row->date_e;
                            $termiante = $row->date_e;
                      $date1 = date_create($termiante);
                      $date2 = date_create($tardate);
                      $diff = date_diff($date2, $date1);
                          $days = $diff->format("%R%a");
                          $days = $days+1;
                          $days = str_replace("+", "", $days);
                          if($service_grand_total> 0 && $d>$days){
                              $service_grand_total = abs(round(($service_grand_total/$d)*$days));
                              $service_amount = abs(round(($service_amount/$d)*$days));
                          }
                  }

              }
        }
            $total += $service_amount;

                $i++

            @endphp

            <td> {{ $j++ }} </td>
            <td style="text-align: center">
                @if(in_array($row['asset_no'],$allReadyBillGenerate))
                    <span style="background: green;
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



            <td> {{ $foodCourtS->rate??0 }}  <input type="hidden"  name="rate[]" value="{{$foodCourtS->rate??0}}">
            </td>
            <td style="text-align: right;"> {{ number_format($service_amount) }}
                <input type="hidden"  name="amount[]" value="{{ round($service_amount)}}"> </td>
            <td style="text-align: right;">
                @php $vat = 0; @endphp

                @php
                    if(isset($service->vat) && $service->vat!=0)
                        $vat = ($foodCourtS->rate??0)*$row->area*($service->vat/100);
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
