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
                        <h5 class="card-title">Trial Balance
                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_tb_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_tb_tbl')" class="btn btn-sm btn-danger float-right">Print</button>
                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.tb')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Date From <span style="color:red">*</span></label>
                                    <input autocomplete="off" required  type="text" id="date_s" name="date_s"  class="form-control" >

                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Date To <span style="color:red">*</span></label>
                                    <input autocomplete="off" required type="text" id="date_e" name="date_e"  class="form-control" >
                                </div>
                                <div style="margin-top: 20px;" class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showTbReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>

                            <div class="row" style="display: none;">
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label for="type">Type <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="type" id="type" required>
                                        <option value="">None</option>
                                        <option value="Asset">Asset</option>
                                        <option value="Expense">Expense</option>
                                        <option value="Liability">Liability</option>
                                        <option value="Income">Income</option>
                                    </select>

                                </div>


                            </div>
{{--                            <div >--}}
{{--                                <br>--}}
{{--                                <div class="row">--}}
{{--                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
{{--                                        <button type="button" onclick="showTbReport();" class="btn btn-sm btn-success float-right">Show Report</button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </form>

                    <div id="show_tb_tbl">

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

        function showTbReport() {

            // if($("#type").val()==''){
            //     alert("Please Select Ledger Type")
            //     return false;
            // }
            if($("#date_s").val()==''){
                alert("Please Select Start Date")
                return false;
            }
            if($("#date_e").val()==''){
                alert("Please Select End Date")
                return false;
            }
            let data = {
                'type' : '',
                'date_s' : $("#date_s").val(),
                'date_e' : $("#date_e").val(),
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(".preloader").show();
            $.ajax({
                type: "POST",
                url: 'tb',
                data: data,
                success: function(data){
                    $("#show_tb_tbl").html(data.result)
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

    </script>
@endsection
