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
                        <h5 class="card-title">Add Cash Collection</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addIncome" action="{{route('cash-collection.store')}}" method="post" class="" style="font-size: 16px;">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Shop No / Customer Name <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="customer_id" id="customer_id" onchange="getDueInvoice(this.value);collection.autoSelectC();" required>
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row}}">{{$row->shop_no}} - {{$row->shop_name}}</option>
                                        @endforeach

                                    </select>

                                </div>
                                <div style="margin-top: 25px;" class="form-check  form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                        <input class="form-check-input" type="checkbox" value="" id="defaultFlexCheckDefault" onchange="collection.getCustomerShop()">
                                        <label class="form-check-label" for="defaultFlexCheckDefault">
                                            Show With Child Shop
                                        </label>



                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" value="" id="customer" name="customer" >
                                <input type="hidden" value="" id="bill_id" name="bill_id" >

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="shop_no">Shop No <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="shop_no" id="shop_no" onchange="getDueInvoice(this.value)" multiple>
                                        <option value="">None</option>


                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Invoice No <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="invoice_no"  id="invoice_no"  multiple>
                                        <option value="">None</option>

                                    </select>


                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Mode of Payment <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="payment_mode" id="payment_mode" required
                                    onchange="collection.getPaymentType(this.value)">
                                        <!--
