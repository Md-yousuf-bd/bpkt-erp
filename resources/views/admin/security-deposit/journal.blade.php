@extends('admin.layouts.app')
@php include_once(app_path().'/helpers/Helper.php'); @endphp
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

                        <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('journal_entry_div')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span>
                        </h5>

                    </div>
                        <div class="card-body" id="pdiv">
                            <div id="journal_entry_div" class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                    <tr>
                                        <td  colspan="3" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <strong> Accounts Department</strong></p>
                                            <p> <strong> Journal Voucher</strong></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="1" style="width:35%;"> Voucher No: {{ $journal->voucher_no }}   </td>
                                        <td  style="width:30%;"> Posting Date: {{ $journal->issue_date }} </td>
                                        <td col  style="width:35%;text-align: right"> Effective Date: {{ $journal->journal_date??"" }}</td>


                                    </tr>

                                </table>
                                <table  class="table table-bordered table-responsive" style="font-size: 14px; width:50% !important;">
                                    <tbody>
                                    <tr style="">
                                        <td style="background: #d9d9d9;color:#000;"><strong>Ledger Name</strong></td>
                                        <td style="background: #d9d9d9;color:#000;width:100px !important;"><strong>Ledger Type</strong></td>
                                        <td style="background: #d9d9d9;color:#000;width:200px !important;"><strong>Customer/Vendor Name</strong></td>
                                        <td style="background: #d9d9d9;color:#000;width:200px !important;"><strong>Payment Ref.</strong></td>
                                        <td style="background: #d9d9d9;color:#000;"><strong>Narration</strong></td>
                                        <td style="background: #d9d9d9;color:#000;width:200px !important;"><strong>Debit (Tk.)</strong></td>
                                        <td style="background: #d9d9d9;color:#000;width:200px !important;"><strong>Credit (Tk.)</strong></td>

                                    </tr>


                                    @php $debit_total=0; $credit_total=0; @endphp
                                    @foreach($details as $row)
                                        @php $debit_total += round($row->debit,2);  $credit_total +=round($row->credit,2);@endphp
                                        <tr>
                                            <td style="">{{$row->ledger_head}}</td>
                                            <td style="">{{ $row->ledger_type??"" }}</td>
                                            <td style="width:200px !important;">{{$row->shop_no??""}} @if($row->customer_name!='') - @endif {{$row->customer_name}}</td>
                                            <td style="width:200px !important;">{{$row->payment_ref}}</td>
                                            <td style=""><?=wordwrap($row->remarks, 40, "<br>")?></td>
                                            <td style="text-align: right">{{ number_format(round($row->debit,2),2) }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->credit,2),2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="5">                                 <div style="float: left"> {{ curInWord($credit_total,'Amount in Word: ') }} Only</div>
                                        </td>
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
                                    <tr>
                                        <td colspan="3"  style=" width:20%;text-align: left;">   <div style="height: 3%;float: left;"> Printed Date: {{date('Y-m-d h:i A')}} </div>  </td>

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
