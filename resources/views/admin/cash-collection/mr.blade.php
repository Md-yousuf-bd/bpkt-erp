@extends('admin.layouts.app')
@php include_once(app_path().'/helpers/Helper.php'); @endphp
@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-xs-12 mx-auto ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">For {{$journal->payment_mode}} Collection
                            <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('collection_mr_div')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span>
                        </h5>
                    </div>
                    <div class="card-body" id="collection_mr_div">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  ">
                            <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                            <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                <tr>
                                    <td  colspan="3" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                        <p> <strong> Police Headquarters, Phoenix Road, Dhaka-1000</strong> <br>
                                        <strong> Police Plaza Concord </strong>
                                        <p> <strong> MONEY RECEIPT</strong></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width:35%; "> MR Serial No: {{ $journal->money_receipt_no }}   </td>
                                    <td  style="width:40%;">  </td>
                                    <td  style="width:35%; text-align: center"> Date: {{ date('Y-m-d',strtotime($journal->collection_date))  }}</td>


                                </tr>
                                <tr >
                                    <td colspan="3">
                                        Received with thanks from: <strong>{{$journal->shop_name}} </strong> against the bill as follows:
                                    </td>
                                </tr>

                            </table>



                            <table  class="table table-bordered" style="font-size: 14px; ">
                                <tbody>
                                <tr style="background: #d9d9d9;color:#000;">
                                    <td style=""><strong>S.N.</strong></td>
                                    <td style="width:100px !important;"><strong>Purpose</strong></td>
                                    <td style="width:200px !important;"><strong>Period</strong></td>
                                    <td style="width:200px !important;"><strong>Shop No</strong></td>
                                    <td style="width:200px !important;"><strong>Invoice No</strong></td>
                                    <td style=""><strong>Amount (Tk.)</strong></td>
                                    <td style=""><strong>Fixed Fine (Tk.)</strong></td>
                                    <td style=""><strong>Interest (Tk.)</strong></td>
                                    <td style=""><strong>Discount (Tk.)</strong></td>
                                    <td style=""><strong>Net Payment(Tk.)</strong></td>


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

                                      ;


                                        $rowTotal += round($row->paid_fixed_fine,2);
                                        $rowTotal += round($row->paid_fine_amount,2);
                                        $rowTotal += round($row->payment_amount,2);
                                        $rowTotal += round($row->paid_vat_amount,2);
                                        $month=$row->month;
                                            $bill_total += round($rowTotal-$row->discount);
                                    @endphp
{{--                                    $bill_total += round($row->payment_amount,2);--}}
{{--                                    $bill_total += round($row->paid_vat_amount,2);--}}
{{--                                    $bill_total += round($row->paid_fine_amount,2)--}}
                                <tr>
                                    <td style="">{{$i++}}</td>
                                    <td style="">{{$row->item_head}}</td>
                                    <td style="width:200px !important;">{{$row->month}}</td>
                                    <td style="width:200px !important;">{{$row->shop_no}}</td>
                                    <td style="width:200px !important;">{{$row->invoice_no}}</td>
                                    <td style="text-align: right">  {{ number_format($row->payment_amount,2) }} </td>
                                    <td style="text-align: right">  {{ number_format($row->paid_fixed_fine,2) }} </td>

                                    <td style="text-align: right">  {{ number_format($row->paid_fine_amount,2) }} </td>
                                    <td style="text-align: right">  {{ number_format($row->discount,2) }} </td>
                                    <td style="text-align: right">  {{ number_format($rowTotal-$row->discount,2) }} </td>
                                </tr>
                                @endforeach
{{--                                 <tr>--}}
{{--                                    <td style="">{{$i++}}</td>--}}
{{--                                    <td style="">Sales VAT</td>--}}
{{--                                    <td style="width:200px !important;">{{$month}}</td>--}}
{{--                                    <td style="width:200px !important;"></td>--}}
{{--                                    <td style="text-align: right">  {{ number_format(round($row->payment_amount,2),2) }} </td>--}}
{{--                                </tr>--}}
                                @if($journal->is_settlement)
                                <tr>
                                    <td style="">{{$i++}}</td>
                                    <td style="">Security Deposit</td>
                                    <td style="width:200px !important;"></td>
                                    <td style="width:200px !important;"></td>
                                    <td style="width:200px !important;"></td>
                                    <td style="text-align: right">  </td>
                                    <td style="text-align: right">   </td>

                                    <td style="text-align: right"> </td>

                                    <td style="text-align: right"> -{{ number_format(abs($journal->security_settalment),2) }} </td>
                                </tr>
                                @endif

                                <tr>
                                    <td colspan="5" style="text-align: right"> <strong> Total</strong></td>
                                    <td style="text-align: right"> <strong>{{number_format($amount,2)}}</strong></td>

                                    <td style="text-align: right"> <strong>
                                            {{number_format($fixed_fine,2)}}
                                        </strong></td>
                                    <td style="text-align: right"> <strong>{{number_format($fine_amount,2)}}</strong></td>


                                    <td style="text-align: right"> <strong>
                                        {{number_format($discount,2)}}
                                        </strong></td>
                                    <td style="text-align: right"> <strong>

                                            @if($journal->is_settlement)
                                                {{number_format(abs($tdebit-$journal->security_settalment),2)}}
                                               @php $tdebit = abs($tdebit-$journal->security_settalment); @endphp
                                            @else
                                                {{number_format($tdebit-$discount,2)}}
                                                @endif


                                        </strong></td>
                                </tr>
                                <tr style="display: none;">
                                    <td colspan="4" style="text-align: right"> <strong>Less: Discount</strong></td>
                                    <td style="text-align: right"> <strong>
                                        {{ number_format($tdebit-$discount,2) }}
                                        </strong></td>
                                </tr>
                                <tr style="display: none">
                                    <td colspan="4" style="text-align: right"> <strong>Less: TDS</strong></td>
                                    <td style="text-align: right"> <strong>
                                            0.00
                                        </strong></td>
                                </tr>
                                <tr style="display: none;">
                                    <td colspan="4" style="text-align: right"> <strong>Less: VDS</strong></td>
                                    <td style="text-align: right"> <strong>0.00</strong></td>
                                </tr>
                                <tr style="display: none;">
                                    <td colspan="4" style="text-align: right"> <strong>Total paid amount</strong></td>
                                    <td style="text-align: right"> <strong>{{number_format(abs(($tdebit + $journal->paid_vat_amount-$journal->security_settalment)) ,2)}}</strong></td>
                                </tr>
                                </tbody>
                            </table>
                            <div style="float: left"> {{ curInWord($tdebit,'Amount Word: ') }} Only</div>

                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%;">&nbsp;</div>
                            <div style="text-align: left;">
                            @if($journal->payment_mode=='Cheque')
                            Payment Mode: Cheque No:  {{$journal->cheque_no}} of {{$journal->cheque_bank_name}}. dated  {{  date(' d M Y',strtotime($journal->collection_date ))}}
                            @elseif($journal->payment_mode=='Cash')
                                Payment Mode: Cash
                            @endif
                            </div>
                                <div style="height: 3%;text-align: left;">&nbsp;</div>

                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <table style="width: 100%">
                                <tr>
                                    <td  style=" width:25%;text-align: left;">  <hr> Prepared By: {{ $journal->user->name }}  </td>
                                    <td style="width: 60%"></td>
                                    <td style=" width:20%;text-align: right;"><hr> Checked By</td>
                                </tr>

                            </table>
                            <div style="height: 3%;float: left"> Printed Date: {{date('Y-m-d h:i A')}} </div>
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
