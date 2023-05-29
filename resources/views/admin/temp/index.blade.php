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
                        <h5 class="card-title"> New Entry temp List

                            <span style="float: right;cursor: pointer" class="active" onclick="createAllPrintView()">  <i style="font-size: 24px;" class="bi bi-printer "></i>  </span>

                        </h5>

                        <div class="card-tools">
                            @if(auth()->user()->can('create-bulk'))
                                <a href="{{route('bulk.create')}}" class="btn btn-sm btn-outline-primary pull-right"><span class="fa fa-plus-circle"></span> New Entery temp</a>
                            @endif
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">

                            <div style="float: right;" class="  pull-right" aria-labelledby="headingOne" >
                                <form id="bulkListForm"  action="" method="post" class="form" >
                                    <div class="card-body" style="float:right !important;width:90%;padding-top: 0px;padding-bottom: 0px;">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="form-group col-md-2 col-xs-12">
                                                <label class="form-control-label">Date Type</label>
                                                <select class="form-control select2" name="date_type" id="date_type" >
                                                      <option value="3">Receivable Date</option>
                                                    <option value="1">Due Date</option>
                                                    <option value="2">Issue Date</option>

                                                </select>
                                            </div>
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
                                            <div class="form-group col-md-1 col-xs-12">
                                                <br>
                                                <button onclick="showSearchBulkList()" type="button" class="btn btn-sm btn-success btn-save" style="color:white; " >
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </table>
                            <table id="bulkTbl" class="table table-bordered table-hover table-striped" style="font-size: 14px; width:100%;">
                                <thead>
                                <tr>
                                    <th style="width: 3%;">S.L.</th>
                                    <th style="width: 3%;"> <input id="chkAll" type="checkbox" onclick="checkAllnputPrint()" ></th>
                                    <th style="width: 150px !important;" >Action

                                    </th>
                                    <th>Billing Id</th>
                                    <th>Billing Month</th>
                                    <th>Bill Type</th>
                                    <th>Shop No</th>
                                    <th>Shop Name</th>
                                    <th>Invoice No</th>
                                    <th>Amount (Tk.)</th>
                                    <th>Issue Date</th>
                                    <th>Due Date</th>
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

    <div id="ddd" style="display: none;">

    </div>

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
            listView({data:{},_token: "{{csrf_token()}}"});
            jqueryCalendar('date_from');
            jqueryCalendar('date_to');
            $('#bulkTbl')
                .on( 'error.dt', function ( e, settings, techNote, message ) {
                    console.log( 'An error has been reported by DataTables: ', message );
                } )
                .DataTable();
        })(jQuery);

        function showSearchBulkList(){
            var data = $("#bulkListForm").serializeArray();
            console.log(data)
            $('#bulkTbl').DataTable().destroy();
            listView({data,_token: "{{csrf_token()}}"});

        }

        function listView(data) {
            console.log(data);
            $('#bulkTbl').DataTable({
                "processing": true,
                "serverSide": true,
                // destroy: true,
                "searching": false,
                order: [ 2, 'desc' ],
                fixedHeader: true,
                lengthMenu: [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, 'All']],
                "ajax":{
                    "url": '{!! route('bulk.list') !!}',
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
                    {data:'sl',name:'sl'},
                    {"data": "id",searchable: false,orderable: false,
                        render:function( data, type, row  ) {
                            console.log(data);
                            return '<div style="text-align: center !important;"> <input class="checkP" type="checkbox" name="p_chk[]" id="p_'+data+'" value="'+data+'"></div>';


                        }
                    },
                    {data:'action',name:'action',searchable: false, orderable: false},
                    {data:'id',name:'id'},
                    {data:'month',name:'month'},
                    {data:'bill_type',name:'bill_type'},
                    {data:'shop_no',name:'shop_no'},
                    {data:'shop_name',name:'shop_name'},
                    {data:'invoice_no',name:'invoice_no'},
                    {data:'amount',render:function( data, type, row  ) {
                            console.log(data);
                            return '<div style="text-align: right !important;">' + data + '</div>';


                        }},
                    {data:'issue_date',name:'issue_date'},
                    {data:'due_date',name:'due_date'},
                    {data:'created_by',name:'created_by'},
                    {data:'updated_at',name:'updated_at'}
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
        function  checkAllnputPrint(){

            if ($("#chkAll").is(':checked')) {

                $('.checkP').prop('checked',true);


            } else {
                $('.checkP').prop('checked',false);
            }

        }

     function createAllPrintView(){
         let ar= $('.checkP:checked').map(function() {return this.value;}).get().join(',')
      //   $("#print_options").val(ar);
         console.log(ar);
         $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
         $(".preloader").show();

         $.ajax({
             type: "POST",
             url: 'print-options',
             data: {data:ar},
             success:function(data){
                 console.log(data);
                 // $("#ddd").val();
                 $(".preloader").hide();
                $("#ddd").html(data.html);
                //  printDiv('print_div_billing_bulk')
                  var url = (typeof u != 'undefined') ? u : '';
                 var w = (typeof p != 'undefined') ? 900 : 700;

                 var content_vlue;
                 var dStr;


                     content_vlue = document.getElementById('print_div_billing_bulk').innerHTML;
                     dStr = '<link rel="preconnect" href="https://fonts.gstatic.com">';
                     dStr = '<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">';
                     dStr += '<link href="<?php echo e(URL::asset('admin/dist/icons/bootstrap-icons-1.4.0/bootstrap-icons.min.css')); ?>" rel="stylesheet" type="text/css">';
                     dStr += '<link href="<?php echo e(URL::asset('admin/dist/css/bootstrap-docs.css')); ?>" rel="stylesheet" type="text/css">';
                     dStr += '<link href="<?php echo e(URL::asset('admin/libs/slick/slick.css')); ?>" rel="stylesheet" type="text/css">';
                     dStr += '<link href="<?php echo e(URL::asset('admin/dist/css/app.min.css')); ?>" rel="stylesheet" type="text/css">';
                     dStr += '<link href="<?php echo e(URL::asset('bower/DataTables/datatables.min.css')); ?>" rel="stylesheet" type="text/css">';
                     dStr += '<link rel="stylesheet" href="<?php echo e(URL::asset('bower/select2/dist/css/select2.css')); ?>">';
                     dStr += '<style type="text/css" media="print"> @media print{  pre, blockquote {page-break-inside: avoid;} }, @page {  size:A4; margin: 0},body {   margin-top: 20px; margin-bottom: 30px;}' +
                         ' </style>';
                     dStr += '</head><body class="" onLoad="self.print()" style="background: #fff;">';
                     dStr += '<div style="text-align: center;" >';

                 var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
                 disp_setting += "scrollbars=no,  left=0, top=15";

                 var docprint = window.open("", "", disp_setting);
                 docprint.document.open();
                 docprint.document.write('<html><head><style>@page{margin-top: 25px;margin-bottom: 25px;}</style><title>PrintView</title>');
                 docprint.document.write(dStr);
                 docprint.document.write(content_vlue);
                 docprint.document.write('</div></div></body></html>');
                 docprint.document.close();
                 docprint.focus();
             },error:function(){
                 console.log(error);
             }
                 // if(guardHtml)
                 // {
                 //     var content = document.getElementById(divId).innerText;
                 // }
                 // else
                 // {
                 //     var content = document.getElementById(divId).innerHTML;
                 // }

                 // var mywindow = window.open('', 'Print', 'height=600,width=1024');
                 //
                 // mywindow.document.write('<html onload="window.close();"><head><title>Print</title>' + '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"><style>\n' +
                 //     '    th,td{\n' +
                 //     '        font-size: 13px;\n' +
                 //     '        padding-top:.5rem !important;\n' +
                 //     '        padding-bottom:.5rem !important;\n' +
                 //     '    }</style>');
                 // mywindow.document.write('</head><body onload="window.print();">');
                 // mywindow.document.write(data.html);
                 // mywindow.document.write('</body></html>');
                 //
                 // mywindow.document.close();
              //   mywindow.focus()
              //   return true;
            //  },error:function(){
            //      console.log(error);
            //  }
         });

     }

    </script>

@endsection
