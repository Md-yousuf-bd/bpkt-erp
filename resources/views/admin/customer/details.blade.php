@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 mx-auto ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Customer Details</h5>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered  table-striped" style="font-size: 14px; width:100%;">
                                <tbody>
                                <tr>
                                    <td style="width:200px !important;" colspan="3"> <strong>Basic Information</strong></td>


                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Customer Id</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->id }}</td>

                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Shop No.</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->shop_no }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Shop Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->shop_name }}</td>
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
                                    <td> @if(isset($details->regionName->name)){{ $details->regionName->name }} @endif </td>
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
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Bin</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->bin }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Any VAT Exemption</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->vat_exemption }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Contact Person Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->contact_person_name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Designation</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->designation }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Contact No</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->contact_person_phone }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Email</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->email }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Customer Remarks</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->customer_remarks }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Black Listed</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->black_listed }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;" colspan="3"><strong>Contract Information</strong></td>

                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Contact No</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->contact_no }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Contact Date</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->contact_date }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Contact Start Date</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->contact_s_date }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Renewal Date</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->renewal_date }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Contact Closure Date</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->contact_closure_date }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Advance Deposit</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->advance_deposit }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Security Deposit</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->security_deposit }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Monthly adj. of Advance Deposit</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->adj_adv_deposit }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Adj. effective from</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->adj_effective_from }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Adj. closure date</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->adj_closure_date }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Area (sft)</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->area_sft }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rate/ sft</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->rent_sft }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Monthly Rent</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->monthly_rent }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Renewal Rent</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->renewal_rent }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Service Charges</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->service_charge }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Billing System</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->billing_system }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Credit Period</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->credit_period }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Owner Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->owner->name??"" }}</td>
                                </tr>

                                <tr>
                                    <td style="width:200px !important;">Assigned Ledger</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->ledger }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Opening Balance</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->opening_balance }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;" colspan="3"><strong>Sales/ Rental Info</strong></td>

                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Prior Monthly Rent</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->opening_balance }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Current Monthly Rent/td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->renewal_rent }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rate Effective From</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->rate_effective_from }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rate Effective To</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->rate_effective_to }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Service Charges per sft</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->service_charge!=""?number_format($details->service_charge,2):0.00 }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">SC Fine after due date</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ number_format($details->sc_fine)!=''?number_format($details->sc_fine,2):0.00 }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Interest Rate on SC</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->sc_interest_rate??'0' }}%</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">SC Rate Effective From</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->sc_rate_effective_from }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">SC Rate effective To</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->rate_effective_to }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Food Court SC</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->food_court_sc!=''?number_format($details->food_court_sc,2):0.00 }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rate Effective From</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->food_rate_effective_from }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rate Effective To</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->food_rate_effective_to }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Special SC</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->special_sc!=''?number_format($details->special_sc,2):0.00 }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rate Effective From</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->rate_effective_special_sc_from }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rate Effective To</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->rate_effective_special_sc_to }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Advertisement:</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->advertisement!=''?number_format($details->advertisement,2):0.00 }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Rate Effective From</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->advertisement_rate_effective_from }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rate Effective To</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->advertisement_rate_effective_to }}</td>
                                </tr>

                                <tr>
                                    <td style="width:200px !important;">Electricity Rate per Unit</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->electricity_rate_unit }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Electricity Meter No.</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->electricity_meter_no }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Electricity Meter Reading OP Bal.</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->electricity_meter_reading }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Electricity Fine Rate</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->electricity_fine_rate??"0" }}%</td>
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
