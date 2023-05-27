<?php $i=1;
$j=$oppset;
$bill_amount = 0;
$due_amount = 0;
$moth = 0; ?>

<table id="assetInfo" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
    <thead>
    <tr>
        <td style="width: 3%;">S.L.</td>
        <td style="text-align: center;">Select all <br></br>
            <input type="checkbox" id="checkAllss" onchange="Bulk.checkAllnput()" checked>
        </td>
        <td>Status</td>
        <td>Asset No</td>
        <td>Tenant Name</td>
        <td>Area (Sft)</td>
        <td>Rate/Sft</td>
        <td>Total Amount</td>
        <td>VAT</td>
        <td>Total Bill

        </td>

    </tr>
    </thead>
    <tbody>

    @if(count($customer)==0)
        <tr>
            <td style="color: red;text-align: center" colspan="10">
                No Record Found
            </td>
        </tr>
    @endif
    @foreach($customer as $row)
        <tr>
            @if(round($row->rate*$row->area)==0)
                @continue
            @endif
            @php
                $date1=   date_create($bill_date);
              $date2=   date_create($row['last_increment_date']);
              $interval = date_diff($date2, $date1);// ->diff($date1);
              $month = ($interval->y * 12) + $interval->m ;
                 if($month >= $row['increment_effective_month']){
                    $increment = round(($row['rent_increment']/100)*$row['rate'],3);
                    $total_rate =$row['rate']+$increment;

                 }else{
                        $total_rate =$row['rate'];
                 }
              $bill_amount = $bill_amount+ $row->amount ;
      $due_amount +=($row->amount -  $row->payment_amount);
      $moth =  $row->month;

      $grand_total=0;
      $bill_amount=0;
                      $grand_total = $total_rate*$row->area;
                      $bill_amount = $total_rate*$row->area;

                  if ($type == 29) {
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
                          if($days  >0){
                              $tardate = date('Y-m-'.$d1,strtotime($bill_date));
                              $cmpdate = $row->date_s;
                              $date1 = date_create($tardate);
                              $date2 = date_create($cmpdate);
                              $diff = date_diff($date2, $date1);
                              $days = $diff->format("%R%a");
                          $days = $days+1;

                              if($grand_total> 0 && $days<=30 ){
                                     $grand_total = abs(round(($grand_total/$d)*$days));
                                     $bill_amount = abs(round(($bill_amount/$d)*$days));
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
                              if($grand_total> 0 ){
                                  $grand_total = abs(round(($grand_total/$d)*$days));
                                  $bill_amount = abs(round(($bill_amount/$d)*$days));
                              }
                          }
                      }else{
                           $tardate = date('Y-m-01',strtotime($bill_date));
                           $d=cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($bill_date)),date('Y',strtotime($bill_date)));

                          $termiante = $row->date_e;
                          $date1 = date_create($termiante);
                          $date2 = date_create($tardate);
                          $diff = date_diff($date2, $date1);
                          $days = $diff->format("%R%a");
                          if($row->asset_no==109){
                               // echo       $days = $days+1;
                          }

                          if($days  >0){
                              $tardate = date('Y-m-01',strtotime($bill_date));
                              $cmpdate = $row->date_e;
                                $termiante = $row->date_e;
                          $date1 = date_create($termiante);
                          $date2 = date_create($tardate);
                          $diff = date_diff($date2, $date1);
                              $days = $diff->format("%R%a");
                              $days = $days+1;
                              $days = str_replace("+", "", $days);
                              if($grand_total> 0 && $d>$days){
                                  $grand_total = abs(round(($grand_total/$d)*$days));
                                  $bill_amount = abs(round(($bill_amount/$d)*$days));
                              }
                      }

                  }
            }
          $i++;
            @endphp
            <td> {{ $j++ }} </td>
            <td style="text-align: center">
                @if(in_array($row['asset_no'],$allReadyBillGenerate))
                    <span style="    background: green;
    padding: 4px;
    color: #fff;"> Bill Done</span>
                @else
                <input class="checkIs"


                                                  checked   type="checkbox" name="chk" value="{{ $i-2 }}">
                                                  @endif
                                                </td>
                <td> {{ $row->as_sttaus }} </td>
            <td> {{ $row->asset_no }} <input type="hidden" name="asset_no[]" value="{{$row->asset_no}}"></td>
            <td> {{ $row->shop_name }} <input type="hidden" name="customer_name[]" value="{{$row->shop_name}}">
                <input type="hidden" name="customer_id[]" value="{{$row->id}}">
            </td>
            <td> {{ $row->area }} <input type="hidden" name="area[]" value="{{$row->area}}"></td>
            @if($type==29)
                <td> {{ $total_rate }} <input type="hidden" name="rate[]" value="{{$total_rate}}">
                </td>
                <td style="text-align: right"> {{ number_format($bill_amount) }}
                    <input type="hidden" name="amount[]" value="{{round($bill_amount)}}"></td>
                <td style="text-align: right;">
                    @php $vat = 0; @endphp
                    @if($row->vat_exemption=='No')
                        @php
                            $vat = $total_rate*$row->area*.15;
                        @endphp
                        {{number_format($vat,2)}}
                    @endif
                    <input type="hidden" name="vat[]" value="{{$vat}}">
                </td>
                <td style="text-align: right"> {{ number_format($grand_total) }}
                    <input type="hidden" name="total[]" value="{{round($grand_total)}}">
                </td>
            @elseif($type==31)
                <td> {{ $service->rate }} <input type="hidden" name="rate[]" value="{{$service->rate}}">
                </td>
                <td style="text-align: right"> {{ number_format($service->rate*$row->area) }}
                    <input type="hidden" name="amount[]" value="{{round($service->rate*$row->area)}}"></td>
                <td>
                    @php $vat = 0; @endphp
                    @if($row->vat_exemption=='No')
                        @php
                            $vat = $service->rate*$row->area*.15;
                        @endphp
                        {{$vat}}
                    @endif
                    <input type="hidden" name="vat[]" value="{{$vat}}">
                </td>
                <td style="text-align: right"> {{ number_format(($service->rate*$row->area)+$vat) }}
                    <input type="hidden" name="total[]" value="{{round(($service->rate*$row->area)+$vat)}}">
                </td>
            @elseif($type==33)
            @elseif($type==34)
            @endif

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
