@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="card">
                        <div class="card-header">
                            <div class="form-group row" style="margin-bottom: 0px;">
                                <label class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-form-label">
                                    <strong class="card-title">লগ</strong>
                                </label>
                                    <label for="from" class="col-lg-offset-1 col-md-offset-1 col-lg-1 col-md-1 col-sm-1 col-xs-1 col-form-label text-right"  style="max-width: 50px; margin-top: 5px;">From</label>
                                    <div class="col-lg-2 col-md-2 col-sm-11 col-xs-11" style="margin-top: 5px;">
                                        <input type="text" name="from" id="from" class="form-control" value="" style="">
                                    </div>
                                    <label for="from" class="col-md-1 col-sm-1 col-xs-1 col-form-label text-right" style="max-width: 30px; margin-top: 5px;">To</label>
                                    <div class="col-lg-2 col-md-2 col-sm-11 col-xs-11" style="margin-top: 5px;">
                                        <input type="text" name="to" id="to" class="form-control" value="" style="">
                                    </div>
                                    <label for="user_id" class="col-lg-1 col-md-1 col-sm-1 col-xs-1 col-form-label text-right" style="max-width: 50px; margin-top: 5px;">User</label>
                                    <div class="col-lg-2 col-md-2 col-sm-11 col-xs-11" style="margin-top: 5px;">
                                        <select class="col-lg-11 col-md-11 col-sm-11 col-xs-11 form-control select2" name="user_id[]" id="user_id" style="min-width: 200px;" multiple>
                                            @if(!auth()->user()->can('read-all-user-log'))
                                                <option value="{{Auth::user()->id}}" selected>{{Auth::user()->name}}</option>
                                            @else
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}" @if($user->id==$user_id) selected @endif >{{$user->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-12 col-xs-12 text-center" style="margin-left: 10px;">
                                        <button type="submit" class="btn btn-sm btn-default" id="searchBtn" style="margin-top: 8px;"><i class="fa fa-search"></i></button>
                                    </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="logTable" class="table table-bordered table-hover table-striped" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th style="width: 5%;">Sl</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                        <th>Actor</th>
                                        <th style="min-width: 190px;">Created At</th>
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
    </div> <!-- .content -->
@endsection

@section('uncommonExJs')
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
    @include('admin.layouts.commons.dataTableJs')
@endsection

