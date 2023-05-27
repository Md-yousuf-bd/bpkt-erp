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
                        <h5 class="card-title">Dues Statement Customer Wise
                            <button style="float: right" type="button" onclick="dpdf1();" class="btn btn-sm btn-primary float-right">Generate PDF</button>
                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_ds_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_ds_tbl')" class="btn btn-sm btn-danger float-right">Print</button>

                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.el')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Billing Period From</label>
                                    @php
                                        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                                        $year = date('Y');
                                        $years = array($year,$year-1,$year-2,$year-3,$year-4,$year-5,$year-6,$year-7,$year-8);
                                    @endphp

                                    <select class="form-control select2" name="month_from" id="month_from" >
                                        <option value="">None</option>
                                        @foreach($years as $year)
                                            @foreach($month as $row)
                                                <option value="{{$row.' '.$year}}">{{$row.' '.$year}}</option>
                                            @endforeach
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label for="status">Billing Period To</label>

                                    <select class="form-control select2" name="month_to" id="month_to" >
                                        <option value="">None</option>
                                        @foreach($years as $year)
                                            @foreach($month as $row)
                                                <option value="{{$row.' '.$year}}">{{$row.' '.$year}}</option>
                                            @endforeach
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Category</label>
                                    <select class="form-control select2" name="service" id="service">
                                        <option value="">None</option>
                                        <option value="Shop">Shop</option>
                                        <option value="Office">Office</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="category"> Service Name</label>

                                    <select class="form-control select2" name="bill_type" id="bill_type" >
                                        <option value="">None</option>
                                        <option value="Rent">Rent</option>
                                        <option value="Service Charge">Service Charge</option>
                                        <option value="Electricity">Electricity</option>
                                        <option value="Food Court Service Charge">Food Court SC</option>
                                        <option value="Special Service Charge">Special Service Charge</option>
                                        <option value="Advertisement">Advertisement</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Asset No</label>
                                    <select class="form-control select2" name="type" id="type" onchange="showParentData(this.value)">
                                        <option value="">None</option>
                                        @foreach($asset as $r)
                                            <option value="{{$r->shop_no.'@@@'.$r->customer_id}}">{{$r->shop_no}} - {{$r->name??""}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label"> Show With Child Shop</label>
                                    <select class="form-control select2" name="child_data" id="child_data" multiple>
                                        <option value="">None</option>


                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Owner </label>
                                                    <select class="form-control select2" name="owner" id="owner" >
                                                        <option value="">None</option>
                                                        @foreach($owner as $row)
                                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                                        @endforeach

                                                   
                                    </select>
                                </div>
                                <div style="margin-top: 21px;" class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showDSReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div id="show_ds_tbl">

                    </div>
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
            jqueryCalendar('date_s');
            jqueryCalendar('date_e');
        });

        function showDSReport() {
            // if($("#type").val()==''){
            //     alert('Please select Shop no');
            //     return false;
            // }
            let shop = $("#child_data").val();
            let shop_no = $("#type").val();


            if($("#child_data").val() !=''){
                if(shop_no!=''){
                    shop_no = shop_no+','+shop.join(',');
                }else{
                    shop_no = shop.join(',');
                }

            }else {

            }

            let data = {

                'month_from' : $("#month_from").val(),
                'month_to' : $("#month_to").val(),
                'shop_no' : shop_no,
                'owner' : $("#owner").val(),
                't' : 'view',
                'bill_type' : $("#bill_type").val(),
                'service' : $("#service").val(),
            }
            console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(".preloader").show();
            $.ajax({
                type: "POST",
                url: 'due-statement-customer',
                data: data,
                success: function(data){
                    $("#show_ds_tbl").html(data.result)
                    if($("#type").val()!=''){
                        let htmls = $("#type").find('option:selected').text();
                        let ht = '';
                        if($("#child_data").val() !=''){
                            var ar = $("#child_data").val();
                            console.log('sf');
                            console.log(ar);

                            ar.forEach(el=>{
                                var vd = el.split('@@@');
                                if (ht!=''){
                                    ht += ','+vd[0];
                                }else{
                                    ht = vd[0];
                                }

                            })
                        }
                        if(ht!=''){
                            $("#sp_shop").html(' | '+htmls+"("+ht+")");
                        }else{
                            $("#sp_shop").html(' | '+htmls)
                        }


                    }
                    console.log(data);
                    $(".preloader").hide();
                },
                error: function(xhr, status, error){
                    console.error(xhr);
                    console.error(error);
                    $(".preloader").hide();
                }
            });
        }
        function dpdf1(){
            let shop = $("#child_data").val();
            let shop_no = $("#type").val();


            if($("#child_data").val() !=''){
                if(shop_no!=''){
                    shop_no = shop_no+','+shop.join(',');
                }else{
                    shop_no = shop.join(',');
                }

            }else {

            }
            let data = {

              
                'month_from' : $("#month_from").val(),
                'month_to' : $("#month_to").val(),
                'shop_no' : shop_no,
                'owner' : $("#owner").val(),
                't' : 'pdf',
                'bill_type' : $("#bill_type").val(),
                'service' : $("#service").val(),
            }
            $(".preloader").show();
            console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: 'due-statement-customer',
                data: data,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response){
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "dues-statement-customer-wise.pdf";
                    link.click();
                    $(".preloader").hide();
                },
                error: function(blob){
                    $(".preloader").hide();
                    console.log(blob);
                }
            });
        }
        function showParentData(id){


// alert(id);
            if(id==''){
                // $("#child_data").html('');
                return false;
            }
            // let d = JSON.parse($("#customer_id").val());
            // console.log(d);
            // id = d.shop_no;
            // if(id==''){
            //
            // }
            $(".preloader").show();
            let ad = id.split('@@@');
            $.ajax({
                type: 'GET',
                url: '../cash-collection/get-customer-invoice/'+[ad[0]],
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
                        $("#child_data").html(html);

                    }else{
                        $("#child_data").html('<option value="">None</option>');
                    }
                },error:function(){
                    console.log(data);
                }
            });
        }
    </script>
@endsection
