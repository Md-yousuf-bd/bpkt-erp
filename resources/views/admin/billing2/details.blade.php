@extends('admin.layouts.app')

@php include_once(app_path().'/helpers/Helper.php'); @endphp

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection
@php $div_print="print_div_billing"; @endphp;
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10  col-xs-12 mx-auto ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Invoice Description
                            <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('{{$div_print}}')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span>


                        </h5>

                    </div>
                    @if($income->bill_type=='Service Charge')
                        <div class="card-body card-block" id="{{$div_print}}">
                            <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                    <tr>
                                        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                                <!--<span> aigwel@police.gov.bd</span> <br>-->
                                                <!--<span> Contact No: 01321142060</span> <br>-->
                                                <span> <strong>  Service Charge </strong> </span>
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
                                                    <td> {{ $assetShop->shop_name??"" }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Customer Name</td>
                                                    <td style="width:10px;">: </td>
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
                                                    <td style="width:10px;">: </td>
                                                    <td style="text-align: left">
                                                        @if($income->bill_type=='Advertisement' )
                                                            {{ $income->billing_period_manual  }}
                                                        @else
                                                            @if(isset($details[0]['month']))
                                                                {{ $details[0]['month'] }}
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td style="width:20%;"> Issue Date</td>
                                                    <td style="width:2%;text-align: left">: </td>
                                                    <td style="text-align: left"> {{ $income->issue_date }}</td>
                                                </tr>

                                                <tr>
                                                    <td style="width:100px;">Due Date</td>
                                                    <td style="width:10px;">: </td>
                                                    <td style="text-align: left"> {{$income->due_date }}</td>
                                                </tr>


                                            </table>
                                        </td>
                                    </tr>
                                    <tr style="display: none">
                                        <td colspan="2">
                                            <table>
                                                <tr>
                                                    <td style="width:106px;text-align: left;"> Attention  </td>
                                                    <td style="text-align: left;"> :  </td>
                                                    <td style="text-align: left"> {{ $income->customer->contact_person_name??"---" }}</td>

                                                </tr>
                                                <tr>
                                                    <td style="text-align: left"> Designation:  </td>
                                                    <td style="text-align: left"> :  </td>
                                                    <td style="text-align: left"></td>

                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: left"> &nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: left">Dear Sir, <br>

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
                                        <td style="width:100px !important;;display: none;"><strong>
                                                @if($income->bill_type == 'Service Charge')
                                                    Total SC(Tk.)
                                                @else
                                                    Total Rent(Tk.)d
                                                @endif</strong></td>
                                        <td style="display:none;width:70px !important;"><strong>Vat Rate %</strong></td>
                                        <td style="display:none;width:100px !important;"><strong>Vat Amount(Tk.)</strong></td>
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
                                            <td style="width:300px !important;">{{round($row->rate_sft,2)}}</td>
                                            <td style="text-align: right;display: none;">{{ number_format(round($row->amount,2),2) }}</td>
                                            <td style="display:none;text-align: right">{{ $row->vat }}</td>
                                            <td style="display:none;text-align: right">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="2"><strong> Total (This invoice) </strong> </td>
                                        <td style="text-align: right;display: none;"><strong>{{number_format($total,2)}}</strong></td>
                                        <td style="display:none;"><strong></strong></td>
                                        <td style="display:none;text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                        <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"> Previous Dues  </td>
                                        <td style="text-align: right;display: none;"><strong></strong></td>
                                        <td style="display:none;"><strong></strong></td>
                                        <td style="display:none;text-align: right"><strong></strong></td>
                                        <td style="text-align: right">{{number_format($due,2)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong> Total Dues </strong> </td>
                                        <td style="text-align: right;display: none;"><strong></strong></td>
                                        <td style="display:none;"><strong></strong></td>
                                        <td style="display:none;text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                    </tr>

                                </table>
                                <div  style="text-align: left;">  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                                <p></p>
                                <div style="text-align: left;">
                                    <br>

                                    <p>
                                        Fine after due date (Bill Amount + 500) = {{ number_format($total+500,2)  }}, next month fine Will be {(Bill Amount) + 3%} = {{ number_format($total+500+($total)*.03,2)  }} & will Gradually increase 6%, 9%,12% respectively
                                    </p>

                                    <p>
                                        Please pay the above mentioned dues by Cash / Pay Order in the name of <strong>"POLICE PLAZA CONCORD"</strong> or directly deposit to the following bank account:
                                    </p>
                                    <p> Account Name: POLICE PLAZA CONCORD <br>
                                        Account No: 0040300770101 <br>
                                        Routing No: 310274247 <br>
                                        Branch Name: Motijheel <br>
                                        Bank Name: Community Bank Bangladesh Limited </p>
                                </div>



                                <div style="height: 3%">&nbsp;</div>
                                <table style="width: 100%">
                                    <tr>
                                        <td  style=" width:50%;text-align: left;">  <hr> Prepared By
                                            <br>
                                            Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                            Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                            <br>
                                            Bangladesh Police Kallyan Trust
                                            <br>
                                            Police Headquarter, Dhaka
                                        </td>
                                        <td style="width: 30%"></td>
                                        <td style=" width:20%;text-align: right;"><hr> Authorized By
                                            <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                            <br> &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>





                    @elseif($income->bill_type=='Food Court Service Charge')
                            <div class="card-body card-block" id="{{$div_print}}">
                                <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                    <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                        <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                        <tr>
                                            <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                                <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                                    <!--<span> aigwel@police.gov.bd</span> <br>-->
                                                    <!--<span> Contact No: 01769693755</span> <br>-->

                                                    <span> <strong> Invoice for Food Court Service Charge </strong> </span>
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
                                                        <td> {{ $assetShop->shop_name??"" }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:200px;">Customer Name</td>
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
                                                        <td style="width:106px;text-align: left;"> Attention  </td>
                                                        <td style="text-align: left;"> :  </td>
                                                        <td style="text-align: left"> {{ $income->customer->contact_person_name??"---" }}</td>

                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"> Designation:  </td>
                                                        <td style="text-align: left"> :  </td>
                                                        <td style="text-align: left"></td>

                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="text-align: left"> &nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="text-align: left">bert bDear Sir, <br>

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
                                            <td style="width:100px !important;display:none;"><strong>
                                                    @if($income->bill_type == 'Service Charge')
                                                        Total SC(Tk.)
                                                    @else
                                                        Total Rent(Tk.)
                                                    @endif</strong></td>
                                            <td style="display:none;width:70px !important;"><strong>Vat Rate %</strong></td>
                                            <td style="display:none;width:100px !important;"><strong>Vat Amount(Tk.)</strong></td>
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
                                                <td style="text-align: right;display:none;">{{ number_format(round($row->amount,2),2) }}</td>
                                                <td style="display:none;text-align: right">{{ $row->vat }}</td>
                                                <td style="display:none;text-align: right">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                                <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="2"><strong> Total (This invoice) </strong> </td>
                                            <td style="text-align: right;display:none;"><strong>{{number_format($total,2)}}</strong></td>
{{--                                            <td style="display:none;"><strong></strong></td>--}}
                                            <td style="display:none;text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                            <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> Previous Dues  </td>
{{--                                            <td style="text-align: right"><strong></strong></td>--}}
{{--                                            <td style="display:none;"><strong></strong></td>--}}
                                            <td style="display:none;text-align: right"><strong></strong></td>
                                            <td style="text-align: right">{{number_format($due,2)}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><strong> Total Dues </strong> </td>
{{--                                            <td style="text-align: right"><strong></strong></td>--}}
                                            <td style="display:none;"><strong></strong></td>
                                            <td style="display:none;text-align: right"><strong></strong></td>
                                            <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                        </tr>

                                    </table>
                                    <div  style="text-align: left;">  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                                    <p></p>
                                    <div style="text-align: left;">
                                        <p>
                                            Please pay the above mentioned dues by Pay Order in the name of <strong>"POLICE PLAZA CONCORD"</strong> or directly deposit to the following bank account:
                                        </p>
                                        <p> Account Name: POLICE PLAZA CONCORD <br>
                                            Account No: 0040300770101<br>
                                            Routing No: 310274247 <br>
                                            Branch Name: Motijheel <br>
                                            Bank Name: Community Bank Bangladesh Limited </p>
                                    </div>


                                    <div style="height: 3%">&nbsp;</div>
                                    <table style="width: 100%">
                                        <tr>
                                            <td  style=" width:50%;text-align: left;">  <hr> Prepared By
                                                <br>
                                                Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                                Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                                <br>
                                                Bangladesh Police Kallyan Trust
                                                <br>
                                                Police Headquarter, Dhaka
                                            </td>
                                            <td style="width: 30%"></td>
                                            <td style=" width:20%;text-align: right;"><hr> Authorized By
                                                <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                                <br> &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                    @elseif($income->bill_type=='Special Service Charge')
                        <div class="card-body card-block" id="{{$div_print}}">
                            <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                    <tr>
                                        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                                <!--<span> aigwel@police.gov.bd</span> <br>-->
                                                <!--<span> Contact No: 01769693755</span> <br>-->

                                                <span> <strong> Invoice for Special Service Charge </strong> </span>
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
                                                    <td> {{ $assetShop->shop_name??"" }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Customer Name</td>
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
                                                    <td style="width:106px;text-align: left;"> Attention  </td>
                                                    <td style="text-align: left;"> :  </td>
                                                    <td style="text-align: left"> {{ $income->customer->contact_person_name??"---" }}</td>

                                                </tr>
                                                <tr>
                                                    <td style="text-align: left"> Designation:  </td>
                                                    <td style="text-align: left"> :  </td>
                                                    <td style="text-align: left"></td>

                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: left"> &nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="text-align: left">bert bDear Sir, <br>

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
                                        <td style="width:100px !important;display: none;"><strong>
                                                @if($income->bill_type == 'Service Charge')
                                                    Total SC(Tk.)
                                                @else
                                                    Total Rent(Tk.)d
                                                @endif</strong></td>
                                        <td style="display:none;width:70px !important;"><strong>Vat Rate %</strong></td>
                                        <td style="display:none;width:100px !important;"><strong>Vat Amount(Tk.)</strong></td>
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
                                            <td style="text-align: right;display: none;">{{ number_format(round($row->amount,2),2) }}</td>
                                            <td style="display:none;text-align: right">{{ $row->vat }}</td>
                                            <td style="display:none;text-align: right">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="2"><strong> Total (This invoice) </strong> </td>
                                        <td style="text-align: right;display: none;"><strong>{{number_format($total,2)}}</strong></td>
                                        <td style="display:none;"><strong></strong></td>
                                        <td style="display:none;text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                        <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"> Previous Dues  </td>
                                        <td style="text-align: right;display: none;"><strong></strong></td>
                                        <td style="display:none;"><strong></strong></td>
                                        <td style="display:none;text-align: right"><strong></strong></td>
                                        <td style="text-align: right">{{number_format($due,2)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong> Total Dues </strong> </td>
                                        <td style="text-align: right;display: none;"><strong></strong></td>
                                        <td style="display:none;"><strong></strong></td>
                                        <td style="display:none;text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                    </tr>

                                </table>
                                <div  style="text-align: left;">  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                                <p></p>
                                <div style="text-align: left;">

                                    <p>
                                        Please pay the above mentioned dues by Pay Order in the name of <strong>"POLICE PLAZA CONCORD"</strong> or directly deposit to the following bank account:
                                    </p>
                                    <p> Account Name: POLICE PLAZA CONCORD <br>
                                        Account No: 0040300770101<br>
                                        Routing No: 310274247 <br>
                                        Branch Name: Motijheel <br>
                                        Bank Name: Community Bank Bangladesh Limited </p>
                                </div>


                                <div style="height: 3%">&nbsp;</div>
                                <table style="width: 100%">
                                    <tr>
                                        <td  style=" width:50%;text-align: left;">  <hr> Prepared By
                                            <br>
                                            Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                            Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                            <br>
                                            Bangladesh Police Kallyan Trust
                                            <br>
                                            Police Headquarter, Dhaka
                                        </td>
                                        <td style="width: 30%"></td>
                                        <td style=" width:20%;text-align: right;"><hr> Authorized By
                                            <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                            <br> &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @elseif($income->bill_type=='Income')
                        <div class="card-body card-block" id="{{$div_print}}">
                            <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                                    <tr>
                                        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                                <!--<span> aigwel@police.gov.bd</span> <br>-->
                                                <!--<span> Contact No: 01769693755</span> <br>-->

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
                                                    <td> {{ $assetShop->shop_name??"" }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Customer Name</td>
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
                                        <td colspan="2"><strong> Total (This invoice) </strong> </td>
                                        <td style="text-align: right"><strong>{{number_format($total,2)}}</strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                        <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"> Previous Dues  </td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td style="text-align: right">{{number_format($due,2)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong> Total Dues </strong> </td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td><strong></strong></td>
                                        <td style="text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                    </tr>
                                </table>
                                <div style="text-align: left">  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                                <p></p>
                                <div style="text-align: left;display: none;">
                                    <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>BANGLADESH POLICE KALLYAN TRUST</strong> for Rent.</p>
                                    <p> Please pay the above mentioned dues by Cash or Pay Order in the name of <strong>POLICE PLAZA CONCORD</strong>  for Service Charge, Electricity etc.</p>
                                </div>


                                <div style="height: 3%">&nbsp;</div>
                                <table style="width: 100%">
                                    <tr>
                                        <td  style=" width:50%;text-align: left;">  <hr> Prepared By
                                            <br>
                                            Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                            Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                            <br>
                                            Bangladesh Police Kallyan Trust
                                            <br>
                                            Police Headquarter, Dhaka
                                        </td>
                                        <td style="width: 30%"></td>
                                        <td style=" width:20%;text-align: right;"><hr> Authorized By
                                            <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                            <br> &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @elseif($income->bill_type=='Electricity')
                        <div class="card-body card-block" id="{{$div_print}}">
                            <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">

                                    <tr>
                                        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
{{--                                                <span> aigwel@police.gov.bd</span> <br>--}}
{{--                                                <span> Contact No: 01769693755</span> <br>--}}

                                                <span> <strong> Electricity Bill </strong> </span>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%" valign="top">
                                            <table  style="font-size: 14px; width:50% !important;" >

                                                <tr>
                                                    <td style="width:200px;">Shop No.</td>
                                                    <td style="width:10px;">: </td>
                                                    <td style="padding-left: 5px;"> {{ $income->shop_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Shop Name</td>
                                                    <td style="width:10px;">:</td>
                                                    <td> {{ $assetShop->shop_name??"" }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Customer Name</td>
                                                    <td style="width:10px;">: </td>
                                                    <td style="padding-left: 5px;"> {{ $income->shop_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Meter No.</td>
                                                    <td style="width:10px;">: </td>
                                                    <td style="padding-left: 5px;"> {{ $income->meter_no??"---" }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Invoice No.</td>
                                                    <td style="width:10px;">: </td>
                                                    <td style="padding-left: 5px;"> {{ $income->invoice_no }}</td>

                                                </tr>


                                            </table>
                                        </td>

                                        <td style="width: 50%;float: right"  >
                                            <table valign="top" class=" " style="font-size: 14px; width:100% !important;">
                                                <tr>
                                                    <td style="width:100px;">Period</td>
                                                    <td style="width:10px;">:</td>
                                                    <td style="text-align: left;padding-left:5px;"> @if(isset($details[0]['month'])){{ $details[0]['month'] }} @endif</td>
                                                </tr>
                                                <tr>

                                                    <td style="width:20%;"> Issue Date</td>
                                                    <td style="width:2%;text-align: left">:</td>
                                                    <td style="text-align: left;padding-left:5px;"> {{ date('d-m-Y',strtotime($income->issue_date)) }}</td>
                                                </tr>

                                                <tr>
                                                    <td style="width:100px;">Due Date</td>
                                                    <td style="width:10px;">:</td>
                                                    <td style="text-align: left;padding-left:5px;"> {{ date('d-m-Y',strtotime($income->due_date)) }}</td>
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
                                        <td style="width:100px !important;;display: none;"><strong>Bill Amount (Tk.)</strong></td>
                                        <td style="width:70px !important;display: none;"><strong>Vat Rate %</strong></td>
                                        <td style="width:100px !important;display: none;"><strong>Vat Amount(Tk.)</strong></td>
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
                                            <td style="width:300px !important;">{{round($row->kwt_rate,3)}}</td>
                                            <td style="text-align: right;display: none;">{{ number_format(round($row->amount,2),2) }}</td>
                                            <td style="text-align: right;display: none;">{{ $row->vat }}</td>
                                            <td style="text-align: right;display: none;">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="4"><strong> Total (This invoice) </strong> </td>
                                        <td style="text-align: right;display: none;"><strong>{{number_format($total,2)}}</strong></td>
                                        <td style="display: none;"><strong></strong></td>
                                        <td style="text-align: right;display: none;"><strong>{{number_format($vat_total,2)}}</strong></td>
                                        <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"> Previous Dues  </td>
                                        <td style="text-align: right;display: none;"><strong></strong></td>


                                        <td style="text-align: right">{{number_format($due,2)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><strong> Total Dues </strong> </td>
                                        <td style="text-align: right;display: none;"><strong></strong></td>


                                        <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                    </tr>
                                </table>
                                <div style="text-align: left;">  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                                <p></p>
                                <div style="text-align: left;">

                                    <p> Payable After Due Date (Bill Amount + 10%)= {{ number_format(($g_total+($g_total*.1)),2) }}</p>

                                    <p>
                                        Please pay the above mentioned dues by Pay Order in the name of <strong>"POLICE PLAZA CONCORD"</strong> or directly deposit to the following bank account:
                                    </p>
                                    <p> Account Name: POLICE PLAZA CONCORD <br>
                                 Account No: 0040300770101 <br>
                                        Routing No: 310274247 <br>
                                    Branch Name: Motijheel <br>
                                    Bank Name: Community Bank Bangladesh Limited </p>
                                </div>


                                <div style="height: 3%">&nbsp;</div>
                                <table style="width: 100%">
                                    <tr>
                                        <td  style=" width:50%;text-align: left;">  <hr> Prepared By
                                            <br>
                                            Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                            Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                            <br>
                                            Bangladesh Police Kallyan Trust
                                            <br>
                                            Police Headquarter, Dhaka
                                        </td>
                                        <td style="width: 30%"></td>
                                        <td style=" width:20%;text-align: right;"><hr> Authorized By
                                            <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                            <br> &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @elseif($income->bill_type=='Advertisement')
                        <div class="card-body card-block" id="{{$div_print}}">
                            <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                                <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">

                                    <tr>
                                        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                                <!--<span> aigwel@police.gov.bd</span> <br>-->
                                                <!--<span> Contact No: 01769693755</span> <br>-->

                                                <span> <strong> Invoice for Advertisement </strong> </span>
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
                                                    <td> {{ $assetShop->shop_name??"" }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width:200px;">Customer Name</td>
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
                                                    <td style="text-align: left">  @if($income->bill_type=='Advertisement' )
                                                            {{ $income->billing_period_manual  }}
                                                        @else
                                                            @if(isset($details[0]['month']))
                                                                {{ $details[0]['month'] }}
                                                            @endif
                                                        @endif</td>
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
                                        <td style=""><strong>Space Name</strong></td>
                                        <td style=""><strong>Volume (Sft)</strong></td>
                                        {{--                                    <td style="width:90px !important;"><strong>Month</strong></td>--}}
                                        <td style=""><strong>Rate per Sft</strong></td>
                                        <td style="width:100px !important;display: none;"><strong>
                                                @if($income->bill_type == 'Service Charge')
                                                    Total SC(Tk.)
                                                @else
                                                    Total Rent(Tk.)
                                                @endif

                                            </strong>
                                        </td>
                                        <td style="display:none;width:70px !important;"><strong>Vat Rate %</strong></td>
                                        <td style="display:none;width:100px !important;"><strong>Vat Amount(Tk.)</strong></td>
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
                                            <td style="width:300px !important;">{{$row->space_name}}</td>
                                            <td style="width:300px !important;">{{$row->area_sft}}</td>
                                            {{--                                        <td style="">{{$row->month}}</td>--}}
                                            <td style="width:300px !important;">{{round($row->rate_sft,2)}}</td>
                                            <td style="text-align: right;display: none;">{{ number_format(round($row->amount,2),2) }}</td>
                                            <td style="display:none;text-align: right">{{ $row->vat }}</td>
                                            <td style="display:none;text-align: right">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                            <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="3"><strong> Total (This invoice)
                                            </strong> </td>
                                        <td style="text-align: right;display: none;"><strong>{{number_format($total,2)}}</strong></td>
                                        <td style="display:none;"><strong></strong></td>
                                        <td style="display:none;text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                        <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"> Previous Dues </td>
                                        <td style="text-align: right;display: none;"><strong></strong></td>
                                        <td style="display:none;"><strong></strong></td>
                                        <td style="display:none;text-align: right"><strong></strong></td>
                                        <td style="text-align: right">{{number_format($due,2)}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><strong> Total Dues </strong> </td>
                                        <td style="text-align: right;display: none;"><strong></strong></td>
                                        <td style="display:none;"><strong></strong></td>
                                        <td style="display:none;text-align: right"><strong></strong></td>
                                        <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                    </tr>
                                </table>
                                <div style="text-align: left">  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                                <p></p>
                                <div style="text-align: left;">
                                    <br>

                                    <p>
                                        Please pay the above mentioned dues by Pay Order in the name of <strong>"PPC BIGGAPON AAI"</strong> or directly deposit to the following bank account:</p>
                                    <p> Account Name: PPC BIGGAPON AAI <br>
                                        Account No: 0040301943101<br>
                                        Routing No: 310274247 <br>
                                        Branch Name: Motijheel <br>
                                        Bank Name: Community Bank Bangladesh Limited </p>
                                </div>


                                <div style="height: 3%">&nbsp;</div>
                                <table style="width: 100%">
                                    <tr>
                                        <td  style=" width:50%;text-align: left;">  <hr> Prepared By
                                            <br>
                                            Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                            Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                            <br>
                                            Bangladesh Police Kallyan Trust
                                            <br>
                                            Police Headquarter, Dhaka
                                        </td>
                                        <td style="width: 30%"></td>
                                        <td style=" width:20%;text-align: right;"><hr> Authorized By
                                            <br> &nbsp; <br> &nbsp; <br> &nbsp;
                                            <br> &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @else
                    <div class="card-body card-block" id="{{$div_print}}">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;" src="{{ URL::asset('images/logos/logo-in.png') }}">

                                <tr>
                                    <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                       <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                         <!--<span> aigwel@police.gov.bd</span> <br>-->
                                        <!--<span> Contact No: 01769693755</span> <br>-->

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
                                                <td> {{ $assetShop->shop_name??"" }}</td>
                                            </tr>
                                            <tr>
                                                <td style="width:200px;">Customer Name</td>
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
                                    <td style="width:100px !important;display: none;"><strong>
                                            @if($income->bill_type == 'Service Charge')
                                            Total SC(Tk.)
                                            @else
                                                Total Rent(Tk.)
                                            @endif

                                        </strong>
                                    </td>
                                    <td style="display:none;width:70px !important;"><strong>Vat Rate %</strong></td>
                                    <td style="display:none;width:100px !important;"><strong>Vat Amount(Tk.)</strong></td>
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
                                        <td style="width:300px !important;">{{round($row->rate_sft,2)}}</td>
                                        <td style="text-align: right;display: none;">{{ number_format(round($row->amount,2),2) }}</td>
                                        <td style="display:none;text-align: right">{{ $row->vat }}</td>
                                        <td style="display:none;text-align: right">{{ number_format(round($row->vat_amount,2),2) }}</td>
                                        <td style="text-align: right">{{ number_format(round($row->total,2),2) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="2"><strong> Total (This invoice)
                                        </strong> </td>
                                    <td style="text-align: right;display: none;"><strong>{{number_format($total,2)}}</strong></td>
                                    <td style="display:none;"><strong></strong></td>
                                    <td style="display:none;text-align: right"><strong>{{number_format($vat_total,2)}}</strong></td>
                                    <td style="text-align: right"><strong>{{number_format($g_total,2)}}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2"> Previous Dues </td>
                                    <td style="text-align: right;display: none;"><strong></strong></td>
                                    <td style="display:none;"><strong></strong></td>
                                    <td style="display:none;text-align: right"><strong></strong></td>
                                    <td style="text-align: right">{{number_format($due,2)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><strong> Total Dues </strong> </td>
                                    <td style="text-align: right;display: none;"><strong></strong></td>
                                    <td style="display:none;"><strong></strong></td>
                                    <td style="display:none;text-align: right"><strong></strong></td>
                                    <td style="text-align: right"><strong>{{number_format(($due+$g_total),2)}}</strong></td>
                                </tr>
                            </table>
                            <div style="text-align: left">  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>
                            <p></p>
                            <div style="text-align: left;">

                                <p>
                                    Please pay the above mentioned dues by Pay Order in the name of <strong>"BANGLADESH POLICE KALLYAN TRUST"</strong> or directly deposit to the following bank account:
                                </p>
                                <p> Account Name: BANGLADESH POLICE KALLYAN TRUST <br>
                                    Account No: 0040300732301 <br>
                                    Routing No: 310274247 <br>
                                    Branch Name: Motijheel <br>
                                    Bank Name: Community Bank Bangladesh Limited </p>                            </div>


                            <div style="height: 3%">&nbsp;</div>
                            <table style="width: 100%">
                                <tr>
                                    <td  style=" width:50%;text-align: left;">  <hr> Prepared By
                                    <br>
                                    Name: {{\Illuminate\Support\Facades\Auth::user()->name ?? ''}} <br>
                                        Designation:   {{ucwords(str_replace('-',' ',\Illuminate\Support\Facades\Auth::user()->roles->first()->name)) ?? ''}}
                                    <br>
                                        Bangladesh Police Kallyan Trust
                                        <br>
                                        Police Headquarter, Dhaka
                                    </td>
                                    <td style="width: 30%"></td>
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

            </script>



@endsection



@section('uncommonInJs')


@endsection
