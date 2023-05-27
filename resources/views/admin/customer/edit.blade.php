@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title cus" style="font-weight: 700;font-size: 19px;">Basic Information
                        </h5>

                        <!-- Nav tabs -->
                        <ul class="nav nav-pills" style="display: none;">
                            <li class="nav-item" style="display: none;">
                                <a class="nav-item nav-link active cusBasicInfo" onclick="showTabPage('cus_basic');" data-toggle="tab" >Basic Info</a>
                            </li>
                            <li class="nav-item" style="display: none;">
                                <a class="nav-link cusContuct" onclick="showTabPage('cus_contact');" data-toggle="tab" >Contract Info</a>
                            </li>
                            <li class="nav-item" style="display: none">
                                <a class="nav-link cusRent" onclick="showTabPage('cus_rent');" data-toggle="tab" >Sales/ Rental Info</a>
                            </li>

                        </ul>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addOwner" action="{{route('customer.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <span id="cus_basic">
                            <div class="row">

{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12" style="display: none;">--}}
{{--                                    <label for="shop_no">Shop No</label>--}}
{{--                                    <input type="text"--}}

{{--                                           class="form-control" value="{{ $editData->shop_no }}" name="shop_no" id="shop_no" >--}}

{{--                                </div>--}}
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="shop_name">Customer Name</label>
                                    <input type="text" class="form-control" value="{{ $editData->shop_name }}" name="shop_name" id="shop_name" required>

                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_name">Owner Name</label>
                                    <input type="text" class="form-control" value="{{ $editData->owner_name }}" name="owner_name" id="owner_name" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="owner_contact">Owner Contact No</label>
                                <input type="text" class="form-control" value="{{ $editData->owner_contact }}" name="owner_contact" id="owner_contact" >

                            </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_nid">Owner NID</label>
                                    <input type="text" class="form-control" value="{{ $editData->owner_nid }}" name="owner_nid" id="owner_nid" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_address">Owner Address</label>
                                    <input type="text" class="form-control" value="{{ $editData->owner_address }}" name="owner_address" id="owner_address" >

                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="region">Region</label>
                                    <select class="form-control select2" name="region" id="region">
                                        <option value="">Select</option>
                                        @foreach($division as $name)
                                            <option value="{{$name->id}}" @if($name->id==$editData->region) selected @endif>{{$name->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="trade_lincese_no">Trade License No</label>
                                    <input type="text" class="form-control" value="{{ $editData->trade_lincese_no }}" name="trade_lincese_no" id="trade_lincese_no" >

                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="incorporation_no">Incorporation No (if any)</label>
                                    <input type="text" class="form-control" value="{{ $editData->incorporation_no }}" name="incorporation_no" id="incorporation_no" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="etin">E-TIN</label>
                                    <input type="text" class="form-control" value="{{ $editData->etin }}" name="etin" id="etin" >

                                </div>
                            </div>

                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="bin">BIN</label>
                                    <input type="text" class="form-control"  value="{{ $editData->bin }}" name="bin" id="bin" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="email">Email
                                    </label>
                                    <input type="text" class="form-control" value="{{ $editData->email }}" name="email" id="email" >

                                </div>
                            </div>

                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="contact_person_name">Contact Person Name</label>
                                    <input type="text" class="form-control" value="{{ $editData->contact_person_name }}" name="contact_person_name" id="contact_person_name" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="contact_person_phone">Contact Person Phone No</label>
                                    <input type="text" class="form-control" value="{{ $editData->contact_person_phone }}"  name="contact_person_phone" id="contact_person_phone" >
                                </div>
                            </div>

                                <div class="row">

                                     <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_remarks">Customer Remarks</label>
                                    <input type="text" class="form-control" value="{{ $editData->customer_remarks }}" name="customer_remarks" id="customer_remarks" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="contact_person_phone">Designation:</label>
                                    <input type="text" class="form-control" value="{{ $editData->designation }}" name="designation" id="designation" >
                                </div>
                            </div>
                            <div class="row">
                               <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="vat_exemption">Any VAT Exemption</label>
                                    <select style="padding:7px;" class="form-select" name="vat_exemption" id="vat_exemption">
                                        <option value="">Select</option>
                                        <option value="Yes" @if('Yes'==$editData->vat_exemption) selected @endif>Yes</option>
                                        <option value="No" @if('No'==$editData->vat_exemption) selected @endif>No</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="black_listed">&nbsp; </label>
                                    <label for="black_listed">Black Listed:  </label> <br>
                                     <select style="padding:7px;" class="form-select" name="black_listed" id="black_listed">
                                        <option value="">Select</option>
                                     <option value="Yes" @if('Yes'==$editData->black_listed) selected @endif>Yes</option>
                                        <option value="No" @if('No'==$editData->black_listed) selected @endif>No</option>
                                    </select>


                                </div>
                            </div>

                             <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="type">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="1" @if('1'==$editData->status) selected @endif>Active</option>
                                        <option value="2" @if('2'==$editData->status) selected @endif>In-Active</option>
                                    </select>

                                </div>

                            </div>
                            </span>

                            {{--                            <div class="row">--}}
                            {{--                                <div style="font-weight: 700;font-size: 19px;" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}

                            {{--                                Contract Information--}}
                            {{--                                </div>--}}
                            {{--                                <hr>--}}

                            {{--                            </div>--}}
                            <span id="cus_contact" style="display: none;">
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="contact_no">Contact No</label>
                                    <input type="text" class="form-control" value="{{ $editData->contact_no }}" name="contact_no" id="contact_no" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="contact_date">Contact Date</label>
                                    <input type="text" class="form-control" value="{{ $editData->contact_date }}" name="contact_date" id="contact_date" >
                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="contact_s_date">Contact Start Date</label>
                                    <input type="text" class="form-control" value="{{ $editData->contact_s_date }}" name="contact_s_date" id="contact_s_date" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="renewal_date">Renewal Date</label>
                                    <input type="text" class="form-control" value="{{ $editData->renewal_date }}" name="renewal_date" id="renewal_date" >
                                </div>
                            </div>

                             <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="contact_closure_date">Contact Closure Date</label>
                                    <input type="text" class="form-control" value="{{ $editData->contact_closure_date }}" name="contact_closure_date" id="contact_closure_date" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="advance_deposit">Advance Deposit</label>
                                    <input type="text" onkeypress=" filterKeyNumber(this)" class="form-control" value="{{ $editData->advance_deposit }}" name="advance_deposit" id="advance_deposit" >
                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="security_deposit">Security Deposit</label>
                                    <input type="text" class="form-control" onkeypress="filterKeyNumber(this)"  value="{{ $editData->security_deposit }}" name="security_deposit" id="security_deposit" >
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="adj_adv_deposit">Monthly adj. of Advance Deposit</label>
                                    <input type="text" class="form-control"  onkeypress="filterKeyNumber(this)"   value="{{ $editData->adj_adv_deposit }}" name="adj_adv_deposit" id="adj_adv_deposit" >
                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="adj_effective_from">Adj. effective from</label>
                                    <input type="text" class="form-control" value="{{ $editData->adj_effective_from }}" name="adj_effective_from" id="adj_effective_from" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="adj_closure_date">Adj. closure date</label>
                                    <input type="text" class="form-control" value="{{ $editData->adj_closure_date }}" name="adj_closure_date" id="adj_closure_date" >
                                </div>
                            </div>


                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="credit_period">Credit Period</label>
                                    <input type="text" class="form-control" name="credit_period" id="credit_period" value="{{ $editData->credit_period }}">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="contact_owner_name">Owner Name</label>
                                    <select class="form-control select2" name="contact_owner_name" id="contact_owner_name">
                                        <option value="">Select</option>
                                        @foreach($ownerName as $name)
                                            <option value="{{$name->id}}" @if($name->id==$editData->contact_owner_name) selected @endif>{{$name->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="area_sft">Area (sft)</label>
                                    <input type="text" class="form-control" onkeypress="filterKeyNumber(this)" value="{{ $editData->area_sft }}"  name="area_sft" id="area_sft" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="billing_system">Rate/ sft</label>
                                    <input type="text" class="form-control" value="{{ $editData->rent_sft }}"  name="rent_sft" id="rent_sft" >
                                </div>
                            </div>
                                <div class="row">



                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="billing_system">Billing System</label>
                                    <input type="text" class="form-control" value="{{ $editData->billing_system }}" name="billing_system" id="billing_system" >
                                </div>
                                     <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="service_charge">Assigned Ledger</label>
                                   <select class="form-control select2" name="ledger" id="ledger">
                                        <option value="">Select</option>
                                        @foreach($ledger as $name)
                                           <option value="{{$name->id}}" @if($name->id==$editData->ledger_id) selected @endif>{{$name->head}}</option>
                                       @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">



                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="billing_system">Opening Balance</label>
                                    <input type="text" class="form-control" value="{{ $editData->opening_balance }}" name="opening_balance" id="opening_balance" >
                                </div>
                            </div>

                            </span>


                            <span id="cus_rent" style="display: none">

                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="monthly_rent">Prior Monthly Rent</label>
                                    <input type="text" class="form-control"  onkeypress="filterKeyNumber(this)" value="{{ $editData->monthly_rent }}" name="monthly_rent" id="monthly_rent" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="renewal_rent">Current Monthly Rent</label>
                                    <input type="text" class="form-control"  onkeypress="filterKeyNumber(this)" value="{{ $editData->renewal_rent }}" name="renewal_rent" id="renewal_rent" >
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="adj_effective_from">Rate Effective From</label>
                                    <input type="text" class="form-control" value="{{ $editData->rate_effective_from }}" name="rate_effective_from" id="rate_effective_from" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="rate_effective_to">Rate Effective To</label>
                                    <input type="text" class="form-control" value="{{ $editData->rate_effective_to }}" name="rate_effective_to" id="rate_effective_to" >
                                </div>
                            </div>

                                  <div class="row">
                                      <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="service_charge">Service Charges per sft</label>
                                        <input type="text" class="form-control" onkeypress="filterKeyNumber(this)" value="{{ $editData->service_charge }}" name="service_charge" id="service_charge" >
                                    </div>
                                     <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="sc_fine">SC Fine after due date</label>
                                        <input type="text" class="form-control" onkeypress="filterKeyNumber(this)" value="{{ $editData->sc_fine }}"  name="sc_fine" id="sc_fine" >
                                    </div>
                                </div>
                             <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="sc_rate_effective_from">SC Rate Effective From</label>
                                    <input type="text" class="form-control" value="{{ $editData->sc_rate_effective_from }}" name="sc_rate_effective_from" id="sc_rate_effective_from" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="sc_rate_effective_date_to">SC Rate Effective To</label>
                                    <input type="text" class="form-control" value="{{ $editData->sc_rate_effective_date_to }}" name="sc_rate_effective_date_to" id="sc_rate_effective_date_to" >
                                </div>
                            </div>

                             <div class="row">
                                      <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="sc_interest_rate">Interest Rate on SC</label>
                                        <input type="text" class="form-control" onkeypress="filterKeyNumber(this)"  value="{{ $editData->sc_interest_rate }}" name="sc_interest_rate" id="sc_interest_rate" >
                                    </div>
                                     <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="food_court_sc">Food Court SC</label>
                                        <input type="text" class="form-control" onkeypress="filterKeyNumber(this)" value="{{ $editData->food_court_sc }}" name="food_court_sc" id="food_court_sc" >
                                    </div>
                             </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="food_rate_effective_from">Rate Effective From</label>
                                    <input type="text" class="form-control" value="{{ $editData->food_rate_effective_from }}" name="food_rate_effective_from" id="food_rate_effective_from" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="food_rate_effective_to">Rate Effective To</label>
                                    <input type="text" class="form-control" value="{{ $editData->food_rate_effective_to }}" name="food_rate_effective_to" id="food_rate_effective_to" >
                                </div>
                            </div>

                              <div class="row">
                                      <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="special_sc">Special SC</label>
                                        <input type="text" class="form-control" onkeypress="filterKeyNumber(this)" value="{{ $editData->special_sc }}"  name="special_sc" id="special_sc" >
                                    </div>
                                     <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="rate_effective_special_sc_from">Rate effective From</label>
                                        <input type="text" class="form-control"  value="{{ $editData->rate_effective_special_sc_from }}" name="rate_effective_special_sc_from" id="rate_effective_special_sc_from" >
                                    </div>
                             </div>

                             <div class="row">
                                      <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="rate_effective_special_sc_to">Rate effective to</label>
                                        <input type="text" class="form-control"  value="{{ $editData->rate_effective_special_sc_to }}" name="rate_effective_special_sc_to" id="rate_effective_special_sc_to" >
                                    </div>
                                     <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="advertisement">Advertisement</label>
                                        <input type="text" class="form-control"  onkeypress="filterKeyNumber(this)"   value="{{ $editData->advertisement }}" name="advertisement" id="advertisement" >
                                    </div>
                             </div>

                             <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="advertisement_rate_effective_from">Rate Effective From</label>
                                    <input type="text" class="form-control" value="{{ $editData->advertisement_rate_effective_from }}" name="advertisement_rate_effective_from" id="advertisement_rate_effective_from" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="advertisement_rate_effective_to">Rate Effective To</label>
                                    <input type="text" class="form-control" value="{{ $editData->advertisement_rate_effective_to }}" name="advertisement_rate_effective_to" id="advertisement_rate_effective_to" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="electricity_rate_unit">Electricity Rate per Unit</label>
                                    <input type="text" class="form-control" value="{{ $editData->electricity_rate_unit }}" name="electricity_rate_unit" id="electricity_rate_unit" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="electricity_meter_no">Electricity Meter No</label>
                                    <input type="text" class="form-control" value="{{ $editData->electricity_meter_no }}" name="electricity_meter_no" id="electricity_meter_no" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="electricity_meter_reading">Electricity Meter Reading OP Bal.</label>
                                    <input type="text" class="form-control" value="{{ $editData->electricity_meter_reading }}" name="electricity_meter_reading" id="electricity_meter_reading" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="electricity_fine_rate">Electricity Fine Rate</label>
                                    <input type="text" class="form-control" value="{{ $editData->electricity_fine_rate }}" name="electricity_fine_rate" id="electricity_fine_rate" >
                                </div>
                            </div>

                        </span>



                            <br>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-sm btn-success float-right">Update</button>
                                        <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addOwner','Add Owner Info','Do you really want to reset this form?');return false;">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('uncommonExJs')
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
@endsection
@section('uncommonInJs')
    <script>
        (function() {
            "use strict";
            jqiueryCalendar('contact_date');
            jqiueryCalendar('contact_s_date');
            jqiueryCalendar('renewal_date');
            jqiueryCalendar('contact_closure_date');
            jqiueryCalendar('adj_closure_date');
            jqiueryCalendar('adj_effective_from');
            jqiueryCalendar('rate_effective_from');
            jqiueryCalendar('rate_effective_to');
            jqiueryCalendar('sc_rate_effective_from');
            jqiueryCalendar('sc_rate_effective_date_to');
            jqiueryCalendar('food_rate_effective_from');
            jqiueryCalendar('food_rate_effective_to');
            jqiueryCalendar('rate_effective_special_sc_from');
            jqiueryCalendar('rate_effective_special_sc_to');
            jqiueryCalendar('advertisement_rate_effective_from');
            jqiueryCalendar('advertisement_rate_effective_to');



            // set_parent_type('thana');
        })(jQuery);
        function showTabPage(ref) {

            if(ref=='cus_basic'){
                $(".cus").html('Basic Info');
                $("#cus_basic").show();
                $("#cus_contact").hide();
                $("#cus_rent").hide();
                $(".nav li a").removeClass('active');
                $(".cusBasicInfo").addClass('active');

            }else if(ref == 'cus_contact'){
                $(".cus").html('Contract Info');
                $("#cus_basic").hide();
                $("#cus_contact").show();
                $("#cus_rent").hide();
                $(".nav li a").removeClass('active');
                $(".cusContuct").addClass('active');

            }else if(ref == 'cus_rent'){
                $(".card-title").html('Sales/ Rental Info');
                $("#cus_basic").hide();
                $("#cus_contact").hide();
                $("#cus_rent").show();
                $(".nav li a").removeClass('active');
                $(".cusRent").addClass('active');
            }

        }
        function filterKeyNumber(ref_v) {

            if (((event.which != 46 || (event.which == 46 && $(ref_v).val() == '')) ||
                $(ref_v).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }

        }

        function jqiueryCalendar (ref_id) {
            return new Pikaday({
                field: $('#'+ref_id)[0] ,
                firstDay: 1,
                format: 'YYYY-MM-DD',
                toString: function (date, format) {
                    var day   = date.getDate();
                    var month = date.getMonth() + 1;
                    var year  = date.getFullYear();

                    var yyyy = year;
                    var mm   = ((month > 9) ? '' : '0') + month;
                    var dd   = ((day > 9)   ? '' : '0') + day;

                    return yyyy + '-' + mm + '-' + dd;
                },
                position: 'bottom right',
                minDate: new Date('1900-01-01'),
                maxDate: new Date('2040-12-31'),
                yearRange: [1900, 2040]
            });
        }
        function resetForm(id,header,body,okMessage='From reset successful.',cancelMessage=null) {
            alertify.confirm('<strong>'+header+'</strong>',body,
                function(){
                    document.getElementById(id).reset();
                    if(okMessage){
                        alertify.success(okMessage);
                    }
                },
                function(){
                    if(cancelMessage){
                        alertify.success(cancelMessage);
                    }
                });
        }


    </script>
@endsection
