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
                        <h5 class="card-title">Location List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-location'))
                                <a href="{{route('settings.location.create')}}" class="btn btn-sm btn-default pull-right"><span class="fa fa-plus-circle"></span> Add location</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table id="locationTable" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th style="min-width:150px;">Action</th>
                                    <th style="min-width:60px;">Name</th>
                                    <th style="min-width:80px;">Type</th>
                                    <th>Parent</th>
                                    <th>Parent Type</th>
                                    <th>Updated By</th>
                                    <th style="min-width: 200px;">Updated At</th>
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
        var locationTableColumnNames=[];
        (function() {
            "use strict";
            let locationTableColumnTh=document.querySelectorAll('#locationTable thead tr th');
            for(let i=0; i<locationTableColumnTh.length; i++){locationTableColumnNames[i]=locationTableColumnTh[i].innerText}
        })(jQuery);

        var customLen=0;
        $(window).on('load',function(){
            set_custom_length();
        });

        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        var locationTable= $('#locationTable').DataTable({
            lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
            processing:true,
            colReorder: true,
            serverSide:true,
            scrollX:true,
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
                url:'{!! route('settings.location.get_index') !!}',
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
                // $( row ).find('td:eq(6)').attr({'data-order':data.DT_RowData['data-location'], 'data-search': data.DT_RowData['data-location']});
            },
            columns:[
                {data:'DT_RowIndex',name:'id'},
                {data:'action',name:'action',searchable: false, orderable: false},
                {data:'name',name:'name'},
                {data:'DT_RowData.data-type',name:'type'},
                {data:'DT_RowData.data-parent',name:'parent_id'},
                {data:'DT_RowData.data-parent_type',name:'parent_id'},
                {data:'DT_RowData.data-updated_by',name:'updated_by'},
                {data:'DT_RowData.data-updated_at',name:'updated_at'},
            ],
            buttons: [
                {
                    extend: 'print',
                    title:'{{ config('app.name', 'Laravel') }}: Location List',
                    footer: true,
                    exportOptions: {
                        stripHtml : false,
                        columns: ':visible'
                    }

                },
                {
                    extend: 'excel',
                    title:'{{ config('app.name', 'Laravel') }}: Location List',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }

                },
                {{--{--}}
                {{--    extend: 'pdf',--}}
                {{--    title:'{{ config('app.name', 'Laravel') }}: Location List',--}}
                {{--    footer: true,--}}
                {{--    exportOptions: {--}}
                {{--        columns: ':visible'--}}
                {{--    }--}}

                {{--},--}}
                {
                    extend: 'copy',
                    title:'{{ config('app.name', 'Laravel') }}: Location List',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }

                },
                {{--{--}}
                {{--    extend: 'csv',--}}
                {{--    title:'{{ config('app.name', 'Laravel') }}: Location List',--}}
                {{--    footer: true,--}}
                {{--    exportOptions: {--}}
                {{--        columns: ':visible'--}}
                {{--    }--}}

                {{--},--}}
                {
                    text: 'Column Settings',
                    action: function ( e, dt, node, config ) {
                        showUserTablesCombinationModal(locationTableColumnNames,'Locations','locationTable');
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
            node.id='locationTable_length_custom';
            node.classList.add("form");
            node.classList.add("form-control");
            node.setAttribute('value','5000');
            node.setAttribute('onkeyup','changeLength2(event,this)');
            button.setAttribute('onclick','changeLength()');
            node.setAttribute("style", "float:right !important; width:120px !important; margin-left:5px!important;");
            button.setAttribute("style", "float:right !important; margin-left:-1px!important; height:29px;");
            let el = document.getElementById("locationTable_length");
            el.querySelector("label").appendChild(button);
            el.querySelector("label").appendChild(node);
        }
        function changeLength(){
            let customLength=document.getElementById("locationTable_length_custom").value;
            locationTable.page.len(customLength).draw();
        }

        function changeLength2(event,ele){
            let customLength=ele.value;
            if (event.keyCode === 13) {
                event.preventDefault();
                locationTable.page.len(customLength).draw();
            }
        }

        $(window).on('load',function () {
            $('#locationTable').dataTable().fnSort([ [0,'desc']] );
            setTimeout(function () {
                get_set_combination(locationTable,'locationTable',locationTableColumnNames);
            },500);
        });

    </script>
    @include('admin.layouts.commons.modals.user_tables_combination')
@endsection
