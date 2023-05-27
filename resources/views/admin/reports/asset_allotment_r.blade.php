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
                        <h5 class="card-title">Asset Allotment Report
                            {{-- <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('show_alot_tbl')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span> --}}

                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_alot_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_alot_tbl')" class="btn btn-sm btn-danger float-right">Print</button>

                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.el')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">

                                    <div class="form-group col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                        <label for="status"> Month</label>
                                        @php
                                            $month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                                            $year = date('Y');
                                            $years=array($year,$year-1,$year-2,$year-3);
                                            $curMotnh = date('M');
                                        @endphp
                                        <select class="form-control select2" name="date_s" id="date_s" >
                                            <option value="">None</option>
                                            @foreach($years as $year)
                                            @foreach($month as $row)
                                                 @php
                                                     $month1 = $row.' '.$year;
                                                 @endphp
                                                @if(date('Y')==$year && $row==$curMotnh)
                                                        <option value="{{$row.' '.$year}}"  selected   >{{$row.' '.$year}}</option>

                                                    @else
                                                        <option value="{{$row.' '.$year}}"  @if(trim($curMotnh)==trim($row.' '.$year)) selected @endif  >{{$row.' '.$year}}</option>

                                                    @endif
                                            @endforeach
                                            @endforeach
                                        </select>

                                    </div>

                                </div>


                                <div style="margin-top: 21px;" class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showAssetAllotmentReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div id="show_alot_tbl">

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

        function showAssetAllotmentReport() {

            let data = {

                'date_s' : $("#date_s").val(),
                // 'date_e' : $("#date_e").val(),
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
                url: 'aar',
                data: data,
                success: function(data){
                    $("#show_alot_tbl").html(data.result)
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
