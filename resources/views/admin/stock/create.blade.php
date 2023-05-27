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
                        <h5 class="card-title">Add Stock Item</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addGroupAccount" action="{{route('stock.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <input type="hidden" name="sub_array" id="sub_array" value="" >
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Journal Date</label>
                                    <input class="form-control" name="journal_date" id="journal_date"
                                           value="{{date('Y-m-d')}}" autocomplete="off" required>

                                </div>

                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Due Date</label>
                                    <input class="form-control" name="due_date" id="due_date"
                                           value="{{date('Y-m-d')}}" autocomplete="off" required>

                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Mode of Payment <span
                                            style="color:red">*</span></label>
                                    <select class="form-control select2" name="payment_mode" id="payment_mode" required
                                            onchange="Stock.getPaymentType(this.value)">
                                        <option value="">None</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Creditors">Creditors</option>
                                    </select>

                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Cheque No</label>
                                    <input class="form-control" name="cheque_no" id="cheque_no" value=""
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Ledger Name</label>
                                    <select class="form-control select2" name="ledger_id" id="ledger_id" required>
                                        <option value="">None</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Purchase Ref No</label>
                                    <input class="form-control" name="purchase_ref_no" id="purchase_ref_no" value=""
                                           autocomplete="off">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Store Name</label>
                                    <select class="form-control select2" name="measuring_unit" id="measuring_unit">
                                        <option value="">None</option>
                                        @foreach($godown as $row)
                                            <option value="{{$row->name}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Vendor Name</label>
                                    <select class="form-control select2" name="vendor_id" id="vendor_id" required
                                            onchange="Stock.getVendorBrand(this.value)">
                                        <option value="">None</option>
                                        @foreach($vendor as $row)
                                            <option value="{{$row->id}}">{{$row->vendor_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Product Name</label>
                                    <select class="form-control select2" name="product_id" id="product_id"  >
                                        <option value="">None</option>
                                        @foreach($product as $row)
                                            <option value="{{$row->id}}">{{$row->product_name}}</option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Brand Name</label>
                                    <select class="form-control select2" name="brand_name" id="brand_name">
                                        <option value="">None</option>

                                        {{--                                        @foreach($brand as $row)--}}
                                        {{--                                        <option value="{{$row->id}}">{{$row->name}}</option>--}}
                                        {{--                                        @endforeach--}}
                                    </select>
                                </div>
                                <div style="display: none;" class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="vendor_id">Category Name</label>
                                    <select class="form-control select2" name="product_category" id="product_category"
                                            onchange="Stock.getCategory(this.value,2)">
                                        <option value="">None</option>
                                        @foreach($category as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Size</label>
                                    <select class="form-control select2" name="size" id="size">
                                        <option value="">None</option>
                                        @foreach($size as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Quantity</label>
                                    <input class="form-control" name="qty" id="qty" onblur="Stock.calculateValue(1)"
                                           >

                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rate/Unit </label>
                                    <input type="text" id="rate" name="rate" class="form-control"
                                           onblur="Stock.calculateValue(1)" >
                                </div>
                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Sub Total(Tk.) </label>
                                    <input class="form-control" autocomplete="off" name="sub_total" id="sub_total"
                                           readonly>

                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Vat Rate(%) </label>
                                    <input class="form-control" autocomplete="off" name="vat_rate" id="vat_rate"
                                           onblur="Stock.calculateValue(2)">

                                </div>
                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Total Amount(Tk.) </label>
                                    <input type="text" id="total_amount" readonly autocomplete="off" name="total_amount"
                                           class="form-control">
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Stock Level (Qtn) </label>
                                    <input type="text" id="re_order_label" autocomplete="off" name="re_order_label"
                                           class="form-control">
                                </div>
                            </div>
                            {{--                            <div class="row">--}}

                            {{--                                --}}
                            {{--                            </div>--}}

                            <br>
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="Button" class="btn btn-sm btn-success float-right"
                                            onclick="Stock.add()">Add
                                    </button>
                                </div>
                            </div>
                            <br>
                            <div class="row SsubTbl">
                            </div>
                            <br>
                            <br>
                            <div class="card-footer addSalesFooter">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button id="btnAssetInfo" onclick="Stock.submitValue()" type="button"
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
            jqueryCalendar('journal_date');
            jqueryCalendar('due_date');
            stockArrays = [];

        })(jQuery);
        $(document).ready(function () {

            Stock.show();
        });
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

        let Stock = function () {
            let submitValue = function () {
                if ($("#date_s").val() == '') {
                    alert("Please Enter Contract/Deed Start Date");
                    return false;
                }
                if ($("#date_e").val() == '') {
                    alert("Please Enter Contract/Deed End Date");
                    return false;
                }
                // if ($("#customer_id").val() == '') {
                //     alert("Please Select Customer");
                //     return false;
                // }

                stockArrays = JSON.parse(window.localStorage.getItem('stockArrays'));
                $("#sub_array").val(JSON.stringify(stockArrays));
                if(stockArrays.length==0){
                    alert("Please Add Product");
                    return false;
                }
                window.localStorage.removeItem('stockArrays');
                $("#btnAssetInfo").attr('type', 'submit');
            }
            let calculateValue = (v) => {
                let qty = 0
                let rate = 0;
                let vat_rate = 0;
                if (v == 1) {
                    if ($("#qty").val() != '') {
                        qty = parseFloat($("#qty").val());
                    }
                    if ($("#rate").val() != '') {
                        rate = parseFloat($("#rate").val());
                    }
                    $("#sub_total").val((qty * rate).toFixed(2));
                    $("#total_amount").val((qty * rate).toFixed(2));
                } else if (v == 2) {

                    if ($("#qty").val() != '') {
                        qty = parseFloat($("#qty").val());
                    }
                    if ($("#rate").val() != '') {
                        rate = parseFloat($("#rate").val());
                    }
                    if ($("#vat_rate").val() != '') {
                        vat_rate = parseFloat($("#vat_rate").val());
                    }
                    if (vat_rate != 0) {
                        let total = (qty * rate).toFixed(2);
                        let vqt = ((total * vat_rate) / 100).toFixed(2);
                        $("#total_amount").val((parseFloat(vqt) + parseFloat(total)).toFixed(2));
                    } else {
                        let total = (qty * rate).toFixed(2);
                        $("#total_amount").val(total);
                    }

                }
            }
            let getCategory = (v, ref) => {
                // v=1 category, v=2 brand, v=3,size
                $.ajax({
                    url: "get-product/" + v + "/" + ref,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {


                        if (ref == 1) {
                            console.log(response.category);
                            if (response.category) {
                                html = '<option value="0"> None </option>';
                                response.category.forEach((el) => {
                                    html += "<option value='" + el.product_category + "'>" + el.product_category_name + "</option>";

                                });
                                $('#product_category').html(html);
                            }
                        } else if (ref == 2) {
                            if (response.brand) {
                                html = '<option value="0"> None </option>';
                                response.brand.forEach((el) => {
                                    html += "<option value='" + el.id + "'>" + el.name + "</option>";

                                });
                                $('#brand_id').html(html);
                            }
                        } else if (ref == 3) {
                            html = '<option value="0"> None </option>';
                            response.brand.forEach((el) => {
                                html += "<option value='" + el.id + "'>" + el.name + "</option>";

                            });
                            $('#size').html(html);

                        }

                    }
                });
            }
            let getVendorBrand = (v) => {
                let products = <?=json_encode($vendor);?>;
                console.log(products);
                products.forEach(el => {

                    if (el.id == v) {
                        let html = '<option value="">None</option>';
                        if (el.brand_name_1 != null) {
                            html += '<option value="' + el.brand_name_1 + '">' + el.brand_name_1 + '</option>';
                        }
                        if (el.brand_name_2 != null) {
                            html += '<option value="' + el.brand_name_2 + '">' + el.brand_name_2 + '</option>';
                        }
                        if (el.brand_name_3 != null) {
                            html += '<option value="' + el.brand_name_3 + '">' + el.brand_name_3 + '</option>';
                        }
                        if (el.brand_name_4 != null) {
                            html += '<option value="' + el.brand_name_4 + '">' + el.brand_name_4 + '</option>';
                        }
                        if (el.brand_name_5 != null) {
                            html += '<option value="' + el.brand_name_5 + '">' + el.brand_name_5 + '</option>';
                        }
                        $("#brand_name").html(html);

                    }
                });
            }
            let add = () => {
                if ($("#vendor_id").val() == '') {
                    alert("Please Select Name of the Staff/ Guard");
                    return false;
                }
                if ($("#product_id").val() == '') {
                    alert("Please Select Product");
                    return false;
                }
                if ($("#qty").val() == '' || $("#qty").val() == 0) {
                    alert("Please Enter qty");
                    return false;
                }
                if ($("#rate").val() == '' || $("#rate").val() == 0) {
                    alert("Please Enter rate");
                    return false;
                }
                let vendorCheckFlag=0;
                let vendorCheckFlagName='';
                if(stockArrays.length > 0){
                    stockArrays.forEach(el=>{
                        if(el.vendor_id !=$("#vendor_id").val()){
                            vendorCheckFlag=1;
                            vendorCheckFlagName=el.vendor_name;
                        }
                    });
                }
                if(vendorCheckFlag){
                    alert("Please select this vendor  name "+vendorCheckFlagName);
                    return false;
                }

                console.log(stockArrays);
                let obj = {
                    'vendor_id': $("#vendor_id").val(),
                    'vendor_name': $("#vendor_id").find('option:selected').text(),
                    'stock_id': $("#stock_id").val(),
                    'product_id': $("#product_id").val(),
                    'product_name': $("#product_id").find('option:selected').text(),
                    'brand_name': $("#brand_name").val(),
                    'qty': $("#qty").val(),
                    'size': $("#size").val(),
                    'vat_rate': parseFloat($("#vat_rate").val()).toFixed(2),
                    'sub_total': parseFloat($("#sub_total").val()).toFixed(2),
                    'rate': $("#rate").val(),
                    'total_amount': parseFloat($("#total_amount").val()).toFixed(2),
                    're_order_label': $("#re_order_label").val(),
                    'measuring_unit': $("#measuring_unit").val(),
                    'size_name': $("#size").find('option:selected').text(),
                };
                console.log(obj);
                let flag=0;
                stockArrays.forEach(el=>{
                    if(el.product_id===$("#product_id").val() && el.size===$("#size").val() && el.brand_name===$("#brand_name").val() && el.rate===$("#rate").val()){
                        flag=1;
                    }
                });
                if(!flag){
                    stockArrays.push(obj)
                }else{
                    alert('Duplicate entries are not allowed!');
                    return  false;
                }
                $("#product_id").val('').change();
                $("#rate").val('').change();
                $("#qty").val('').change();
                $("#vat_rate").val('').change();
                $("#brand_name").val('').change();
                $("#total_amount").val('').change();
                $("#sub_total").val('').change();
                $("#size").val('').change();
                if (stockArrays.length > 0) {
                    $('.addSalesFooter').show();
                } else {
                    $('.addSalesFooter').hide();
                }
                console.log(stockArrays);
                window.localStorage.setItem('stockArrays', JSON.stringify(stockArrays));
                Stock.show();
            }
            let show = () => {
                stockArrays = JSON.parse(window.localStorage.getItem('stockArrays'));
                if (stockArrays == null) {
                    stockArrays = [];
                    $('.addSalesFooter').hide();
                    return;
                }
                let html = "<table class='table table-bordered table-striped'> <tr> <td style='text-align: center;font-weight: bold'>S.L.</td><td style='text-align: center;font-weight: bold'>Product Name</td><td style='text-align: center;font-weight: bold'>Brand Name </td><td style='text-align: center;font-weight: bold'>Size</td><td style='text-align: center;font-weight: bold'>Qty.</td><td style='text-align: center;font-weight: bold'>Rate <br>Unit</td><td style='text-align: center;font-weight: bold'>Sub Total</td><td style='text-align: center;font-weight: bold'>Vate <br>Rate</td><td style='text-align: center;font-weight: bold'>Total <br>Amount</td><td style='text-align: center;font-weight: bold'>Store Name</td><td></td></tr>";

                if (stockArrays.length > 0) {
                    $(".addSalesFooter").show();
                }

                let total = 0;
                stockArrays.forEach((el, i) => {
                    total += parseFloat(el.total_amount);
                    html += '<tr style="text-align: center;"><td>' + (i + 1) + '</td>';
                    html += '<td>' + el.product_name + '</td>';
                    html += '<td>' + el.brand_name + '</td>';
                    html += '<td>' + el.size_name + '</td>';
                    html += '<td>' + el.qty + '</td>';
                    html += '<td style="text-align: right;">' + (el.rate) + '</td>';
                    html += '<td style="text-align: right;">' + (el.sub_total) + '</td>';
                    html += '<td style="text-align: right;">' + (el.vat_rate) + '</td>';
                    html += '<td style="text-align: right;">' + el.total_amount + '</td>';
                    html += '<td style="text-align: right;">' + el.measuring_unit + '</td>';
                    html += '<td style="text-align: center;font-weight: bold;font-size: 17px;cursor: pointer;color:red;" onclick="Stock.delItem(' + i + ')">X</td></tr>';
                });

                //
                html += '<tr><td colspan="8"> <strong>Grand Total=</td>'
                html += '<td style="text-align: right;font-weight: bold;">' + total.toFixed(2) + '</td>';
                html += '</table>';
                $(".SsubTbl").html(html);

            }
            let delItem = (i) => {
                stockArrays.splice(i, 1);
                window.localStorage.setItem('stockArrays', JSON.stringify(stockArrays));
                Stock.show();
            }
            let getPaymentType = (v) => {
                $(".preloader").show();
                $.ajax({
                    url: "get-ledger/" + v,
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {

                        console.log(response.ledger);
                        if (response.ledger) {
                            html = '<option value="0"> None </option>';
                            response.ledger.forEach((el) => {
                                html += "<option value='" + el.id + "'>" + el.head + "</option>";

                            });
                            $('#ledger_id').html(html);
                            $(".preloader").hide();
                        }

                    }
                });
            }
            return {
                submitValue: submitValue,
                getCategory: getCategory,
                calculateValue: calculateValue,
                add: add,
                show: show,
                delItem: delItem,
                getPaymentType: getPaymentType,
                getVendorBrand: getVendorBrand
            }

        }();


    </script>
@endsection
