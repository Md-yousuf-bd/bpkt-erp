@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10  col-xs-12 mx-auto ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Journal Voucher</h5>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                <tr>
                                    <td  colspan="3" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                        <p> <strong> Accounts Department</strong></p>
{{--                                        <p> <strong> Voucher Type (Receipt/Payment/Journal Voucher)</strong></p>--}}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width:35%;"> Voucher No: {{ $journal->voucher_no }}   </td>
                                    <td  style="width:30%;"> Posting Date: {{ $journal->post_date }} </td>
                                    <td  style="width:35%;"> Effective Date: {{ $effective_date }}</td>


                                </tr>

                            </table>
                            <table  class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tbody>
                                <tr style="background: #000;color:#fff;">
                                    <td style=""><strong>Ledger Name</strong></td>
                                    <td style="width:100px !important;"><strong>Ledger Type</strong></td>
                                    <td style="width:200px !important;"><strong>Party Name</strong></td>
                                    <td style="width:200px !important;"><strong>Payment Ref.</strong></td>
                                    <td style=""><strong>Description</strong></td>
                                    <td style="width:200px !important;"><strong>Debit</strong></td>
                                    <td style="width:200px !important;"><strong>Credit</strong></td>

                                </tr>
                                <tr>
                                    <td style="">Accounts Receivable</td>
                                    <td>Income</td>
                                    <td>{{$journal->shop_name}}</td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right">{{ number_format(round($journal->total,2) + round($journal->vat_amount,2),2) }}</td>
                                    <td style="text-align: right">0.00</td>
                                </tr>

                                @php $total=0; @endphp
                                @foreach($details as $row)
                                    @php $total += round($row->amount,2) @endphp
                                <tr>
                                    <td style="">{{$row->income_head}}</td>
                                    <td style="">Income</td>
                                    <td style="width:200px !important;">{{$journal->shop_name}}</td>
                                    <td style="width:200px !important;"></td>
                                    <td style="width: 30%">{{$row->remarks}}</td>
                                    <td style="text-align: right">0.00</td>
                                    <td style="text-align: right">{{ number_format(round($row->amount,2),2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td style="width:200px !important;">Sales VAT Payable A/C</td>
                                    <td >Income</td>
                                    <td>{{$journal->shop_name}}</td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right">0.00</td>
                                    <td style="text-align: right">{{ number_format(round($journal->vat_amount,2),2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td style="text-align: right"> <strong>{{number_format(round($journal->total,2)+round($journal->vat_amount,2),2)}}</strong></td>
                                    <td style="text-align: right"><strong>{{number_format($total + round($journal->vat_amount,2),2)}}</strong></td>
                                </tr>

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
                            <div style="height: 3%"> Printed Date: {{date('Y-m-d h:i A')}} </div>
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
