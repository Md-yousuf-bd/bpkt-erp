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
                        <h5 class="card-title">Advertisement Space List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-advertisement'))
                                <a href="{{route('advertisement.create')}}" class="btn btn-sm btn-outline-primary pull-right"><span class="fa fa-plus-circle"></span> Add Advertisement Space</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">

                                <div style="float: right;width: 82%"   class="  pull-right" aria-labelledby="headingOne" >
                                    <form id="showSearchAdvertisesListForm"  action="" method="post" class="form" >
                                        <div  style="width:90%;padding-top: 0px;padding-bottom: 0px;">
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div style="margin-top: -26px;" class="form-group col-md-3 col-xs-12">
                                                    <label class="form-control-label">Code </label>
                                                    <input type="text" id="code" autocomplete="off" name="code"  class="form-control" >

                                                </div>

                                                <div style="margin-top: -26px;" class="form-group col-md-3 col-xs-12">
                                                    <label class="form-control-label"> Space Name</label>
                                                    <input type="text" id="space_name" autocomplete="off" name="space_name"  class="form-control" >

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

                                                <div class="form-group col-md-3 col-xs-12">

                                                    <button onclick="showSearchAdvertisesList()" type="button" class="btn btn-sm btn-success btn-save" style="color:white; " >
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </table>
                            <table id="advertiseTbl" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th style="width: 3%;">S.L.</th>
                                    <th >Action</th>
                                    <th>Advert. Code</th>
                                    <th>Space Name</th>
                                    <th>Shop No.</th>
                                    <th>Customer Name</th>
                                    <th>Area</th>
                                    <th>Rate</th>
                                    <th>Effective Start Date</th>
                                    <th>Effective End Date </th>
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
            AdvertisementListView({data:{},_token: "{{csrf_token()}}"});
            $('#advertiseTbl')
                .on( 'error.dt', function ( e, settings, techNote, message ) {
                    console.log( 'An error has been reported by DataTables: ', message );
                } )
                .DataTable();


        })(jQuery);


        function showSearchAdvertisesList(){
            var data = $("#showSearchAdvertisesListForm").serializeArray();
            console.log(data)
            $('#advertiseTbl').DataTable().destroy();
            AdvertisementListView({data,_token: "{{csrf_token()}}"});

        }
        function AdvertisementListView(data) {
            console.log(data);
            $('#advertiseTbl').DataTable({
                "processing": true,
                "serverSide": true,
                // destroy: true,
                "searching": false,
                fixedHeader: true,
                lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
                "ajax":{
                    "url": '{!! route('advertisement.list') !!}',
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
                    {data:'code',name:'code'},
                    {data:'space_name',name:'space_name'},
                    {data:'asset_no',name:'asset_no'},
                    {data:'DT_RowData.data-customer-name',name:'customer_id'},
                    {data:'area',name:'area'},
                    {data:'rate',name:'rate'},
                    {data:'DT_RowData.date_convert_s',name:'date_s'},
                    {data:'DT_RowData.date_e',name:'date_e'},
                    {data:'DT_RowData.data-updated_at',name:'updated_at'},
                ],
                // "dom": 'lBfrtip',

                // buttons: [
                //     'excel',
                //     'csv',
                //     'pdf',
                //     'print'
                // ],

                scrollX: true,
            });
        }
        /*
        var csrfToken= $('meta[name="csrf-token"]').attr('content');

        var userTable= $('#assetInfo').DataTable({
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
            processing:true,
            serverSide:true,
            colReorder: true,
            scrollX:true,
            responsive: true,
            scrollCollapse: true,
            dom: "Bflrtip",
            scrollY:($(window).height()*0.7)+"px",
            // fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //     //debugger;
            //     var index = iDisplayIndexFull + 1;
            //     $("td:first", nRow).html(index);
            //     return nRow;
            // },
            ajax: {
                url:'{!! route('assets.list') !!}',
                dataType: "json",
                contentType: "application/json",
                type: "POST",
                headers : {
                    'Accept': 'application/json',
                },
                data: function ( d ) {
                    d['_token']=csrfToken;
                    return JSON.stringify( d );
                },
                complete: function(response) {
                    let result=response.responseJSON;
                },
                error: function (xhr, error, thrown) {
                    alert("An error occurred while attempting to retrieve data via ajax.\n"+thrown );
                }
            },
            createdRow: function( row, data, dataIndex ) {
                $( row ).find('td:eq(6)').attr({'data-order':data.DT_RowData['data-role'], 'data-search': data.DT_RowData['data-role']});
            },
            columns:[
                {data:'DT_RowIndex',name:'id'},
                {data:'action',name:'action',searchable: false, orderable: false},
                {data:'asset_no',name:'asset_no'},
                {data:'off_type',name:'off_type'},
                {data:'floor_name',name:'floor_name'},
                {data:'area_sft',name:'area_sft'},
                {data:'DT_RowData.data-customer-name',name:'customer_id'},
                {data:'DT_RowData.data-owner',name:'owner_id'},
                {data:'DT_RowData.date_convert_s',name:'date_s'},
                {data:'DT_RowData.date_e',name:'date_e'},
                {data:'status',name:'status'},
                {data:'DT_RowData.data-updated_at',name:'updated_at'},
            ],
            buttons: [
                {
                    extend: 'print',
                    title:'{{ config('app.name', 'Laravel') }}: User List',
                    footer: true,
                    exportOptions: {
                        stripHtml : false,
                        columns: ':visible'
                    }

                },
                {
                    extend: 'excel',
                    title:'{{ config('app.name', 'Laravel') }}: User List',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }

                },

                {
                    extend: 'copy',
                    title:'{{ config('app.name', 'Laravel') }}: User List',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }

                },


            ]
        });

*/



    </script>

@endsection
