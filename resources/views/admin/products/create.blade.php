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
                        <h5 class="card-title" style="font-weight: 700;font-size: 19px;">Product Information
                        </h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-product'))
                                <a href="{{route('product.create')}}" class="btn btn-sm btn-default pull-right"><span class="fa fa-plus-circle"></span> Add Product Info</a>
                            @endif
                        </div>
                    </div>
                    <form id="addOwner" action="{{route('product.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="service_type">Service Type</label>
                                    <select  class="form-control select2" name="service_type" id="service_type" onchange="this.value=='Product'?($('.pro').show(),$('.ser').hide()):($('.ser').show(),$('.pro').hide())">
                                        <option value="Product">Product</option>
                                        <option value="Service">Service</option>
                                    </select>

                                </div>
                            </div>
                            <div class="row ">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="vendor_id">Vendor</label>
                                    <select class="form-control select2" name="vendor_id" id="vendor_id" onchange="getVendorInfo(this.value);">
                                        <option value="">None</option>
                                        @foreach($vendor as $name)
                                            <option value="{{$name->id}}">{{$name->vendor_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="product_name">Product/Service Name</label>
                                    <input type="text" class="form-control" name="product_name" id="product_name" required>
                                    <input type="hidden" name="vendor_name" id="vendor_name">
                                </div>
                            </div>
                            <div class="row pro">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="size">Size</label>
                                    <input type="text" class="form-control" name="size" id="size" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="unit_id">Unit</label>
                                    <select class="form-control select2" name="unit_id" id="unit_id">
                                        <option value="">None</option>
                                        @foreach($unit as $name)
                                            <option value="{{$name->id}}">{{$name->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row pro">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="brand_name">Brand Name</label>
                                    <select class="form-control select2" name="brand_name" id="brand_name">
                                        <option value="">None</option>

                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="regular_price">Regular Price</label>
                                    <input type="text" onkeypress="return filterKeyNumber(this,event,'r_error')" class="form-control" name="regular_price" id="regular_price" >
                                    <p id="r_error" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>

                                </div>
                            </div>
                            <div class="row pro">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="discounted_price">Discounted Price</label>
                                    <input type="text" onkeypress=" return filterKeyNumber(this,event,'sp_error')" class="form-control" name="discounted_price" id="discounted_price" >
                                    <p id="sp_error" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>

                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="rate_effective_date">Rate effective Date</label>
                                    <input type="text" class="form-control" name="rate_effective_date" id="rate_effective_date" >

                                </div>
                            </div>
                            <div class="row ">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="vds_section">VDS Section</label>
                                    <input type="text" class="form-control" name="vds_section" id="vds_section" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="vds_rate">VDS Rate</label>
                                    <input type="text" onkeypress="return filterKeyNumber(this,event,'vrate_error')" class="form-control" name="vds_rate" id="vds_rate" >
                                    <p id="vrate_error" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>

                                </div>
                            </div>
                            <div class="row ">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="vds_head">VDS Head</label>
                                    <select class="form-control select2" name="vds_head" id="vds_head" >
                                        <option value="">None</option>

                                    </select>                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="tds_section">TDS Section</label>
                                    <input type="text" class="form-control" name="tds_section" id="tds_section" >
                                </div>
                            </div>

                            <div class="row ">



                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="tds_rate">TDS Rate
                                    </label>
                                    <input type="text" onkeypress="return filterKeyNumber(this,event,'trate_error')" class="form-control" name="tds_rate" id="tds_rate" >
                                    <p id="trate_error" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="tds_head">TDS Head</label>
                                    <select class="form-control select2" name="tds_head" id="tds_head" >
                                        <option value="">None</option>

                                    </select>

                                </div>
                            </div>

                            <div class="row ">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="effective_date_from">Effective Date From</label>
                                    <input type="text" class="form-control" name="effective_date_from" id="effective_date_from" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="effective_date_to">Effective Date To</label>
                                    <input type="text" class="form-control" name="effective_date_to" id="effective_date_to" >
                                </div>
                            </div>
                            <div class="row ">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="assigned_ledger">Assigned Ledger</label>
                                    <select class="form-control select2" name="assigned_ledger" id="assigned_ledger" >
                                        <option value="">None</option>
                                        @foreach($ledger as $row)
                                            <option value="{{$row->id}}">{{$row->sub_category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="1">Active</option>
                                        <option value="2">In-Active</option>
                                    </select>

                                </div>
                            </div>
                            <div class="row pro">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="opening_balance">Opening Balance</label>
                                    <input type="text" onkeypress=" return filterKeyNumber(this,event,'b_error')" class="form-control" name="opening_balance" id="opening_balance" >
                                </div>
                                <p id="b_error" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>



                            </div>

                            <p></p>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
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
            jqueryCalendar('rate_effective_date');
            jqueryCalendar('effective_date_from');
            jqueryCalendar('effective_date_to');

        })(jQuery);


        function jqueryCalendar (ref_id) {
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

    function getVendorInfo(id) {
        $('#vendor_name').val($('#vendor_id').find('option:selected').text());
        $.ajax({
            url: "get-vendor-info/"+id,
            type: 'GET',
            dataType: 'JSON',
            success: function (response)
            {

                if(response)
                {
                    console.log(response.brand);
                    if(response.brand){
                        html = '<option value="0"> None </option>';
                        Object.keys(response.brand).forEach((el)=>{
                            html+="<option value='"+response.brand[el]+"'>"+response.brand[el]+"</option>";

                        });
                        $('#brand_name').html(html);
                    }
                    $("#product_name").val(response.vendor.service_name);
                    $("#vds_rate").val(response.vendor.vds_rate);
                    $("#vds_section").val(response.vendor.vds_section);
                    $("#tds_rate").val(response.vendor.tds_rate);
                    $("#tds_section").val(response.vendor.tds_section);



                }
            }
        });
    }
    </script>
@endsection
