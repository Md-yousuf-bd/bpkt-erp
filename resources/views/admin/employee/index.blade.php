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
                        <h5 class="card-title">Employee List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-employee'))
                                <a  href="{{route('employee.create')}}" class="btn btn-sm btn-default pull-right"><span class="fa fa-plus-circle"></span> Add Employee</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table id="empTbl" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th>S.L.</th>
                                    <th style="width:320px !important;">Action</th>

                                    <th>Id</th>
                                    <th>Employee Name</th>
                                    <th>Employee No</th>
                                    <th>Phone</th>
                                    <th>NID</th>
                                    <th>Designation</th>
                                    <th>Rank</th>
                                    <th>Department</th>
                                    <th>Task</th>
                                    <th>Job Status</th>
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
        $(document).ready(function () {
            $('#empTbl').modal({
                backdrop: 'static',
                keyboard: false
            })
        });
        var userTableColumnNames=[];
        (function() {
            "use strict";
            let userTableColumnTh=document.querySelectorAll('#empTbl thead tr th');
            for(let i=0; i<userTableColumnTh.length; i++){userTableColumnNames[i]=userTableColumnTh[i].innerText}
        })(jQuery);

        var customLen=0;
        // $(window).on('load',function(){
        //     set_custom_length();
        // });

        var csrfToken= $('meta[name="csrf-token"]').attr('content');

        var userTable= $('#empTbl').DataTable({
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
            processing:true,
            serverSide:true,
            colReorder: true,
            scrollX:true,
            responsive: true,
            scrollCollapse: true,
            order: [[2, "DESC"]],
            dom: "Bflrtip",
            scrollY:($(window).height()*0.7)+"px",
            ajax: {
                url:'{!! route('employee.list') !!}',
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
                {data:'id',name:'id'},
                {data:'name',name:'name' },
                {data:'employee_no',name:'employee_no'},
                {data:'phone',name:'phone'},
                {data:'nid',name:'nid'},
                {data:'designation',name:'designation'},
                {data:'rank_name',name:'rank_name'},
                {data:'dept_name',name:'dept_name'},
                {data:'task',name:'task'},
                {data:'job_status',name:'job_status'},
                {data:'status',name:'status'},
                {data:'DT_RowData.data-updated_at',name:'updated_at'},
            ],
            buttons: [
                {
                    extend: 'print',
                    title:'{{ config('app.name', 'Laravel') }}: Owner List',
                    footer: true,
                    exportOptions: {
                        stripHtml : false,
                        columns: ':visible'
                    }

                },
                {
                    extend: 'excel',
                    title:'{{ config('app.name', 'Laravel') }}: Owner List',
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
                {{--{--}}
                    {{--extend: 'copy',--}}
                    {{--title:'{{ config('app.name', 'Laravel') }}: Owner List',--}}
                    {{--footer: true,--}}
                    {{--exportOptions: {--}}
                        {{--columns: ':visible'--}}
                    {{--}--}}

                {{--},--}}
                {{--{--}}
                {{--    extend: 'csv',--}}
                {{--    title:'{{ config('app.name', 'Laravel') }}: User List',--}}
                {{--    footer: true,--}}
                {{--    exportOptions: {--}}
                {{--        columns: ':visible'--}}
                {{--    }--}}

                {{--},--}}
                // {
                //     text: 'Column Settings',
                //     action: function ( e, dt, node, config ) {
                //         showUserTablesCombinationModal(userTableColumnNames,'Users','userTable');
                //     }
                // }
            ]
        });

        // $(window).on('load',function () {
        //     $('#userTable').dataTable().fnSort([[0,'desc']]);
        //     setTimeout(function () {
        //         get_set_combination(userTable,'userTable',userTableColumnNames);
        //     },500);
        // });

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
