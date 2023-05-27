@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Asset Info</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addGroupAccount" action="{{route('assets.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Asset No.</label>
                                    <input type="text" value="{{$editData->asset_no}}" id="asset_no" name="asset_no"  class="form-control" required="required">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Shop/Office Name</label>
                                    <select class="form-control select2" name="customer_id" id="customer_id">
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}" @if($row->id==$editData->customer_id) selected @endif >{{$row->shop_name}}</option>
                                        @endforeach
                                    </select>

                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Floor Name</label>
                                    <select class="form-control select2" name="floor_name" id="floor_name">
                                        <option value="">None</option>
                                        @foreach($floor as $row)
                                            <option value="{{$row->name}}" @if($row->name==$editData->floor_name) selected @endif>{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Area (Sft)</label>
                                    <input type="text" value="{{$editData->area_sft}}" id="area_sft" name="area_sft"  class="form-control" >
                                </div>

                            </div>

                            <div class="row" style="display: none;">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Electricity Meter Number</label>
                                    <input class="form-control" value="{{$editData->meter_no}}" name="meter_no" id="meter_no">

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Electricity Meter Opening Reading </label>
                                    <input type="text" value="{{$editData->opening_reading}}" id="opening_reading" name="opening_reading"  class="form-control" >
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Contract/Deed Start Date </label>
                                    <input class="form-control" autocomplete="off" value="{{$editData->date_s}}" name="date_s" id="date_s">

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Contract/Deed End Date </label>
                                    <input type="text" id="date_e" autocomplete="off" value="{{$editData->date_e}}" name="date_e"  class="form-control" >
                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_id">Owner Name</label>
                                    <select class="form-control select2" name="owner_id" id="owner_id">
                                        <option value="">None</option>
                                        @foreach($owner as $row)
                                            <option value="{{$row->id}}" @if($row->id==$editData->owner_id) selected @endif>{{$row->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="Allotted & Open" @if('Allotted & Open'==$editData->status) selected @endif>Allotted & Open</option>
                                        <option value="Allotted & Closed" @if('Allotted & Closed'==$editData->status) selected @endif>Allotted & Closed</option>
                                        <option value="Un-allotted" @if('Un-allotted'==$editData->status) selected @endif>Un-allotted</option>
                                    </select>

                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Service Charge Applicable</label>
                                    <select class="form-control select2" name="service_charge_status" id="service_charge_status">
                                        <option value="Yes" @if('Yes'==$editData->service_charge_status) selected @endif>Yes</option>
                                        <option value="No" @if('No'==$editData->service_charge_status) selected @endif>No</option>

                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Food Court SC Applicable</label>
                                    <select class="form-control select2" name="food_court_status" id="food_court_status">
                                        <option value="Yes" @if('Yes'==$editData->food_court_status) selected @endif>Yes</option>
                                        <option value="No" @if('No'==$editData->food_court_status) selected @endif>No</option>

                                    </select>

                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rate (Sft) </label>
                                    <input type="text" id="rate" name="rate" value="{{$editData->rate}}"  class="form-control" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rent Increment Rate </label>
                                    <input type="text" id="rent_increment" value="{{$editData->rent_increment}}" name="rent_increment"  class="form-control" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="off_type">Type</label>
                                    <select class="form-control select2" name="off_type" id="off_type">
                                        <option value="Shop" @if('Shop'==$editData->off_type) selected @endif>Shop</option>
                                        <option value="Office" @if('Office'==$editData->off_type) selected @endif>Office</option>
                                        <option value="Adv" @if('Adv'==$editData->off_type) selected @endif>Adv</option>
                                        <option value="Others" @if('Others'==$editData->off_type) selected @endif>Others</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Vat </label>
                                    <input type="text" id="vat" value="{{$editData->vat}}" name="vat"  class="form-control" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rate Increment Interval Months</label>
                                    <input type="number" value="{{$editData->increment_effective_month}}" id="increment_effective_month" name="increment_effective_month"  class="form-control" >
                                </div>
                            </div>
                            <br>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button id="btnEditAssetInfo" onclick="AssetInfo.submitEditValue()" type="button" class="btn btn-sm btn-success float-right">Update</button>
                                        <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addGroupAccount','Add Group Account Form','Do you really want to reset this form?');return false;">Reset</button>
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
            jqueryCalendar('date_s');
            jqueryCalendar('date_e');
        })(jQuery);

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

        let AssetInfo = function (){
            let submitEditValue = function (){
                if($("#date_s").val()==''){
                    alert("Please Enter Contract/Deed Start Date");
                    return false;
                }
                if($("#date_e").val()==''){
                    alert("Please Enter Contract/Deed End Date");
                    return false;
                }
                if($("#customer_id").val()==''){
                    alert("Please Select Customer");
                    return false;
                }
                $("#btnEditAssetInfo").attr('type', 'submit');
            }
            return {
                submitEditValue: submitEditValue
            }

        }();
    </script>
@endsection
