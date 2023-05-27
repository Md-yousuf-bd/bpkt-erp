@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"> Advance & Security Deposit Report
                            {{-- <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('show_asrs_tbl')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span> --}}

                            {{-- <button style="float: right" type="button" onclick="cspdf1();" class="btn btn-sm btn-primary float-right">Generate PDF</button> --}}
                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_asrs_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_asrs_tbl')" class="btn btn-sm btn-danger float-right">Print</button>

                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.asset-report')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">


                                <div class="form-group col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <label for="category"> Category </label>

                                    <select class="form-control select2" name="service" id="service" >
                                        <option value="115">Security Deposit</option>
                                        <option value="117">Advance Deposit</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                    <label for="category"> Type </label>

                                    <select class="form-control select2" name="deposit_re" id="deposit_re" >
                                        <option value="1">Deposit Required</option>
                                        <option value="2">Deposit Statement</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                                    <label for="owner_id"> Customer Name</label>
                                    <select class="form-control select2" name="customer_name" id="customer_name" >
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}">{{$row->shop_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label for="status"> Shop No</label>
                                    <select class="form-control select2" name="shop_no" id="shop_no" >
                                        <option value="">None</option>
                                        @foreach($shops as $row)
                                            <option value="{{$row->asset_no}}">{{$row->asset_no}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label for="status"> Date From</label>

                                    <input autocomplete="off" required type="text" id="date_s" name="date_s"  class="form-control" >

                                </div>
                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                    <label for="status">Date To</label>
                                    <input autocomplete="off" required type="text" id="date_e" name="date_e"  class="form-control" >


                                </div>

                                <div style="margin-top: 21px;" class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showAdvanceSecurityReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>



                        </div>
                    </form>

                    <div id="show_asrs_tbl" style="overflow: auto;height: 500px;">

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

        function showAdvanceSecurityReport() {

            let data = {
                'date_s' : $("#date_s").val(),
                'date_e' : $("#date_e").val(),
                'service' : $("#service").val(),
                'customer_name' : $("#customer_name").val(),
                'shop_no' : $("#shop_no").val(),
                'deposit_re' : $("#deposit_re").val()
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
                url: 'security-deposit',
                data: data,
                success: function(data){
                    $("#show_asrs_tbl").html(data.result)
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

        function  showSecurityAdvanceDetails(shop_no,ledger_id){
            let id=shop_no;
            window.open('show-deduction-details/'+ledger_id+'/'+shop_no, '_blank');

        }


    </script>
@endsection
