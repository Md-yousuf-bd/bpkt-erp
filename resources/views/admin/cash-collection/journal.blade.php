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
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  ">

                            <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                <tr>
                                    <td  colspan="3" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                        <p> <strong> Accounts Department</strong></p>
                                        <p> <strong> Voucher Type (Receipt)</strong></p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="width:35%; "> Voucher No: {{ $journal->voucher_no }}   </td>
                                    <td  style="width:30%;"> Posting Date: {{ date('Y-m-d',strtotime($journal->created_at)) }} </td>
                                    <td  style="width:35%;"> Effective Date: {{ date('Y-m-d',strtotime($journal->collection_date))  }}</td>


                                </tr>

                            </table>



                            <table  class="table table-bordered" style="font-size: 14px; ">
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

                                @php $tdebit=0;$tcredit=0; @endphp
                                @foreach($details as $row)
                                    @php $tdebit += round($row->debit,2);$tcredit += round($row->credit,2) @endphp
                                <tr>
                                    <td style="">{{$row->ledger_head}}</td>
                                    <td style="width:200px !important;">{{$row->ledger_type}}</td>
                                    <td style="width:200px !important;">{{ $journal->shop_no }}-{{$row->customer_name}}</td>
                                    <td style="width:200px !important;">{{$row->payment_ref}}</td>
                                    <td style="width: 10%"><?php echo wordwrap($row->remarks,30,"<br>") ?> </td>
                                    <td style="text-align: right"> @if((int)$row->debit!=0) {{ number_format(round($row->debit,2),2) }} @else 0 @endif</td>
                                    <td style="text-align: right"> @if((int)$row->credit !=0){{ number_format(round($row->credit,2),2) }} @else 0 @endif</td>
                                </tr>
                                @endforeach

                                <tr>
                                    <td colspan="5" style="text-align: right"> <strong>Total Taka</strong></td>
                                    <td style="text-align: right"> <strong>{{number_format($tdebit,2)}}</strong></td>
                                    <td style="text-align: right"><strong>{{number_format($tcredit,2)}}</strong></td>
                                </tr>

                                </tbody>
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
