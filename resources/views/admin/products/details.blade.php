@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Product Details</h5>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered  table-striped" style="font-size: 14px; width:100%;">
                                <tbody>

                                <tr>
                                    <td style="width:200px !important;">Product Id</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->id }}</td>

                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Product/Service Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->product_name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Vendor Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->vendor_name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Brand Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->brand_name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Size</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->size }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Regular Price</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->regular_price }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Discounted Price</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->discounted_price }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rate effective Date</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->rate_effective_date }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">VDS Head</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->vds_head }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">VDS Rate</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->vds_rate }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">TDS Head</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->tds_head }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">TDS Rate</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->tds_rate }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Effective Date Range</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->effective_date_from }} to {{ $details->effective_date_to }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Assigned Ledger</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->assigned_ledger }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Opening Balance</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->opening_balance }}</td>
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
