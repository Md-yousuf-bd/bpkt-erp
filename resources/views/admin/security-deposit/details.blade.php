@extends('admin.layouts.app')

@php include_once(app_path().'/helpers/Helper.php'); @endphp

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10  col-xs-12 mx-auto ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Invoice Description</h5>
                        <div style="float: right;"> <button onclick="printDiv('GFG')" class="btn btn-outline-secondary d-none d-md-block btn-icon">
                                <i class="bi bi-printer"></i> Print
                            </button> </div>
                    </div>
                    @if($income->bill_type=='Service Charge')
                        <div class="card-body card-block" id="GFG">
                            <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                    <tr>
                                        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                                 <span> Contact No: 01321142060, 01321142063</span> <br>
                                                <span> <strong> Invoice for Service Charge </strong> </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%" valign="top">
                                            <table  style="font-size: 14px; width:50% !important;" >

                                                <tr>
                                                    <td style="width:200px;">Shop No.</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $income->shop_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Shop Name</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $income->shop_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Invoice No.</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $income->invoice_no }}</td>

                                                </tr>


                                            </table>
                                        </td>

                                        <td style="width: 50%;float: right"  >
                                            <table valign="top" class=" " style="font-size: 14px; width:100% !important;">
                                                <tr>
                                                    <td style="width:100px;">Period</td>
                                                    <td style="width:10px;">:</td>
                                                    <td style="text-align: left"> @if(isset($details[0]['month'])){{ $details[0]['month'] }} @endif</td>
                                                </tr>
                                                <tr>

                                                    <td style="width:20%;"> Issue Date</td>
                                                    <td style="width:2%;text-align: left">:</td>
                                                    <td style="text-align: left"> {{ $income->issue_date }}</td>
                                                </tr>

                                                <tr>
                                                    <td style="width:100px;">Due Date</td>
                                                    <td style="width:10px;">:</td>
                                                    <td style="text-align: left"> {{$income->due_date }}</td>
                                                </tr>


                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="display: none">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td style="width:106px;"> Attention  </td>
                                                    <td> :  </td>
                                                    <td> {{ $income->customer->contact_person_name??"---" }}</td>

                                                </tr>
                                                <tr>
                                                    <td> Designation:  </td>
                                                    <td> :  </td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td colspan="3"> &nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"> Dear Sir, <br>

                                                        Please pay our bill within the due date as follows:</td>

                                                </tr>
                                            </table>

                                        </td>

                                    </tr>

                                </table>

                                <div style="height: 2%">&nbsp;</div>
                                <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                    <tr style="background: #d9d9d9;color:#000;">
                                        <td style=""><strong>Volume (Sft)</strong></td>
                                        {{--                                    <td style="width:90px !important;"><strong>Month</strong></td>--}}
                                        <td style=""><strong>Rate per Sft</strong></td>
                                        <td style="width:100px !important;"><strong>
                                                @if($income->bill_type == 'Service Charge')
                                                    Total SC(Tk.)
                                                @else
                                                    Total Rent(Tk.)d
                                                @endif</strong></td>
                                        <td style="width:70px !important;"><strong>Vat Rate %</strong></td>
                                        <td style="width:100px !important;"><strong>Vat Amount(Tk.)</strong></td>
                                        <td style="width:100px !important;"><strong>Total(Tk.)</strong></td>
                                    </tr>
                                    @php
                                        $total=0;
                                        $vat_total=0;
                                        $g_total=0;
                                    @endphp
                                    @foreach($details as $row)
                                        @php
                                            $total += round($row->amount,2);
                                            $vat_total += round($row->vat_amount,2);
                                            $g_total += round($row->total,2);

                                        @endphp
                                        <tr>
                                            <td style="width:300px !important;">{{$row->area_sft}}</td>
                                            {{--                                        <td style="">{{$row->month}}</td>--}}
                                            <td style="width:300px !important;">{{$row->rate_sft}}</td>
                                            <td style="text-align: right">{{ number_format(round($row->amount,2),2) }}</td>
                                            <td style="text-align: right">{{ $row->vat }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="2"><strong> Total </strong> </td>
                                        <td style="text-align: right"><strong>{{number_format($total,2)}}</strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                        <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong> Opening Dues </strong> </td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format($due,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong> Grand Total </strong> </td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                    </tr>

                                </table>
                                <div>  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                                <p></p>
                                <div>
                                    <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>BANGLADESH POLICE KALLYAN TRUST</strong> for Rent.</p>
                                    <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>POLICE PLAZA CONCORD</strong>  for Service Charge, Electricity etc.</p>
                                </div>

                                <div style="height: 3%">&nbsp;</div>
                                <div style="height: 3%">&nbsp;</div>
                                <div style="height: 3%">&nbsp;</div>
                                <table style="width: 100%">
                                    <tr>
                                        <td  style=" width:20%;text-align: left;">  <hr> Prepared By
                                            <br>
                                            Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                            Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                            <br>
                                            Bangladesh Police Kallyan Trust
                                            <br>
                                            Police Headquarter, Dhaka
                                        </td>
                                        <td style="width: 60%"></td>
                                        <td style=" width:20%;text-align: right;"><hr> Authorized By
                                            <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                            <br> &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @elseif($income->bill_type=='Income')
                        <div class="card-body card-block" id="GFG">
                            <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                    <tr>
                                        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                             <span> Contact No: 01321142060, 01321142063</span> <br>

                                                <span> <strong> Invoice for Income </strong> </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%" valign="top">
                                            <table  style="font-size: 14px; width:50% !important;" >

                                                <tr>
                                                    <td style="width:200px;">Shop No.</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $income->shop_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Shop Name</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $income->shop_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Invoice No.</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $income->invoice_no }}</td>

                                                </tr>


                                            </table>
                                        </td>

                                        <td style="width: 50%;float: right"  >
                                            <table valign="top" class=" " style="font-size: 14px; width:100% !important;">
                                                <tr>
                                                    <td style="width:100px;">Period</td>
                                                    <td style="width:10px;">:</td>
                                                    <td style="text-align: left"> @if(isset($details[0]['month'])){{ $details[0]['month'] }} @endif</td>
                                                </tr>
                                                <tr>

                                                    <td style="width:20%;"> Issue Date</td>
                                                    <td style="width:2%;text-align: left">:</td>
                                                    <td style="text-align: left"> {{ $income->issue_date }}</td>
                                                </tr>

                                                <tr>
                                                    <td style="width:100px;">Due Date</td>
                                                    <td style="width:10px;">:</td>
                                                    <td style="text-align: left"> {{$income->due_date }}</td>
                                                </tr>


                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="display: none">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td style="width:106px;"> Attention  </td>
                                                    <td> :  </td>
                                                    <td> {{ $income->customer->contact_person_name??"---" }}</td>

                                                </tr>
                                                <tr>
                                                    <td> Designation:  </td>
                                                    <td> :  </td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td colspan="3"> &nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"> Dear Sir, <br>

                                                        Please pay our bill within the due date as follows:</td>

                                                </tr>
                                            </table>

                                        </td>

                                    </tr>

                                </table>

                                <div style="height: 2%">&nbsp;</div>
                                <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                    <tr style="background: #d9d9d9;color:#000;">
                                        <td style=""><strong>Item Head</strong></td>
                                        {{--                                    <td style="width:90px !important;"><strong>Month</strong></td>--}}
                                        <td style=""><strong>Description</strong></td>
                                        <td style="width:100px !important;"><strong>Bill (Tk.)</strong></td>
                                        <td style="width:70px !important;"><strong>Vat Rate %</strong></td>
                                        <td style="width:100px !important;"><strong>Vat Amount(Tk.)</strong></td>
                                        <td style="width:100px !important;"><strong>Total(Tk.)</strong></td>
                                    </tr>
                                    @php
                                        $total=0;
                                        $vat_total=0;
                                        $g_total=0;
                                    @endphp
                                    @foreach($details as $row)
                                        @php
                                            $total += round($row->amount,2);
                                            $vat_total += round($row->vat_amount,2);
                                            $g_total += round($row->total,2);

                                        @endphp
                                        <tr>
                                            <td style="width:300px !important;">{{$row->ledger_name}}</td>
                                            {{--                                        <td style="">{{$row->month}}</td>--}}
                                            <td style="width:300px !important;">{{$row->remarks}}</td>
                                            <td style="text-align: right">{{ number_format(round($row->amount,2),2) }}</td>
                                            <td style="text-align: right">{{ $row->vat }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="2"><strong> Total </strong> </td>
                                        <td style="text-align: right"><strong>{{number_format($total,2)}}</strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                        <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong> Opening Dues </strong> </td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format($due,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong> Grand Total </strong> </td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                    </tr>
                                </table>
                                <div>  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                                <p></p>
                                <div>
                                    <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>BANGLADESH POLICE KALLYAN TRUST</strong> for Rent.</p>
                                    <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>POLICE PLAZA CONCORD</strong>  for Service Charge, Electricity etc.</p>
                                </div>

                                <div style="height: 3%">&nbsp;</div>
                                <div style="height: 3%">&nbsp;</div>
                                <div style="height: 3%">&nbsp;</div>
                                <table style="width: 100%">
                                    <tr>
                                        <td  style=" width:20%;text-align: left;">  <hr> Prepared By
                                            <br>
                                            Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                            Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                            <br>
                                            Bangladesh Police Kallyan Trust
                                            <br>
                                            Police Headquarter, Dhaka
                                        </td>
                                        <td style="width: 60%"></td>
                                        <td style=" width:20%;text-align: right;"><hr> Authorized By
                                            <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                            <br> &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @elseif($income->bill_type=='Electricity')
                        <div class="card-body card-block" id="GFG">
                            <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                    <tr>
                                        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                                 <span> Contact No: 01321142060, 01321142063</span> <br>

                                                <span> <strong> Invoice for Electricity Service </strong> </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%" valign="top">
                                            <table  style="font-size: 14px; width:50% !important;" >

                                                <tr>
                                                    <td style="width:200px;">Shop No.</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $income->shop_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Shop Name</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $income->shop_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Invoice No.</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $income->invoice_no }}</td>

                                                </tr>


                                            </table>
                                        </td>

                                        <td style="width: 50%;float: right"  >
                                            <table valign="top" class=" " style="font-size: 14px; width:100% !important;">
                                                <tr>
                                                    <td style="width:100px;">Period</td>
                                                    <td style="width:10px;">:</td>
                                                    <td style="text-align: left"> @if(isset($details[0]['month'])){{ $details[0]['month'] }} @endif</td>
                                                </tr>
                                                <tr>

                                                    <td style="width:20%;"> Issue Date</td>
                                                    <td style="width:2%;text-align: left">:</td>
                                                    <td style="text-align: left"> {{ $income->issue_date }}</td>
                                                </tr>

                                                <tr>
                                                    <td style="width:100px;">Due Date</td>
                                                    <td style="width:10px;">:</td>
                                                    <td style="text-align: left"> {{$income->due_date }}</td>
                                                </tr>


                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="display: none">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td style="width:106px;"> Attention  </td>
                                                    <td> :  </td>
                                                    <td> {{ $income->customer->contact_person_name??"---" }}</td>

                                                </tr>
                                                <tr>
                                                    <td> Designation:  </td>
                                                    <td> :  </td>
                                                    <td></td>

                                                </tr>
                                                <tr>
                                                    <td colspan="3"> &nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"> Dear Sir, <br>

                                                        Please pay our bill within the due date as follows:</td>

                                                </tr>
                                            </table>

                                        </td>

                                    </tr>

                                </table>

                                <div style="height: 2%">&nbsp;</div>
                                <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                    <tr style="background: #d9d9d9;color:#000;">
                                        <td style=""><strong>Current Readings</strong></td>
                                        <td style=""><strong>Previous Readings</strong></td>
                                        <td style=""><strong>KWH</strong></td>
                                        <td style=""><strong>Rate/KWH</strong></td>
                                        <td style="width:100px !important;"><strong>Bill Amount (Tk.)</strong></td>
                                        <td style="width:70px !important;"><strong>Vat Rate %</strong></td>
                                        <td style="width:100px !important;"><strong>Vat Amount(Tk.)</strong></td>
                                        <td style="width:100px !important;"><strong>Total Bill (Tk.)</strong></td>
                                    </tr>
                                    @php
                                        $total=0;
                                        $vat_total=0;
                                        $g_total=0;
                                    @endphp
                                    @foreach($details as $row)
                                        @php
                                            $total += round($row->amount,2);
                                            $vat_total += round($row->vat_amount,2);
                                            $g_total += round($row->total,2);

                                        @endphp
                                        <tr>
                                            <td style="width:300px !important;">{{$row->current_reading}}</td>
                                            <td style="width:300px !important;">{{$row->pre_reading}}</td>
                                            <td style="width:300px !important;">{{$row->kwt}}</td>
                                            <td style="width:300px !important;">{{$row->kwt_rate}}</td>
                                            <td style="text-align: right">{{ number_format(round($row->amount,2),2) }}</td>
                                            <td style="text-align: right">{{ $row->vat }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="4"><strong> Total </strong> </td>
                                        <td style="text-align: right"><strong>{{number_format($total,2)}}</strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                        <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><strong> Opening Dues </strong> </td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format($due,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><strong> Grand Total </strong> </td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                    </tr>
                                </table>
                                <div>  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                                <p></p>
                                <div>
                                    <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>BANGLADESH POLICE KALLYAN TRUST</strong> for Rent.</p>
                                    <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>POLICE PLAZA CONCORD</strong>  for Service Charge, Electricity etc.</p>
                                </div>

                                <div style="height: 3%">&nbsp;</div>
                                <div style="height: 3%">&nbsp;</div>
                                <div style="height: 3%">&nbsp;</div>
                                <table style="width: 100%">
                                    <tr>
                                        <td  style=" width:20%;text-align: left;">  <hr> Prepared By
                                            <br>
                                            Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                            Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                            <br>
                                            Bangladesh Police Kallyan Trust
                                            <br>
                                            Police Headquarter, Dhaka
                                        </td>
                                        <td style="width: 60%"></td>
                                        <td style=" width:20%;text-align: right;"><hr> Authorized By
                                            <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                            <br> &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @else
                    <div class="card-body card-block" id="GFG">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                <img style=" width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                <tr>
                                    <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                       <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                         <span> Contact No: 01321142060, 01321142063</span> <br>
                                        <span> <strong> Invoice for Rent </strong> </span>
                                    </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%" valign="top">
                                        <table  style="font-size: 14px; width:50% !important;" >

                                             <tr>
                                                <td style="width:200px;">Shop No.</td>
                                                <td style="width:10px;">:</td>
                                                <td> {{ $income->shop_no }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px;">Shop Name</td>
                                                <td style="width:10px;">:</td>
                                                <td> {{ $income->shop_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px;">Invoice No.</td>
                                                <td style="width:10px;">:</td>
                                                <td> {{ $income->invoice_no }}</td>

                                            </tr>


                                        </table>
                                    </td>

                                    <td style="width: 50%;float: right"  >
                                        <table valign="top" class=" " style="font-size: 14px; width:100% !important;">
                                            <tr>
                                                <td style="width:100px;">Period</td>
                                                <td style="width:10px;">:</td>
                                                <td style="text-align: left"> @if(isset($details[0]['month'])){{ $details[0]['month'] }} @endif</td>
                                            </tr>
                                            <tr>

                                                <td style="width:20%;"> Issue Date</td>
                                                <td style="width:2%;text-align: left">:</td>
                                                <td style="text-align: left"> {{ $income->issue_date }}</td>
                                            </tr>

                                            <tr>
                                                <td style="width:100px;">Due Date</td>
                                                <td style="width:10px;">:</td>
                                                <td style="text-align: left"> {{$income->due_date }}</td>
                                            </tr>


                                        </table>
                                    </td>
                                </tr>
                                <tr style="display: none">
                                    <td colspan="2">
                                        <table>
                                            <tr>
                                                <td style="width:106px;"> Attention  </td>
                                                <td> :  </td>
                                                <td> {{ $income->customer->contact_person_name??"---" }}</td>

                                            </tr>
                                            <tr>
                                                <td> Designation:  </td>
                                                <td> :  </td>
                                                <td></td>

                                            </tr>
                                            <tr>
                                                <td colspan="3"> &nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"> Dear Sir, <br>

                                                 Please pay our bill within the due date as follows:</td>

                                            </tr>
                                        </table>

                                    </td>

                                </tr>

                            </table>

                            <div style="height: 2%">&nbsp;</div>
                            <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tr style="background: #d9d9d9;color:#000;">
                                    <td style=""><strong>Volume (Sft)</strong></td>
{{--                                    <td style="width:90px !important;"><strong>Month</strong></td>--}}
                                    <td style=""><strong>Rate per Sft</strong></td>
                                    <td style="width:100px !important;"><strong>
                                            @if($income->bill_type == 'Service Charge')
                                            Total SC(Tk.)
                                            @else
                                                Total Rent(Tk.)
                                            @endif

                                        </strong>
                                    </td>
                                    <td style="width:70px !important;"><strong>Vat Rate %</strong></td>
                                    <td style="width:100px !important;"><strong>Vat Amount(Tk.)</strong></td>
                                    <td style="width:100px !important;"><strong>Total(Tk.)</strong></td>
                                </tr>
                                @php
                                    $total=0;
                                    $vat_total=0;
                                    $g_total=0;
                                @endphp
                                @foreach($details as $row)
                                    @php
                                        $total += round($row->amount,2);
                                        $vat_total += round($row->vat_amount,2);
                                        $g_total += round($row->total,2);

                                    @endphp
                                    <tr>
                                        <td style="width:300px !important;">{{$row->area_sft}}</td>
{{--                                        <td style="">{{$row->month}}</td>--}}
                                        <td style="width:300px !important;">{{$row->rate_sft}}</td>
                                        <td style="text-align: right">{{ number_format(round($row->amount,2),2) }}</td>
                                        <td style="text-align: right">{{ $row->vat }}</td>
                                        <td style="text-align: right">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                        <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="2"><strong> Total </strong> </td>
                                    <td style="text-align: right"><strong>{{number_format($total,2)}}</strong></td>
                                    <td><strong></strong></td>
                                    <td style="text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                    <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong> Opening Dues </strong> </td>
                                    <td style="text-align: right"><strong></strong></td>
                                    <td><strong></strong></td>
                                    <td style="text-align: right"><strong></strong></td>
                                    <td style="text-align: right"><strong>{{number_format($due,2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong> Grand Total </strong> </td>
                                    <td style="text-align: right"><strong></strong></td>
                                    <td><strong></strong></td>
                                    <td style="text-align: right"><strong></strong></td>
                                    <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                </tr>
                            </table>
                            <div>  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                            <p></p>
                            <div>
                                <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>BANGLADESH POLICE KALLYAN TRUST</strong> for Rent.</p>
                                <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>POLICE PLAZA CONCORD</strong>  for Service Charge, Electricity etc.</p>
                            </div>

                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <table style="width: 100%">
                                <tr>
                                    <td  style=" width:20%;text-align: left;">  <hr> Prepared By
                                    <br>
                                    Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                        Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                    <br>
                                        Bangladesh Police Kallyan Trust
                                        <br>
                                        Police Headquarter, Dhaka
                                    </td>
                                    <td style="width: 60%"></td>
                                    <td style=" width:20%;text-align: right;"><hr> Authorized By
                                    <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                    <br> &nbsp;
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @endif
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
