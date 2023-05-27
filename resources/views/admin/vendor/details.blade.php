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
                        <h5 class="card-title"> Vendor Details</h5>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered  table-striped" style="font-size: 14px; width:100%;">
                                <tbody>
                                <tr>
                                    <td style="width:200px !important;" colspan="3"> <strong>Basic Information</strong></td>


                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Vendor Id</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->id }}</td>

                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Vendor Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->vendor_name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Owner Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->owner_name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Owner Contact No</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->owner_contact }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Owner NID</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->owner_nid }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Owner Address</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->owner_address }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Region</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->division->name??'' }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Trade License No</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->trade_lincese_no }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Incorporation No (if any)</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->incorporation_no }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">E-TIN</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->etin }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Bin</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->bin }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Contact Person Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->contact_person_name }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Contact No</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->contact_person_phone }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Email</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->email }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Credit Period</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->credit_period }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Supplier Type</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->supplier_type }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Mode of Payment</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->payment_method }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;" colspan="3"><strong>Bank Information</strong></td>

                                </tr>

                               <tr>
                                    <td style="width:200px !important;">Bank Account Title</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->bank_account_title }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Bank Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->bank_name }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Branch Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->branch_name }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Account Number</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->account_no }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Routing Number</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->routing_number }}</td>
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
