@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection
@php $div_print="print_stock"; @endphp;
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10  col-xs-12 mx-auto ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Journal Voucher
                        <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('{{$div_print}}')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span>
                        </h5>
                    </div>
                    <div class="card-body" id="{{$div_print}}">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <img style=" float: left; width: 100px; height: auto;margin-bottom: -66px;margin-top: 0px;" src="{{ URL::asset('images/logos/logo2.png') }}">
                            <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                <tr>
                                    <td  colspan="3" style="text-align: center;font-size:15px;"> <strong> {{ env('APP_Company_Name') }}</strong>
                                        <p> <strong> Accounts Department</strong></p>
                                        {{--                                        <p> <strong> Voucher Type (Receipt/Payment/Journal Voucher)</strong></p>--}}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width:35%;"> Voucher No: {{ $journal->voucher_no }}   </td>
                                    <td  style="width:30%;"> Posting Date: {{ $journal->post_date }} </td>
                                    <td  style="width:35%;"> Effective Date: {{ $journal->effective_date }}</td>


                                </tr>

                            </table>
                            <table  class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tbody>
                                <tr style="background: #d9d9d9;color:#000;">
                                    <td style=""><strong>Ledger Name</strong></td>
                                    <td style="width:100px !important;"><strong>Ledger Type</strong></td>
                                    <td style="width:200px !important;"><strong>Party Name</strong></td>
                                    <td style="width:200px !important;"><strong>Payment Ref.</strong></td>
                                    <td style=""><strong>Quantity</strong></td>
                                    <td style=""><strong>Description</strong></td>
                                    <td style="width:200px !important;"><strong>Debit</strong></td>
                                    <td style="width:200px !important;"><strong>Credit</strong></td>
                                </tr>

                                @php $total=0; @endphp
                                @foreach($details as $row)
                                    @php $total += round($row->amount,2) @endphp
                                    <tr>
                                        <td style="">{{$row->ledger_head}}</td>
                                        <td style="">{{ $row->ledger_type??"" }}</td>
                                        <td style="width:200px !important;">{{$row->customer_name}}</td>

                                        <td style="width:200px !important;"></td>
                                        <td style="width:200px !important;">{{$journal->qty}}</td>
                                        <td style="width: 30%"><?=wordwrap($row->remarks, 40, "<br>")?></td>
                                        <td style="text-align: right">{{ number_format($row->debit,2) }}</td>
                                        <td style="text-align: right">{{ number_format($row->credit,2) }}</td>
                                    </tr>
                                @endforeach



                                </tbody>
                            </table>
                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <table style="width: 100%">
                                <tr>
                                    <td  style=" width:20%;text-align: left;">  <hr> Prepared By  </td>
                                    <td style="width: 60%"></td>
                                    <td style=" width:20%;text-align: right;"><hr> Checked By</td>
                                </tr>

                            </table>
                            <div style="height: 3%;text-align: left;"> Printed Date: {{date('Y-m-d h:i A')}} </div>
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
