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
                        <h5 class="card-title" style="font-weight: 700;font-size: 19px;">Vendor/ Party Information
                        </h5>
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-item nav-link active basicInfo" onclick="showTabPage('basic');" data-toggle="tab" >Vendor/ Party</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bankInfo" onclick="showTabPage('bank');" data-toggle="tab" >Bank Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link regulatoryInfo" onclick="showTabPage('regulatory');" data-toggle="tab" >Regulatory Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link taxVat"  onclick="showTabPage('tax-vat');" data-toggle="tab" >Tax-Vat Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link supplyInfo" onclick="showTabPage('supply');" data-toggle="tab" >Supply/Product Info</a>
                            </li>
                        </ul>


                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addVendor" action="{{route('vendor.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <span id="basic">


                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="shop_name">Vendor Name</label>
                                    <input type="text" class="form-control" value="{{ $editData->vendor_name }}" name="vendor_name" id="vendor_name" required>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_address">Address</label>
                                    <input type="text" class="form-control" value="{{ $editData->owner_address }}" name="owner_address" id="owner_address" >

                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="region">Region</label>
                                    <select class="form-control select2" name="region_id" id="region_id">
                                        <option value="">Select</option>
                                        @foreach($division as $name)
                                            <option value="{{$name->id}}" @if($name->id==$editData->region_id) selected @endif>{{$name->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="supplier_type">Supplier Type</label>
                                    <input type="text" class="form-control" value="{{ $editData->supplier_type }}" name="supplier_type" id="supplier_type" >
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
                                    <input type="text" class="form-control" value="{{ $editData->contact_person_phone }}" name="contact_person_phone" id="contact_person_phone" >
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="type">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="1" @if(1 == $editData->status) selected @endif>Active</option>
                                        <option value="2" @if(2 == $editData->status) selected @endif>In-Active</option>
                                    </select>

                                </div>

                            </div>
</span>
                            <span id="bank" style="display: none">


                            {{--<div class="row">--}}
                                {{--<div style="font-weight: 700;font-size: 19px;" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}

                                {{--Bank Information--}}
                                {{--</div>--}}
                                {{--<hr>--}}

                                {{--</div>--}}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="payment_method">Mode of Payment</label>
                                    <select class="form-control select2" name="payment_method" id="payment_method">
                                        <option value="">Select</option>
                                        <option value="EFT" @if('EFT' == $editData->payment_method) selected @endif>EFT</option>
                                        <option value="Cheque" @if('Cheque' == $editData->payment_method) selected @endif>Cheque</option>
                                        <option value="Cash" @if('Cash' == $editData->payment_method) selected @endif>Cash</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="bank_account_title">Bank Account Title</label>
                                    <input type="text" class="form-control" value="{{ $editData->bank_account_title }}" name="bank_account_title" id="bank_account_title" >
                                </div>
                            </div>
                            <div class="row">



                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="bank_name">Bank Name</label>
                                    <input type="text" class="form-control" value="{{ $editData->bank_name }}" name="bank_name" id="bank_name" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="branch_name">Branch Name</label>
                                    <input type="text" class="form-control" value="{{ $editData->branch_name }}" name="branch_name" id="account_no" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="account_no">Account Number</label>
                                    <input type="text" class="form-control" value="{{ $editData->account_no }}" name="account_no" id="account_no" >
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="routing_number">Routing Number</label>
                                    <input type="text" class="form-control" value="{{ $editData->routing_number }}" name="routing_number" id="routing_number" >
                                </div>

                            </div>
                                </span>
                            <span id="regulatory" style="display: none;">


                            {{--<div class="row">--}}
                                {{--<div style="font-weight: 700;font-size: 19px;" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}

                                {{--Regulatory Info--}}
                                {{--</div>--}}
                                {{--<hr>--}}

                                {{--</div>--}}

                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="trade_lincese_no">Trade License No</label>
                                    <input type="text" class="form-control" value="{{ $editData->trade_lincese_no }}" name="trade_lincese_no" id="trade_lincese_no" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="trade_lincese_no">Validity:</label>
                                <input type="text" class="form-control" value="{{ $editData->validity }}" name="validity" id="validity" >

                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="incorporation_no">Incorporation No (if any)</label>
                                    <input type="text" class="form-control" value="{{ $editData->incorporation_no }}" name="incorporation_no" id="incorporation_no" >
                                </div>
                            </div>
                            </span>

                            <span id="tax-vat" style="display: none;">
                            {{--<div class="row">--}}
                                {{--<div style="font-weight: 700;font-size: 19px;" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}

                                {{--Tax-Vat Info--}}
                                {{--</div>--}}
                                {{--<hr>--}}
                                {{--</div>--}}
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="etin">E-TIN</label>
                                        <input type="text" class="form-control" value="{{ $editData->etin }}" name="etin" id="etin" >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="tax_exemption">Tax Exemption</label>
                                        <input type="text" class="form-control" value="{{ $editData->tax_exemption }}" name="tax_exemption" id="tax_exemption" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="tds_section">TDS Section</label>
                                        <input type="text" class="form-control" value="{{ $editData->tds_section }}" name="tds_section" id="tds_section" >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="tds_rate">TDS Rate</label>
                                        <input type="text" class="form-control" value="{{ $editData->tds_rate }}" name="tds_rate" id="tds_rate" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="vat_reg">BIN/VAT Reg</label>
                                        <input type="text" class="form-control" value="{{ $editData->vat_reg }}" name="vat_reg" id="vat_reg" >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="service_code">Service Code</label>
                                        <input type="text" class="form-control" value="{{ $editData->service_code }}" name="service_code" id="service_code" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="vat_rate">VAT Rate</label>
                                        <input type="text" class="form-control" value="{{ $editData->vat_rate }}" name="vat_rate" id="vat_rate" >
                                    </div>

                                </div>
                                </span>
                            <span id="supply" style="display: none;">


                            {{--<div class="row">--}}
                                {{--<div style="font-weight: 700;font-size: 19px;" class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}

                                {{--Supply/Product Info--}}
                                {{--</div>--}}
                                {{--<hr>--}}
                                {{--</div>--}}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="service_type">Service Type</label>
                                    <select class="form-control select2" name="service_type" id="service_type" onchange="checkServiceType(this.value)">
                                        <option value="Product" @if('Product' == $editData->service_type) selected @endif>Product</option>
                                        <option value="Service" @if('Service' == $editData->service_type) selected @endif>Service</option>
                                    </select>

                                </div>
                            </div>
                            <div class="row pro">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="brand_name_1">Brand Name 1</label>
                                    <input type="text" class="form-control" value="{{ $editData->brand_name_1 }}" name="brand_name_1" id="brand_name_1" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="brand_name_2">Brand Name 2</label>
                                    <input type="text" class="form-control" value="{{ $editData->brand_name_2 }}" name="brand_name_2" id="brand_name_2" >
                                </div>

                            </div>
                            <div class="row pro">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="brand_name_3">Brand Name 3</label>
                                    <input type="text" class="form-control" value="{{ $editData->brand_name_3 }}" name="brand_name_3" id="brand_name_3" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="brand_name_4">Brand Name 4</label>
                                    <input type="text" class="form-control" value="{{ $editData->brand_name_4 }}" name="brand_name_4" id="brand_name_4" >
                                </div>

                            </div>
                                <div class="row pro">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="brand_name_5">Brand Name 5</label>
                                    <input type="text" class="form-control" value="{{ $editData->brand_name_5 }}" name="brand_name_5" id="brand_name_5" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="brand_name_6">Brand Name 6</label>
                                    <input type="text" class="form-control" name="brand_name_6" value="{{ $editData->brand_name_6 }}" id="brand_name_6" >
                                </div>

                            </div>
                            <div class="row ser" style="display: none">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="service_name">Service Name</label>
                                    <input type="text" class="form-control" value="{{ $editData->service_name }}" name="service_name" id="service_name" >
                                </div>

                            </div>


                        </span>
                            <div class="card-footer" style="margin-top: 20px;">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
                                        <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addVendor','Add Owner Info','Do you really want to reset this form?');return false;">Reset</button>
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
            jqiueryCalendar('rate_effective_date');
            jqiueryCalendar('validity');
            // set_parent_type('thana');
        })(jQuery);
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

        function showTabPage(ref) {

            if(ref=='basic'){
                $(".card-title").html('Vendor/ Party Information');
                $("#basic").show();
                $("#regulatory").hide();
                $("#bank").hide();
                $("#tax-vat").hide();
                $("#supply").hide();
                $(".nav li a").removeClass('active');
                $(".basicInfo").addClass('active');

            }else if(ref == 'regulatory'){
                $(".card-title").html('Regulatory Information');
                $("#basic").hide();
                $("#regulatory").show();
                $("#bank").hide();
                $("#tax-vat").hide();
                $("#supply").hide();
                $(".nav li a").removeClass('active');
                $(".regulatoryInfo").addClass('active');

            }else if(ref == 'bank'){
                $(".card-title").html('Bank Information');
                $("#basic").hide();
                $("#regulatory").hide();
                $("#bank").show();
                $("#tax-vat").hide();
                $("#supply").hide();
                $(".nav li a").removeClass('active');
                $(".bankInfo").addClass('active');
            }else if(ref == 'tax-vat'){
                $(".card-title").html('Tax-Vat Information');
                $("#basic").hide();
                $("#regulatory").hide();
                $("#bank").hide();
                $("#tax-vat").show();
                $("#supply").hide();
                $(".nav li a").removeClass('active');
                $(".taxVat").addClass('active');
            }else if(ref == 'supply'){
                $(".card-title").html('Supply/Product Info');
                $("#basic").hide();
                $("#regulatory").hide();
                $("#bank").hide();
                $("#tax-vat").hide();
                $("#supply").show();
                $(".nav li a").removeClass('active');
                $(".supplyInfo").addClass('active');
            }

        }


        function checkServiceType(val) {
            if(val=='Product'){
                $(".pro").show();
                $(".ser").hide();
            }else{
                $(".pro").hide();
                $(".ser").show();
            }

        }
    </script>
@endsection
