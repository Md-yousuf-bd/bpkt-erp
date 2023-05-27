@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection
<style>
    .dataTables_length{
        margin-top : -35px;
        margin-left: 10px;
        float: left !important;
    }
    .dataTables_wrapper .dataTables_length {
        float:left;
        position: absolute;
        width: 20px !important;
    }
</style>
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Asset Info List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-assets'))
                                <a href="{{route('assets.create')}}" class="btn btn-sm btn-outline-primary pull-right"><span class="fa fa-plus-circle"></span> Add Asset Info</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">

                                <div style="float: right;width: 82%"   class="  pull-right" aria-labelledby="headingOne" >
                                    <form id="assetListForm"  action="" method="post" class="form" >
                                        <div  style="width:90%;padding-top: 0px;padding-bottom: 0px;">
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div style="margin-top: -26px;" class="form-group col-md-2 col-xs-12">
                                                    <label class="form-control-label">Owner </label>
                                                    <select class="form-control select2" name="owner" id="owner" >
                                                        <option value="">None</option>
                                                        @foreach($owner as $row)
                                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div style="margin-top: -26px;" class="form-group col-md-2 col-xs-12">
                                                    <label class="form-control-label">Floor Name </label>
                                                    <select class="form-control select2" name="floor_name" id="floor_name" >
                                                        <option value="">None</option>
                                                        @foreach($floor as $row)
                                                            <option value="{{$row->floor_name}}">{{$row->floor_name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>

                                                <div style="margin-top: -26px;" class="form-group col-md-2 col-xs-12">
                                                    <label class="form-control-label"> Type</label>
                                                    <select class="form-control select2" name="date_type" id="date_type" >
                                                        <option value="">None</option>
                                                        <option value="Shop">Shop</option>
                                                        <option value="Office">Office</option>
                                                        <option value="Adv">Adv</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                </div>
                                                <div style="margin-top: -26px;" class="form-group col-md-3 col-xs-12">
                                                    <label class="form-control-label">Customer Name</label>
                                                    <select class="form-control select2" name="shop_name" id="shop_name" >
                                                        <option value="">None</option>
                                                        @foreach($customer as $row)
                                                            <option value="{{$row->id}}">{{$row->shop_name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div style="margin-top: -26px;" class="form-group col-md-3 col-xs-12">
                                                    <label class="form-control-label">Shop No</label>
                                                    <select class="form-control select2" name="shop_no" id="shop_no" >
                                                        <option value="">None</option>
                                                        @foreach($assets as $row)
                                                            <option value="{{$row->asset_no}}">{{$row->asset_no}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>


                                                <div class="form-group col-md-3 col-xs-12">

                                                    <button onclick="showSearchAssetsList()" type="button" class="btn btn-sm btn-success btn-save" style="color:white; " >
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </table>
                            <table id="assetInfoTbl" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th style="width: 3%;">S.L.</th>
                                    <th >Action</th>
                                    <th>Asset No</th>
                                    <th>Shop Name</th>
                                    <th>Type</th>
                                    <th>Floor  Name</th>
                                    <th>Area (Sft)</th>
                                    <th>Rate (Sft)</th>
                                    <th>Shop/Office Name</th>
                                    <th>Owner Name</th>
                                    <th>Bill Start Date</th>
                                    <th>Bill End Date </th>
                                    <th> Last increment date </th>
                                    <th>Status</th>
                                    <th >@lang('commons/table_header.Last Updated At')</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .content -->

@endsection

@section('uncommonExJs')
    @include('admin.layouts.commons.dataTableJs')
@endsection

@section('uncommonInJs')
    <script>
        var userTableColumnNames=[];
        (function() {
            "use strict";
           $.fn.dataTable.ext.errMode = 'none';
            AssetListView({data:{},_token: "{{csrf_token()}}"});
            $('#assetInfoTbl')
                .on( 'error.dt', function ( e, settings, techNote, message ) {
                    console.log( 'An error has been reported by DataTables: ', message );
                } )
                .DataTable();

            $('#dataTables_length').parent().attr( "col-sm-12 col-md-12", "class" );
        })(jQuery);


        function showSearchAssetsList(){
            var data = $("#assetListForm").serializeArray();
            console.log(data)
            $('#assetInfoTbl').DataTable().destroy();
            AssetListView({data,_token: "{{csrf_token()}}"});

        }
        function AssetListView(data) {
            console.log(data);
            $('#assetInfoTbl').DataTable({
                "processing": true,
                "serverSide": true,
                // destroy: true,
                "searching": false,
                order: [ 2, 'asc' ],
                fixedHeader: true,
                lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
                "ajax":{
                    "url": '{!! route('assets.list') !!}',
                    "dataType": "json",
                    "type": "POST",
                    "data": data,
                    "error": function (xhr, error, code) {
                        console.log(xhr.status);
                        if(xhr.status==500){
                            alert("Sorry! Can not Process it");
                        }
                        console.log(error);
                        console.log(code);
                        // ShowDataTable(null);
                    }
                },
                "sScrollY" : "400",
                "sScrollX" : true,
                oLanguage: {sProcessing: "<div  id='loader' style='background: black;padding:10px;color:#fff; font-size:15px;z-index: 5999999 !important;' > Loading.... </div>"},
                "columns": [
                    {data:'DT_RowIndex',name:'id'},
                    {data:'action',name:'action',searchable: false, orderable: false},
                    {data:'asset_no',name:'asset_no'},
                    {data:'shop_name',name:'shop_name'},
                    {data:'off_type',name:'off_type'},
                    {data:'floor_name',name:'floor_name'},
                    {data:'area_sft',name:'area_sft'},
                    {data:'rate',name:'rate'},
                    {data:'DT_RowData.data-customer-name',name:'customer_id'},
                    {data:'DT_RowData.data-owner',name:'owner_id'},
                    {data:'DT_RowData.date_convert_s',name:'date_s'},
                    {data:'DT_RowData.date_e',name:'date_e'},
                    {data:'DT_RowData.last_increment_date',name:'last_increment_date'},
                    {data:'status',name:'status'},
                    {data:'DT_RowData.data-updated_at',name:'updated_at'},
                ],
                "dom": 'lBfrtip',

                buttons: [
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ],

                scrollX: true,
            });
        }



    </script>

@endsection
