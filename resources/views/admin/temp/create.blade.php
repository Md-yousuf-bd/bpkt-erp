@extends('admin.layouts.app')
<?php ini_set('max_input_vars','10000' ); ?>
@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Make Invoice</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addBulk" action="{{route('bulk.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Receivable Date</label>
                                    <input type="text" onblur="Bulk.makeDueDate(this.value,1)" id="journal_date" value="{{date('Y-m-d')}}" autocomplete="off" name="journal_date"  class="form-control" required="required">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="fine_applicable">Is fine applicable</label>
                                    <select class="form-control select2" name="fine_applicable" id="fine_applicable" onchange="if(this.value=='Yes'){$('.dueCl').show()} else{ $('.dueCl').hide()}">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>

                                    </select>

                                </div>

                        </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Type</label>
                                    <select class="form-control select2" name="type" id="type"  onchange="if(this.value==33) { $('#sp_meter_reading_date').show();} else {$('#sp_meter_reading_date').hide();} $('#oppset_id').val(0); $('#listCustomer').html('')">
                                        <option value="">None</option>
                                        <option value="Shop">Shop</option>
                                        <option value="Office">Office</option>
{{--                                        <option value="Adv">Adv</option>--}}
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Issue  Date</label>
                                    <input type="text" id="issue_date" autocomplete="off" value="{{date('Y-m-d')}}" name="issue_date"  class="form-control"  required="required"
                                    onblur="Bulk.makeDueDate(this.value,2)">
                                </div>


                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_id">Owner Name</label>
                                    <select class="form-control select2" name="owner_id" id="owner_id">
                                        <option value="0">None</option>
                                        @foreach($owner as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Billing Period</label>
                                    @php
                                        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                                        $year = date('Y');
                                    @endphp
{{--                                    <select class="form-control select2" name="month" id="month" >--}}
{{--                                        <option value="">None</option>--}}
{{--                                        @foreach($month as $row)--}}
{{--                                            <option value="{{$row.' '.$year}}">{{$row.' '.$year}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
                                    <input  readonly type="text" id="month" autocomplete="off" value="{{date('M Y')}}" name="month" class="form-control" >

                                </div>



                            </div>

                            <div class="row">
                                <div  class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="type">Category</label>
                                    <input type="hidden" name="name" id="name">
                                    <select class="form-control select2" name="category" id="category" onchange="if(this.value==33) { $('#sp_meter_reading_date').show();$('#sp_meter').show();$('#sp_asset').hide();} else {$('#sp_asset').show();$('#sp_meter').hide();$('#sp_meter_reading_date').hide();} $('#oppset_id').val(0); $('#listCustomer').html('')">
                                        )
                                        <option value="29">Rent</option>
                                        <option value="31">Service Charge</option>
                                        <option value="33">Electricity</option>
                                        <option value="34">Food Court SC</option>
                                        <option value="43">Special Service Charge</option>
                                    </select>
                                </div>
                                <div style="display: none;" class="dueCl form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label  class="  form-control-label">Due Date</label>
                                    <input type="text" id="due_date" autocomplete="off" value="{{date('Y-m-d')}}" name="due_date" value="{{date('Y-m-d')}}" class="form-control" >
                                </div>
                            </div>
                            <div class="row" style="display: none;" id="sp_meter_reading_date">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Meter reading date</label>
                                    <input type="text" id="meter_reading_date" autocomplete="off" value="{{date('Y-m-t')}}" name="meter_reading_date" value="{{date('Y-m-d')}}" class="form-control" >
                                </div>

                            </div>
                            <div class="row"  id="sp_meter_reading_date">
                                <div  id="sp_asset"   class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Shop No</label>
                                    <select multiple class="form-control select2" name="specific_shop" id="specific_shop">
                                        <option value="0">None</option>
                                        @foreach($shopList as $row)
                                            <option value="{{$row->asset_no}}">{{$row->asset_no}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="sp_meter" style="display: none;" class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Meter No</label>
                                    <select multiple class="form-control select2" name="specific_meter_no" id="specific_meter_no">
                                        <option value="0">None</option>
                                        @foreach($meterList as $row)
                                            <option value="{{$row->meter_no}}">{{$row->meter_no}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Offset Start</label>
                                    <input type="text"  autocomplete="off"  name="offest_start" id="offest_start" class="form-control" >
                                </div>
                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Offset End</label>
                                    <input type="text"  autocomplete="off"  name="offest_end" id="offest_end" class="form-control" >
                                </div>
                                <div  class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Page No</label>
                                    <select onchange="Bulk.ShowList();"  class="form-control select2" name="limit_s" id="limit_s">
                                        <option value="0">None</option>
                                        <option value="0">0-50</option>
                                        <option value="50">50-100</option>
                                        <option value="100">100-150</option>
                                        <option value="150">150-200</option>
                                        <option value="200">200-250</option>
                                        <option value="250">250-300</option>
                                        <option value="300">300-350</option>
                                        <option value="350">350-400</option>
                                        <option value="400">400-450</option>
                                        <option value="450">450-500</option>
                                        <option value="500">500-550</option>
                                        <option value="550">550-600</option>
                                        <option value="600">600-650</option>

                                    </select>
                                </div>
                            </div>
                            <br>
                            <input type="hidden" value="" id="chk_box" name="chk_box">
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button onclick="Bulk.ShowList();" type="button" class="btn btn-sm btn-success float-right">Show all active Users</button>
                                    <button onclick="Bulk.SubmitValue();" id="btnBSubmit" style=" display: none;   margin-left: 170px;
    color: #fff !important;" type="button" class="btn btn-sm btn-warning float-right">Create Invoices</button>
                                </div>
                            </div>
                        </div>
                            <div id="listCustomer">
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
            jqueryCalendar('journal_date');
            jqueryCalendar('due_date');
            jqueryCalendar('issue_date');
            jqueryCalendar('meter_reading_date');
            // monthCalendar('month');

            jQuery('form').bind('submit', function() {

                $('.checkIs').each(function(){
                    if ($(this).is(':disabled')) {
                        $(this).prop('checked', false);
                        // $('.chec_emp[value="'+$(this).val()+'"]').not(
                    }
                });
               // return false;

            let ar=    $('.checkIs:checked').map(function() {return this.value;}).get().join(',')
                $("#chk_box").val(ar);

                if(ar==''){
                    alert("Please  Checked at least one");
                    return false;
                }
                if($("#month").val()==''){
                    alert("Please Select Month");
                    return false;
                }
               // jQuery(this).find(':disabled').removeAttr('disabled');

            });
        })(jQuery);
        $(document).ready(function (){
            Bulk.makeDueDate($("#issue_date").val(),2);
        });

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
        let Bulk = function (){
            let ShowList = function () {


                if($("#month").val()==''){
                    alert("Please Select Month");
                    return false;
                }
                if($("#type").val()==''){
                    alert("Please Select type");
                    return false;
                }
                let opsset=0;
                if(typeof oppset_id !="undefined"){
                    opsset= $("#oppset_id").val();
                }

                $(".preloader").show();
                let type =$("#category").val()
               // alert($("#offest_nos").val());
                let body = {
                    'due_date': $("#due_date").val(),
                    'owner_id': $("#owner_id").val(),
                    'opsset': opsset,
                    'month': $("#month").val(),
                    'off_type': $("#type").val(),
                    'specific_shop': $("#specific_shop").val(),
                    'specific_meter_no': $("#specific_meter_no").val(),
                    'limit_s': $("#limit_s").val(),
                    'offest_start': $("#offest_start").val(),
                    'offest_end': $("#offest_end").val(),
                    'type': type,
                    '_token': "{{csrf_token()}}"
                }
                console.log(body);

                $.ajax({
                    type: 'POST',
                    url: 'show-all-customer',
                    dataType: 'json',
                    "data": body,
                    success: function (data) {
                        $(".preloader").hide();
                        if(!data.success){
                            alert("Sorry No Record Found");
                        }
                        $("#btnBSubmit").show();
                        $("#offest_nos").val('');
                        // console.log(data);
                        $("#listCustomer").html('');
                        $("#listCustomer").html(data.html);


                    },error:function(){
                        $(".preloader").hide();
                      //  console.log(data);
                    }
                });
            }
            let SubmitValue = function (){
                $('.checkIs').each(function(){
                    if ($(this).is(':disabled')) {
                        $(this).prop('checked', false);
                    }
                });
                if($("#category").val()==33){
                    let flag=0;
                    $('.checkIs').each(function(i,v){
                        console.log(i);
                        console.log(v);
                        if($("#chk_"+(i+2)).is(":checked")){
                            if($('#kwt_'+(i+2)).val() <=0){
                                flag=1;

                            }
                            if($('#kwt_'+(i+2)).val() =='NaN'){
                                flag=1;

                            }
                        }

                        // if ($(this).is(':disabled')) {
                        //     $(this).prop('checked', false);
                        // }
                    });
                    // if(flag==1){
                    //     alert("Zero or negative value not allow");
                    //     return false;
                    // }
                }
               // return false;
                let ar= $('.checkIs:checked').map(function() {return this.value;}).get().join(',')
                $("#chk_box").val(ar);

                if(ar==''){
                    alert("Please  Checked at least one");
                    return false;
                }
                if($("#month").val()==''){
                    alert("Please Select Month");
                    return false;
                }
                $("#btnBSubmit").attr('type', 'submit');
            }
            let checkAllnput = function (){

                    if ($("#checkAllss").is(':checked')) {

                        $('.checkIs').prop('checked',true);


                    } else {
                        $('.checkIs').prop('checked',false);
                    }

            }
            let currentReading = function (v){


                let vat_rate = parseInt($("#vat_rate").val())|| 0;
                let cur_reading = $("#cur_reading_"+v).val();
                let pre_reading = $("#pre_reading_"+v).val();
                let kwt = parseInt(cur_reading) - (parseInt(pre_reading)|| 0);
                $("#kwt_t"+v).html(kwt);
                $("#kwt_"+v).val(kwt);
                // if(kwt<=0){
                //     $("#cur_reading_"+v).val('');
                //     alert("Zero or negative value not allow");
                //     return false;
                // }
                let amount = parseInt(kwt)*parseFloat($("#rate_"+v).val());

                $("#amount_t"+v).html(amount.toFixed());
                $("#amount_"+v).val(amount.toFixed());
                let interst = $("#interest_"+v).val();
                interst = amount*vat_rate/100;
                $("#interest_t_"+v).html(interst.toFixed());
                $("#interest_"+v).val(interst.toFixed());
                $("#total_"+v).val((amount+interst).toFixed());
                $("#total_t"+v).html((amount+interst).toFixed());



            }
            let makeDueDate = (v,ref)=>{
                var  months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];


                if(ref==1){
                    cloneDate1 = new Date(v);
                    let year = cloneDate1.getFullYear();
                    let mm = cloneDate1.getMonth();
                    // alert(mm)
                    $("#month").val(months[mm]+' '+ year);
                }else{
                    cloneDate = new Date(v);

                    cloneDate.setDate(cloneDate.getDate() + 15);
                    let year = cloneDate.getFullYear();

                    let month = (1 + cloneDate.getMonth()).toString().padStart(2, '0');
                    let day = cloneDate.getDate().toString().padStart(2, '0');

                    $("#due_date").val(year+'-'+month+'-'+day);
                }

                if($("#category").val()==33){
                    var today =  new Date(v);
                    var today1 = new Date(today.getFullYear(), today.getMonth()+1, 0);
                    let year1 = today1.getFullYear();
                    let month1 = (1 + today1.getMonth()).toString().padStart(2, '0');
                    let day1 = today1.getDate().toString().padStart(2, '0');
                    $("#meter_reading_date").val(year1+'-'+month1+'-'+day1) ;
                }


            }
            return {
                ShowList: ShowList,
                SubmitValue: SubmitValue,
                checkAllnput: checkAllnput,
                currentReading: currentReading,
                makeDueDate: makeDueDate,

            }

        }();


    </script>
@endsection
