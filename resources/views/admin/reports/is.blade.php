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
                        <h5 class="card-title">Income Statement
                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_is_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_is_tbl')" class="btn btn-sm btn-danger float-right">Print</button>
                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.is')}}" method="post" class="">
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


                                <div style="margin-top: 21px;" class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showIsReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div id="show_is_tbl">

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

        function showIsReport() {


            if($("#date_s").val()==''){
                alert("Please Select Start Date")
                return false;
            }
            if($("#date_e").val()==''){
                alert("Please Select End Date")
                return false;
            }
            let data = {
                'date_s' : $("#date_s").val(),
                'date_e' : $("#date_e").val(),
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
                url: 'is',
                data: data,
                success: function(data){
                    $("#show_is_tbl").html(data.result)
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
