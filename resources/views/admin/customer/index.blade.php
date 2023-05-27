@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <style>
        .dataTables_length{
            width: 2% !important;
            float: left !important;
            margin-top: -46px;
        }
        @media (min-width: 768px) {
            .col-md-2 {
                width: 13.66667% !important;
            }
        }
        .dt-buttons{
            margin-top: 2px;
        }

    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Customer Info List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-customer'))
                                <a href="{{route('customer.create')}}" class="btn btn-sm  btn-outline-primary pull-right"><span class="fa fa-plus-circle"></span> Add Customer Info</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">

                            <div style="float: right;width: 80%;" class=" pull-right" aria-labelledby="headingOne" >
                                    <form id="bulkListForm1"  action="" method="post" class="form" >
                                        <div class="card-body" style="float:right !important;width:90%;padding-top: 0px;padding-bottom: 0px;">
                                            {{ csrf_field() }}
                                            <div class="row">

                                                <div class="form-group col-md-6 col-xs-12">
                                                    <label class="form-control-label">Customer Name</label>
                                                    <select class="form-control select2" name="shop_name" id="shop_name" >
                                                        <option value="">None</option>
                                                        @foreach($customer as $row)
                                                            <option value="{{$row->id}}">{{$row->shop_name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>



                                                <div class="form-group col-md-1 col-xs-12">
                                                    <br>
                                                    <button onclick="showCustomerList()" type="button" class="btn btn-sm btn-success btn-save" style="color:white; " >
                                                        <i class="fa fa-search"></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </table>
                            <table id="customerTbl" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th>S.L.</th>
                                    <th style="width:320px !important;">Action</th>

                                    <th>Customer Id</th>
{{--                                    <th>Shop No</th>--}}
                                    <th style="width:320px !important;">Customer Name</th>
                                    <th>Asset No</th>
                                    <th>Owner Name</th>

                                    <th>Owner Contact No</th>
                                    <th>Owner NID</th>
                                    <th>E-TIN</th>
                                    <th>Email</th>
                                    <th>Address</th>
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

    <!-- Modal -->
    <div class="modal fade  " id="myModal" role="dialog" >
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content col-lg-12">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" onclick="CloseModal()">&times;</button>
                    <h4 style="color:red;"><span class="glyphicon glyphicon-lock"></span> Login</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <label for="usrname"><span class="glyphicon glyphicon-user"></span> Username</label>
                            <input type="text" class="form-control" id="usrname" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
                            <input type="text" class="form-control" id="psw" placeholder="Enter password">
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" value="" checked>Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-default btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Login</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                    <p>Not a member? <a href="#">Sign Up</a></p>
                    <p>Forgot <a href="#">Password?</a></p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('uncommonExJs')
    @include('admin.layouts.commons.dataTableJs')
@endsection

@section('uncommonInJs')
    <script>
        $(document).ready(function () {
            $('#myModal').modal({
                backdrop: 'static',
                keyboard: false
            })
        });
        var userTableColumnNames=[];
        (function() {
            "use strict";
            listView({data:{},_token: "{{csrf_token()}}"});
            // let userTableColumnTh=document.querySelectorAll('#customerTbl thead tr th');
            // for(let i=0; i<userTableColumnTh.length; i++){userTableColumnNames[i]=userTableColumnTh[i].innerText}
        })(jQuery);

        var customLen=0;
        // $(window).on('load',function(){
        //     set_custom_length();
        // });

        // var csrfToken= $('meta[name="csrf-token"]').attr('content');
        function showCustomerList(){
            var data = $("#bulkListForm1").serializeArray();
            console.log(data)
            $('#customerTbl').DataTable().destroy();
            listView({data,_token: "{{csrf_token()}}"});

        }

        function listView(data) {
            console.log(data);
            $('#customerTbl').DataTable({
                "processing": true,
                "serverSide": true,
                // destroy: true,
                "searching": false,
                order: [ 2, 'desc' ],
                fixedHeader: true,
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                "ajax":{
                    "url": '{!! route('customer.list') !!}',
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
                oLanguage: {sProcessing: "<div  id='loader' style='background: black;padding:10px;color:#fff; font-size:15px;z-index: 5999999 !important;' > Loading.... </div>"},

                columns:[
                    {data:'sl',name:'sl'},
                    {data:'action',name:'action',searchable: false, orderable: false},
                    {data:'id',name:'id'},
                    // {data:'shop_no',name:'shop_no' },

                    {"data": "shop_name",searchable: false,orderable: false,
                        render:function( data, type, row  ) {
                            console.log(data);
                            return '<div style="text-align: left !important; white-space: normal;width: 300px;word-wrap: break-word;">' +  data + ' </div>';


                        }
                    },
                    {data:'asset_no',name:'asset_no' ,searchable: false,orderable: false,},
                    {"data": "owner_name",
                        render:function( data, type, row  ) {
                            console.log(data);
                            return '<div style="text-align: left !important; white-space: normal;width: 300px;word-wrap: break-word;">' +  data + ' </div>';


                        }
                    },

                    {data:'owner_contact',name:'owner_contact'},
                    {data:'owner_nid',name:'owner_nid'},
                    {data:'etin',name:'etin'},
                    {data:'email',name:'email'},
                    {data:'owner_address',name:'owner_address'},
                    {data:'status',name:'status'},
                    {data:'updated_at',name:'updated_at'},
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
        }
        {{--var userTable= $('#customerTbl').DataTable({--}}
        {{--    lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],--}}
        {{--    processing:true,--}}
        {{--    serverSide:true,--}}
        {{--    colReorder: true,--}}
        {{--    scrollX:true,--}}
        {{--    "searching": false,--}}
        {{--    responsive: true,--}}
        {{--    order: [ 2, 'desc' ],--}}
        {{--    scrollCollapse: true,--}}

        {{--    dom: "Bflrtip",--}}
        {{--    scrollY:($(window).height()*0.7)+"px",--}}
        {{--    ajax: {--}}
        {{--        url:'{!! route('customer.list') !!}',--}}
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
        {{--        {data:'id',name:'id'},--}}
        {{--        // {data:'shop_no',name:'shop_no' },--}}

        {{--        {"data": "shop_name",searchable: false,orderable: false,--}}
        {{--            render:function( data, type, row  ) {--}}
        {{--                console.log(data);--}}
        {{--                return '<div style="text-align: left !important; white-space: normal;width: 300px;word-wrap: break-word;">' +  data + ' </div>';--}}


        {{--            }--}}
        {{--        },--}}
        {{--        {data:'asset_no',name:'asset_no' ,searchable: false,orderable: false,},--}}
        {{--        {"data": "owner_name",--}}
        {{--            render:function( data, type, row  ) {--}}
        {{--                console.log(data);--}}
        {{--                return '<div style="text-align: left !important; white-space: normal;width: 300px;word-wrap: break-word;">' +  data + ' </div>';--}}


        {{--            }--}}
        {{--        },--}}

        {{--        {data:'owner_contact',name:'owner_contact'},--}}
        {{--        {data:'owner_nid',name:'owner_nid'},--}}
        {{--        {data:'etin',name:'etin'},--}}
        {{--        {data:'email',name:'email'},--}}
        {{--        {data:'owner_address',name:'owner_address'},--}}
        {{--        {data:'status',name:'status'},--}}
        {{--        {data:'DT_RowData.data-updated_at',name:'updated_at'},--}}
        {{--    ],--}}
        {{--    initComplete: function ()--}}
        {{--    {--}}
        {{--        this.api().columns([2,3]).every( function () //Columnas a mostrar--}}
        {{--        {--}}
        {{--            var column = this;--}}
        {{--            var select = $('<select><option value=""></option></select>')--}}
        {{--                .appendTo( $(column.footer()).empty() )--}}
        {{--                .on( 'change', function () {var val = $.fn.dataTable.util.escapeRegex($(this).val());--}}
        {{--                    column--}}
        {{--                        .search( val ? '^'+val+'$' : '', true, false )--}}
        {{--                        .draw();--}}
        {{--                });--}}
        {{--            column.data().unique().sort().each( function ( d, j )--}}
        {{--            {--}}
        {{--                select.append( '<option value="'+d+'">'+d+'</option>' )--}}
        {{--            });--}}
        {{--        });--}}
        {{--    },--}}
        {{--    buttons: [--}}
        {{--        {--}}
        {{--            extend: 'print',--}}
        {{--            title:'{{ config('app.name', 'Laravel') }}: Owner List',--}}
        {{--            footer: true,--}}
        {{--            exportOptions: {--}}
        {{--                stripHtml : false,--}}
        {{--                columns: ':visible'--}}
        {{--            }--}}

        {{--        },--}}
        {{--        {--}}
        {{--            extend: 'excel',--}}
        {{--            title:'{{ config('app.name', 'Laravel') }}: Owner List',--}}
        {{--            footer: true,--}}
        {{--            exportOptions: {--}}
        {{--                columns: ':visible'--}}
        {{--            }--}}

        {{--        },--}}
        {{--        --}}{{--{--}}
        {{--        --}}{{--    extend: 'pdf',--}}
        {{--        --}}{{--    title:'{{ config('app.name', 'Laravel') }}: User List',--}}
        {{--        --}}{{--    footer: true,--}}
        {{--        --}}{{--    exportOptions: {--}}
        {{--        --}}{{--        columns: ':visible'--}}
        {{--        --}}{{--    }--}}

        {{--        --}}{{--},--}}
        {{--        {--}}
        {{--            extend: 'copy',--}}
        {{--            title:'{{ config('app.name', 'Laravel') }}: Owner List',--}}
        {{--            footer: true,--}}
        {{--            exportOptions: {--}}
        {{--                columns: ':visible'--}}
        {{--            }--}}

        {{--        },--}}
        {{--        --}}{{--{--}}
        {{--        --}}{{--    extend: 'csv',--}}
        {{--        --}}{{--    title:'{{ config('app.name', 'Laravel') }}: User List',--}}
        {{--        --}}{{--    footer: true,--}}
        {{--        --}}{{--    exportOptions: {--}}
        {{--        --}}{{--        columns: ':visible'--}}
        {{--        --}}{{--    }--}}

        {{--        --}}{{--},--}}
        {{--        {--}}
        {{--            text: 'Column Settings',--}}
        {{--            action: function ( e, dt, node, config ) {--}}
        {{--                showUserTablesCombinationModal(userTableColumnNames,'Users','userTable');--}}
        {{--            }--}}
        {{--        }--}}
        {{--    ]--}}
        {{--});--}}

        $(window).on('load',function () {
            $('#userTable').dataTable().fnSort([[0,'desc']]);
            setTimeout(function () {
                get_set_combination(userTable,'userTable',userTableColumnNames);
            },500);
        });

        function openCustomerModal() {
             // initialized with no keyboard
            $('#myModal').modal('show')

        }
        function CloseModal() {
            $("#myModal").modal("hide");
        }
        function set_custom_length()
        {
            var button = document.createElement("button");
            button.innerHTML="Custom";
            var node = document.createElement("input");
            node.type='number';
            node.id='userTable_length_custom';
            node.classList.add("form");
            node.classList.add("form-control");
            node.setAttribute('value','5000');
            node.setAttribute('onkeyup','changeLength2(event,this)');
            button.setAttribute('onclick','changeLength()');
            node.setAttribute("style", "float:right !important; width:120px !important; margin-left:5px!important;");
            button.setAttribute("style", "float:right !important; margin-left:-1px!important; height:29px;");
            let el = document.getElementById("userTable_length");
            el.querySelector("label").appendChild(button);
            el.querySelector("label").appendChild(node);
        }
        function changeLength(){
            let customLength=document.getElementById("userTable_length_custom").value;
            userTable.page.len(customLength).draw();
        }

        function changeLength2(event,ele){
            let customLength=ele.value;
            if (event.keyCode === 13) {
                event.preventDefault();
                userTable.page.len(customLength).draw();
            }
        }


    </script>

@endsection
