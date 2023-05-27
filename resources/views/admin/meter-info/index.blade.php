@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Meter Info List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-meter'))
                                <a href="{{route('meter.create')}}" class="btn btn-sm btn-outline-primary pull-right"><span class="fa fa-plus-circle"></span> Add Meter Info</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">

                                <div style="float: left;width: 100%"   class="  pull-right" aria-labelledby="headingOne" >
                                    <form id="metterListForm"  action="" method="post" class="form" >
                                        <div  style="width:100%;padding-top: 0px;padding-bottom: 0px;">
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
                                                <div style="margin-top: -26px;" class="form-group col-md-2 col-xs-12">
                                                    <label class="form-control-label">Customer Name</label>
                                                    <select class="form-control select2" name="shop_name" id="shop_name" >
                                                        <option value="">Select Customer</option>
                                                        @foreach($customer as $row)
                                                            <option value="{{$row->id}}">{{$row->shop_name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div style="margin-top: -26px;" class="form-group col-md-2 col-xs-12">
                                                    <label class="form-control-label">Shop No</label>
                                                    <select class="form-control select2" name="shop_no" id="shop_no" >
                                                        <option value="">Select Shop No</option>
                                                        @foreach($assets as $row)
                                                            <option value="{{$row->asset_no}}">{{$row->asset_no}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div style="margin-top: -26px;" class="form-group col-md-2 col-xs-12">

                                                    <label class="form-control-label">Meter No</label>
                                                    <select class="form-control select2" name="meter_no" id="meter_no" >
                                                        <option value="">Select Meter No</option>
                                                        @foreach($meter as $row)
                                                            <option value="{{$row->meter_no}}">{{$row->meter_no}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>



                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="form-group col-md-2 col-xs-12" style="float: right;">

                                                    <button onclick="showSearchMeterList()" type="button" class="btn btn-sm btn-success btn-save" style="color:white; " >
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </table>
                            <table id="merterInfoTbl" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th style="width: 3%;">S.L.</th>
                                    <th >Action</th>
                                    <th>Asset No</th>
                                    <th>meter No</th>
                                    <th>Type</th>
                                    <th>Floor  Name</th>
                                    <th>Shop/Office Name</th>
                                    <th>Owner Name</th>
                                    <th>Effective Start Date</th>
                                    <th>Effective End Date </th>
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
            // $.fn.dataTable.ext.errMode = 'none';
            meterListView({data:{},_token: "{{csrf_token()}}"});

            // $('#merterInfoTbl')
            //     .on( 'error.dt', function ( e, settings, techNote, message ) {
            //         console.log( 'An error has been reported by DataTables: ', message );
            //     } )
            //     .DataTable();
            //
            // $('#dataTables_length').parent().attr( "col-sm-12 col-md-12", "class" );
            // let userTableColumnTh=document.querySelectorAll('#assetInfo thead tr th');
            // for(let i=0; i<userTableColumnTh.length; i++){userTableColumnNames[i]=userTableColumnTh[i].innerText}
        })(jQuery);




        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        function showSearchMeterList(){
            var data = $("#metterListForm").serializeArray();
            console.log(data)
            $('#merterInfoTbl').DataTable().destroy();
           meterListView({data,_token: "{{csrf_token()}}"});

        }
        function meterListView(data) {
            console.log(data);
            $('#merterInfoTbl').DataTable({
                "processing": true,
                "serverSide": true,
                // destroy: true,
                "searching": false,
                order: [ 2, 'asc' ],
                fixedHeader: true,
                lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
                "ajax":{
                    "url": '{!! route('meter.list') !!}',
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
                    {data:'meter_no',name:'meter_no'},
                    {data:'off_type',name:'off_type'},
                    {data:'floor_name',name:'floor_name'},
                    {data:'DT_RowData.data-customer-name',name:'customer_id'},
                    {data:'DT_RowData.data-owner',name:'owner_id'},
                    {data:'DT_RowData.date_convert_s',name:'date_s'},
                    {data:'DT_RowData.date_e',name:'date_e'},
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


        {{--var userTable= $('#merterInfo').DataTable({--}}
        {{--    lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],--}}
        {{--    processing:true,--}}
        {{--    serverSide:true,--}}
        {{--    colReorder: true,--}}
        {{--    scrollX:true,--}}
        {{--    responsive: true,--}}
        {{--    "searching": false,--}}
        {{--    order: [ 2, 'asc' ],--}}
        {{--    scrollCollapse: true,--}}
        {{--    dom: "Bflrtip",--}}
        {{--    scrollY:($(window).height()*0.7)+"px",--}}
        {{--    // fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {--}}
        {{--    //     //debugger;--}}
        {{--    //     var index = iDisplayIndexFull + 1;--}}
        {{--    //     $("td:first", nRow).html(index);--}}
        {{--    //     return nRow;--}}
        {{--    // },--}}
        {{--    ajax: {--}}
        {{--        url:'{!! route('meter.list') !!}',--}}
        {{--        dataType: "json",--}}
        {{--        contentType: "application/json",--}}
        {{--        type: "POST",--}}
        {{--        headers : {--}}
        {{--            'Accept': 'application/json',--}}
        {{--        },--}}
        {{--        data: function ( d ) {--}}
        {{--            d['_token']=csrfToken;--}}
        {{--            return JSON.stringify( d );--}}
        {{--        },--}}
        {{--        complete: function(response) {--}}
        {{--            let result=response.responseJSON;--}}
        {{--        },--}}
        {{--        error: function (xhr, error, thrown) {--}}
        {{--            alert("An error occurred while attempting to retrieve data via ajax.\n"+thrown );--}}
        {{--        }--}}
        {{--    },--}}
        {{--    createdRow: function( row, data, dataIndex ) {--}}
        {{--        $( row ).find('td:eq(6)').attr({'data-order':data.DT_RowData['data-role'], 'data-search': data.DT_RowData['data-role']});--}}
        {{--    },--}}
        {{--    columns:[--}}
        {{--        {data:'DT_RowIndex',name:'id'},--}}
        {{--        {data:'action',name:'action',searchable: false, orderable: false},--}}
        {{--        {data:'asset_no',name:'asset_no'},--}}
        {{--        {data:'meter_no',name:'meter_no'},--}}
        {{--        {data:'off_type',name:'off_type'},--}}
        {{--        {data:'floor_name',name:'floor_name'},--}}
        {{--        {data:'DT_RowData.data-customer-name',name:'customer_id'},--}}
        {{--        {data:'DT_RowData.data-owner',name:'owner_id'},--}}
        {{--        {data:'DT_RowData.date_convert_s',name:'date_s'},--}}
        {{--        {data:'DT_RowData.date_e',name:'date_e'},--}}
        {{--        {data:'status',name:'status'},--}}
        {{--        {data:'DT_RowData.data-updated_at',name:'updated_at'},--}}
        {{--    ],--}}
        {{--    buttons: [--}}
        {{--        {--}}
        {{--            extend: 'print',--}}
        {{--            title:'{{ config('app.name', 'Laravel') }}: User List',--}}
        {{--            footer: true,--}}
        {{--            exportOptions: {--}}
        {{--                stripHtml : false,--}}
        {{--                columns: ':visible'--}}
        {{--            }--}}

        {{--        },--}}
        {{--        {--}}
        {{--            extend: 'excel',--}}
        {{--            title:'{{ config('app.name', 'Laravel') }}: User List',--}}
        {{--            footer: true,--}}
        {{--            exportOptions: {--}}
        {{--                columns: ':visible'--}}
        {{--            }--}}

        {{--        },--}}

        {{--        {--}}
        {{--            extend: 'copy',--}}
        {{--            title:'{{ config('app.name', 'Laravel') }}: User List',--}}
        {{--            footer: true,--}}
        {{--            exportOptions: {--}}
        {{--                columns: ':visible'--}}
        {{--            }--}}

        {{--        },--}}


        {{--    ]--}}
        {{--});--}}





    </script>

@endsection
