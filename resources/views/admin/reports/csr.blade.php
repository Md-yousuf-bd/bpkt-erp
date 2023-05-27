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
                        <h5 class="card-title"> Daily Collection Statement
{{--                            <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span>--}}
                            <button style="float: right" type="button" onclick="crspdf1();" class="btn btn-sm btn-primary float-right">Generate PDF</button>
                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_csr_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_csr_tbl')" class="btn btn-sm btn-danger float-right">Print</button>

                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.asset-report')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status"> Date From</label>

                                    <input autocomplete="off" value="{{date('Y-m-d')}}"  required type="text" id="date_s" name="date_s"  class="form-control" >

                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Date To</label>
                                    <input autocomplete="off" value="{{date('Y-m-d')}}"  required type="text" id="date_e" name="date_e"  class="form-control" >


                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="category"> Service Name</label>

                                    <select class="form-control select2" name="service" id="service" >
                                        <option value="">None</option>
                                          <option value="Rent">Rent</option>
                                        <option value="Service Charge">Service Charge</option>
                                        <option value="Electricity">Electricity</option>
                                        <option value="Food Court Service Charge">Food Court SC</option>
                                        <option value="Special Service Charge">Special Service Charge</option>
                                        <option value="Advertisement">Advertisement</option>
                                        <!--<option value="29">Rent</option>-->
                                        <!--<option value="31">Service Charge</option>-->
                                        <!--<option value="33">Electricity</option>-->
                                        <!--<option value="34">Food Court SC</option>-->
                                        <!--<option value="43">Special Service Charge</option>-->
                                        <!--<option value="44">Advertisement</option>-->
                                    </select>

                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label for="status"> Shop No</label>

                                    <select class="form-control select2" name="shop_no" id="shop_no" >
                                        <option value="">None</option>
                                        @foreach($shops as $row)
                                            <option value="{{$row->shop_no}}">{{$row->shop_no}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label for="owner_id"> Customer Name</label>
                                    <select class="form-control select2" name="customer_name" id="customer_name" >
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->customer_id}}">{{$row->shop_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label for="floor_name"> Payment Mode</label>
                                    <select class="form-control select2" name="payment_mode" id="payment_mode" >
                                        <option value="">None</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Cash">Cash</option>
                                         <option value="Advance Deposit">Advance Deposit</option>
                                        <option value="Security Deposit">Security Deposit</option>
                                    </select>

                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="category"> Category</label>

                                    <select class="form-control select2" name="category" id="category" >
                                        <option value="">None</option>
                                            <option value="Shop">Shop</option>
                                            <option value="Office">Office</option>
                                            <option value="Others">Others</option>
                                            <option value="Advertisement">Advertisement</option>
                                            <option value="Other Income">Other Income</option>
                                    </select>

                                </div>

                                <div style="margin-top: 21px;" class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showDailyCollectionReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>



                        </div>
                    </form>

                    <div id="show_csr_tbl">

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

        function showDailyCollectionReport() {

            let data = {
                'date_s' : $("#date_s").val(),
                'date_e' : $("#date_e").val(),
                'service' : $("#service").val(),
                'customer_name' : $("#customer_name").val(),
                'payment_mode' : $("#payment_mode").val(),
                'category' : $("#category").val(),
                't' :'view',
                'shop_no' : $("#shop_no").val(),
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
                url: 'csr',
                data: data,
                success: function(data){
                    $("#show_csr_tbl").html(data.result)
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

        function  showDetailsDue(ref_id){
            let id=ref_id;
{{--            url = "{{ route('show-dues-details', ':id') }}";--}}
//             url = url.replace(':id', id);
//             console.log(url);
            window.open('show-dues-details/'+ref_id, '_blank');
        }
        function  crspdf1(){
            let data = {
                'date_s' : $("#date_s").val(),
                'date_e' : $("#date_e").val(),
                'service' : $("#service").val(),
                'customer_name' : $("#customer_name").val(),
                'payment_mode' : $("#payment_mode").val(),
                't' :'pdf',
                'shop_no' : $("#shop_no").val(),
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
                url: 'csr',
                data: data,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response){
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Daily Collection Statement.pdf";
                    link.click();
                    $(".preloader").hide();
                },
                error: function(blob){
                    $(".preloader").hide();
                    console.log(blob);
                }
            });
        }
    </script>
@endsection
