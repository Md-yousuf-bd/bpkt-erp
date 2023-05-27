@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <style>
        .dataTables_length{
            width: 2% !important;
            float: left !important;
            margin-top: -88px;
        }
        @media (min-width: 768px) {
            .col-md-2 {
                width: 13.66667% !important;
            }
        }
        .dt-buttons{
            margin-top: -40px;
        }

    </style>
    <!-- Modal -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Cash Collection List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-cash-collection'))
                                <a  href="{{route('cash-collection.create-new')}}" class="btn btn-sm  btn-outline-primary pull-right"><span class="fa fa-plus-circle"></span> Add Cash Collection</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">

                                <div style="float: right;" class="  pull-right" aria-labelledby="headingOne" >
                                    <form id="CashCListForm"  action="" method="post" class="form" >
                                        <div class="card-body" style="float:right !important;width:90%;padding-top: 0px;padding-bottom: 0px;">
                                            {{ csrf_field() }}
                                            <div class="row">

                                                <div class="form-group col-md-2 col-xs-12">
                                                    <label class="form-control-label">Date From</label>
                                                    <input autocomplete="off" value="" placeholder="Date From"  type="text" id="date_from" name="date_from"  class="form-control" >

                                                </div>
                                                <div class="form-group col-md-2 col-xs-12">
                                                    <label class="form-control-label">Date To</label>
                                                    <input autocomplete="off" value="" placeholder="Date To"  type="text" id="date_to" name="date_to"  class="form-control" >

                                                </div>
                                                <div class="form-group col-md-2 col-xs-12">
                                                    <label class="form-control-label">Shop No</label>
                                                    <input autocomplete="off" placeholder="Shop No"  type="text" id="shop_no" name="shop_no"  class="form-control" >

                                                </div>
                                                <div class="form-group col-md-3 col-xs-12">
                                                    <label class="form-control-label">Shop Name</label>
                                                    <select class="form-control select2" name="shop_name" id="shop_name" >
                                                        <option value="">None</option>
                                                        @foreach($customer as $row)
                                                            <option value="{{$row->id}}">{{$row->shop_name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2 col-xs-12">
                                                    <label class="form-control-label">Invoice No</label>
                                                    <input autocomplete="off" placeholder="Invoice No"  type="text" id="invoice_no" name="invoice_no"  class="form-control" >

                                                </div>
                                                <div class="form-group col-lg-2 col-md-2 col-sm-12 col-xs-12">
                                                    <label class="form-control-label">Category</label>
                                                    <select class="form-control select2" name="service" id="service">
                                                        <option value="">None</option>
                                                        <option value="Shop">Shop</option>
                                                        <option value="Office">Office</option>
                                                        <option value="Others">Others</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                                    <label for="category"> Type</label>

                                                    <select class="form-control select2" name="bill_type" id="bill_type" >
                                                        <option value="">None</option>
                                                        <option value="Rent">Rent</option>
                                                        <option value="Service Charge">Service Charge</option>
                                                        <option value="Electricity">Electricity</option>
                                                        <option value="Food Court Service Charge">Food Court SC</option>
                                                        <option value="Special Service Charge">Special Service Charge</option>
                                                        <option value="Advertisement">Advertisement</option>
                                                    </select>

                                                </div>
                                                <div class="form-group col-md-3 col-xs-12">
                                                    <label class="form-control-label">Posted by</label>
                                                    <select class="form-control select2" name="userid" id="userid" >
                                                        <option value="">None</option>
                                                        @foreach($users as $row)
                                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="form-group col-md-1 col-xs-12">
                                                    <br>
                                                    <button onclick="showSearchCollectList()" type="button" class="btn btn-sm btn-success btn-save" style="color:white; " >
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </table>
                            <table id="cashTbl" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th style="width: 3%;">S.L.</th>
                                    <th style="width: 177px !important;" >Action</th>
                                    <th>Bill Type</th>
                                    <th>Shop No</th>
                                    <th>Shop Name</th>
                                    <th>Invoice No</th>
                                    <th>voucher No</th>
                                    <th>Posting Date</th>
                                    <th>Effective Date</th>
                                    <th>created by</th>
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
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
@endsection

@section('uncommonInJs')
    <script>
        var userTableColumnNames=[];
        (function() {
            "use strict";
            listCView({data:{},_token: "{{csrf_token()}}"});
            jqueryCalendar('date_from');
            jqueryCalendar('date_to');
            $('#cashTbl')
                .on( 'error.dt', function ( e, settings, techNote, message ) {
                    console.log( 'An error has been reported by DataTables: ', message );
                } )
                .DataTable();
        })(jQuery);

        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        function showSearchCollectList(){
            var data = $("#CashCListForm").serializeArray();
            console.log(data)
            $('#cashTbl').DataTable().destroy();
            listCView({data,_token: "{{csrf_token()}}"});

        }
        function listCView(data) {
            $('#cashTbl').DataTable({
                "processing": true,
                "serverSide": true,
                // destroy: true,
                "searching": false,
                order: [ 2, 'desc' ],
                fixedHeader: true,
                lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
                "ajax":{
                    "url": '{!! route('cash-collection.list') !!}',
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
                    {data:'bill_type',name:'bill_type'},
                    {data:'shop_no',name:'shop_no'},
                    {data:'shop_name',name:'shop_name'},
                    {data:'invoice_no',name:'invoice_no'},
                    {data:'voucher_no',name:'voucher_no'},

                    {data:'DT_RowData.data-created_at',name:'created_at'},
                    {data:'DT_RowData.data-collection_date',name:'collection_date'},

                    {data:'DT_RowData.data-created_by',name:'created_by'},
                    {data:'DT_RowData.data-updated_at',name:'updated_at'},
                ],
                "dom": 'lBfrtip',

                buttons: [
                    {
                        extend: 'print',
                        title:'Bulk List print',
                        footer: true,
                        exportOptions: {
                            stripHtml : false,
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'excel',
                        title:'Bulk List excel',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'csv',
                        title:'Bulk List csv',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }

                    },

                    {
                        extend: 'pdf',
                        title:'Bulk List pdf',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }

                    },

                ],

                scrollX: true,
            });
            {{-- $('#cashTbl').DataTable({--}}
            {{--    processing:true,--}}
            {{--    serverSide:true,--}}
            {{--    colReorder: true,--}}
            {{--    scrollX:true,--}}
            {{--    "searching": false,--}}
            {{--    responsive: true,--}}
            {{--    order: [[0, "DESC"]],--}}
            {{--    scrollCollapse: true,--}}
            {{--    dom: "Bflrtip",--}}
            {{--    scrollY:($(window).height()*0.7)+"px",--}}

            {{--    ajax: {--}}
            {{--        url:'{!! route('cash-collection.list') !!}',--}}
            {{--        dataType: "json",--}}
            {{--        contentType: "application/json",--}}
            {{--        type: "POST",--}}
            {{--        "data": data,--}}
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
            {{--        {data:'shop_no',name:'shop_no'},--}}
            {{--        {data:'shop_name',name:'shop_name'},--}}
            {{--        {data:'invoice_no',name:'invoice_no'},--}}
            {{--        {data:'DT_RowData.data-created_by',name:'created_by'},--}}
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
            {{--            --}}{{--{--}}
            {{--            --}}{{--    extend: 'pdf',--}}
            {{--            --}}{{--    title:'{{ config('app.name', 'Laravel') }}: User List',--}}
            {{--            --}}{{--    footer: true,--}}
            {{--            --}}{{--    exportOptions: {--}}
            {{--            --}}{{--        columns: ':visible'--}}
            {{--            --}}{{--    }--}}

            {{--            --}}{{--},--}}
            {{--        {--}}
            {{--            extend: 'copy',--}}
            {{--            title:'{{ config('app.name', 'Laravel') }}: User List',--}}
            {{--            footer: true,--}}
            {{--            exportOptions: {--}}
            {{--                columns: ':visible'--}}
            {{--            }--}}

            {{--        },--}}
            {{--            --}}{{--{--}}
            {{--            --}}{{--    extend: 'csv',--}}
            {{--            --}}{{--    title:'{{ config('app.name', 'Laravel') }}: User List',--}}
            {{--            --}}{{--    footer: true,--}}
            {{--            --}}{{--    exportOptions: {--}}
            {{--            --}}{{--        columns: ':visible'--}}
            {{--            --}}{{--    }--}}

            {{--            --}}{{--},--}}

            {{--    ]--}}
            {{--});--}}

        }




    </script>

@endsection
