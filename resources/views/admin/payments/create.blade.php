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
                        <h5 class="card-title">Add Payments</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addIncome" action="{{route('payment.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <input type="hidden" value="" name="accountRecord" id="accountRecord">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Payment Type</label>
                                    <select class="form-control select2" name="payment_type" id="payment_type"
                                            >
{{--                                        onchange="Payment.loadBillForm(this.value)"--}}
                                        <option value="">None</option>
                                        <option value="Vendor Payment">Vendor Payment</option>
                                        <option value="Internal Advance Payment">Internal Advance Payment</option>
                                        <option value="Internal Expense Payment">Internal Expense Payment</option>
                                        <option value="Inter Transfer">Inter-transfer</option>
                                        <option value="Source Tax Payment">Source Tax Payment</option>
                                        <option value="Source VAT Payment">Source VAT Payment</option>
                                        <option value="Sales VAT Payment">Sales VAT Payment</option>
                                        <option value="Corporate Tax Payment">Corporate Tax Payment</option>
                                        <option value="Advance Tax Payment">Advance Tax Payment</option>
                                        <option value="Others Payment">Others Payment</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Vendor Name </label>
                                    <select class="form-control select2" name="vendor_id" id="vendor_id" onchange="Payment.getVendorInvoice(this.value)" >
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}">{{$row->vendor_name}}</option>
                                            @endforeach

                                    </select>

                                </div>


                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Mode of Payment <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="payment_mode" id="payment_mode" required
                                    onchange="Payment.loadLedger(this.value,'')">
                                        <option value="">None</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Cash">Cash</option>


                                    </select>

                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Journal Date<span style="color:red">*</span></label>
                                    <input autocomplete="off"  type="text" id="journal_date" name="journal_date"  class="form-control" required>


                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Payment Date<span style="color:red">*</span></label>
                                    <input autocomplete="off" onchange="makeDueDate(this.value)" type="text" id="issue_date" name="issue_date"  class="form-control" required>


                                </div>
{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}

{{--                                    <label class="form-control-label">Due Date<span style="color:red">*</span></label>--}}
{{--                                    <input  type="text" id="due_date" name="due_date" autocomplete="off"  class="form-control" required>--}}

{{--                                </div>--}}
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Payment Reference/Cheque Number</label>
                                    <input  type="text" id="payment_reference" name="payment_reference" autocomplete="off"  class="form-control" >

                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Invoice</label>
                                    <select class="form-control select2" name="invoice_no" id="invoice_no"  multiple>
                                        <option value="">None</option>
                                    </select>
                                </div>


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Paid Amount (Tk.)</label>
                                    <input  type="text" id="paid_amount" name="paid_amount" autocomplete="off"  class="form-control" >
                                </div>
                            </div>
                            <div class="row sp_check"  style="display: none">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Cheque Date <span style="color:red">*</span></label>
                                    <input  type="text" value="{{date('Y-m-d')}}" id="cheque_date" name="cheque_date" autocomplete="off"  class="form-control" >
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

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Purpose/ Narration</label>
                                    <input autocomplete="off" type="text" id="remarks" name="remarks"  class="form-control" >
                                </div>
                            </div>

                            <br>



                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
{{--                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addGodown','Add add Godown Form','Do you really want to reset this form?');return false;">Reset</button>--}}
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
        let customerInfo=[];
        jQuery(function() {
            jQuery('form').bind('submit', function() {
                // if(accountRecord.length==0){
                //     alert("Please Add Payment Ledger");
                //     return false;
                // }

                if($('#journal_date').val()==''){
                    alert("Please journal date");
                    return false;
                }
                if($('#vendor_id').val()==''){
                    alert("Please Select Vendor Name");
                    return false;
                }
                if($('#paid_amount').val()==''){
                    alert("Please Enter Paid Amount");
                    return false;
                }
                if($('#invoice_no').val()==''){
                    alert("Please Select Invoice");
                    return false;
                }
                jQuery(this).find(':disabled').removeAttr('disabled');
                customerInfo=[];
            });

        });
        $(document).ready(function () {
            jqueryCalendar('payment_date');
            jqueryCalendar('issue_date');
            jqueryCalendar('due_date');
            jqueryCalendar('journal_date');
            jqueryCalendar('ad_payment_date');
        });
        let accountRecord=[];
        let legerArray={};
         legerArray['Internal Expense Payment']=51;
         legerArray['Internal Advance Payment']=50;
         legerArray['Inter Transfer']=6;
         legerArray['Source Tax Payment']='41,52';
         legerArray['Source VAT Payment']='39';
         legerArray['Sales VAT Payment']='38';
         legerArray['Corporate Tax Payment']='54';
         legerArray['Others Payment']='53';
         legerArray['Advance Tax Payment']='49';

        let Payment = function (){

            let getVendorInvoice = function (id){
                $.ajax({url: "get-vendor-invoice/"+id, success: function(result){
                        console.log(result);
                        let ar =result;
                        let html = '<option value="">None</option>';
                        ar.forEach(el=>{
                            html +='<option value="'+el.id+'">'+el.voucher_no+'</option>';
                        });
                        if(ar.length>0){
                            $("#invoice_no").html(html);
                        }else{
                            $("#invoice_no").html(html);
                        }
                    }});

            }
            let loadBillForm = function (v){
                if(v=='Internal Expense Payment' || v=='Internal Advance Payment' || v=='Vendor Payment'){

                    $(".showExpensePaymentTbl").show();
                    $(".showAdvancePayment").hide();
                    // Payment.loadLedger(legerArray[v],'income_head_id');

                }
                else if(v=='Inter Transfer' ||  v=='Source Tax Payment'
                    ||  v=='Source Tax Payment' || v=='Source VAT Payment'
                    || v=='Sales VAT Payment' || v=='Corporate Tax Payment'
                     || v =='Others Payment' ||  v=='Advance Tax Payment'){

                    $(".showAdvancePayment").show();
                    $(".showExpensePaymentTbl").hide();
                    Payment.loadLedger(legerArray[v],'income_head_id2');
                }else if(v=='Electricity'){
                    console.log(customerInfo);
                    // $("#electricity_id").val(customerInfo.income_head_id).change();
                    // $("#electricity_month").val(item.month).change();
                    // $("#current_reading").val(item.current_reading),
                    //     $("#pre_reading").val(item.pre_reading),
                    //     $("#kwt").val(item.kwt);
                    $("#kwt_rate").val(customerInfo.electricity_rate_unit);
                    // $("#electricity_vat").val(item.vat);
                    // $("#electricity_vat_amt").val(item.vat_amt);
                    // $("#electricity_amt_total").val(item.total);
                    // $("#electricity_amount").val(item.amount);
                    $(".showTbl").hide();
                    $(".showElectricity").show();
                    $(".showIncomeTbl").hide();
                }else{
                    $("#sp_rs").html('');
                    $(".showTbl").hide();
                    $(".showIncomeTbl").show();
                    $(".showElectricity").hide();
                }
            }

            let addRecord = function() {
                if($("#payment_type").val()=='') {
                    alert("Please select paymet type");
                    return  false;
                }
                let flag=-1;
                let obj = {};
                if($("#payment_type").val()=='Inter Transfer' ||  $("#payment_type").val()=='Advance Tax Payment'
                    ||  $("#payment_type").val()=='Source Tax Payment' || $("#payment_type").val()=='Source VAT Payment'
                    || $("#payment_type").val()=='Sales VAT Payment' || $("#payment_type").val()=='Corporate Tax Payment'
                    || $("#payment_type").val() =='Others Payment'){
                    if($("#income_head_id2").val() == ''){
                        alert("Please Select Income Head");
                        return false;
                    }

                    if($("#ad_amount").val() == ''){
                        alert("Please Enter Amount");
                        return false;
                    }
                    accountRecord.forEach((el,index) => {
                        if(el.income_head_id == $("#income_head_id2").val()){
                            flag = index;
                        }
                    });
                    obj = {
                        'income_head_id': $("#income_head_id2").val(),
                        'income_head': $("#income_head_id2").find('option:selected').text(),
                        'remarks': $("#ad_remarks").val(),
                        'payment_date': $("#issue_date").val(),
                        'amount': $("#ad_amount").val()
                    }
                    $("#income_head_id2").val('').change();
                    $("#ad_remarks").val('');
                    $("#ad_payment_date").val('');
                    $("#ad_amount").val('');

                }
                else if($("#payment_type").val() == 'Vendor Payment' || $("#payment_type").val() == 'Internal Advance Payment' ||  $("#payment_type").val()=='Internal Expense Payment') {

                    if($("#income_head_id").val() == ''){
                        alert("Please Select Income Head");
                        return false;
                    }
                    accountRecord.forEach((el,index) => {
                        if(el.income_head_id == $("#income_head_id").val()){
                            flag = index;

                        }
                    });

                    obj = {
                        'income_head_id': $("#income_head_id").val(),
                        'income_head': $("#income_head_id").find('option:selected').text(),
                        'staff_name': $("#staff_id").find('option:selected').text(),
                        'remarks': $("#remarks").val(),
                        'payment_date': $("#issue_date").val(),
                        'staff_id': $("#staff_id").val(),
                        'payment_mode': $("#payment_mode").val(),
                        'journal_date': $("#journal_date").val(),
                        'payment_reference': $("#payment_reference").val(),
                        'vendor_id': $("#vendor_id").val(),
                        'vendor_name': $("#vendor_id").find('option:selected').text(),
                        'amount': $("#amount").val()
                    }

                    $("#income_head_id").val('').change();
                    $("#remarks").val('');
                    // $("#payment_date").val('');
                    $("#amount").val('');

                }
                else if($("#bill_type").val()=='Income'){

                    if($("#income_id").val() == ''){
                        alert("Please Select Income Head");
                        return false;
                    }
                    if($("#income_month").val() == ''){
                        alert("Please Select Month");
                        return false;
                    }
                    accountRecord.forEach((el,index) => {
                        if(el.income_head_id == $("#income_id").val()){
                            flag = index;
                        }
                    });

                    obj = {
                        'income_head_id': $("#income_id").val(),
                        'income_head': $("#income_id").find('option:selected').text(),
                        'month': $("#income_month").val(),
                        'vat': $("#income_vat").val(),
                        'bill_type': $("#bill_type").val(),
                        'vat_amt': $("#income_vat_amt").val(),
                        'total': $("#income_amt_total").val(),
                        'amount': $("#income_amount").val(),
                        'remarks': $("#income_remarks").val()
                    }

                    $("#income_id").val('0').change();
                    // $("#income_month").val('');
                    $("#income_vat").val('');
                    $("#income_vat_amt").val('');
                    $("#income_amount").val('');
                    $("#income_amt_total").val('');

                }
                if(flag > -1){
                    accountRecord[flag] = obj;
                }else{
                    accountRecord.push(obj);
                }
                console.log(accountRecord);


                if(accountRecord.length > 0) {
                    $("#payment_type").prop("disabled", true);
                }
                Payment.showRecord();

            }
            let editRecord = function(index) {
                let item = accountRecord[index];
                console.log(item);
                if($("#payment_type").val()=='Internal Advance Payment' || $("#payment_type").val()=='Internal Expense Payment'){
                    $("#income_head_id").val(item.income_head_id).change();
                    $("#staff_id").val(item.staff_id).change();
                    $("#remarks").val(item.remarks);
                    $("#amount").val(item.amount);


                }else if($("#payment_type").val()=='Inter Transfer' ||  $("#payment_type").val()=='Advance Tax Payment'
                    ||  $("#payment_type").val()=='Source Tax Payment' || $("#payment_type").val()=='Source VAT Payment'
                    || $("#payment_type").val()=='Sales VAT Payment' || $("#payment_type").val()=='Corporate Tax Payment'
                    || $("#payment_type").val() =='Others Payment'){
                    $("#income_head_id2").val(item.income_head_id).change();
                    $("#ad_remarks").val(item.remarks);
                    $("#ad_amount").val(item.amount);

                }

                Payment.showRecord();
            }
            let deleteRecord = function (index) {
                accountRecord.splice(index,1);
                Payment.showRecord();

            }
            let showRecord = function() {
                console.log(accountRecord);
                let html = "";
                $("#accountRecord").val(JSON.stringify(accountRecord));

                if($("#payment_type").val()=='Inter Transfer' || $("#payment_type").val()=='Advance Tax Payment'
                    ||  $("#payment_type").val()=='Source Tax Payment' || $("#payment_type").val()=='Source VAT Payment'
                || $("#payment_type").val()=='Sales VAT Payment' || $("#payment_type").val()=='Corporate Tax Payment'
                || $("#payment_type").val() =='Others Payment') {
                    Payment.showAdvancePayment();
                    return false;
                }
                if($("#bill_type").val()=='Income'){
                    Payment.showIncome();
                    return false;
                }

                let amount = 0;
                let vat_amount=0;
                let total=0;
                let gtotal=0;
                accountRecord.forEach((el,index) => {

                    let temp =  parseFloat(amount)+ parseFloat(el.amount) ;
                    total = temp.toFixed(2);
                    html += '<tr><td style="width:5% !important;">'+ parseInt(index+1) +' </td>';
                    html += '<td style="width: 16% !important;">'+ el.income_head +' </td>';
                    html += '<td style="width: 12% !important;">'+ el.staff_name +' </td>';
                    html += '<td style="width: 8% !important;">'+ el.remarks +' </td>';
                    // html += '<td style="width: 8% !important;">'+ el.payment_date +' </td>';
                    html += '<td style="width: 15% !important;">'+ el.amount +' </td>';
                    html += '<td style="width: 9% !important;"> <i onclick="Payment.editRecord('+index+')" style="cursor:pointer;color:green;" class="bi-pencil-square " aria-hidden="true"></i> ' +
                        '&nbsp;&nbsp; <i onclick="Payment.deleteRecord('+index+')" style="cursor:pointer;color:red;" class="bi-trash-fill" aria-hidden="true"></i>   </td></tr>';

                });
                if(accountRecord.length > 0){
                    $(".showExpensePaymentTbl").show();
                    html +='<tr><td colspan="3" style="text-align: right"><strong>Total = </strong></td>';
                    html +='<input type="hidden" name="vat_amount_total" value="'+vat_amount+'" id="vat_amount_total">' +
                        '<input type="hidden" name="grand_total" value="'+total+'" id="grand_total">' +
                        '<input type="hidden" name="total" value="'+amount+'" id="total">' +
                        '<td></td><td><strong>'+total+'</strong></td> </tr>';
                    let i=2;
                    $('#subEnpenseTbl').html(html);
                    if(accountRecord.length == 0) {
                        $("#payment_type").prop('disabled',false);
                    }

                }else{
                    $('#subEnpenseTbl').html('');
                }
                if(accountRecord.length == 0) {
                    $("#payment_type").prop('disabled',false);
                }

            }
            let showAdvancePayment = function () {
                console.log(accountRecord);
                let html = "";

                let amount = 0;
                let vat_amount=0;
                let total=0;
                let gtotal=0;
                accountRecord.forEach((el,index) => {

                    let temp =  parseFloat(total)+ parseFloat(el.amount) ;
                    total = temp.toFixed(2);

                    html += '<tr><td style="width:5% !important;">'+ parseInt(index+1) +' </td>';
                    html += '<td style="width: 16% !important;">'+ el.income_head +' </td>';
                    html += '<td style="width: 8% !important;">'+ el.remarks +' </td>';
                    html += '<td style="width: 15% !important;">'+ el.amount +' </td>';
                    html += '<td style="width: 9% !important;"> <i onclick="Payment.editRecord('+index+')" style="cursor:pointer;color:green;" class="bi-pencil-square " aria-hidden="true"></i> ' +
                        '&nbsp;&nbsp; <i onclick="Payment.deleteRecord('+index+')" style="cursor:pointer;color:red;" class="bi-trash-fill" aria-hidden="true"></i>   </td></tr>';

                });
                if(accountRecord.length > 0){
                    $(".showExpensePaymentTbl").hide();
                    $(".showAdvancePayment").show();
                    html +='<tr><td colspan="2" style="text-align: right"><strong>Total = </strong></td>';
                    html +='<input type="hidden" name="ad_total" value="'+total+'" id="ad_total">' +
                    '<td></td><td><strong>'+total+'</strong></td> </tr>';
                    let i=2;
                    $('#subAdvanceTbl').html(html);
                    if(accountRecord.length == 0) {
                        $("#payment_type").prop('disabled',false);
                    }

                }else{
                    $('#subAdvanceTbl').html('');
                }
                if(accountRecord.length == 0) {
                    $("#payment_type").prop('disabled',false);
                }


            }

            let  showIncome = function () {
                console.log(accountRecord);
                let html = '';
                $("#accountRecord").val(JSON.stringify(accountRecord));
                let amount = 0;
                let vat_amount=0;
                let total=0;
                let gtotal=0;
                accountRecord.forEach((el,index) => {
                    let temp =  parseFloat(amount)+ parseFloat(el.amount) ;
                    let temp1 =  parseFloat(vat_amount)+ parseFloat(el.vat_amt) ;
                    amount = temp.toFixed(2);
                    vat_amount = temp1.toFixed(2);
                    total = (parseFloat(total)+parseFloat(el.total)).toFixed(2);

                    html += '<tr><td style="width:5% !important;">'+ parseInt(index+1) +' </td>';
                    html += '<td style="width: 20% !important;">'+ el.income_head +' </td>';
                    html += '<td style="width: 12% !important;">'+ el.month +' </td>';
                    html += '<td style="width: 20% !important;">'+ el.remarks +' </td>';
                    html += '<td style="width: 10% !important;">'+ el.amount +' </td>';
                    html += '<td style="width: 7% !important;">'+ el.vat +' </td>';
                    html += '<td style="width: 10% !important;">'+ el.vat_amt +' </td>';
                    html += '<td style="width: 11% !important;">'+ el.total +' </td>';
                    html += '<td style="width: 5% !important;"> <i onclick="Payment.editRecord('+index+')" style="cursor:pointer;color:green;" class="bi-pencil-square " aria-hidden="true"></i> ' +
                        '&nbsp;&nbsp; <i onclick="Payment.deleteRecord('+index+')" style="cursor:pointer;color:red;" class="bi-trash-fill" aria-hidden="true"></i>   </td></tr>';

                });

                    $(".showTbl").hide();
                    $(".subElectricityTbl").hide();

                    html +='<tr><td colspan="4" style="text-align: right"><strong>Total = </strong></td>';
                    html +='<input type="hidden" name="vat_amount_total" value="'+vat_amount+'" id="vat_amount_total">' +
                        '<input type="hidden" name="grand_total" value="'+total+'" id="grand_total">' +
                        '<input type="hidden" name="i_total" value="'+amount+'" id="i_total">' +
                        '<td ><strong><span id="sp_amount"> '+amount+'</span></strong></td><td></td><td><strong>'+vat_amount+'</strong></td> <td><strong>'+total+'</strong></td> </tr>';

                    $("#subIncomeTbl").html(html);

            }

            let  getCreditPeriod = function(id) {

                $.ajax({url: "get-credit-period/"+id, success: function(result){
                        let ar = JSON.parse(result);
                        console.log(ar);
                        customerInfo = ar;
                        $("#area").val(ar.area_sft);
                        $("#rate").val(ar.rent_sft);
                        let amt = parseInt(ar.area_sft) * parseInt(ar.rent_sft);
                        if(ar.vat_exemption=='No'){
                            $('#vat').val(15);


                        }
                        $('#amount').val(amt.toFixed(2));
                        $("#amt_total").val(amt.toFixed(2));


                        $("#credit_period").val(ar.credit_period);
                        calculateVat();
                    }});
            }
            let changeOption = function (v){
                if(v==31){
                    console.log();
                    $("#area").val(customerInfo.area_sft);
                    $("#rate").val(customerInfo.service_charge);
                    let amt = parseInt(customerInfo.area_sft) * parseInt(customerInfo.service_charge);
                    if(customerInfo.vat_exemption=='No'){
                        $('#vat').val(15);


                    }
                    $('#amount').val(amt.toFixed(2));
                    $("#amt_total").val(amt.toFixed(2));


                    $("#credit_period").val(customerInfo.credit_period);
                    calculateVat();

                }else if(v==29){
                    $("#area").val(customerInfo.area_sft);
                    $("#rate").val(customerInfo.rent_sft);
                    let amt = parseInt(customerInfo.area_sft) * parseInt(customerInfo.rent_sft);
                    if(customerInfo.vat_exemption=='No'){
                        $('#vat').val(15);


                    }
                    $('#amount').val(amt.toFixed(2));
                    $("#amt_total").val(amt.toFixed(2));
                    $("#credit_period").val(customerInfo.credit_period);
                    calculateVat();
                }
            }

            let electricityCalculate = function (){
                let kwt = parseInt($("#kwt").val());
                let kwt_rate = parseInt($("#kwt_rate").val());

                let amt = kwt*kwt_rate;

                $("#electricity_amount").val(amt.toFixed(2));

                let v = parseFloat($("#electricity_vat").val());
                let vat = (amt*(v/100)).toFixed(2);
                $("#electricity_vat_amt").val(vat);
                $("#electricity_amt_total").val((parseFloat(amt)+parseFloat(vat)).toFixed(2));


            }

            let loadLedger = function (v,ref){
                if(v=='Cheque'){
                    $('.chk_no').show()
                    $(".preloader").show();
                    $.ajax({url: "get-coa/"+v, success: function(result){
                            let ar = JSON.parse(result);
                            console.log(ar);
                            $(".preloader").hide();

                            let html='<option value="" >None</option>';
                            $.each(ar.ledger, function(index, item) {
                                html +='<option value="'+item.id+'">' + item.head  +'</option >';
                            });
                            $("#ledger_head").html(html);


                        }});
                    $(".sp_check").show();
                }else if(v=='Cash'){
                    $.ajax({url: "get-coa/"+v, success: function(result){
                            let ar = JSON.parse(result);
                            console.log(ar);
                            $(".preloader").hide();

                            let html='<option value="" >None</option>';
                            $.each(ar.ledger, function(index, item) {
                                html +='<option value="'+item.id+'">' + item.head  +'</option >';
                            });
                            $("#ledger_head").html(html);
                        }});
                    $('.chk_no').hide()
                    $(".sp_check").hide();
                }else{
                    $.ajax({url: "get-payment-coa/"+v, success: function(result){
                            let ar = JSON.parse(result);
                            console.log(ar);
                            $(".preloader").hide();

                            let html='<option value="" >None</option>';
                            $.each(ar, function(index, item) {
                                html +='<option value="'+item.id+'">' + item.head  +'</option >';
                            });
                            $("#"+ref).html(html);
                        }});
                    // $('.chk_no').hide();
                }

            }
            return {
                loadBillForm:loadBillForm,
                addRecord: addRecord,
                editRecord: editRecord,
                showRecord: showRecord,
                deleteRecord: deleteRecord,
                showAdvancePayment: showAdvancePayment,
                showIncome: showIncome,
                getCreditPeriod: getCreditPeriod,
                changeOption: changeOption,
                getVendorInvoice: getVendorInvoice,
                electricityCalculate: electricityCalculate,
                loadLedger: loadLedger
            }

        }();



        function calculateVat(d) {
            let v=0;
            if($("#bill_type").val() == 'Income'){
                if($("#income_vat").val()==''){
                    v = 0;
                }else{
                    v = parseInt($("#income_vat").val());
                }
                let amount = parseFloat($("#income_amount").val());
                let vat = (amount*(v/100)).toFixed(2);
                $("#income_vat_amt").val(vat);
                $("#income_amt_total").val((parseFloat(vat)+parseFloat(amount)).toFixed(2));
            }else if($("#bill_type").val() == 'Electricity'){
                if($("#electricity_vat").val()==''){
                    v = 0;
                }else{
                    v = parseInt($("#electricity_vat").val());
                }
                let amount = parseFloat($("#electricity_amount").val());
                let vat = (amount*(v/100)).toFixed(2);
                $("#electricity_vat_amt").val(vat);
                $("#electricity_amt_total").val((parseFloat(vat)+parseFloat(amount)).toFixed(2));
            }else {
                if($("#vat").val()==''){
                    v = 0;
                }else{
                    v = parseInt($("#vat").val());
                }
                let amount = parseFloat($("#amount").val());
                let vat = (amount*(v/100)).toFixed(2);
                $("#vat_amt").val(vat);
                $("#amt_total").val((parseFloat(vat)+parseFloat(amount)).toFixed(2));
            }


            // $("#vat_amount_1").html(vat);
            // $("#vat_amount").val(vat);
            // $("#grand_total_1").html((parseFloat(vat)+parseFloat(amount)).toFixed(2));
            // $("#grand_total").val((parseFloat(vat)+parseFloat(amount)).toFixed(2));

        }


        function makeDueDate(date) {
            let date1 = date;
            var date = new Date(date);
                days = parseInt($("#credit_period").val());
            if(!isNaN(date.getTime())){

                if(isNaN(parseInt($("#credit_period").val()))){
                    $("#due_date").val(date1);
                }else {

                    date.setDate(date.getDate() + days);
                    $("#due_date").val(date.toInputFormat());
                }
            } else {
                alert("Invalid Date");
            }

        }
        Date.prototype.toInputFormat = function() {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = this.getDate().toString();
            return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
        };
        function calculateVatAmount() {
            if($("#area").val()!='' && $("#rate").val()!=''){
                let amt = parseFloat($("#area").val()) * parseFloat($("#rate").val());
                $("#amount").val(amt.toFixed(2));
                calculateVat(6)
            }

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
