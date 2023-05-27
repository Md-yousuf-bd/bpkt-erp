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
                        <h5 class="card-title">Invoice Details</h5>

                    </div>
                    <div class="card-body card-block">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tbody>
                                <tr>
                                    <td style="width:200px;">Invoice No.</td>
                                    <td style="width:10px;">:</td>
                                    <td> {{ $income->invoice_no }}</td>
                                </tr> <tr>
                                    <td style="width:200px;">Shop No.</td>
                                    <td style="width:10px;">:</td>
                                    <td> {{ $income->shop_no }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px;">Shop Name</td>
                                    <td style="width:10px;">:</td>
                                    <td> {{ $income->shop_name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px;">Due Date</td>
                                    <td style="width:10px;">:</td>
                                    <td> {{$income->due_date }}</td>
                                </tr>

                                </tbody>
                            </table>
                            <div style="height: 2%">&nbsp;</div>
                            <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tr style="background: #000;color:#fff;">
                                    <td style="width:200px !important;"><strong>Item Head</strong></td>
                                    <td style="width:200px !important;"><strong>Month</strong></td>
                                    <td style="width:200px !important;"><strong>Description</strong></td>
                                    <td style="width:200px !important;"><strong>Bill (Tk.)</strong></td>
                                    <td style="width:200px !important;"><strong>Vat Rate %</strong></td>
                                    <td style="width:200px !important;"><strong>Vat Amount(Tk.)</strong></td>
                                    <td style="width:200px !important;"><strong>Total(Tk.)</strong></td>
                                </tr>
                                @php
                                    $total=0;
                                    $vat_total=0;
                                    $g_total=0;
                                @endphp
                                @foreach($details as $row)
                                    @php
                                        $total += round($row->amount,2);
                                        $vat_total += round($row->vat_amount,2);
                                        $g_total += round($row->total,2);

                                    @endphp
                                    <tr>
                                        <td style="width:200px !important;">{{$row->income_head}}</td>
                                        <td style="">{{$row->month}}</td>
                                        <td style="">{{$row->remarks}}</td>
                                        <td style="text-align: right">{{ round($row->amount,2) }}</td>
                                        <td style="text-align: right">{{ $row->vat }}</td>
                                        <td style="text-align: right">{{ round($row->vat_amount,2) }}</td>
                                        <td style="text-align: right">{{ round($row->total,2) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="3"> </td>
                                    <td style="text-align: right"><strong>{{$total}}</strong></td>
                                    <td><strong></strong></td>
                                    <td style="text-align: right"><strong>{{$vat_total}}</strong></td>
                                    <td style="text-align: right"><strong>{{$g_total}}</strong></td>
                                </tr>

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
