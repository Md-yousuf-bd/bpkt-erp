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
                        <h5 class="card-title">Add Income</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addIncome" action="{{route('income.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Shop No/Shop Name</label>
                                    <select class="form-control select2" name="customer_id" id="customer_id" onchange="getCreditPeriod(this.value)">
                                        <option value="0">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}">{{$row->shop_no}} - {{$row->shop_name}}</option>
                                            @endforeach

                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Sales Person</label>
                                    <select class="form-control select2" name="person_id" id="person_id">
                                        <option value="0">None</option>

                                    </select>


                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Credit Period</label>
                                    <input onkeypress="return filterKeyNumber(this,event,'er_c')" type="text" id="credit_period" name="credit_period"  class="form-control" >
                                    <p id="er_c" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Issue Date</label>
                                    <input autocomplete="off" onchange="makeDueDate(this.value)" type="text" id="issue_date" name="issue_date"  class="form-control" >


                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Due Date</label>
                                    <input  type="text" id="due_date" name="due_date" autocomplete="off"  class="form-control" >

                                </div>

                            </div>


                            <div class="row showTbl" >


                                <table  class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr>
                                        <input type="hidden" value="" name="accountRecord" id="accountRecord">
                                        <input type="hidden" value="" name="total" id="total">
                                        <td style="width:5%">#</td>
                                        <td style="width: 30% !important;">Income Head</td>
                                        <td style="width: 20% !important;">Month</td>
                                        <td style="width: 30% !important;">Description</td>
                                        <td style="width: 10% !important;">Amount(Tk.)</td>
                                        <td style="width: 5% !important;">Action</td>
                                    </tr>
                                    </thead>
                                    <tbody  >
                                    <tr>
                                        <td></td>
                                    <td>

                                        <select class="form-control select2" name="income_head_id" id="income_head_id" >
                                            <option value="0">None</option>
                                            @foreach($income_head as $row)
                                                @if($row->sub_sub_category_id == 0)
                                                    <option value="{{$row->id}}">{{$row->sub_category}}</option>
                                                @else
                                                    <option value="{{$row->id}}">{{$row->sub_sub_category}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                        <td>
                                            @php

                                             $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

                                            $year = date('Y');
                                                    @endphp
                                            <select class="form-control select2" name="month" id="month" >
                                                <option value="0">None</option>
                                                @foreach($month as $row)

                                                        <option value="{{$row.' '.$year}}">{{$row.' '.$year}}</option>

                                                @endforeach
                                            </select>

                                        </td>
                                        <td>
                                            <input size="6"  type="text" id="remarks" name="remarks"  class="form-control" >
                                        </td>


                                        <td> <input size="6" onkeypress="return filterKeyNumber(this,event,'r_a')" type="text" id="amount" name="amount"  class="form-control" >
                                            <p id="r_a" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p></td>

                                        <td>
                                        <span class="btn btn-success" onclick="addRecord()"> + </span>
                                    </td>
                                    </tr>

                                  <div id="">

                                  </div>

                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <tbody id="subTbl" >
                                    </tbody>
                                </table>


                            </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
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
            jqueryCalendar('issue_date');
            jqueryCalendar('due_date');
        });


       let accountRecord=[];
        function addRecord() {

            if($("#income_head_id").val() == 0){
                alert("Please Select Income Head");
                return false;
            }
            if($("#month").val() == 0){
                alert("Please Select Month");
                return false;
            }
            if($("#amount").val() == ''){
                alert("Please Enter Amount");
                return false;
            }

            let flag=-1;
            accountRecord.forEach((el,index) => {
                if(el.income_head_id == $("#income_head_id").val()){
                    flag = index;

                }
            });

            if(flag > -1){
                accountRecord[flag] = {
                    'income_head_id': $("#income_head_id").val(),
                    'income_head': $("#income_head_id").find('option:selected').text(),
                    'month': $("#month").val(),
                    'remarks': $("#remarks").val(),
                    'amount': $("#amount").val()
                };
            }else{
                accountRecord.push({
                    'income_head_id': $("#income_head_id").val(),
                    'income_head': $("#income_head_id").find('option:selected').text(),
                    'month': $("#month").val(),
                    'remarks': $("#remarks").val(),
                    'amount': $("#amount").val()
                });
            }

            $("#income_head_id").val('0').change();
            // $("#date").val('');
            $("#amount").val('');
            $("#remarks").val('');
            // $("#notes").val('');
            showRecord();

        }
        function editRecord(index) {
            let item = accountRecord[index];
            $("#income_head_id").val(item.income_head_id).change();
            $("#month").val(item.month).change();
            $("#remarks").val(item.remarks);
            $("#amount").val(item.amount);
            showRecord();
        }
        function deleteRecord(index) {
            accountRecord.splice(index,1);
            showRecord();
        }
        function showRecord() {
            console.log(accountRecord);
            let html = '';
            $("#accountRecord").val(JSON.stringify(accountRecord));
            let amount = 0;
            accountRecord.forEach((el,index) => {
                let temp =  parseFloat(amount)+ parseFloat(el.amount) ;
                amount = temp.toFixed(2);
                html += '<tr><td style="width:5%">'+ parseInt(index+1) +' </td>';
                html += '<td style="width: 30% !important;">'+ el.income_head +' </td>';
                html += '<td style="width: 20% !important;">'+ el.month +' </td>';
                html += '<td style="width: 30% !important;">'+ el.remarks +' </td>';
                html += '<td style="width: 10% !important;">'+ el.amount +' </td>';
                html += '<td style="width: 5% !important;"> <i onclick="editRecord('+index+')" style="cursor:pointer;color:green;" class="bi-pencil-square " aria-hidden="true"></i> ' +
                    '&nbsp;&nbsp; <i onclick="deleteRecord('+index+')" style="cursor:pointer;color:red;" class="bi-trash-fill" aria-hidden="true"></i>   </td></tr>';

            });
            $("#total").val(amount);
            if(accountRecord.length > 0){
                $(".showTbl").show();

                html +='<tr><td colspan="4" style="text-align: right"><strong>Sub Total = </strong></td>';
                html +='<td ><strong><span id="sp_amount"> '+amount+'</span></strong></td><td></td></tr>';

                html += '<tr> <td></td> ';
                html += '<td >\n' +
                    '\n' +
                    '                                        <label class="form-control-label">Vat %</label>\n' +
                    '                                        <input onblur="calculateVat(this.value)" onkeypress="return filterKeyNumber(this,event,\'er_v\')" type="text" id="vat" name="vat"  class="form-control" >\n' +
                    '                                        <p id="er_v" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>\n' +
                    '\n' +
                    '                                </div></td> <td > </td><td > </td><td ><strong> <span id="vat_amount_1"></span></strong></td><td></td></tr>';

                html += '<tr> <td colspan="4" style="text-align: right"><strong>Grand Total = </strong> ' +
                    '<input type="hidden" name="grand_total" id="grand_total">' +
                    ' <input type="hidden" name="vat_amount" id="vat_amount">' +
                    '</td><td><strong> <span id="grand_total_1"></span></strong></td><td></td></tr>';
                $("#subTbl").html(html);
                calculateVat();
                // $("#subTbl tr:first").after(html);
            }


        }
        function calculateVat(d) {
            let v=0;
            if($("#vat").val()==''){
                 v = 0;
            }else{
                 v = parseInt($("#vat").val());
            }


            let amount = parseFloat($("#sp_amount").text());
            let vat = (amount*(v/100)).toFixed(2);
            $("#vat_amount_1").html(vat);
            $("#vat_amount").val(vat);
            $("#grand_total_1").html((parseFloat(vat)+parseFloat(amount)).toFixed(2));
            $("#grand_total").val((parseFloat(vat)+parseFloat(amount)).toFixed(2));

        }

        function getCreditPeriod(id) {

            $.ajax({url: "get-credit-period/"+id, success: function(result){
                let ar = JSON.parse(result);
                $("#credit_period").val(ar.credit_period);
                }});
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
