@extends('admin.layouts.app')
@php include_once(app_path().'/helpers/Helper.php'); @endphp
@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mx-auto ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">For {{$journal->payment_mode}} Collection
                            <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('collection_mr_div')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span>
                        </h5>
                    </div>
                    <div class="card-body" id="collection_mr_div">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  ">


                            <table style="width: 100%;">
                                <tr>
                                    <td>
                                        <img style=" float: left; width: 75px; height: 75px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                        <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                            <tr>
                                                <td  colspan="3" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                                    <p> <strong> Police Headquarters, Phoenix Road, Dhaka-1000</strong> <br>
                                                        <strong> Police Plaza Concord </strong>
                                                    <p style="margin-bottom: 0px;    line-height: 0px;"> <strong> MONEY RECEIPT</strong></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width:35%; "> MR Serial No: {{ $journal->money_receipt_no }}   </td>
                                             
                                                <td  style="width:35%; text-align: left"> Date: {{ date('d-m-Y',strtotime($journal->collection_date))  }}</td>


                                            </tr>

                                        </table>
                                    </td>
                                    <td>
                                        <img style="margin-left: 6%; float: left; width: 75px; height: 75px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                        <table style="width: 100%;border: none;margin-left: 6%;"  class="table  table-borderless" valign="top">
                                            <tr>
                                                <td  colspan="3" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                                    <p> <strong> Police Headquarters, Phoenix Road, Dhaka-1000</strong> <br>
                                                        <strong> Police Plaza Concord </strong>
                                                    <p style="margin-bottom: 0px;    line-height: 0px;"> <strong> MONEY RECEIPT</strong></p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="width:35%; "> MR Serial No: {{ $journal->money_receipt_no }}   </td>
                                               
                                                <td  style="width:35%; text-align: left"> Date: {{ date('d-m-Y',strtotime($journal->collection_date))  }}</td>


                                            </tr>


                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="margin-top:-40px;width: 40%;padding-right: 33px; border-right: 2px dotted #D3D3D3;">
                                        <table  class="table table-bordered" style="margin-top:-10px;font-size: 14px;width: 180px ">
                                            <tbody>
                                            <tr style="background: #d9d9d9;color:#000;">
                                                <td style="width:30% !important;"><strong>Particulars</strong></td>
                                                <td style="width:15% !important;"><strong>Period</strong></td>
                                                <td style="width:10%;"><strong>Amount (Tk.)</strong></td>


                                            </tr>

                                            @php $amount=0; $fine_amount=0; $fixed_fine=0;$tdebit=0;$tcredit=0;$discount=0; $i=1;$bill_total=0; $month='';@endphp
                                            @foreach($details as $row)
                                                @php
                                                    $rowTotal=0;
                                                        $amount += round($row->payment_amount,2);
                                                        $tdebit += round($row->payment_amount,2);
                                                        $tdebit += round($row->paid_vat_amount,2);
                                                        $tdebit += round($row->paid_fine_amount,2);
                                                        $fine_amount += round($row->paid_fine_amount,2);
                                                        $fixed_fine += round($row->paid_fixed_fine,2);
                                                        $tdebit += round($row->paid_fixed_fine,2);
                                                        $discount += round($row->discount,2);
                                                        $rowTotal += round($row->paid_fixed_fine,2);
                                                        $rowTotal += round($row->paid_fine_amount,2);
                                                        $rowTotal += round($row->payment_amount,2);
                                                        $rowTotal += round($row->paid_vat_amount,2);
                                                        $month=$row->month;
                                                            $bill_total += round($rowTotal-$row->discount);
                                                             $shop_name= $row->shop_no.', '.$row->shop_name.' ('.$row->bill_type.')';
                                                @endphp
                                                <tr>
                                                    {{-- <td style="">{{$row->item_head}}</td> --}}
                                                    <td style="font-size: 11px"> {!! wordwrap($shop_name,50,'<br>',true) !!}</td>
                                                    <td style="width:180px !important;">{{$row->month}}</td>
                                                    <td style="text-align: right">  {{ number_format($row->payment_amount,2) }} </td>

                                                </tr>
                                            @endforeach

                                            @if($journal->is_settlement)
                                                <tr>
                                                    <td style="">Security Deposit</td>
                                                    <td style=""></td>

                                                    <td style="text-align: right"> -{{ number_format(abs($journal->security_settalment),2) }} </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td colspan="2" style="text-align: right"> <strong> Total Receivable </strong></td>
                                                <td style="text-align: right"> <strong>
                                                        @if($journal->is_settlement)
                                                            {{number_format(abs($amount-$journal->security_settalment),2)}}

                                                        @else

                                                            {{number_format($amount,2)}}
                                                        @endif

                                                    </strong></td>

                                            </tr>
                                            <tr>
                                                <td colspan="2" style="text-align: right"><strong> Total Fine</strong></td>
                                                <td style="text-align: right"> <strong>{{ number_format($fine_amount+$fixed_fine,2) }}</strong> </td>
                                            </tr>
                                            <tr>
                                                <td  colspan="2" style="text-align: right"><strong> Less: Discount</strong></td>
                                                <td style="text-align: right"> <strong>{{ number_format($discount,2) }}</strong> </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="text-align: right"><strong> Total Paid</strong></td>
                                                <td style="text-align: right"> <strong>  @if($journal->is_settlement)
                                                            {{number_format(abs($tdebit-$journal->security_settalment),2)}}
                                                            @php $tdebit = abs($tdebit-$journal->security_settalment); @endphp
                                                        @else
                                                            @php $tdebit = abs($tdebit-$discount); @endphp
                                                            {{number_format($tdebit,2)}}
                                                        @endif
                                                    </strong> </td>
                                            </tr>



                                            </tbody>
                                        </table>
                                        <div style="float: left"> {{ curInWord($tdebit,'Amount Word: ') }} Only</div>
                                      <br>


                                        @if($journal->payment_mode=='Cheque')
                                            Payment Mode: Cheque No:  {{$journal->cheque_no}} of {{$journal->cheque_bank_name}}.  @if($journal->cheque_date!='' && $journal->cheque_date!='0000-00-00') dated {{  date(' d M Y',strtotime($journal->cheque_date ))}} @endif
                                        @elseif($journal->payment_mode=='Cash')
                                            Payment Mode: Cash
                                        @endif

                                        <br><br>
                                        <br><br>
                                           <br>
                                        <table style="width: 50%">
                                            <tr>
                                                <td style=" width:20%;text-align: center;    line-height: 0px;"><hr> Accountant</td>
                                            </tr>

                                        </table>
                                        {{--                                        <div style="height: 3%;float: left"> Printed Date: {{date('Y-m-d h:i A')}} </div>--}}

                                    </td>
                                    <td style="width: 40%;padding-left: 3%;" valign="top">
                                        <table  class="table table-bordered" style="margin-top:-10px;font-size: 14px;width: 180px ">
                                            <tbody>
                                            <tr style="background: #d9d9d9;color:#000;">
                                                <td style="width:30% !important;"><strong>Particulars</strong></td>
                                                <td style="width:15% !important;"><strong>Period</strong></td>
                                                <td style="width:10%;"><strong>Amount (Tk.)</strong></td>



                                            </tr>

                                            @php $amount=0; $fine_amount=0; $fixed_fine=0;$tdebit=0;$tcredit=0;$discount=0; $i=1;$bill_total=0; $month='';@endphp
                                            @foreach($details as $row)
                                                @php
                                                    $rowTotal=0;
                                                        $amount += round($row->payment_amount,2);
                                                        $tdebit += round($row->payment_amount,2);
                                                        $tdebit += round($row->paid_vat_amount,2);
                                                        $tdebit += round($row->paid_fine_amount,2);
                                                        $tdebit += round($row->paid_fixed_fine,2);
                                                        $fine_amount += round($row->paid_fine_amount,2);
                                                        $fixed_fine += round($row->paid_fixed_fine,2);
                                                        $discount += round($row->discount,2);
                                                        $rowTotal += round($row->paid_fixed_fine,2);
                                                        $rowTotal += round($row->paid_fine_amount,2);
                                                        $rowTotal += round($row->payment_amount,2);
                                                        $rowTotal += round($row->paid_vat_amount,2);
                                                        $month=$row->month;
                                                            $bill_total += round($rowTotal-$row->discount);
                                                              $shop_name= $row->shop_no.', '.$row->shop_name.' ('.$row->bill_type.')';
                                                @endphp
                                                <tr>
                                                    {{-- <td style="">{{$row->item_head}}</td> --}}
                                                    <td style="font-size: 11px"> {!! wordwrap($shop_name,50,'<br>',true) !!}</td>
                                                    <td style="width:200px !important;">{{$row->month}}</td>
                                                    <td style="text-align: right">  {{ number_format($row->payment_amount,2) }} </td>

                                                </tr>
                                            @endforeach

                                            @if($journal->is_settlement)
                                                <tr>
                                                    <td style="">Security Deposit</td>
                                                    <td style=""></td>

                                                    <td style="text-align: right"> -{{ number_format(abs($journal->security_settalment),2) }} </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td colspan="2" style="text-align: right"> <strong> Total Receivable </strong></td>
                                                <td style="text-align: right"> <strong>  @if($journal->is_settlement)
                                                            {{number_format(abs($amount-$journal->security_settalment),2)}}

                                                        @else

                                                            {{number_format($amount,2)}}
                                                        @endif</strong></td>

                                            </tr>
                                            <tr>
                                                <td  colspan="2" style="text-align: right"><strong> Total Fine</strong></td>
                                                <td style="text-align: right"> <strong>{{ number_format($fine_amount+$fixed_fine,2) }}</strong> </td>
                                            </tr>
                                            <tr>
                                                <td  colspan="2" style="text-align: right"><strong> Less: Discount</strong></td>
                                                <td style="text-align: right"> <strong>{{ number_format($discount,2) }}</strong> </td>
                                            </tr>
                                            <tr>
                                                <td  colspan="2" style="text-align: right"><strong> Total Paid</strong></td>
                                                <td style="text-align: right"> <strong>  @if($journal->is_settlement)
                                                            {{number_format(abs($tdebit-$journal->security_settalment),2)}}
                                                            @php $tdebit = abs($tdebit-$journal->security_settalment); @endphp
                                                        @else
                                                            @php $tdebit = abs($tdebit-$discount); @endphp
                                                            {{number_format($tdebit,2)}}
                                                        @endif
                                                    </strong> </td>
                                            </tr>



                                            </tbody>
                                        </table>
                                        <div style="float: left"> {{ curInWord($tdebit,'Amount Word: ') }} Only </div>

                                        <br>
                                        @if($journal->payment_mode=='Cheque')
                                            Payment Mode: Cheque No:  {{$journal->cheque_no}} of {{$journal->cheque_bank_name}}.  @if($journal->cheque_date!='' && $journal->cheque_date!='0000-00-00') dated {{  date(' d M Y',strtotime($journal->cheque_date ))}} @endif
                                        @elseif($journal->payment_mode=='Cash')
                                            Payment Mode: Cash
                                        @endif

                                        <br><br>
                                        <br><br>
                                        <br>
                                        <table style="width: 50%">
                                            <tr>
                                                <td style=" width:20%;text-align: center;    line-height: 0px;"><hr> Accountant</td>
                                            </tr>

                                        </table>
                                        {{--                                        <div style="height: 3%;float: left"> Printed Date: {{date('Y-m-d h:i A')}} </div>--}}
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .content -->

@endsection

@section('uncommonExJs')
    @include('admin.layouts.commons.dataTableJs')
@endsection

@section('uncommonInJs')

@endsection
