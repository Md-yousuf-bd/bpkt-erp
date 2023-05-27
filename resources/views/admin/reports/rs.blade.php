@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection
<script type="text/javascript">


</script>
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title" >Receivable Statement

                            <button style="float: right" type="button" onclick="pdf1();" class="btn btn-sm btn-primary float-right">Generate PDF</button>
                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_rs_tbl');" class="btn btn-sm btn-primary float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_rs_tbl')" class="btn btn-sm btn-primary float-right">Print</button>

                        </h5>

                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.rs')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label for="type">Type <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="type" id="type" required>

                                        <option value="1">Dues Statement</option>
                                        <option value="2">Paid Statement</option>
                                    </select>

                                </div>


                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Date From </label>
                                    <input autocomplete="off" required  type="text" id="date_s" name="date_s"  class="form-control" >

                                </div>
                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Date To </label>
                                    <input autocomplete="off" required type="text" id="date_e" name="date_e"  class="form-control" >
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
                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label for="customer">Customer </label>
                                    <select class="form-control select2" name="customer" id="customer" >
                                        <option value="">All Customer</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}">{{$row->shop_name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                                    <label for="customer">Shop No </label>
                                    <select class="form-control select2" name="shop_no" id="shop_no" onchange="showParentData(this.value)">
                                        <option value="">All Shop</option>
                                        @foreach($asset as $row)
                                            <option value="{{$row->shop_no.'@@@'.$row->customer_id}}">{{$row->shop_no}} - {{$row->name??""}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label"> Show With Child Shop</label>
                                    <select class="form-control select2" name="child_data" id="child_data" multiple>
                                        <option value="">None</option>


                                    </select>
                                </div>

                                <div style="margin-top: 26px;" class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showRsReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div id="show_rs_tbl">

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('uncommonExJs')
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.min.js"></script>
@endsection

@section('uncommonInJs')
    <script>
        $(document).ready(function () {
            jqueryCalendar('date_s');
            jqueryCalendar('date_e');
        });

        function showRsReport() {

            if($("#type").val()==''){
                alert("Please Select Ledger Type")
                return false;
            }
            // if($("#date_s").val()==''){
            //     alert("Please Select Start Date")
            //     return false;
            // }
            // if($("#date_e").val()==''){
            //     alert("Please Select End Date")
            //     return false;
            // }
            let shop = $("#child_data").val();
            let shop_no = $("#shop_no").val();
            if($("#child_data").val() !=''){
                if(shop_no!=''){
                    shop_no = shop_no+','+shop.join(',');
                }else{
                    shop_no = shop.join(',');
                }

            }
            let data = {
                'type' : $("#type").val(),
                'date_s' : $("#date_s").val(),
                'date_e' : $("#date_e").val(),
                'customer' : $("#customer").val(),
                'shop_no' : shop_no,
                'service' : $("#service").val(),
                't' : 'view',
                'bill_type' : $("#bill_type").val(),
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
                url: 'rs',
                data: data,
                success: function(data){
                    $("#show_rs_tbl").html(data.result)
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
        function pdf1(){
            let shop = $("#child_data").val();
            let shop_no = $("#shop_no").val();
            if($("#child_data").val() !=''){
                if(shop_no!=''){
                    shop_no = shop_no+','+shop.join(',');
                }else{
                    shop_no = shop.join(',');
                }

            }
            let data = {
                'type' : $("#type").val(),
                'date_s' : $("#date_s").val(),
                'date_e' : $("#date_e").val(),
                'customer' : $("#customer").val(),
                'shop_no' : shop_no,
                'service' : $("#service").val(),
                't' : 'pdf',
                'bill_type' : $("#bill_type").val(),
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
                url: 'rs',
                data: data,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response){
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "receivable-statement.pdf";
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
        function pdf(){
            const element = document.getElementById("show_rs_tbl");

            const opt = {
                filename: "methodo.pdf",
                image: { type: "pdf", quality: 1 },
                html2canvas: {
                    scale: 10,
                    logging: true,
                    letterRendering: true,
                    useCORS: true,
                },
                jsPDF: {
                    unit: "mm",
                    format: "A3",
                    orientation: "landscape",
                },
            };
            // var opt = {
            //     jsPDF: {
            //         format: 'A4',
            //         unit: 'mm',
            //         orientation: "landscape",
            //     },
            //     html2canvas:  {  scale: 10, width: 1080, height: 1920, useCORS: true,     logging: true},
            //     margin: 1,
            //     image: {type: 'jpeg', quality: 1}
            // };

            // Promise-based usage:
            html2pdf()
                .set(opt)
                .from(element)
                .toPdf()
                .save();
        }
    </script>
@endsection
