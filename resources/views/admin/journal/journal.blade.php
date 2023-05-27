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
                            <table  class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tbody>
                                <tr style="background: #000;color:#fff;">
                                    <td style="width:200px !important;"><strong>Particulars</strong></td>
                                    <td style="width:200px !important;"><strong>Debit</strong></td>
                                    <td style="width:200px !important;"><strong>Credit</strong></td>

                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Accounts Receivable</td>
                                    <td>{{ round($journal->total,2) }}</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Accounts Receivable (VAT)</td>
                                    <td>{{ round($journal->vat_amount,2) }}</td>
                                    <td>0</td>
                                </tr>
                                @php $total=0; @endphp
                                @foreach($details as $row)
                                    @php $total += round($row->amount,2) @endphp
                                <tr>
                                    <td style="width:200px !important;">{{$row->income_head}}</td>
                                    <td style="">0</td>
                                    <td>{{ round($row->amount,2) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td style="width:200px !important;">Sales VAT Payable A/C</td>
                                    <td>0</td>
                                    <td>{{ round($journal->vat_amount,2) }}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td> <strong>{{round($journal->total,2)+round($journal->vat_amount,2)}}</strong></td>
                                    <td><strong>{{$total + round($journal->vat_amount,2)}}</strong></td>
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