<option value="">None</option>-->
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        
                                        <option value="Advance Deposit">Advance Deposit</option>
{{--                                        <option value="Security Deposit">Security Deposit</option>--}}
{{--                                        <option value="Discount of Sales">Discount of Sales</option>--}}
                                        <option value="TDS Challan">TDS Challan</option>
                                        <option value="VDS Challan">VDS Challan</option>
{{--                                        @foreach($ledger as $row)--}}
{{--                                            <option value="{{$row->head}}">{{$row->head}} </option>--}}
{{--                                        @endforeach--}}
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Collection Date</label>
                                    <input  type="text" value="{{date('Y-m-d')}}" id="collection_date" name="collection_date" autocomplete="off"  class="form-control" >

                                </div>


                            </div>
                            <div class="row sp_check"  style="display: none">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Cheque Date </label>
                                    <input  type="text"  id="cheque_date" name="cheque_date" autocomplete="off"  class="form-control" >

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Cheque Number <span style="color:red">*</span></label>
                                    <input autocomplete="off" id="cheque_no" name="cheque_no"  class="form-control" >


                                </div>

                            </div>
                            <div class="row sp_check" style="display: none">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Deposit Account Ledger </label>
                                    <select class="form-control select2" name="ledger_id" id="ledger_id" >
                                        <option value="">None</option>

                                                                                @foreach($ledger as $row)
                                                                                    <option value="{{$row->id}}">{{$row->head}} </option>
                                                                                @endforeach

                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Cheque Bank Name</label>
                                    <input  type="text" id="cheque_bank_name" name="cheque_bank_name" autocomplete="off"  class="form-control" >

                                </div>

                            </div>

                            <div class="row sp_tds" style="display: none">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">TDS Certificate No </label>
                                    <input name="tds_certificate_no" id="tds_certificate_no"  autocomplete="off"  class="form-control">

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Certificate Date</label>
                                    <input  type="text" value="{{date('Y-m-d')}}" id="certificate_date" name="certificate_date" autocomplete="off"  class="form-control" >

                                </div>

                            </div>
                            <div class="row sp_tds" style="display: none">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">TR Challan No </label>
                                    <input  type="text" id="tr_challan_no" name="tr_challan_no" autocomplete="off"  class="form-control" >

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">TR Challan Date</label>
                                    <input  type="text" value="{{date('Y-m-d')}}" id="tr_challan_date" name="tr_challan_date" autocomplete="off"  class="form-control" >

                                </div>

                            </div>
                            <div class="row " >

                                <div style="display: none" class="sp_tds form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Challan Issuer Bank Name </label>
                                    <input  type="text" id="challan_issuer_bank" name="challan_issuer_bank" autocomplete="off"  class="form-control" >

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Accumulated Dues</label>
                                    <input readonly  type="text" id="balance_due" name="balance_due" autocomplete="off"  class="form-control" >

                                </div>
                                <div style="margin-bottom:10px;" class="both form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Deposit Account Ledger </label>
                                    <select class="form-control select2" name="ledger_id2" id="ledger_id2" >

                                        <span id="sp_ck">
                                            <option value="6" selected="" data-select2-id="23">Cash</option>
                                
                                        </span>


                                    </select>
                                </div>


                            </div>
                            <div  class=" form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 sp-adv-dep" style="display: none;">

                                <label class="form-control-label">Advance Deposit Balance </label>
                                <input  type="text" id="advance_deposit_balance" name="advance_deposit_balance" autocomplete="off" readonly  class="form-control">

                            </div>
                            <div class="row " >

                                <div  class=" form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 sp-sec-dep" style="display: none;">

                                    <label class="form-control-label">Security Deposit Balance </label>
                                    <input  type="text" id="security_deposit_balance" name="security_deposit_balance" autocomplete="off" readonly  class="form-control">

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12 sp-sec-dep" style="margin-top:25px; display: none;">

                                    <input   type="checkbox" id="is_settlement" name="is_settlement" autocomplete="off"  value="1"
                                    >
{{--                                    onchange="collection.cashSettelment()"--}}
                                    <label class="form-control-label">Settlement</label>

                                </div>

                                <br>

                            </div>

                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button  type="Button" class="btn btn-sm btn-success float-right" onclick="collection.showList()">Show List</button>
                                </div>
                            </div>
                            <br>


                            <div class="row showTbl spSh"  style="display:none;">


                                <table  class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr>
                                        <input type="hidden" value="" name="accountRecord" id="accountRecord">
                                        <input type="hidden" value="" name="total" id="total">
{{--                                        <td style="width:5%">#</td>--}}
                                        <td style="width: 40% !important;">Collection Head</td>
                                        <td style="width: 12% !important;">Month</td>
                                        <td style="width: 12% !important;">Invoice No</td>
                                        <td style="width: 5% !important;"></td>
                                        <td style="width: 10% !important;">Bill Amount(Tk.)</td>
                                        <td style="width: 10% !important;">Due Amount(Tk.) </td>
                                        <td style="width: 10% !important;">Remarks </td>
                                        <td style="width: 10% !important;">Discount(Tk.) </td>
                                        <td style="width: 11% !important;">Payment Amount(Tk.) </td>

                                    </tr>
                                    </thead>
                                    <tbody id="collection_details">


                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <tbody id="subTbl" >
                                    </tbody>
                                    <input type="hidden" id="payment_amount" name="payment_amount" value="0">
                                </table>


                            </div>

                        <div class="card-footer spSh" style="display: none;">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button id="btnSubmit" type="Button" class="btn btn-sm btn-success float-right" onclick="collection.addCollection()">Submit</button>
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addGodown','Add add Godown Form','Do you really want to reset this form?');return false;">Reset</button>
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
        $(document).ready(function () {
            jqueryCalendar('cheque_date');
            jqueryCalendar('certificate_date');
            jqueryCalendar('tr_challan_date');
            jqueryCalendar('collection_date');
        });
        let collection = function (){

            let getPaymentType = function (v){

                let flag=0;
                if(v=='Cheque'){
                    $('.sp_check').show();
                    $('.sp_tds').hide();
                    $('.sp_vds').hide();
                    $('.sp-adv-dep').hide();
                    $('.sp-sec-dep').hide();
                    $('.both').hide();
                    // collection.getCoa('Curren Bank Accounts')
                }
                else if(v=='Cash'){
                    $('.sp_check').hide();
                    $('.sp_tds').hide();
                    $('.sp_vds').hide();
                    $('.sp-adv-dep').hide();
                    $('.sp-sec-dep').show();
                    $('.both').show();
                    $("#ledger_id2").html('  <option value="">None</option> <option value="6" selected>Cash</option>');
                    // collection.getCoa('Cash in Hand')
               } else if(v=='TDS Challan' || v=='VDS Challan'){
                    $('.sp_check').hide();
                    $('.sp_tds').show();
                    $('.sp_vds').hide();
                    $('.sp-adv-dep').hide();
                    $('.sp-sec-dep').hide();
                    $('.both').show();
                    if(v=='TDS Challan'){
                        $("#ledger_id2").html('<option value="">None</option><option value="48">TDS by Customer</option>');
                    }else{
                        $("#ledger_id2").html('<option value="">None</option><option value="47">VDS by Customer</option>');
                    }

                }
                else if(v==='Advance Deposit'){
                    $('.sp_check').hide();
                    $('.sp_tds').hide();
                    $('.sp_vds').hide();
                    $('.sp-adv-dep').show();
                    $('.sp-sec-dep').hide();
                    $('.both').hide();
                }
                // else if(v==='Security Deposit'){
                //     $('.sp_check').hide();
                //     $('.sp_tds').hide();
                //     $('.sp_vds').hide();
                //     $('.sp-adv-dep').hide();
                //     $('.sp-sec-dep').show();
                //     $('.both').hide();
                // }
                else{
                    $('.sp_check').hide();
                    $('.sp_tds').hide();
                    $('.sp_vds').hide();
                    $('.sp-adv-dep').hide();
                    $('.sp-sec-dep').hide();
                    $('.both').hide();
                }


            }
            let addCollection = function () {

                if($("#customer_id").val()==''){
                    alert("Please Select Customer");
                    return false;
                }

                // if($("#invoice_no").val()==''){
                //     alert("Please Select Invoice No");
                //     return false;
                // }
                if($("#payment_mode").val()==''){
                    alert("Please Select Payment mode");
                    return false;
                }
                if($("#collection_date").val()==''){
                    alert("Please Enter Collection Date");
                    return false;
                }
               if($("#payment_mode").val()=='Cash'){
                   if($("#ledger_id2").val()==''){
                       alert("Please Select Ledger");
                       return false;
                   }

               }else if($("#payment_mode").val()=='Cheque'){
                   //if($("#cheque_date").val()==''){
                   //    alert("Please Enter Cheque Date");
                     //  return false;
                   //}
                   if($("#ledger_id").val()==''){
                       alert("Please Select Ledger");
                       return false;
                   }
                   if($("#cheque_no").val()==''){
                       alert("Please Enter cheque no");
                       return false;
                   }

               }else if($("#payment_mode").val()=='TDS Challan' || $("#payment_mode").val()=='VDS Challan'){
                   if($("#ledger_id2").val()==''){
                       alert("Please Select Ledger");
                       return false;
                   }
               }
                let temp = JSON.parse($("#customer_id").val());
               $("#customer").val(temp.customer_id);
               // alert($("#invoice_no").val());
                $("#btnSubmit").attr('type', 'submit');
            }

            let  getCoa = function (id) {

                if(id==''){
                    $("#collection_details").html('');
                    return false;
                }
                $(".preloader").show();
                $.ajax({
                    type: 'GET',
                    url: 'get-coa-list/'+id,
                    dataType: 'json',
                    success: function (data) {
                        $(".preloader").hide();
                        // console.log(data);
                        $("#collection_details").html(data.html);


                    },error:function(){
                        console.log(data);
                    }
                });

            }
            let showList = function (){
                // if(id==''){
                //     return;
                // }
                // $.ajaxSetup({
                //     headers:
                //         { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                // });
                if($("#customer_id").val()==''){
                    alert("Please Select Customer");
                    return false;
                }
                let invoice = $("#invoice_no").val();
                let temp = JSON.parse($("#customer_id").val());
                let assetNo = $("#shop_no").val();
                 assetNo.push(temp.shop_no+'@@@'+temp.customer_id);
                let body = {
                    'invoice': invoice,
                    'customer_id': temp.customer_id,
                    'shop_no' : assetNo
                }
                console.log(body);
                $(".preloader").show();
                $.ajax({
                    url: 'get-invoice-details',
                    type: 'POST',
                    dataType: 'json',
                    data: {body},
                    success: function(data) {
                        console.log(data);
                        $(".preloader").hide();
                        $(".spSh").show();
                        $("#collection_details").html(data.html);
                    },
                    beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    }
                });
                // return
                {{--$(".preloader").show();--}}
                {{--$.post("get-invoice-details",{data:body,"_token": "{{ csrf_token() }}", function(data){--}}
                {{--    console.log(data);--}}
                {{--        $(".preloader").hide();--}}
                {{--        $(".spSh").show();--}}
                {{--        $("#collection_details").html(data.html);--}}
                {{--    });--}}
            }
            let cashSettelment = function (){
                let invoice = $("#invoice_no").val();
                // return
                if($("#is_settlement").is(":checked")){
                    let temp = JSON.parse($("#customer_id").val());

                    console.log(temp);

                    $(".preloader").show();
                    $.ajax({url: "get-security-deposit/"+temp.shop_no, success: function(data){
                            $(".preloader").hide();
                            $(".spSh").show();
                            $("#collection_details").html(data.html);
                        }});
                }else{
                    $("#security_deposit_balance").val('0');
                }


            }
            let getCustomerShop =(v)=>{
                if($("#defaultFlexCheckDefault").is(":checked")){
                    if($("#customer_id").val()==''){
                        alert("Select Shop No / Customer Name");
                        return  false;
                    }
                    if(v==''){
                        $("#collection_details").html('');
                        return false;
                    }
                    let d = JSON.parse($("#customer_id").val());
                    // console.log(d);
                    id = d.shop_no;
                    // if(id==''){
                    //
                    // }
                    $(".preloader").show();
                    $.ajax({
                        type: 'GET',
                        url: 'get-customer-invoice/'+id,
                        dataType: 'json',
                        success: function (data) {
                            $(".preloader").hide();
                            console.log(data);
                            if(data.length > 0){
                                let html = '<option value="">None</option>';
                                data.forEach(el=>{
                                    var refId = el.asset_no+'@@@'+el.customer_id;
                                    html +='<option value="'+refId+'" selected>'+el.asset_no+'</option>';
                                })
                                $("#shop_no").html(html);
                                getDueInvoice()
                            }else {
                                $("#shop_no").html('');
                            }
                        },error:function(){
                            console.log(data);
                        }
                    });

                }else{
                    $("#shop_no").html('');
                    getDueInvoice()
                }

            }
            let  autoSelectC = function (){
                $("#defaultFlexCheckDefault").prop('checked',true).change();
            }

            return {
                getPaymentType:getPaymentType,
                addCollection:addCollection,
                getCoa:getCoa,
                cashSettelment:cashSettelment,
                showList:showList,
                autoSelectC:autoSelectC,
                getCustomerShop:getCustomerShop,
            }

        }();

        function checkAmount(v,id) {
           // console.log(v);
         //   console.log(id);
            if (id != '') {
                if(id==38){
                    let due = parseFloat($("#rd_" + id).val());
                    let disc = parseFloat($("#dis_" + id).val());
                    let amt = parseFloat($("#amt_" + id).val());
                    if(isNaN(amt)) {
                        v = 0;
                    }else{
                        v =amt;
                    }

                    if (v > due) {
                        $("#r_" + id).show();
                        $("#r_" + id).html("Please Enter Due Amount.");
                        $("#paid_vat_amount" + id).val('').change();
                    } else {
                        $("#r_" + id).hide();
                    }
                }else{
                    let due = parseFloat($("#rd_" + id).val());
                    let disc = parseFloat($("#dis_" + id).val());
                    let amt = parseFloat($("#amt_" + id).val());
                    let t=0;
                    if(isNaN(disc)) {
                        disc = 0;
                    }
                    if(disc==0 && due>amt){
                        // alert(disc);
                        // alert(due);
                        // alert(amt);
                        t = amt;
                    }else{
                        t = parseInt(due) - parseInt(disc);
                        amt = t;
                    }

                    $("#amt_" + id).val(t);

                    if(isNaN(amt)) {
                        v = 0;
                    }else{
                        v =amt;
                    }
                    console.log(v);
                    if(v<0){
                        $("#amt_" + id).val(0);
                        $("#dis_" + id).val(due);
                        $("#r_" + id).show();
                        $("#r_" + id).html("Please Enter Due Amount.");
                    }
                    v= v+disc;

                    console.log(v);


                    if (v > due) {
                        $("#in_" + id).val('').change();
                        $("#r_" + id).show();
                        $("#r_" + id).html("Please Enter Due Amount.");
                    } else {
                        $("#r_" + id).hide();
                    }
                }



        }
           let total = 0;
            $("input[id^='amt_']").each(function(){
                let id = $(this).attr('id');
                let temp = 0;
                 temp = parseFloat($("#"+id).val()).toFixed();
                if(isNaN(temp)) {
                     temp = 0;
                }
                    total = parseInt(total) + parseInt(temp);

              //  console.log(temp);
                // console.log(total);
            });
           // let gtotal = total;
            let discount_total = 0;
            $("input[id^='dis_']").each(function(){
                let ids = $(this).attr('id');
                let refSplite = ids.split('_');
              //  alert(ids)
                let temp = 0;
                temp = parseFloat($("#"+ids).val()).toFixed();
                if(isNaN(temp)) {
                    temp = 0;
                }
                discount_total = parseInt(discount_total) + parseInt(temp);
                let t = parseInt($("#amt_"+refSplite[1]).val()) - parseInt(temp);
                // $("#amt_"+refSplite[1]).val(t);

                console.log(temp);
                // console.log(total);
            });
            //let gtotal = discount_total;
            // if($("#paid_vat_amount").val()!=''){
            //      gtotal += +$("#paid_vat_amount").val();
            // }

          //  console.log(discount_total);
            $("#sp_discount_total").html(discount_total);
            $("#sp_total").html(total);

            $("#payment_amount").val(total);

        }
        function getInvoiceDetails(id) {
         //    id = $("#invoice_no").val();
            if(id==''){
                  $("#collection_details").html('');
                return false;
            }
            $(".preloader").show();
            $.ajax({
                type: 'GET',
                url: 'get-invoice-details/'+id,
                dataType: 'json',
                success: function (data) {
                    $(".preloader").hide();
                    // console.log(data);
                    $("#collection_details").html(data.html);


                },error:function(){
                    console.log(data);
                }
            });

        }

        function getDueInvoice(d) {
            if($("#customer_id").val()==''){
                alert("Select Shop No / Customer Name ");
                return ;
            }
           let refD = JSON.parse($("#customer_id").val());
           // console.log(refD);
            // console.log(refD.id);
         //   let id=refD.id;

           $("#bill_id").val(refD.id);
           let id= $("#shop_no").val();
           id.push(refD.shop_no);
           console.log(id);
           let customer_id= refD.customer_id;
           // let id = refD.shop_no;//

            if(id===''){
                $("#invoice_no").html('<option value="" >None</option>');
                $("#balance_due").val(0);
                return;
            }
            console.log(id);
            $(".preloader").show();
            let body = {
                "id":id,
                "customer_id":customer_id,
                '_token': "{{csrf_token()}}"
            }

            $.ajax({
                // url: "due-invoice/"+id+'/'+customer_id,
                type: 'POST',
                url: 'due-invoice',
                dataType: 'json',
                "data": body,
                success: function(result){
                    $(".preloader").hide();
                    // console.log(result);
                let ar = result;
                    let html='<option value="" >None</option>';
                    $.each(ar.income, function(index, item) {
                        html +='<option value="'+item.id+'">'+item.bill_type+'-'+item.month +'-'+ item.invoice_no  +'</option >';

                    });

                    // collection.getCustomerShop();
                    $("#invoice_no").html(html);
                    $("#balance_due").val(ar.due);
                    $("#advance_deposit_balance").val(ar.advance_deposit_amount);
                    $("#security_deposit_balance").val(ar.security_deposit_amount);
                }});

        }
        function delInvocieCus(invoice,i) {
            console.log(i);

            let tot = parseFloat($('#sp_total').text());
            let sp_tot_t = parseFloat($('#sp_tot_t').text());
            let amt = parseFloat($('#amt_'+i).val());
            let bi = parseFloat($('#bi_'+i).val());
            let tot1= tot-amt;

            let tot2= sp_tot_t-bi;
            if(tot2>0){
                $("#sp_tot_t").html(tot2.toFixed())
            }else{
                $("#sp_tot_t").html(0)
            }
            if(tot1>0){
                $("#sp_total").html(tot1.toFixed())
            }else{
                $("#sp_total").html(0)
            }
            $('.'+invoice).html('');
            // $("input[id^='amt_']").each(function (){
            //     console.log($(this).attr('id'));
            // });

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
