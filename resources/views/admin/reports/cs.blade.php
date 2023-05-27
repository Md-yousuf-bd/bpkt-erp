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
                        <h5 class="card-title">Collection Statement
{{--                            <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('show_elm_tbl')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span>--}}
                            <button style="float: right" type="button" onclick="cspdf1();" class="btn btn-sm btn-primary float-right">Generate PDF</button>
                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_elm_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_elm_tbl')" class="btn btn-sm btn-danger float-right">Print</button>

                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.el')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Billing Period</label>
                                    @php
                                        $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                                        $year = date('Y');
                                    @endphp
                                    <select class="form-control select2" name="month" id="month" >
                                        <option value="">None</option>
                                        @foreach($month as $row)
                                            <option value="{{$row.' '.$year}}">{{$row.' '.$year}}</option>
                                        @endforeach
                                    </select>

                                </div>
{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--                                    <label class="form-control-label">Type</label>--}}
{{--                                    <select class="form-control select2" name="type" id="type">--}}
{{--                                        <option value="">None</option>--}}
{{--                                        <option value="Shop">Shop</option>--}}
{{--                                        <option value="Office">Office</option>--}}

{{--                                    </select>--}}
{{--                                </div>--}}



                                <div style="margin-top: 21px;" class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showElReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div id="show_elm_tbl">

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

        function showElReport() {


            // if($("#date_s").val()==''){
            //     alert("Please Select Start Date")
            //     return false;
            // }
            // if($("#date_e").val()==''){
            //     alert("Please Select End Date")
            //     return false;
            // }
            let data = {

                'month' : $("#month").val(),
                't' : 'view',
                'off_type' : $("#type").val(),
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
                url: 'el',
                data: data,
                success: function(data){
                    $("#show_elm_tbl").html(data.result)
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
        function  cspdf1(){
            let data = {

                'month' : $("#month").val(),
                't' : 'pdf',
                'off_type' : $("#type").val(),
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
                url: 'el',
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