@section('uncommonInJs')
    <script>
        var logTableColumnNames=[];
        (function() {
            "use strict";

            let logTableColumnTh=document.querySelectorAll('#logTable thead tr th');
            for(let i=0; i<logTableColumnTh.length; i++){logTableColumnNames[i]=logTableColumnTh[i].innerText}


            var from = new Pikaday({
                field: $('#from')[0] ,
                firstDay: 1,
                format: 'YYYY-MM-DD',
                toString: function (date, format) {
                    var day   = date.getDate();
                    var month = date.getMonth() + 1;
                    var year  = date.getFullYear();

                    var yyyy = year;
                    var mm   = ((month > 9) ? '' : '0') + month;
                    var dd   = ((day > 9)   ? '' : '0') + day;

                    return yyyy + '-' + mm + '-' + dd;
                },
                position: 'bottom right',
                minDate: new Date('1900-01-01'),
                maxDate: new Date('2040-12-31'),
                yearRange: [1900, 2040]
            });

            var to = new Pikaday({
                field: $('#to')[0] ,
                firstDay: 1,
                format: 'YYYY-MM-DD',
                toString: function (date, format) {
                    var day   = date.getDate();
                    var month = date.getMonth() + 1;
                    var year  = date.getFullYear();

                    var yyyy = year;
                    var mm   = ((month > 9) ? '' : '0') + month;
                    var dd   = ((day > 9)   ? '' : '0') + day;

                    return yyyy + '-' + mm + '-' + dd;
                },
                position: 'bottom right',
                minDate: new Date('1900-01-01'),
                maxDate: new Date('2040-12-31'),
                yearRange: [1900, 2040]
            });

        })(jQuery);

        var customLen=0;
        $(window).on('load',function(){
            set_custom_length();
        });

        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        var logTable= $('#logTable').DataTable({
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
                url:'{!! route('settings.log.get_index') !!}',
                dataType: "json",
                contentType: "application/json",
                type: "POST",
                headers : {
                    'Accept': 'application/json',
                },
                data: function ( d ) {
                    d['from']=$('#from').val();
                    d['to']=$('#to').val();
                    d['user_id']=$('#user_id').val();
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
                // $( row ).find('td:eq(6)').attr({'data-order':data.DT_RowData['data-log'], 'data-search': data.DT_RowData['data-log']});
            },
            columns:[
                {data:'DT_RowIndex',name:'id'},
                {data:'description',name:'description'},
                {data:'action',name:'action'},
                {data:'DT_RowData.data-created_by',name:'users.name'},
                {data:'DT_RowData.data-created_at',name:'created_at'},
            ],
            buttons: [
                {
                    extend: 'print',
                    title:'{{ config('app.name', 'Laravel') }}: Log List',
                    footer: true,
                    exportOptions: {
                        stripHtml : false,
                        columns: ':visible'
                    }

                },
                {
                    extend: 'excel',
                    title:'{{ config('app.name', 'Laravel') }}: Log List',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }

                },
                {{--{--}}
                {{--    extend: 'pdf',--}}
                {{--    title:'{{ config('app.name', 'Laravel') }}: Log List',--}}
                {{--    footer: true,--}}
                {{--    exportOptions: {--}}
                {{--        columns: ':visible'--}}
                {{--    }--}}

                {{--},--}}
                {
                    extend: 'copy',
                    title:'{{ config('app.name', 'Laravel') }}: Log List',
                    footer: true,
                    exportOptions: {
                        columns: ':visible'
                    }

                },
                {{--{--}}
                {{--    extend: 'csv',--}}
                {{--    title:'{{ config('app.name', 'Laravel') }}: Log List',--}}
                {{--    footer: true,--}}
                {{--    exportOptions: {--}}
                {{--        columns: ':visible'--}}
                {{--    }--}}

                {{--},--}}
                {
                    text: 'Column Settings',
                    action: function ( e, dt, node, config ) {
                        showUserTablesCombinationModal(logTableColumnNames,'Logs','logTable');
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
            node.id='logTable_length_custom';
            node.classList.add("form");
            node.classList.add("form-control");
            node.setAttribute('value','5000');
            node.setAttribute('onkeyup','changeLength2(event,this)');
            button.setAttribute('onclick','changeLength()');
            node.setAttribute("style", "float:right !important; width:120px !important; margin-left:5px!important;");
            button.setAttribute("style", "float:right !important; margin-left:-1px!important; height:29px;");
            let el = document.getElementById("logTable_length");
            el.querySelector("label").appendChild(button);
            el.querySelector("label").appendChild(node);
        }
        function changeLength(){
            let customLength=document.getElementById("logTable_length_custom").value;
            logTable.page.len(customLength).draw();
        }

        function changeLength2(event,ele){
            let customLength=ele.value;
            if (event.keyCode === 13) {
                event.preventDefault();
                logTable.page.len(customLength).draw();
            }
        }

        $('#searchBtn').on('click',function () {
            logTable.ajax.reload();
        });

        $(window).on('load',function () {
            $('#logTable').dataTable().fnSort([ [0,'desc']] );
            setTimeout(function () {
                get_set_combination(logTable,'logTable',logTableColumnNames);
            },500);
        });


        {{--// Websocket--}}
        {{--@if(auth()->user()->can('read-all-user-log'))--}}
        {{--window.Echo.channel('all-activity-log-channel')--}}
        {{--    .listen('.AllLogsEvent', (e) => {--}}
        {{--        let userIDs=$('#user_id').val();--}}
        {{--        if(e.ajaxReload && userIDs.includes(e.actor_id.toString()) && logTable.page.info().page===0){--}}
        {{--            logTable.ajax.reload();--}}
        {{--        }--}}
        {{--    });--}}
        {{--@else--}}
        {{--window.Echo.private('activity-log-channel.' + window.Laravel.user)--}}
        {{--    .listen('.LogsEvent', (e) => {--}}
        {{--        if(e.ajaxReload && logTable.page.info().page===0){--}}
        {{--            logTable.ajax.reload();--}}
        {{--        }--}}
        {{--});--}}
        {{--@endif--}}
        {{--// Websocket--}}
    </script>

    @include('admin.layouts.commons.modals.user_tables_combination')
@endsection
