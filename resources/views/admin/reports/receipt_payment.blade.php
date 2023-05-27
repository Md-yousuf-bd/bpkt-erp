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
                        <h5 class="card-title">Receipt & Payment Statement
                            <button style="float: right" type="button" onclick="RPpdf();" class="btn btn-sm btn-primary float-right">Generate PDF</button>
                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_gl_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_gl_tbl')" class="btn btn-sm btn-danger float-right">Print</button>

                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form action="{{route('report.show-gl')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}

                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Date From <span style="color:red">*</span></label>
                                    <input autocomplete="off" required type="text" id="date_s" name="date_s"
                                           class="form-control">


                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Date To <span style="color:red">*</span></label>
                                    <input autocomplete="off" required type="text" id="date_e" name="date_e"
                                           class="form-control">


                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="ledger">Ledger <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="ledger" id="ledger" required>
                                        <option value="">None</option>
                                        <option value="6">Cash</option>
                                        @foreach($ledger as $r)
                                            <option value="{{$r->id}}">{{$r->head}}</option>
                                        @endforeach

                                    </select>


                                </div>

                            </div>

                            <br>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="button" onclick="showGllReport();"
                                                class="btn btn-sm btn-success float-right">Show Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div style="margin-top: -18px;" id="show_gl_tbl">

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

        function showGllReport() {
            let data = {
                'ledger': $("#ledger").val(),
                'date_s': $("#date_s").val(),
                't': 'view',
                'date_e': $("#date_e").val(),
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
                url: 'receipt-payment',
                data: data,
                success: function (data) {
                    $("#show_gl_tbl").html(data.result)
                    console.log(data);
                    $(".preloader").hide();
                },
                error: function (xhr, status, error) {
                    console.error(xhr);
                    console.error(error);
                    $(".preloader").hide();
                }
            });
        }

        function showCustomerShop(v){
            console.log(v);
            $(".preloader").show();
            $.ajax({
                type: 'GET',
                url: 'show-customer-shop/' + v,
                dataType: 'json',
                success: function (data) {
                    $(".preloader").hide();
                    console.log(data);
                    let html = '<option value="" >None</option>';
                    $.each(data, function (index, item) {
                        console.log(item);
                        html += '<option value="' + item.asset_no + '">' + item.asset_no + '</option >';

                    });

                    $("#shop_no").html(html);

                }, error: function () {
                    $(".preloader").hide();
                    console.log(data);
                }
            });
        }

        function getLedger(type) {
            $(".preloader").show();
            $.ajax({
                type: 'GET',
                url: 'ledger/' + type,
                dataType: 'json',
                success: function (data) {
                    $(".preloader").hide();
                    console.log(data);
                    let html = '<option value="0" >None</option>';
                    $.each(data.ledger, function (index, item) {
                        console.log(item);
                        html += '<option value="' + item.id + '">' + item.head + '</option >';

                    });

                    $("#ledger").html(html);

                }, error: function () {
                    $(".preloader").hide();
                    console.log(data);
                }
            });
        }
        function RPpdf(){
            let data = {
                'ledger': $("#ledger").val(),
                'date_s': $("#date_s").val(),
                't': 'pdf',
                'date_e': $("#date_e").val(),
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
                url: 'receipt-payment',
                data: data,
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response){
                    var blob = new Blob([response]);
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "Receipt-Payment-Statement.pdf";
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
