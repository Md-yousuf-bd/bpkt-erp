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
                                    <td style="width:200px;">Sales Person</td>
                                    <td style="width:10px;">:</td>
                                    <td> {{ '---' }}</td>
                                </tr>

                                </tbody>
                            </table>
                            <div style="height: 2%">&nbsp;</div>
                            <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tr style="background: #000;color:#fff;">
                                    <td style="width:200px !important;"><strong>Income Head</strong></td>
                                    <td style="width:200px !important;"><strong>Date</strong></td>
                                    <td style="width:200px !important;"><strong>Amount (Tk.)</strong></td>
                                </tr>
                                @php $total=0; @endphp
                                @foreach($details as $row)
                                    @php $total += round($row->amount,2) @endphp
                                    <tr>
                                        <td style="width:200px !important;">{{$row->income_head}}</td>
                                        <td style="">{{$row->date}}</td>
                                        <td>{{ round($row->amount,2) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td><strong>Sub Total </strong></td>
                                    <td> </td>
                                    <td><strong>{{$total}}</strong></td>
                                </tr>
                                <tr>
                                    <td> <strong>Add: VAT</strong></td>
                                    <td> {{ $income->vat }}% </td>
                                    <td><strong>{{round($income->vat_amount,2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td> <strong>Grand Total</strong></td>
                                    <td>  </td>
                                    <td><strong>{{ round($income->total,2) + round($income->vat_amount,2) }}</strong></td>
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
