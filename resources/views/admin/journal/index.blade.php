@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-header">
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" >
                            <form id="myForm"  action="" method="post" class="form" >
                                <div class="card-body" style="padding-top: 0px;padding-bottom: 0px;">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="form-group col-md-2 col-xs-12">
                                            <label></label>
                                            <select class="form-control select2" name="date_type" id="date_type" >
                                                <option value="1">Effective Date</option>
                                                <option value="2">Posting Date</option>

                                            </select>

                                        </div>
                                        <div class="form-group col-md-2 col-xs-12">
                                            <label class="form-control-label">Date From</label>
                                            <input autocomplete="off" value="{{ date('Y-m-d') }}" placeholder="Date From"  type="text" id="date_from" name="date_from"  class="form-control" >

                                        </div>
                                        <div class="form-group col-md-2 col-xs-12">
                                            <label class="form-control-label">Date To</label>
                                            <input autocomplete="off"  value="{{ date('Y-m-d') }}" placeholder="Date To"  type="text" id="date_to" name="date_to"  class="form-control" >

                                        </div>
                                        <div class="form-group col-md-2 col-xs-12">
                                            <label class="form-control-label">Shop No</label>
                                            <input autocomplete="off" placeholder="Shop No"  type="text" id="shop_no" name="shop_no"  class="form-control" >

                                        </div>
                                        <div class="form-group col-md-2 col-xs-12">
                                            <label style="color:#000;" for="customer_id">Client Name</label>
                                            <select class="form-control select2" name="customer_id" id="customer_id" >
                                                <option value="">None</option>
                                                @foreach($customer as $row)
                                                    <option value="{{$row->id}}"> {{$row->shop_name}}</option>
                                                @endforeach

                                            </select>

                                        </div>
                                        <div class="form-group col-md-2 col-xs-12">
                                            <label style="color:#000;" class="ledger">Ledger</label>
                                            <select class="form-control select2" name="ledger" id="ledger" >
                                                <option value="">None</option>
                                                @foreach($ledger as $row)
                                                    <option value="{{$row->id}}"> {{$row->head}}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="form-group col-md-1 col-xs-12">
                                            <br>
                                            <button onclick="showJournalList()" type="button" class="btn btn-sm btn-success btn-save" style="color:white; " >
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>


                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table id="journalTbl" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr style="background: #55A95D;color:#fff;">
                                    <th style="width: 3%;font-weight:bold">S.L.</th>
                                    <th style="font-weight:bold">Posting  <br> Date</th>
                                    <th style="font-weight:bold">effective <br> Date</th>
                                    <th style="font-weight:bold">Transaction <br> Type</th>
                                    <th style="font-weight:bold"> Invoice No</th>
                                    <th style="font-weight:bold"> Client Name</th>
                                    <th style="font-weight:bold"> Shop No</th>
                                    <th style="font-weight:bold">Ledger Name</th>
                                    <th style="font-weight:bold">Debit (Tk)</th>
                                    <th style="font-weight:bold">Credit (Tk)</th>
                                    <th style="font-weight:bold">Description</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                <tr>


                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>
                                <th class="text-right"></th>

                                    <th style="text-align: right">Total</th>
                                <th style="text-align: right"></th>
                                    <th style="text-align: right"></th>
                                    <th style="text-align: right"></th>

                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .content -->
    <style>
        .dt-buttons{
            float: left !important;
            text-align: right;
        }
        .dataTables_length{
            float: left !important;
            text-align: left;
        }
        .dataTables_filter{
            float: right !important;
        }
    </style>
@endsection

@section('uncommonExJs')
    @include('admin.layouts.commons.dataTableJs')
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
@endsection

@section('uncommonInJs')
    <script>
        (function() {
            "use strict";
            $.fn.dataTable.ext.errMode = 'none';
            jqueryCalendar('date_from');
            jqueryCalendar('date_to');

            listView({data:{},_token: "{{csrf_token()}}"});
            $('#journalTbl')
                .on( 'error.dt', function ( e, settings, techNote, message ) {
                    console.log( 'An error has been reported by DataTables: ', message );
                } )
                .DataTable();
        })(jQuery);
        function showJournalList(){
                var data = $("#myForm").serializeArray();
                console.log(data)
                $('#journalTbl').DataTable().destroy();
                listView({data,_token: "{{csrf_token()}}"});

        }

        function listView(data) {
            console.log(data);
            $('#journalTbl').DataTable({
                "processing": true,
                "serverSide": true,
                // destroy: true,
                "searching": true,
                fixedHeader: true,
                lengthMenu: [[10, 25, 50, 100, 500, 1000], [10, 25, 50, 100, 500, 1000]],
                "ajax":{
                    "url": '{!! route('journal.list') !!}',
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
                    { "data": "id", "orderable":false},
                    { "data": "date" },
                    { "data": "effective_date" },
                    { "data": "transaction_type" },
                    { "data": "invoice_no" },
                    { "data": "customer_name" },
                    { "data": "shop_no" },
                    { "data": "ledger_head" },
                    {"data": "debit",
                        render:function( data, type, row  ) {
                        console.log(data);
                            return '<div style="text-align: right !important;">' + data + '</div>';


                    }
                    },

                    { "data": "credit" ,
                        render:function( data, type, row  ) {
                            console.log(data);
                            return '<div style="text-align: right !important;">' + data + '</div>';


                        }
                        },
                    { "data": "remarks" },


                ],
                "dom": 'lBfrtip',

                buttons: [
                    'excel',
                    'csv',
                    'pdf',
                    'print'
                ],
                footerCallback: function (tfoot, data, start, end, display) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };


                    //Total over this page
                    let pageTotal7 = api
                        .column(8)
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    //Update footer
                    $( api.column(8).footer() ).html(
                        pageTotal7.toFixed(2)
                    );

                    //Total over this page
                    let pageTotal8 = api
                        .column(9)
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    //Update footer
                    $( api.column(9).footer() ).html(
                        ReplaceNumberWithCommas(pageTotal8.toFixed(2))

                );
                },
                scrollX: true,


            });
        }

        var table = $('#journalTbl').DataTable();
        $('#journalTbl tbody').on('dblclick', 'tr', function () {
            var data = table.row( this ).data();
            console.log(data);
            let url = '';
            if(data.ref_module=='Manual Journal'){
                let id=data['ref_id'];
                 url = "{{ route('manual-journal.show', ':id') }}";
                url = url.replace(':id', id);

            }else if(data.ref_module=='Billing' || data.ref_module=="Bulk Entry"){
                let id=data['ref_id'];
                url = "{{ route('billing.journal', ':id') }}";
                url = url.replace(':id', id);
            }else if(data.ref_module=='Cash Collection'){
                let id=data['ref_id'];
                url = "{{ route('cash-collection.journal', ':id') }}";
                url = url.replace(':id', id);
            }else if(data.ref_module=='Payment'){
                let id=data['ref_id'];
                url = "{{ route('payment.journal', ':id') }}";
                url = url.replace(':id', id);
            }
            else{
                let id=data['ref_id'];
                 url = "{{ route('income.journal', ':id') }}";
                url = url.replace(':id', id);
            }

            console.log(url);
            window.open(url, '_blank');
        });
        function ReplaceNumberWithCommas(yourNumber) {
            //Seperates the components of the number
            var n= yourNumber.toString().split(".");
            //Comma-fies the first part
            n[0] = n[0].replace(/\B(?=(\d{2})+(?!\d))/g, ",");
            //Combines the two sections
            return yourNumber.toLocaleString('en') ;// n.join(".");
        }
    </script>


@endsection
