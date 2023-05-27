@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Add Asset Info</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addGroupAccount" action="{{route('assets.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Asset No.</label>
                                    <input type="text" id="asset_no" name="asset_no" class="form-control"
                                           required="required">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Shop Name</label>
                                    <input type="text" id="shop_name" name="shop_name" class="form-control"
                                          >
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Customer/Office Name</label>
                                    <select class="form-control select2" name="customer_id" id="customer_id">
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}">{{$row->shop_name}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Floor Name</label>
                                    <select class="form-control select2" name="floor_name" id="floor_name">
                                        <option value="">None</option>
                                        @foreach($floor as $row)
                                            <option value="{{$row->name}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>



                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Area (Sft)</label>
                                    <input type="text" id="area_sft" name="area_sft" class="form-control">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rent Rate (Sft) </label>
                                    <input type="text" id="rate" name="rate" class="form-control">
                                </div>
                            </div>
                            <div class="row" style="display: none;">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Electricity Meter Number</label>
                                    <input class="form-control" name="meter_no" id="meter_no">

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Electricity Meter Opening Reading </label>
                                    <input type="text" id="opening_reading" name="opening_reading" class="form-control">
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Bill Start Date </label>
                                    <input class="form-control" autocomplete="off" name="date_s" id="date_s">

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Bill End Date </label>
                                    <input type="text" id="date_e" autocomplete="off" name="date_e"
                                           class="form-control">
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Allotment Start date</label>
                                    <input id="contact_s_date" name="contact_s_date" class="form-control">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="advance_deposit">Advance Deposit Effective From</label>
                                    <input type="text" autocomplete="off" class="form-control"
                                           name="advance_deposit_date" id="advance_deposit_date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label"> Last increment date</label>
                                    <input id="last_increment_date" name="last_increment_date" class="form-control">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rent Increment Rate </label>
                                    <input type="text" id="rent_increment" name="rent_increment" class="form-control">
                                </div>
                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_id">Owner Name</label>
                                    <select class="form-control select2" name="owner_id" id="owner_id">
                                        <option value="">None</option>
                                        @foreach($owner as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="Allotted & Open">Allotted & Open</option>
                                        <option value="Allotted & Closed">Allotted & Closed</option>
                                        <option value="Un-allotted">Un-allotted</option>
                                    </select>

                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Service Charge Applicable</label>
                                    <select class="form-control select2" name="service_charge_status"
                                            id="service_charge_status">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>

                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Food Court SC Applicable</label>
                                    <select class="form-control select2" name="food_court_status"
                                            id="food_court_status">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>

                                    </select>

                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Service Charge Rate </label>
                                    <input type="text" id="sc_rate" name="sc_rate" class="form-control">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Food Court SC Rate</label>
                                    <input type="text" id="food_court_rate" name="food_court_rate" class="form-control">
                                </div>
                            </div>
                            <div class="row" style="display:none;">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Service Charge Start Date </label>
                                    <input class="form-control" autocomplete="off" name="service_date_s" id="service_date_s">

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Service Charge End Date </label>
                                    <input type="text" id="service_date_e" autocomplete="off" name="service_date_e"
                                           class="form-control">
                                </div>

                            </div>
                            <div class="row" style="display:none;">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Food Court SC Start Date </label>
                                    <input class="form-control" autocomplete="off" name="food_date_s" id="food_date_s">

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Food Court SC End Date </label>
                                    <input type="text" id="food_date_e" autocomplete="off" name="food_date_e"
                                           class="form-control">
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="off_type">Type</label>
                                    <select class="form-control select2" name="off_type" id="off_type">
                                        <option value="Shop">Shop</option>
                                        <option value="Office">Office</option>
                                        <option value="Adv">Adv</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Vat </label>
                                    <input type="text" id="vat" name="vat" class="form-control">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="advance_deposit">Advance Deposit</label>
                                    <input type="text" onkeypress=" filterKeyNumber(this)" class="form-control"
                                           name="advance_deposit" id="advance_deposit">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="security_deposit">Security Deposit</label>
                                    <input type="text" class="form-control" onkeypress="filterKeyNumber(this)"
                                           name="security_deposit" id="security_deposit">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rate Increment Interval Months</label>
                                    <input type="number" id="increment_effective_month" name="increment_effective_month"
                                           class="form-control">
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="parent_asset">Parent Asset No</label>
                                    <select class="form-control select2" name="parent_asset" id="parent_asset">
                                        <option value="">None</option>
                                        @foreach($assets as $row)
                                            <option value="{{$row->asset_no}}">{{$row->asset_no}}</option>
                                        @endforeach
                                    </select></div>

                            </div>
{{--                            <div class="row">--}}
{{--                              --}}
{{--                            </div>--}}
                            <br>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button id="btnAssetInfo" onclick="AssetInfo.submitValue()" type="button"
                                                class="btn btn-sm btn-success float-right">Submit
                                        </button>
                                        <button type="reset" class="btn btn-sm btn-default"
                                                onclick="resetForm('addGroupAccount','Add Group Account Form','Do you really want to reset this form?');return false;">
                                            Reset
                                        </button>
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
        (function () {
            jqueryCalendar('date_s');
            jqueryCalendar('date_e');
            jqueryCalendar('last_increment_date');
            jqueryCalendar('advance_deposit_date');
            jqueryCalendar('food_date_s');
            jqueryCalendar('food_date_e');
            jqueryCalendar('service_date_e');
            jqueryCalendar('service_date_s');
            jqueryCalendar('contact_s_date');

        })(jQuery);

        function resetForm(id, header, body, okMessage = 'From reset successful.', cancelMessage = null) {
            alertify.confirm('<strong>' + header + '</strong>', body,
                function () {
                    document.getElementById(id).reset();
                    if (okMessage) {
                        alertify.success(okMessage);
                    }
                },
                function () {
                    if (cancelMessage) {
                        alertify.success(cancelMessage);
                    }
                });
        }

        let AssetInfo = function () {
            let submitValue = function () {
                if ($("#date_s").val() == '') {
                    alert("Please Enter Contract/Deed Start Date");
                    return false;
                }
                // if($("#date_e").val()==''){
                //     alert("Please Enter Contract/Deed End Date");
                //     return false;
                // }
                if ($("#customer_id").val() == '') {
                    alert("Please Select Customer");
                    return false;
                }
                $("#btnAssetInfo").attr('type', 'submit');
            }
            return {
                submitValue: submitValue
            }

        }();


    </script>
@endsection
