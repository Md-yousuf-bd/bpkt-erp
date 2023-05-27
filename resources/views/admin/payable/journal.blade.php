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
                        <h5 class="card-title">Journal Voucher

                        </h5>

                    </div>
                        <div class="card-body">
                            <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                    <tr>
                                        <td  colspan="3" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <strong> Accounts Department</strong></p>
                                            <p> <strong> {{$journal->payment_type }} Journal Voucher</strong></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="width:35%;"> Voucher No: {{ $journal->voucher_no }}   </td>
                                        <td  style="width:30%;"> Posting Date: {{ $journal->post_date }} </td>
                                        <td  style="width:35%;"> Effective Date: {{ $journal->journal_date??"" }}</td>


                                    </tr>

                                </table>
                                <table  class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                    <tbody>
                                    <tr style="background: #000;color:#fff;">
                                        <td style=""><strong>Ledger Name</strong></td>
                                        <td style="width:100px !important;"><strong>Ledger Type</strong></td>
                                        <td style="width:200px !important;"><strong>Staff Name</strong></td>
                                        <td style="width:200px !important;"><strong>Payment Ref.</strong></td>
                                        <td style=""><strong>Narration</strong></td>
                                        <td style="width:200px !important;"><strong>Debit</strong></td>
                                        <td style="width:200px !important;"><strong>Credit</strong></td>

                                    </tr>


                                    @php $debit_total=0; $credit_total=0; @endphp
                                    @foreach($details as $row)
                                        @php $debit_total += round($row->debit,2);  $credit_total +=round($row->credit,2);@endphp
                                        <tr>
                                            <td style="">{{$row->ledger_head}}</td>
                                            <td style="">{{ $row->ledger_type??"" }}</td>
                                            <td style="width:200px !important;">{{$row->staff_name}}</td>
                                            <td style="width:200px !important;">{{$row->payment_ref}}</td>
                                            <td style="width: 30%"><?=wordwrap($row->remarks, 40, "<br>")?></td>
                                            <td style="text-align: right">{{ number_format(round($row->debit,2),2) }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->credit,2),2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="5"></td>
                                        <td style="text-align: right"> <strong>{{number_format($debit_total,2)}}</strong></td>
                                        <td style="text-align: right"><strong>{{number_format( $credit_total,2)}}</strong></td>
                                    </tr>

                                    </tbody>
                                </table>
                                <div style="height: 3%">&nbsp;</div>
                                <div style="height: 3%">&nbsp;</div>
                                <div style="height: 3%">&nbsp;</div>
                                <table style="width: 100%">
                                    <tr>
                                        <td  style=" width:20%;text-align: left;">  <hr> Prepared By: {{ $journal->user->name??""  }}  </td>
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
