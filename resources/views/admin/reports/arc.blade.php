@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Receivable & Collection Summary Report
                            {{-- <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('show_ds_tbl')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span> --}}

                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_arc_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_arc_tbl')" class="btn btn-sm btn-danger float-right">Print</button>

                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.el')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="category"> Category</label>

                                    <select class="form-control select2" name="category" id="category" >
                                        <option value="">None</option>
                                        <option value="Rent">Rent</option>
                                        <option value="Service Charge">Service Charge</option>
                                        <option value="Electricity">Electricity</option>
                                        <option value="Income">Income</option>
                                        <option value="Advertisement">Advertisement</option>
                                        <option value="Food Court Service Charge">Food Court Service Charge</option>
                                        <option value="Special Service Charge">Special Service Charge</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="category"> Type</label>

                                    <select class="form-control select2" name="type" id="type" >
                                        <option value="">None</option>
                                        <option value="Shop">Shop</option>
                                        <option value="Office">Office</option>
                                        <!--<option value="Adv">Adv</option>-->
                                        <!--<option value="Parking">Parking</option>-->
                                        <!--<option value="Hotel">Hotel</option>-->
                                        <!--<option value="Godown">Godown</option>-->
                                        <!--<option value="Top Floor">Top Floor</option>-->
                                        <option value="Advertisement">Advertisement</option>
                                        <!--<option value="Motor Pump">Motor Pump</option>-->
                                        <!--<option value="Motor Pump Light House">Motor Pump Light House</option>-->
                                        <!--<option value="Motor Pump Officer Mess">Motor Pump Officer Mess</option>-->
                                        <!--<option value="Motor Shops">Motor Shops</option>-->
                                        <!--<option value="Officer Mess">Officer Mess</option>-->
                                        <!--<option value="Tea Stall">Tea Stall</option>-->
                                        <!--<option value="Foodcourt">Foodcourt</option>-->
                                        <option value="Others">Others</option>

                                    </select>

                                </div>

                                <div class="form-group col-lg-3 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status"> From</label>
                                    @php
                                        $month = ["Dec","Nov","Oct","Sep","Aug","Jul","Jun","May","Apr","Mar","Feb","Jan"];
                                        $year = date('Y');
                                        $years = array($year,$year-1,$year-2,$year-3,$year-4,$year-5,$year-6,$year-7);
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
                                    <label for="status"> To</label>

                                    <select class="form-control select2" name="month_to" id="month_to" >
                                        <option value="">None</option>
                                        @foreach($years as $year)
                                            @foreach($month as $row)
                                                <option value="{{$row.' '.$year}}">{{$row.' '.$year}}</option>
                                            @endforeach
                                        @endforeach
                                    </select>

                                </div>





                                <div style="margin-top: 21px;" class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showARCReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div id="show_arc_tbl">

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

        function showARCReport() {

            let data = {
                'category' : $("#category").val(),
                'type' : $("#type").val(),
                'month_from' : $("#month_from").val(),
                'month_to' : $("#month_to").val(),
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
                url: 'rcs',
                data: data,
                success: function(data){
                    $("#show_arc_tbl").html(data.result)
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

        function  showDetailsDue(ref_id,category,type){
            let id=ref_id;
{{--            url = "{{ route('show-dues-details', ':id') }}";--}}
//             url = url.replace(':id', id);
//             console.log(url);
            window.open('show-dues-details/'+ref_id+'/'+category+'/'+type, '_blank');

            // window.open('show-dues-details/'+ref_id, '_blank');
        }
    </script>
@endsection
