@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Billing</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addIncome" action="{{route('billing.update', [$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <input type="hidden" value="" name="accountRecord" id="accountRecord">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Shop No/Shop Name <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="customer_id" id="customer_id" onchange="Bill.getCreditPeriod(this.value)" required>
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}" @if($row->asset_no==$editData->shop_no) selected @endif>{{$row->asset_no}} - {{$row->customer->shop_name??"None"}}</option>
                                        @endforeach

                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Sales Person</label>
                                    <select class="form-control select2" name="person_id" id="person_id">
                                        <option value="0">None</option>
                                        @foreach($employee as $row)
                                            <option value="{{$row->id}}" @if($row->id==$editData->person_id) selected @endif>{{$row->name}}</option>
                                        @endforeach
                                    </select>


                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Credit Period</label>
                                    <input onkeypress="return filterKeyNumber(this,event,'er_c')" value="{{$editData->credit_period}}" type="text" id="credit_period" name="credit_period"  class="form-control" >
                                    <p id="er_c" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Journal Date<span style="color:red">*</span></label>
                                    <input autocomplete="off"  type="text" id="journal_date" name="journal_date" value="{{$editData->journal_date}}" class="form-control" required>


                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Issue Date<span style="color:red">*</span></label>
                                    <input autocomplete="off" onchange="makeDueDate(this.value)" type="text"  value="{{$editData->issue_date}}" id="issue_date" name="issue_date"  class="form-control" required>


                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Due Date<span style="color:red">*</span></label>
                                    <input  type="text" id="due_date" name="due_date" value="{{$editData->due_date}}"  autocomplete="off"  class="form-control" required>

                                </div>

                            </div>
                            <div class="row " >

                                <div  class=" form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Bill Type</label>
                                    <select class="form-control select2" name="bill_type" id="bill_type"
                                            onchange="Bill.loadBillForm(this.value)" >
                                        <option value="Rent" @if('Rent'==$editData->bill_type) selected @endif>Rent</option>
                                        <option value="Service Charge" @if('Service Charge'==$editData->bill_type) selected @endif>Service Charge</option>
                                        <option value="Electricity" @if('Electricity'==$editData->bill_type) selected @endif>Electricity</option>
                                        <option value="Income" @if($editData->bill_type=='Income') selected @endif>Income</option>

                                    </select>
                                </div>

                            </div>
                            <br>


                            <div class="row showTbl" >
                                <table  class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr>
                                        <td style="width:5%">#</td>
                                        <td style="width: 16% !important;">Ledger</td>
                                        <td style="width: 12% !important;">Month</td>
                                        <td style="width: 8% !important;">Area(Sft)</td>
                                        <td style="width: 8% !important;">Rate(Sft)</td>
                                        <td style="width: 10% !important;"> <span id="sp_rs"> Rent </span> Amount(Tk.)</td>
                                        <td style="width: 7% !important;">Vat%</td>
                                        <td style="width: 10% !important;">Vat Amount(Tk.) </td>
                                        <td style="width: 15% !important;">Total(Tk.) </td>
                                        <td style="width: 9% !important;"></td>
                                    </tr>
                                    </thead>
                                    <tbody  >
                                    <tr>
                                        <td></td>
                                        <td>

                                            <select class="form-control select2"  name="income_head_id" id="income_head_id" onchange="Bill.changeOption(this.value)">
                                                <option class="show_29" value="29">Rent-Shop</option>


                                                <option style="display: none" id="show_31" value="31">Service Charge Revenue</option>


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
                                        <td> <input size="6" onblur=" calculateVatAmount();" onkeypress="return filterKeyNumber(this,event,'r_ar')" type="text" id="area" name="area"  class="form-control" >
                                            <p id="r_ar" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td> <input size="6" onblur=" calculateVatAmount();" onkeypress="return filterKeyNumber(this,event,'r_rate')" type="text" id="rate" name="rate"  class="form-control" >
                                            <p id="r_rate" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>


                                        <td> <input size="6" onblur="calculateVat();" onkeypress="return filterKeyNumber(this,event,'r_a')" type="text" id="amount" name="amount"  class="form-control" >
                                            <p id="r_a" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td> <input size="6" onblur=" calculateVat();" onkeypress="return filterKeyNumber(this,event,'r_v')" type="text" id="vat" name="vat" value="0" class="form-control" >
                                            <p id="r_v" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>

                                        <td> <input size="6" onkeypress="return filterKeyNumber(this,event,'r_am')" type="text" id="vat_amt" name="vat_amt" value="0"  class="form-control" >
                                            <p id="r_am" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td> <input size="6" onkeypress="return filterKeyNumber(this,event,'r_t')" type="text" id="amt_total" name="amt_total" value="0" class="form-control" >
                                            <p id="r_t" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td>
                                            <span class="btn btn-success" onclick="Bill.addRecord()"> + </span>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody id="subTbl">

                                    </tbody>

                                </table>



                            </div>
                            <div class="row showIncomeTbl"  style="display: none;">


                                <table  class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr>
                                        <td style="width:5%">#</td>
                                        <td style="width: 20% !important;">Income Head</td>
                                        <td style="width: 12% !important;">Month</td>
                                        <td style="width: 20% !important;">Description</td>
                                        <td style="width: 10% !important;">Amount(Tk.)</td>
                                        <td style="width: 7% !important;">Vat%</td>
                                        <td style="width: 10% !important;">Vat Amount(Tk.) </td>
                                        <td style="width: 11% !important;">Total(Tk.) </td>
                                        <td style="width: 5% !important;"></td>
                                    </tr>
                                    </thead>
                                    <tbody  >
                                    <tr>
                                        <td></td>
                                        <td>

                                            <select class="form-control select2" name="income_id" id="income_id" >
                                                <option value="0">None</option>
                                                @foreach($income_head as $row)
                                                    <option class="ledger_{{$row->id}}" value="{{$row->id}}">{{$row->head}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            @php
                                                $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                                                $year = date('Y');
                                            @endphp
                                            <select class="form-control select2" name="income_month" id="income_month" >
                                                <option value="0">None</option>
                                                @foreach($month as $row)

                                                    <option value="{{$row.' '.$year}}">{{$row.' '.$year}}</option>

                                                @endforeach
                                            </select>

                                        </td>
                                        <td>
                                            <input size="6"  type="text" id="income_remarks" name="income_remarks"  class="form-control" >
                                        </td>


                                        <td> <input size="6" onblur=" calculateVat();" onkeypress="return filterKeyNumber(this,event,'r_a')" type="text" id="income_amount" name="income_amount"  class="form-control" >
                                            <p id="r_a" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td> <input size="6" onblur=" calculateVat();" onkeypress="return filterKeyNumber(this,event,'r_v')" type="text" id="income_vat" name="income_vat" value="0" class="form-control" >
                                            <p id="r_v" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>

                                        <td> <input size="6" onkeypress="return filterKeyNumber(this,event,'r_am')" type="text" id="income_vat_amt" name="income_vat_amt" value="0"  class="form-control" >
                                            <p id="r_am" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td> <input size="6" onkeypress="return filterKeyNumber(this,event,'r_t')" type="text" id="income_amt_total" name="income_amt_total" value="0" class="form-control" >
                                            <p id="r_t" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td>
                                            <span class="btn btn-success" onclick="Bill.addRecord()"> + </span>
                                        </td>
                                    </tr>

                                    </tbody>
                                    <tbody id="subIncomeTbl" >
                                    </tbody>
                                </table>



                            </div>
                            <div class="row showElectricity"  style="display: none;">


                                <table  class="table table-bordered" style="width: 100%">
                                    <thead class="table-dark">
                                    <tr>
                                        <td style="width:5%">#</td>
                                        <td style="width: 16% !important;">Income Head</td>
                                        <td style="width: 12% !important;">Month</td>
                                        <td style="width: 10% !important;">Current Readings</td>
                                        <td style="width: 10% !important;">Previous Readings</td>
                                        <td style="width: 10% !important;">KWH</td>
                                        <td style="width: 5% !important;">Rate/KWH</td>
                                        <td style="width: 10% !important;">Bill Amount(Tk.)</td>
                                        <td style="width: 7% !important;">Vat%</td>
                                        <td style="width: 10% !important;">Vat Amount(Tk.) </td>
                                        <td style="width: 11% !important;">Total(Tk.) </td>
                                        <td style="width: 5% !important;"></td>
                                    </tr>
                                    </thead>
                                    <tbody  >
                                    <tr>
                                        <td></td>
                                        <td>

                                            <select class="form-control select2" name="electricity_id" id="electricity_id" >
                                                <option value="0">None</option>
                                                @foreach($income_head as $row)
                                                    <?php
                                                    if($row->id!=33){
                                                        continue ;
                                                    }

                                                    ?>
                                                    <option value="{{$row->id}}" @if($row->id==33) selected @endif >{{$row->head}}</option>

                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            @php
                                                $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                                                $year = date('Y');
                                            @endphp
                                            <select class="form-control select2" name="electricity_month" id="electricity_month" >
                                                <option value="0">None</option>
                                                @foreach($month as $row)

                                                    <option value="{{$row.' '.$year}}">{{$row.' '.$year}}</option>

                                                @endforeach
                                            </select>

                                        </td>
                                        <td>
                                            <input size="6"  onblur=" Bill.electricityCalculate();" type="text" id="current_reading" name="current_reading"  class="form-control" >
                                        </td>

                                        <td>
                                            <input size="6"   onblur=" Bill.electricityCalculate();" type="text" id="pre_reading" name="pre_reading"  class="form-control" >
                                        </td>
                                        <td>
                                            <input size="6"  onblur=" Bill.electricityCalculate();" type="text" id="kwt" name="kwt"  class="form-control" >
                                        </td>
                                        <td>
                                            <input size="6"  onblur=" Bill.electricityCalculate();" type="text" id="kwt_rate" name="kwt_rate"  class="form-control" >
                                        </td>

                                        <td> <input size="6" onblur=" Bill.electricityCalculate();" onkeypress="return filterKeyNumber(this,event,'r_a')" type="text" id="electricity_amount" name="electricity_amount"  class="form-control" >
                                            <p id="r_a" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td> <input size="6" onblur=" Bill.electricityCalculate();"onkeypress="return filterKeyNumber(this,event,'r_v')" type="text" id="electricity_vat" name="electricity_vat" value="0" class="form-control" >
                                            <p id="r_v" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>

                                        <td> <input size="6" onkeypress="return filterKeyNumber(this,event,'r_am')" type="text" id="electricity_vat_amt" name="electricity_vat_amt" value="0"  class="form-control" >
                                            <p id="r_am" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td> <input size="6" onkeypress="return filterKeyNumber(this,event,'r_t')" type="text" id="electricity_amt_total" name="electricity_amt_total" value="0" class="form-control" >
                                            <p id="r_t" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                        </td>
                                        <td>
                                            <span class="btn btn-success" onclick="Bill.addRecord()"> + </span>
                                        </td>
                                    </tr>

                                    <div id="">

                                    </div>

                                    </tbody>
                                    <tbody id="subElectricityTbl" >
                                    </tbody>
                                </table>

                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-sm btn-success float-right">Update</button>
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
        let accountRecord=[];
        let customerInfo=[];
        jQuery(function() {
            jQuery('form').bind('submit', function() {
                if(accountRecord.length==0){
                    alert("Please Add Ledger");
                    return false;
                }
                jQuery(this).find(':disabled').removeAttr('disabled');
                customerInfo=[];
            });

        });
        $(document).ready(function () {
            jqueryCalendar('issue_date');
            jqueryCalendar('due_date');
            jqueryCalendar('journal_date');
            let temp = <?= json_encode($details)?>;
            Bill.loadBillForm('<?= $editData->bill_type?>');
            accountRecord =JSON.parse(temp);
            Bill.showRecord();
        });

        let Bill = function (){
            let loadBillForm = function (v){
                if(v=='Rent'){
                    $("#sp_rs").html('Rent');
                    $(".showTbl").show();
                    $(".showIncomeTbl").hide();
                    $(".showElectricity").hide();
                    $("#income_head_id").val(29).change();
                    $('#show_29').next(".select2-container").show();
                    $('#show_31').next(".select2-container").hide();
                }else if(v=='Service Charge'){
                    $("#sp_rs").html('SC');
                    $(".showTbl").show();
                    $(".showIncomeTbl").hide();
                    $(".showElectricity").hide();
                    $("#income_head_id").val(31).change();
                    $('#show_29').next(".select2-container").hide();
                    $('#show_31').next(".select2-container").show();
                }else if(v=='Electricity'){
                    console.log(customerInfo);
                    // $("#electricity_id").val(customerInfo.income_head_id).change();
                    // $("#electricity_month").val(item.month).change();
                    // $("#current_reading").val(item.current_reading),
                    //     $("#pre_reading").val(item.pre_reading),
                    //     $("#kwt").val(item.kwt);

                    if(customerInfo.electricity_rate_unit!=''){
                        $("#kwt_rate").val(customerInfo.electricity_rate_unit);
                    }
                    let pre_reading = '<?=$pre_month ?>';
                    console.log(pre_reading);
                    if(pre_reading!=''){
                        $("#pre_reading").val(pre_reading);
                    }else{
                        $("#pre_reading").val(customerInfo.electricity_meter_reading);
                    }
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
                let flag=-1;
                let obj = {};
                if($("#bill_type").val() == 'Rent' || $("#bill_type").val() == 'Service Charge'){
                    if($("#income_head_id").val() == ''){
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
                    accountRecord.forEach((el,index) => {
                        if(el.income_head_id == $("#income_head_id").val()){
                            flag = index;

                        }
                    });
                    obj = {
                        'income_head_id': $("#income_head_id").val(),
                        'income_head': $("#income_head_id").find('option:selected').text(),
                        'month': $("#month").val(),
                        'vat': $("#vat").val(),
                        'bill_type': $("#bill_type").val(),
                        'vat_amt': $("#vat_amt").val(),
                        'total': $("#amt_total").val(),
                        'area': $("#area").val(),
                        'rate': $("#rate").val(),
                        'amount': $("#amount").val()
                    }
                    $("#income_head_id").val('0').change();
                    $("#vat_amt").val('0');
                    $("#vat").val('0');
                    $("#amt_total").val('0');

                } else if($("#bill_type").val()=='Electricity') {

                    if($("#electricity_id").val() == 0){
                        alert("Please Select Income Head");
                        return false;
                    }
                    if($("#electricity_month").val() == 0){
                        alert("Please Select Month");
                        return false;
                    }

                    accountRecord.forEach((el,index) => {
                        if(el.income_head_id == $("#electricity_id").val()){
                            flag = index;

                        }
                    });

                    obj = {
                        'income_head_id': $("#electricity_id").val(),
                        'income_head': $("#electricity_id").find('option:selected').text(),
                        'month': $("#electricity_month").val(),
                        'current_reading': $("#current_reading").val(),
                        'pre_reading': $("#pre_reading").val(),
                        'kwt': $("#kwt").val(),
                        'kwt_rate': $("#kwt_rate").val(),
                        'vat': $("#electricity_vat").val(),
                        'bill_type': $("#bill_type").val(),
                        'vat_amt': $("#electricity_vat_amt").val(),
                        'total': $("#electricity_amt_total").val(),
                        'amount': $("#electricity_amount").val()
                    }

                    $("#electricity_id").val('0').change();
                    $("#electricity_vat").val('');
                    $("#electricity_vat_amt").val('');
                    // $("#kwt").val('');
                    // $("#kwt_rate").val('');
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
                    $("#bill_type").prop("disabled", true);
                }
                Bill.showRecord();

            }
            let editRecord = function(index) {
                let item = accountRecord[index];
                if($("#bill_type").val()=='Income'){
                    $("#income_id").val(item.income_head_id).change();
                    $("#income_month").val(item.month).change();
                    $("#income_amount").val(item.amount);
                    $("#income_amt_total").val(item.total);
                    $("#income_vat").val(item.vat);
                    $("#income_remarks").val(item.remarks);
                    $("#income_vat_amt").val(item.vat_amt);
                }else if($("#bill_type").val()=='Electricity'){

                    $("#electricity_id").val(item.income_head_id).change();
                    $("#electricity_month").val(item.month).change();
                    $("#current_reading").val(item.current_reading),
                    $("#pre_reading").val(item.pre_reading),
                    $("#kwt").val(item.kwt);
                    $("#kwt_rate").val(item.kwt_rate);
                    $("#electricity_vat").val(item.vat);
                    $("#electricity_vat_amt").val(item.vat_amt);
                    $("#electricity_amt_total").val(item.total);
                    $("#electricity_amount").val(item.amount);
                }else{

                    $("#income_head_id").val(item.income_head_id).change();
                    $("#month").val(item.month).change();
                    $("#area").val(item.area),
                        $("#rate").val(item.rate),
                        $("#amount").val(item.amount);
                    $("#amt_total").val(item.total);
                    $("#vat").val(item.vat);
                    $("#vat_amt").val(item.vat_amt);
                }

                Bill.showRecord();
            }
            let deleteRecord = function (index) {
                accountRecord.splice(index,1);
                Bill.showRecord();

            }
            let showRecord = function() {
                console.log(accountRecord);
                let html = "";
                $("#accountRecord").val(JSON.stringify(accountRecord));

                if($("#bill_type").val()=='Electricity') {
                    Bill.showElectricityCharge();
                    return false;
                }
                if($("#bill_type").val()=='Income'){
                    Bill.showIncome();
                    return false;
                }

                let amount = 0;
                let vat_amount=0;
                let total=0;
                let gtotal=0;
                accountRecord.forEach((el,index) => {
                    let temp =  parseFloat(amount)+ parseFloat(el.amount) ;
                    let temp1 =  parseFloat(vat_amount)+ parseFloat(el.vat_amt) ;
                    amount = temp.toFixed();
                    vat_amount = temp1.toFixed();
                    total = (parseFloat(total)+parseFloat(el.total)).toFixed();

                    html += '<tr><td style="width:5% !important;">'+ parseInt(index+1) +' </td>';
                    html += '<td style="width: 16% !important;">'+ el.income_head +' </td>';
                    html += '<td style="width: 12% !important;">'+ el.month +' </td>';
                    html += '<td style="width: 8% !important;">'+ el.area +' </td>';
                    html += '<td style="width: 8% !important;">'+ el.rate +' </td>';
                    html += '<td style="width: 15% !important;text-align: right">'+ el.amount +' </td>';
                    html += '<td style="width: 7% !important;">'+ el.vat +' </td>';
                    html += '<td style="width: 10% !important;text-align: right">'+ el.vat_amt +' </td>';
                    html += '<td style="width: 15% !important;text-align: right">'+ el.total +' </td>';
                    html += '<td style="width: 9% !important;"> <i onclick="Bill.editRecord('+index+')" style="cursor:pointer;color:green;" class="bi-pencil-square " aria-hidden="true"></i> ' +
                        '&nbsp;&nbsp; <i onclick="Bill.deleteRecord('+index+')" style="cursor:pointer;color:red;" class="bi-trash-fill" aria-hidden="true"></i>   </td></tr>';

                });
                if(accountRecord.length > 0){
                    $(".showTbl").show();
                    html +='<tr><td colspan="5" style="text-align: right"><strong>Total = </strong></td>';
                    html +='<input type="hidden" name="vat_amount_total" value="'+vat_amount+'" id="vat_amount_total">' +
                        '<input type="hidden" name="grand_total" value="'+total+'" id="grand_total">' +
                        '<input type="hidden" name="total" value="'+amount+'" id="total">' +
                        '<td style="text-align: right"><strong><span id="sp_amount"> '+amount+'</span></strong></td><td></td><td><strong>'+vat_amount+'</strong></td> <td><strong>'+total+'</strong></td> </tr>';


                    let i=2;
                    $('#subTbl').html(html);
                    if(accountRecord.length == 0) {
                        $("#bill_type").prop('disabled',false);
                    }
                    // calculateVat();
                    // $("#subTbl tr:first").after(html);
                }else{
                    $('#subTbl').html('');
                }
                if(accountRecord.length == 0) {
                    $("#bill_type").prop('disabled',false);
                }

            }
            let showElectricityCharge = function () {
                console.log(accountRecord);
                let html = "";
                $("#accountRecord").val(JSON.stringify(accountRecord));
                let amount = 0;
                let vat_amount=0;
                let total=0;
                let gtotal=0;
                accountRecord.forEach((el,index) => {
                    let temp =  parseFloat(amount)+ parseFloat(el.amount) ;
                    let temp1 =  parseFloat(vat_amount)+ parseFloat(el.vat_amt) ;
                    amount = temp.toFixed();
                    vat_amount = temp1.toFixed();
                    total = (parseFloat(total)+parseFloat(el.total)).toFixed();

                    html += '<tr><td style="width:5% !important;">'+ parseInt(index+1) +' </td>';
                    html += '<td style="width: 16% !important;">'+ el.income_head +' </td>';
                    html += '<td style="width: 12% !important;">'+ el.month +' </td>';
                    html += '<td style="width: 8% !important;">'+ el.current_reading +' </td>';
                    html += '<td style="width: 8% !important;">'+ el.pre_reading +' </td>';
                    html += '<td style="width: 8% !important;">'+ el.kwt +' </td>';
                    html += '<td style="width: 8% !important;">'+ el.kwt_rate +' </td>';
                    html += '<td style="width: 15% !important;text-align: right">'+ el.amount +' </td>';
                    html += '<td style="width: 7% !important;">'+ el.vat +' </td>';
                    html += '<td style="width: 10% !important;text-align: right">'+ el.vat_amt +' </td>';
                    html += '<td style="width: 15% !important;text-align: right">'+ el.total +' </td>';
                    html += '<td style="width: 9% !important;"> <i onclick="Bill.editRecord('+index+')" style="cursor:pointer;color:green;" class="bi-pencil-square " aria-hidden="true"></i> ' +
                        '&nbsp;&nbsp; <i onclick="Bill.deleteRecord('+index+')" style="cursor:pointer;color:red;" class="bi-trash-fill" aria-hidden="true"></i>   </td></tr>';

                });

                $(".showTbl").hide();
                html +='<tr><td colspan="7" style="text-align: right"><strong>Total = </strong></td>';
                html +='<input type="hidden" name="vat_amount_total" value="'+vat_amount+'" id="vat_amount_total">' +
                    '<input type="hidden" name="grand_total" value="'+total+'" id="grand_total">' +
                    '<input type="hidden" name="e_total" value="'+amount+'" id="e_total">' +
                    '<td style="text-align: right"><strong><span id="sp_amount"> '+amount+'</span></strong></td><td></td><td style="text-align: right"><strong>'+vat_amount+'</strong></td> <td><strong>'+total+'</strong></td> </tr>';


                let i=2;
                $('#subElectricityTbl').html(html);
                if(accountRecord.length == 0) {
                    $("#bill_type").prop('disabled',false);
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
                    amount = temp.toFixed();
                    vat_amount = temp1.toFixed();
                    total = (parseFloat(total)+parseFloat(el.total)).toFixed();

                    html += '<tr><td style="width:5% !important;">'+ parseInt(index+1) +' </td>';
                    html += '<td style="width: 20% !important;">'+ el.income_head +' </td>';
                    html += '<td style="width: 12% !important;">'+ el.month +' </td>';
                    html += '<td style="width: 20% !important;">'+ el.remarks +' </td>';
                    html += '<td style="width: 10% !important;text-align: right">'+ el.amount +' </td>';
                    html += '<td style="width: 7% !important;">'+ el.vat +' </td>';
                    html += '<td style="width: 10% !important;text-align: right">'+ el.vat_amt +' </td>';
                    html += '<td style="width: 11% !important;text-align: right">'+ el.total +' </td>';
                    html += '<td style="width: 5% !important;"> <i onclick="Bill.editRecord('+index+')" style="cursor:pointer;color:green;" class="bi-pencil-square " aria-hidden="true"></i> ' +
                        '&nbsp;&nbsp; <i onclick="Bill.deleteRecord('+index+')" style="cursor:pointer;color:red;" class="bi-trash-fill" aria-hidden="true"></i>   </td></tr>';

                });

                $(".showTbl").hide();
                $(".subElectricityTbl").hide();

                html +='<tr><td colspan="4" style="text-align: right"><strong>Total = </strong></td>';
                html +='<input type="hidden" name="vat_amount_total" value="'+vat_amount+'" id="vat_amount_total">' +
                    '<input type="hidden" name="grand_total" value="'+total+'" id="grand_total">' +
                    '<input type="hidden" name="i_total" value="'+amount+'" id="i_total">' +
                    '<td style="text-align: right"><strong><span id="sp_amount"> '+amount+'</span></strong></td><td></td><td style="text-align: right"><strong>'+vat_amount+'</strong></td> <td style="text-align: right"><strong>'+total+'</strong></td> </tr>';

                $("#subIncomeTbl").html(html);

            }

            let  getCreditPeriod = function(id) {

                $.ajax({url: "get-credit-period/"+id, success: function(result){
                        let ar = JSON.parse(result);
                        console.log(ar);
                        customerInfo = ar;
                        Bill.loadBillForm($("#bill_type").val());
                        $("#area").val(ar.area_sft);
                        $("#rate").val(ar.rent_sft);
                        let amt = parseInt(ar.area_sft) * parseInt(ar.rent_sft);
                        if(ar.vat_exemption=='No'){
                            $('#vat').val(15);
                        }
                        $('#amount').val(amt.toFixed());
                        $("#amt_total").val(amt.toFixed());
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
                    $('#amount').val(amt.toFixed());
                    $("#amt_total").val(amt.toFixed());


                    $("#credit_period").val(customerInfo.credit_period);
                    calculateVat();

                }else if(v==29){
                    $("#area").val(customerInfo.area_sft);
                    $("#rate").val(customerInfo.rent_sft);
                    let amt = parseInt(customerInfo.area_sft) * parseInt(customerInfo.rent_sft);
                    if(customerInfo.vat_exemption=='No'){
                        $('#vat').val(15);


                    }
                    $('#amount').val(amt.toFixed());
                    $("#amt_total").val(amt.toFixed());
                    $("#credit_period").val(customerInfo.credit_period);
                    calculateVat();
                }
            }

            let electricityCalculate = function (){
                let current_reading=0;
                let pre_reading=0;
                if($("#current_reading").val()!=''){
                    current_reading = parseInt($("#current_reading").val());
                }
                if($("#pre_reading").val()!=''){
                    pre_reading = parseInt($("#pre_reading").val());
                }

                let temp = 0;
                temp = current_reading - pre_reading;
                $("#kwt").val(temp.toFixed());
                let kwt =0;
                let kwt_rate =0;
                if($("#kwt").val()!=''){
                    kwt = parseInt($("#kwt").val());
                }
                if($("#kwt_rate").val()!=''){
                    kwt_rate = parseInt($("#kwt_rate").val());
                }
                //let kwt_rate = parseInt($("#kwt_rate").val());

                let amt = kwt*kwt_rate;

                $("#electricity_amount").val(amt.toFixed());



                let v = parseFloat($("#electricity_vat").val());
                let vat = (amt*(v/100)).toFixed();
                if(isNaN(vat)){
                    console.log(vat);

                }else{
                    $("#electricity_vat_amt").val(vat);
                }

                $("#electricity_amt_total").val((parseFloat(amt)+parseFloat(vat)).toFixed());
            }

            return {
                loadBillForm:loadBillForm,
                addRecord: addRecord,
                editRecord: editRecord,
                showRecord: showRecord,
                deleteRecord: deleteRecord,
                showElectricityCharge: showElectricityCharge,
                showIncome: showIncome,
                getCreditPeriod: getCreditPeriod,
                changeOption: changeOption,
                electricityCalculate: electricityCalculate,
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
                let vat = (amount*(v/100)).toFixed();
                $("#income_vat_amt").val(vat);
                $("#income_amt_total").val((parseFloat(vat)+parseFloat(amount)).toFixed());
            }else if($("#bill_type").val() == 'Electricity'){
                if($("#electricity_vat").val()==''){
                    v = 0;
                }else{
                    v = parseInt($("#electricity_vat").val());
                }
                let amount = parseFloat($("#electricity_amount").val());
                let vat = (amount*(v/100)).toFixed();
                $("#electricity_vat_amt").val(vat);
                $("#electricity_amt_total").val((parseFloat(vat)+parseFloat(amount)).toFixed());
            }else {
                if($("#vat").val()==''){
                    v = 0;
                }else{
                    v = parseInt($("#vat").val());
                }
                let amount = parseFloat($("#amount").val());
                let vat = (amount*(v/100)).toFixed();
                $("#vat_amt").val(vat);
                $("#amt_total").val((parseFloat(vat)+parseFloat(amount)).toFixed());
            }


            // $("#vat_amount_1").html(vat);
            // $("#vat_amount").val(vat);
            // $("#grand_total_1").html((parseFloat(vat)+parseFloat(amount)).toFixed());
            // $("#grand_total").val((parseFloat(vat)+parseFloat(amount)).toFixed());

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
                $("#amount").val(amt.toFixed());
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