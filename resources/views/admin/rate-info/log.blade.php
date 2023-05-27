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
                        <h5 class="card-title">Log Rate Info</h5>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table id="rateLogInfo" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th style="width: 3%;">S.L.</th>
                                    <th>Name</th>
                                    <th>Rate (Sft)</th>
                                    <th>Effective Date From</th>
                                    <th>Effective Date To</th>
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
            let userTableColumnTh=document.querySelectorAll('#rateLogInfo thead tr th');
            for(let i=0; i<userTableColumnTh.length; i++){userTableColumnNames[i]=userTableColumnTh[i].innerText}
        })(jQuery);



        var csrfToken= $('meta[name="csrf-token"]').attr('content');

        var userTable= $('#rateLogInfo').DataTable({
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
                url:'{!! route('rate.log') !!}',
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
                {data:'name',name:'name'},
                {data:'rate',name:'rate'},
                {data:'effective_date',name:'effective_date'},
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





    </script>

@endsection
