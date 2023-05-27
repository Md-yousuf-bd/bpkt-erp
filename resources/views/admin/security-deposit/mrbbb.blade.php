@extends('admin.layouts.app')
@php include_once(app_path().'/helpers/Helper.php'); @endphp
@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 col-md-9  col-xs-12 mx-auto ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"> 
                        <!--Security Desopit-->
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
                                    <td  style="width:35%; text-align: center"> Date: {{ date('Y-m-d',strtotime($journal->journal_date))  }}</td>


                                </tr>
                                <tr >
                                    <td colspan="3">
                                        Received with thanks from: <strong>{{$journal->shop_name}}, Shop No: {{$journal->shop_no}} </strong> against the bill as follows:
                                    </td>
                                </tr>

                            </table>



                            <table  class="table table-bordered" style="font-size: 14px; ">
                                <tbody>
                                <tr style="background: #d9d9d9;color:#000;">
                                    <td style=""><strong>S.N.</strong></td>
                                    <td style="width:100px !important;"><strong>Purpose</strong></td>
                                    <td style="width:200px !important;"><strong>Payment Ref.</strong></td>
                                    <td style="width:200px !important;"><strong>Purpose/ Narration</strong></td>
                                    <td style=""><strong>Amount (Tk.)</strong></td>

                                </tr>

                                @php $tdebit=0;$tcredit=0; $i=1;$bill_total=0; $month=''; @endphp

                                <tr>
                                    <td style="">{{$i++}}</td>
                                    <td style="">@if($journal->category==117)Advance Deposit for Rent @else Security Deposit @endif</td>                                    <td style="width:200px !important;">{{ $details[0]['payment_ref']??"" }}</td>
                                    <td style="width:200px !important;">{{ $details[0]['remarks']??"" }}</td>
                                    <td style="text-align: right">  {{number_format($journal->amount,2)}} </td>

                                </tr>


                                <tr>
                                    <td colspan="4" style="text-align: right"> <strong>Total</strong></td>
                                    <td style="text-align: right"> <strong>{{number_format($journal->amount,2)}}</strong></td>

                                </tr>


                                </tbody>
                            </table>
                            <div style="float: left"> {{ curInWord($journal->amount,'Amount Word: ') }} Only</div>

                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%;">&nbsp;</div>
                            <div style="text-align: left;">

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
