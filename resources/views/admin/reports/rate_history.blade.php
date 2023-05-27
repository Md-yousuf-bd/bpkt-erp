@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"> Rate & Allotment History
                            {{-- <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('show_ds_tbl')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span> --}}

                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_rate_h_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_rate_h_tbl')" class="btn btn-sm btn-danger float-right">Print</button>

                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.rate-history')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">


                                <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="owner_id"> Customer Name</label>
                                    <select class="form-control select2" name="customer_name" id="customer_name" >
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->customer_id}}">{{$row->shop_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="status"> Shop No</label>
                                    <select class="form-control select2" name="shop_no" id="shop_no" >
                                        <option value="">None</option>
                                        @foreach($shops as $row)
                                            <option value="{{$row->asset_no}}">{{$row->asset_no}}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="status"> Category</label>
                                    <select class="form-control select2" name="category" id="category" >
                                        <option value="">None</option>
                                        <option value="Shop">Shop</option>
                                        <option value="Office">Office</option>
                                        <option value="Others">Others</option>

                                    </select>

                                </div>



                                <div style="margin-top: 21px;" class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showARCReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>


                        </div>
                    </form>

                    <div id="show_rate_h_tbl">

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
                'customer' : $("#customer_name").val(),
                'shop_no' : $("#shop_no").val(),
                'category' : $("#category").val(),
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
                url: 'rate-history',
                data: data,
                success: function(data){
                    $("#show_rate_h_tbl").html(data.result)
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
