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
                        <h5 class="card-title">Add Security Deposit</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addIncome" action="{{route('security-deposit.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}

                            <div class="row">
                                <input type="hidden" value="" name="accountRecord" id="accountRecord">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Journal Date<span
                                            style="color:red">*</span></label>
                                    <input autocomplete="off" value="{{ date('Y-m-d') }}" type="text" id="journal_date"
                                           name="journal_date" class="form-control" required>
                                </div>
                                <div style="display: none" class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Issue Date<span style="color:red">*</span></label>
                                    <input autocomplete="off" type="text" id="issue_date" name="issue_date"
                                           class="form-control">
                                </div>
                            </div>

                            <br>


                            <div class="row showPaymentTbl">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr>

                                        <td style="width:5%">#</td>
                                        <td style=""> Category</td>
                                        <td style="width: 20%"> Ledger</td>
                                        <td style="width: 15%">Shop Name</td>

                                        <td style="">Cheque No.</td>
                                        <td style="">Purpose/ Narration</td>
                                        <td style="">Amount (Tk.)</td>
                                        <td></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <select class="form-control select2" name="category" id="category">
                                                <option value="115">Security Deposit</option>
                                                <option value="117">Advance Deposit for Rent</option>

                                            </select>
                                        </td>
                                        <td>

                                            <select class="form-control select2" name="ledger_id" id="ledger_id">
                                                <option value="">None</option>
                                                @foreach($income_head as $row)
                                                    <option value="{{$row->id}}">
                                                        <norb>{{$row->head }}</norb>
                                                    </option>
                                                @endforeach
                                            </select>

                                        </td>

                                        <td>
                                            <select class="form-control select2" name="customer" id="customer">
                                                <option value="">None</option>

                                                @foreach($customer as $row)
                                                    <option value="{{$row->asset_no}}">
                                                        <norb>{{$row->asset_no }} - {{$row->shop_name??"None" }}</norb>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="text" id="payment_ref" name="payment_ref" class="form-control">

                                        </td>
                                        <td><input type="text" id="remarks" name="remarks" class="form-control">

                                        </td>


                                        <td>
                                            <input size="6" onblur="ManualJournal.checkBalance(2);" type="text"
                                                   id="credit" name="credit" class="form-control"
                                                   onkeypress="return filterKeyNumber(this,event,'e_a')">

                                            <p id="e_a"
                                               style="display: none;color: red; margin-top: -11px; text-align: left;">
                                                Please Enter Number Only</p>
                                        </td>
                                        <td>
                                            <span class="btn btn-success" onclick="ManualJournal.addRecord()"> + </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody id="subMaTbl">

                                    </tbody>

                                </table>


                            </div>


                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button id="glSubmit" type="submit" class="btn btn-sm btn-success float-right">
                                            Submit
                                        </button>
                                        <button type="reset" class="btn btn-sm btn-default"
                                                onclick="resetForm('addGodown','Add add Godown Form','Do you really want to reset this form?');return false;">
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
        let customerInfo = [];
        jQuery(function () {
            jQuery('form').bind('submit', function () {
                // if($("#balance").val()!=0){
                //     alert("Debit and Credit value will be equal.");
                //     return false;
                // }
                $("#rank_name").val($("#rank").find('option:selected').text());
                $("#branch_name").val($("#branch_id").find('option:selected').text());
                $("#dept_name").val($("#department").find('option:selected').text());
                // $("#btnEmpSubmit").attr('type', 'submit');
                // customerInfo=[];
            });
            jqueryCalendar('journal_date');
            jqueryCalendar('issue_date');
        });

        let accountRecord = [];
        let ManualJournal = function () {

            let submitValue = function () {

            }
            let addRecord = function () {
                if ($("#ledger_id").val() == '') {
                    alert("Please select Ledger");
                    return false;
                }
                if ($("#credit").val() == '') {
                    alert("amount can not be 0");
                    return false;
                }
                if ($("#customer").val() == '') {

                    alert("Please Select  Customer");
                    return false;
                }
                let flag = -1;
                let obj = {};
                obj = {
                    'ledger_id': $("#ledger_id").val(),
                    'category': $("#category").val(),
                    'category_name': $("#category").find('option:selected').text(),
                    'ledger_name': $("#ledger_id").find('option:selected').text(),
                    'customer': $("#customer").val(),
                    'customer_name': $("#customer").find('option:selected').text(),
                    'payment_ref': $("#payment_ref").val(),
                    'credit': $("#credit").val() != '' ? parseFloat($("#credit").val(), 2) : 0,
                    'debit': $("#debit").val() != '' ? parseFloat($("#debit").val(), 2) : 0,
                    'remarks': $("#remarks").val()
                }
                accountRecord.forEach((el, index) => {
                    if (el.ledger_id == $("#ledger_id").val()) {
                        flag = index;
                    }
                });

                $("#ledger_id").val('').change();
                $("#customer").val('').change();
                $("#payment_ref").val('');
                $("#credit").val('');
                $("#debit").val('');
                $("#remarks").val('');


                if (flag > -1) {
                    accountRecord[flag] = obj;
                } else {
                    accountRecord.push(obj);
                }
                console.log(accountRecord);
                ManualJournal.showRecord();

            }
            let editRecord = function (index) {
                let item = accountRecord[index];
                $("#ledger_id").val(item.ledger_id).change();
                $("#remarks").val(item.remarks);
                $("#credit").val(item.credit);
                $("#payment_ref").val(item.payment_ref);
                $("#customer").val(item.customer).change();
                $("#category").val(item.category).change();
                ManualJournal.showRecord();
            }
            let deleteRecord = function (index) {
                accountRecord.splice(index, 1);
                ManualJournal.showRecord();

            }
            let showRecord = function () {
                console.log(accountRecord);
                let html = "";
                $("#accountRecord").val(JSON.stringify(accountRecord));


                let amount = 0;
                let vat_amount = 0;
                let total_debit = 0;
                let total_credit = 0;
                let balance = 0;
                accountRecord.forEach((el, index) => {
                    let temp = parseFloat(total_debit) + parseFloat(el.debit);
                    total_debit = temp.toFixed(2);
                    let temp1 = parseFloat(total_credit) + parseFloat(el.credit);
                    total_credit = temp1.toFixed(2);
                    balance = total_debit - total_credit;
                    html += '<tr><td style="width:5% !important;">' + parseInt(index + 1) + ' </td>';
                    html += '<td style="width: 10% !important;">' + el.category_name + ' </td>';
                    html += '<td style="width: 20% !important;">' + el.ledger_name + ' </td>';
                    html += '<td style="width: 12% !important;">' + el.customer_name + ' </td>';
                    html += '<td style="">' + el.payment_ref + ' </td>';
                    html += '<td style="width: 8% !important;">' + el.remarks + ' </td>';
                    html += '<td style="text-align:right;width: 12% !important;">' + el.credit + ' </td>';
                    html += '<td style="width: 9% !important;"> <i onclick="ManualJournal.editRecord(' + index + ')" style="cursor:pointer;color:green;" class="bi-pencil-square " aria-hidden="true"></i> ' +
                        '&nbsp;&nbsp; <i onclick="ManualJournal.deleteRecord(' + index + ')" style="cursor:pointer;color:red;" class="bi-trash-fill" aria-hidden="true"></i>   </td></tr>';

                });
                if (accountRecord.length > 0) {
                    html += '<tr><td colspan="6" style="text-align: right"><strong>Total = </strong></td>';
                    html += '<input type="hidden" name="total_debit" value="' + total_debit + '" id="total_debit">' +
                        '<input type="hidden" name="total_credit" value="' + total_credit + '" id="total_credit">' +
                        '<input type="hidden" name="balance" value="' + balance + '" id="balance">' +

                        '<td style="text-align: right"> <strong>' + total_credit + ' </strong> </td> ';
                    // '</tr>';
                    // html +='<tr><td colspan="5" style="text-align: right"><strong>Balance = </strong></td> <td colspan="2" style="text-align: right"><strong>'+balance+'</strong></td> </tr>';
                    let i = 2;
                    $('#subMaTbl').html(html);
                } else {
                    $('#subMaTbl').html('');
                }
            }

            let checkBalance = function (v) {
                if (v == 1) {
                    $("#credit").val('');
                } else {
                    $("#debit").val('');
                }
            }

            return {
                addRecord: addRecord,
                editRecord: editRecord,
                showRecord: showRecord,
                deleteRecord: deleteRecord,
                checkBalance: checkBalance,
                submitValue: submitValue
            }

        }();


        function calculateVat(d) {
            let v = 0;
            if ($("#bill_type").val() == 'Income') {
                if ($("#income_vat").val() == '') {
                    v = 0;
                } else {
                    v = parseInt($("#income_vat").val());
                }
                let amount = parseFloat($("#income_amount").val());
                let vat = (amount * (v / 100)).toFixed(2);
                $("#income_vat_amt").val(vat);
                $("#income_amt_total").val((parseFloat(vat) + parseFloat(amount)).toFixed(2));
            } else if ($("#bill_type").val() == 'Electricity') {
                if ($("#electricity_vat").val() == '') {
                    v = 0;
                } else {
                    v = parseInt($("#electricity_vat").val());
                }
                let amount = parseFloat($("#electricity_amount").val());
                let vat = (amount * (v / 100)).toFixed(2);
                $("#electricity_vat_amt").val(vat);
                $("#electricity_amt_total").val((parseFloat(vat) + parseFloat(amount)).toFixed(2));
            } else {
                if ($("#vat").val() == '') {
                    v = 0;
                } else {
                    v = parseInt($("#vat").val());
                }
                let amount = parseFloat($("#amount").val());
                let vat = (amount * (v / 100)).toFixed(2);
                $("#vat_amt").val(vat);
                $("#amt_total").val((parseFloat(vat) + parseFloat(amount)).toFixed(2));
            }
        }


        function makeDueDate(date) {
            let date1 = date;
            var date = new Date(date);
            days = parseInt($("#credit_period").val());
            if (!isNaN(date.getTime())) {

                if (isNaN(parseInt($("#credit_period").val()))) {
                    $("#due_date").val(date1);
                } else {

                    date.setDate(date.getDate() + days);
                    $("#due_date").val(date.toInputFormat());
                }
            } else {
                alert("Invalid Date");
            }
        }

        Date.prototype.toInputFormat = function () {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth() + 1).toString(); // getMonth() is zero-based
            var dd = this.getDate().toString();
            return yyyy + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + (dd[1] ? dd : "0" + dd[0]); // padding
        };

        function calculateVatAmount() {
            if ($("#area").val() != '' && $("#rate").val() != '') {
                let amt = parseFloat($("#area").val()) * parseFloat($("#rate").val());
                $("#amount").val(amt.toFixed(2));
                calculateVat(6)
            }
        }

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
    </script>
@endsection
