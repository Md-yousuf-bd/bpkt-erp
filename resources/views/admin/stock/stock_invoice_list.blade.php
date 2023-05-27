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
                        <h5 class="card-title">Stock Invoice List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-stock'))
                                <a href="{{route('stock.create')}}" class="btn btn-sm btn-outline-primary pull-right"><span class="fa fa-plus-circle"></span> Add Stock Item</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table id="stockInfoTbl" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th style="width: 3%;">S.L.</th>
                                    <th>Action</th>
                                    <th>Voucher no.</th>
                                    <th>Purchase Ref No.</th>
                                    <th>Total</th>
                                    <th>Due</th>
                                    <th>Due Date</th>
                                    <th>@lang('commons/table_header.Last Updated At')</th>
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
            StockListView({data:{},_token: "{{csrf_token()}}"});
            $('#stockInfoTbl')
                .on( 'error.dt', function ( e, settings, techNote, message ) {
                    console.log( 'An error has been reported by DataTables: ', message );
                } )
                .DataTable();

            $('#dataTables_length').parent().attr( "col-sm-12 col-md-12", "class" );
        })(jQuery);


        function showSearchstockList(){
            var data = $("#stockListForm").serializeArray();
            console.log(data)
            $('#stockInfoTbl').DataTable().destroy();
            StockListView({data,_token: "{{csrf_token()}}"});

        }


        function StockListView (data) {
            console.log(data);
            $('#stockInfoTbl').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [[2, "DESC"]],
                "searching": false,
                fixedHeader: true,
                lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
                "ajax":{
                    "url": '{!! route('stock-invoice.list') !!}',
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
                    {data: 'id', name:'id'},
                    {data:'action',name:'action',searchable: false, orderable: false},
                    {data:'voucher_no',name:'voucher_no'},
                    {data:'purchase_ref_no',name:'purchase_ref_no'},
                    {data:'invoice_total',name:'invoice_total'},
                    {data:'due_amount',name:'due_amount'},
                    {data:'due_date',name:'due_date'},
                    {data:'updated_at',name:'updated_at'},
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





    </script>

@endsection
