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
                        <h5 class="card-title"> Asset List Report
                            {{-- <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('show_ass_tbl')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span> --}}

                            <button style="float: right;margin-left: 5px;margin-right: 5px;" type="button" onclick="exportToExcel('show_ass_tbl');" class="btn btn-sm btn-success float-right">Generate Excel</button>
                            <button style="float: right;" type="button" onclick="printDiv('show_ass_tbl')" class="btn btn-sm btn-danger float-right">Print</button>

                        </h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form  action="{{route('report.asset-report')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
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
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label for="status"> Status</label>

                                    <select class="form-control select2" name="status" id="status" >
                                        <option value="">None</option>
                                        <option value="Allotted & Open">Allotted & Open</option>
                                        <option value="Allotted & Closed">Allotted & Closed</option>
                                        <option value="Un-allotted">Un-allotted</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label for="owner_id"> Owner Type</label>
                                    <select class="form-control select2" name="owner_id" id="owner_id" >
                                        <option value="">None</option>
                                        <option value="7">BPKT</option>
                                        <option value="6">Concord</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <label for="floor_name"> Floor</label>
                                    <select class="form-control select2" name="floor_name" id="floor_name" >
                                        <option value="">None</option>
                                        @foreach($floor as $r)
                                            <option value="{{$r->name}}">{{$r->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div style="margin-top: 21px;" class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <button type="button" onclick="showAssetRCReport();" class="btn btn-sm btn-success float-right">Show Report</button>
                                </div>
                            </div>



                        </div>
                    </form>

                    <div id="show_ass_tbl">

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

        function showAssetRCReport() {

            let data = {
                'category' : $("#category").val(),
                'status' : $("#status").val(),
                'owner_id' : $("#owner_id").val(),
                'floor_name' : $("#floor_name").val(),
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
                url: 'asset-report',
                data: data,
                success: function(data){
                    $("#show_ass_tbl").html(data.result)
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
    </script>
@endsection
