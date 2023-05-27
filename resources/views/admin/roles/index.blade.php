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
                        <h5 class="card-title">Role List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-role'))
                                <a href="{{route('settings.role.create')}}" class="btn btn-sm btn-default pull-right"><span class="fa fa-plus-circle"></span> Add Role</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table id="roleTable" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th style="width:5%;">Sl</th>
                                    <th style="min-width:80px;">Action</th>
                                    <th>Role</th>
                                    <th>Guard Name</th>
                                    <th>Level</th>
                                    <th style="min-width: 190px;">Created At</th>
                                    <th style="min-width: 190px;">Last Updated At</th>
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
        var roleTableColumnNames=[];
        (function() {
            "use strict";

            let roleTableColumnTh=document.querySelectorAll('#roleTable thead tr th');
            for(let i=0; i<roleTableColumnTh.length; i++){roleTableColumnNames[i]=roleTableColumnTh[i].innerText}
        })(jQuery);

        var customLen=0;
        $(window).on('load',function(){
            set_custom_length();
        });

        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        var roleTable= $('#roleTable').DataTable({
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
            processing:true,
            serverSide:true,
            scrollX:true,
            colReorder: true,
            responsive: true,
            scrollCollapse: true,
            dom: "Bflrtip",
            scrollY:($(window).height()*0.7)+"px",
            // aoColumns: [null, { "bSortable": false },{ "bSortable": false }, null, null, null, null, null,  null],
            // fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //     //debugger;
            //     var index = iDisplayIndexFull + 1;
            //     $("td:first", nRow).html(index);
            //     return nRow;
            // },
            ajax: {
                url:'{!! route('settings.role.get_index') !!}',
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
                // $( row ).find('td:eq(6)').attr({'data-order':data.DT_RowData['data-role'], 'data-search': data.DT_RowData['data-role']});
            },
            columns:[
                {data:'DT_RowIndex',name:'id'},
                {data:'action',name:'action',searchable: false, orderable: false},
                {data:'DT_RowData.data-role',name:'name'},
                {data:'guard_name',name:'guard_name'},
                {data:'level',name:'level'},
                {data:'DT_RowData.data-created_at',name:'created_at'},
                {data:'DT_RowData.data-updated_at',name:'updated_at'},
            ],
            buttons: [
                {
                    extend: 'print',
                    title:'{{ config('app.name', 'Laravel') }}: Role List',
                    footer: true,
                    exportOptions: {
                        stripHtml : false,
                        columns: ':visible'
                    }

                },
                {
                    extend: 'excel',
                    title:'{{ config('app.name', 'Laravel') }}: Role List',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }

                },
                {{--{--}}
                {{--    extend: 'pdf',--}}
                {{--    title:'{{ config('app.name', 'Laravel') }}: Role List',--}}
                {{--    footer: true,--}}
                {{--    exportOptions: {--}}
                {{--        columns: ':visible'--}}
                {{--    }--}}

                {{--},--}}
                {
                    extend: 'copy',
                    title:'{{ config('app.name', 'Laravel') }}: Role List',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }

                },
                {{--{--}}
                {{--    extend: 'csv',--}}
                {{--    title:'{{ config('app.name', 'Laravel') }}: Role List',--}}
                {{--    footer: true,--}}
                {{--    exportOptions: {--}}
                {{--        columns: ':visible'--}}
                {{--    }--}}

                {{--},--}}
                {
                    text: 'Column Settings',
                    action: function ( e, dt, node, config ) {
                        showUserTablesCombinationModal(roleTableColumnNames,'Roles','roleTable');
                    }
                }
            ]
        });

        function set_custom_length()
        {
            var button = document.createElement("button");
            button.innerHTML="Custom";
            var node = document.createElement("input");
            node.type='number';
            node.id='roleTable_length_custom';
            node.classList.add("form");
            node.classList.add("form-control");
            node.setAttribute('value','5000');
            node.setAttribute('onkeyup','changeLength2(event,this)');
            button.setAttribute('onclick','changeLength()');
            node.setAttribute("style", "float:right !important; width:120px !important; margin-left:5px!important;");
            button.setAttribute("style", "float:right !important; margin-left:-1px!important; height:29px;");
            let el = document.getElementById("roleTable_length");
            el.querySelector("label").appendChild(button);
            el.querySelector("label").appendChild(node);
        }
        function changeLength(){
            let customLength=document.getElementById("roleTable_length_custom").value;
            roleTable.page.len(customLength).draw();
        }

        function changeLength2(event,ele){
            let customLength=ele.value;
            if (event.keyCode === 13) {
                event.preventDefault();
                roleTable.page.len(customLength).draw();
            }
        }

        $(window).on('load',function () {
            $('#roleTable').dataTable().fnSort([ [4,'asc']] );
            setTimeout(function () {
                get_set_combination(roleTable,'roleTable',roleTableColumnNames);
            },500);
        });


    </script>
    @include('admin.layouts.commons.modals.user_tables_combination')
@endsection
