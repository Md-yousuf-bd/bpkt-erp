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
                        <h5 class="card-title">Store Info List</h5>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table id="storeInfo" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th style="width: 3%;">S.L.</th>
                                    <th >Action</th>
                                    <th>Name</th>
                                    <th>address</th>
                                    <th>contact person name</th>
                                    <th>contact number</th>
                                    <th>email</th>
                                    <th>Status</th>
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
@endsection

@section('uncommonInJs')
    <script>
        var userTableColumnNames=[];
        (function() {
            "use strict";
            let userTableColumnTh=document.querySelectorAll('#storeInfo thead tr th');
            for(let i=0; i<userTableColumnTh.length; i++){userTableColumnNames[i]=userTableColumnTh[i].innerText}

        })(jQuery);

        var customLen=0;
        $(window).on('load',function(){
            set_custom_length();
        });

        var csrfToken= $('meta[name="csrf-token"]').attr('content');

        var userTable= $('#storeInfo').DataTable({
            processing:true,
            serverSide:true,
            colReorder: true,
            scrollX:true,
            responsive: true,
            scrollCollapse: true,
            dom: "Bflrtip",
            scrollY:($(window).height()*0.7)+"px",

            ajax: {
                url:'{!! route('godown.list') !!}',
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
                {data:'name',name:'name'},
                {data:'address',name:'address'},
                {data:'contact_person_name',name:'contact_person_name'},
                {data:'contact_number',name:'contact_number'},
                {data:'email',name:'email'},
                {data:'status',name:'status'},
                {data:'DT_RowData.data-created_by',name:'created_by'},
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
                {{--{--}}
                {{--    extend: 'pdf',--}}
                {{--    title:'{{ config('app.name', 'Laravel') }}: User List',--}}
                {{--    footer: true,--}}
                {{--    exportOptions: {--}}
                {{--        columns: ':visible'--}}
                {{--    }--}}

                {{--},--}}
                {
                    extend: 'copy',
                    title:'{{ config('app.name', 'Laravel') }}: User List',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }

                },
                {{--{--}}
                {{--    extend: 'csv',--}}
                {{--    title:'{{ config('app.name', 'Laravel') }}: User List',--}}
                {{--    footer: true,--}}
                {{--    exportOptions: {--}}
                {{--        columns: ':visible'--}}
                {{--    }--}}

                {{--},--}}
                {
                    text: 'Column Settings',
                    action: function ( e, dt, node, config ) {
                        showUserTablesCombinationModal(userTableColumnNames,'Users','userTable');
                    }
                }
            ]
        });

        $(window).on('load',function () {
            $('#userTable').dataTable().fnSort([[0,'desc']]);
            setTimeout(function () {
                get_set_combination(userTable,'userTable',userTableColumnNames);
            },500);
        });

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

    @include('admin.users.index_modals.user_detail')
    @if(auth()->user()->can('assign-user-permission'))
        @include('admin.users.index_modals.user_permission')
    @endif
    @include('admin.users.index_modals.excel_register')
    @include('admin.layouts.commons.modals.user_tables_combination')
@endsection
