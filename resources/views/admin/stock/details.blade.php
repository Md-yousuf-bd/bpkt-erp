@extends('admin.layouts.app')

@php include_once(app_path().'/helpers/Helper.php'); @endphp

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-8  col-xs-12 mx-auto ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Invoice Details</h5>
                        <div style="float: right;"> <button onclick="printDiv('GFG')" class="btn btn-outline-secondary d-none d-md-block btn-icon">
                                <i class="bi bi-printer"></i> Print
                            </button> </div>
                    </div>
                    <div class="card-body card-block" id="GFG">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                <tr>
                                    <td colspan="2" style="text-align: center;font-size:15px;"> <strong> {{ env('APP_Company_Name') }}</strong>
                                        <p> <strong>Purchase Invoice</strong></p>
                                    </td>
                                </tr>
                                <tr>


                                    <td style="width: 50%;" valign="top"  >
                                        <table valign="top" class=" " style="font-size: 14px; width:100% !important;">

                                            <tr>

                                                <td style="width:20%;"> Vendor Name</td>
                                                <td style="width:2%;text-align: left">:</td>
                                                <td style="text-align: left"> &nbsp; <b>{{ $vendor_name }}</b></td>
                                            </tr>

                                            <tr>
                                                <td style="width:100px;">Address</td>
                                                <td style="width:10px;">:</td>
                                                <td style="text-align: left"> &nbsp; {{ $vendor_address }}</td>
                                            </tr>


                                        </table>
                                    </td>
                                    <td style="width: 50%;float: right;" valign="top">
                                        <table  style="font-size: 14px; width:100% !important;" >


                                            <tr>
                                                <td style="width:20%;">Purchase Ref No</td>
                                                <td style="width:2%;text-align: left">:</td>
                                                <td>&nbsp;{{ $purchase_ref_no }}</td>

                                            </tr>

                                            <tr>
                                                <td style="width:200px;">Voucher No
                                                </td>
                                                <td style="width:10px;">:</td>
                                                <td>&nbsp;{{ $voucher_no }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px;">Purchase Date</td>
                                                <td style="width:10px;">:</td>
                                                                                             
                                                    @php
                                                        $journal_date_array = explode(',', $invoice_data->journal_date, 2);

                                                    @endphp
                                                    
                                                <td> &nbsp; {{ date('d-m-Y',strtotime($journal_date_array[0])) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px;">Due Date</td>
                                                <td style="width:10px;">:</td>
                                                <td> &nbsp; {{ date('d-m-Y',strtotime($invoice_data->due_date)) }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table>
                                            <tr>
                                                <td style="width:106px;"> Vendor Code  </td>
                                                <td> :  </td>
                                                <td>&nbsp; {{ $vendor_id }}</td>

                                            </tr>
                                            <tr>
                                                <td> Store Name:  </td>
                                                <td> :  </td>
                                                <td>&nbsp; {{ $store_name  }}</td>

                                            </tr>
                                            <tr>
                                                <td colspan="3"> &nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"> Purchase details are as follows:</td>

                                            </tr>
                                        </table>

                                    </td>

                                </tr>

                            </table>

                            <div style="height: 2%">&nbsp;</div>
                            <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tr style="background: #BFBFBF;color:#000;">
                                    <td style=""><strong>Sl. <br>No.</strong></td>
                                    <td style=""><strong>Product Name</strong></td>
                                    <td style="width:100px !important;"><strong>Brand Name</strong></td>
                                    <td style="width:70px !important;"><strong>Size/Category</strong></td>
                                    <td style="width:100px !important;"><strong>Qtn.</strong></td>
                                    <td style="width:100px !important;"><strong>Rate/Unit</strong></td>
                                    <td style="width:100px !important;"><strong>Sub total</strong></td>
                                    <td style="width:100px !important;"><strong>Vat</strong></td>
                                    <td style="width:100px !important;"><strong>Total</strong></td>
                                </tr>
                                @php
                                    $i = 1;
                                    $total=0;
                                    $vat_total=0;
                                    $g_total=0;
                                @endphp
                               

                               @foreach($purchase_details as $row)
                                    @php
                                        $g_total += round($row->total_amount,2);

                                    @endphp

                                    <tr>
                                        <td style="text-align: center;">{{ $i++ }}</td>
                                        <td style="text-align: left;">{{ $row->product_name}}</td>
                                        <td style="text-align: left;">{{ $row->brand_name}}</td>
                                        <td style="text-align: left;">{{ $row->size_name}}</td>
                                        <td style="text-align: center;">{{ $row->qty}}</td>
                                        <td style="text-align: right;">{{ number_format($row->rate, 2) }}</td>
                                        <td style="text-align: right;">{{ number_format($row->sub_total, 2) }}</td>
                                        <td style="text-align: center;">{{ number_format($row->vat_rate, 2) }}%</td>
                                        <td style="text-align: right;">{{ number_format($row->total_amount, 2) }}</td>
                                    </tr>

                               @endforeach

                                    <tr>
                                        <td colspan="8"><strong>Total</strong></td>
                                        <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                    </tr>

                            </table>
                            <div>  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                            <p></p>
                            <div style="text-align: left;">
                                <br>
                                <br>


                                
                            </div>


                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <table style="width: 100%">
                                <tr>
                                    <td  style=" width:20%;text-align: left;">
                                        <hr> Prepared By
                                        <br><b>Name: </b>
                                        <br><b>Designation: </b>
                                    </td>
                                    <td style="width: 20%">
                                    </td>
                                    <td style="width: 20%">
                                        <hr> Recorded By
                                        <br><b>Name: </b>
                                        <br><b>Designation: </b>
                                    </td>
                                    <td style="width: 20%">
                                    </td>
                                    <td style=" width:20%;text-align: right;">
                                         <hr>Approved by
                                        <br><b>Name: </b>
                                        <br><b>Designation: </b>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .content -->
    <script>
        function printDiv(divId,false) {
            var url = (typeof u != 'undefined') ? u : '';
            var w = (typeof p != 'undefined') ? 900 : 700;

            var content_vlue;
            var dStr;

                content_vlue = document.getElementById(divId).innerHTML;
                dStr = '<link rel="preconnect" href="https://fonts.gstatic.com">';
        dStr += '<link rel="stylesheet" href="http://localhost:81/accounting/public/admin/dist/icons/bootstrap-icons-1.4.0/bootstrap-icons.min.css" type="text/css">';
         dStr += '<link rel="stylesheet" href="{{asset('admin/dist/css/bootstrap-docs.css')}}" type="text/css">';
        dStr += '<link rel="stylesheet" href="{{asset('admin/libs/slick/slick.css')}}" type="text/css">';
        dStr += '<link rel="stylesheet" href="{{asset('admin/dist/css/app.min.css')}}" type="text/css">';
        dStr += '<link rel="stylesheet" href="{{asset('admin/dist/css/app.min.css')}}" type="text/css">';

                        dStr += '</head><body onLoad="window.print()">';
                        dStr += '<div style="text-align: center;">';

                    var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
                    disp_setting += "scrollbars=yes,width=" + w + ", height=600, left=100, top=25";

                    var docprint = window.open("", "", disp_setting);
                    docprint.document.open();
                    docprint.document.write('<html><head><title>ZXY PrintView</title>');
                    docprint.document.write(dStr);
                    docprint.document.write(content_vlue);
                    docprint.document.write('</div> <script src="{{asset('admin/dist/js/app.min.js')}}"> </body></html>');
                    docprint.document.close();
                    docprint.focus();

                    // if(guardHtml)
                    // {
                    //     var content = document.getElementById(divId).innerText;
                    // }
                    // else
                    // {
                    //     var content = document.getElementById(divId).innerHTML;
                    // }
                    //
                    // var mywindow = window.open('', 'Print', 'height=600,width=1024');
                    //
                    // mywindow.document.write('<html onload="window.close();"><head><title>Print</title>' + '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"><style>\n' +
                    //     '    th,td{\n' +
                    //     '        font-size: 13px;\n' +
                    //     '        padding-top:.5rem !important;\n' +
                    //     '        padding-bottom:.5rem !important;\n' +
                    //     '    }</style>');
                    // mywindow.document.write('</head><body onload="window.print();">');
                    // mywindow.document.write(content);
                    // mywindow.document.write('</body></html>');
                    //
                    // mywindow.document.close();
                    // mywindow.focus()

                }
            </script>



@endsection



@section('uncommonInJs')


@endsection
