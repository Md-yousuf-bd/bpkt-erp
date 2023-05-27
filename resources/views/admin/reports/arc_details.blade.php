
@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Receivable & Collection Details Report
                            <span style="float: right;cursor: pointer" class="active" onclick=" printDiv('show_dsds_tbl')"> <a href="#"> <i style="font-size: 24px;" class="bi bi-printer "></i></a>  </span>

                        </h5>

                    </div>


                    <div id="show_dsds_tbl">
                        <?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>
                        <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                            <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
                            <tr>
                                <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                    <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                                        <span> aigwel@police.gov.bd</span> <br>
                                        <span> Contact No: 01769693755</span> <br>
                                        <br>
                                        <br>
                                        <span> <strong> Receivable & Collection Details Report </strong> </span>


                                    </p>
                                </td>
                            </tr>



                        </table>
                        <table  class="table table-bordered " style="font-size: 14px; width:100%;">
                            <thead>
                            <tr>
                                <td rowspan="2" style="width: 3%;font-weight: bold">S.L.</td>
                                <td rowspan="2" style="font-weight: bold">Service Name</td>
                                <td rowspan="2" style="font-weight: bold">Shop No</td>
                                <td rowspan="2" style="font-weight: bold">Shop Name</td>
                                <td rowspan="2" style="font-weight: bold">Meter No</td>
                                <td colspan="3" style="text-align: center;font-weight: bold"> {{$month}} </td>
                            </tr>
                            <tr>
                                <td style="text-align: center;font-weight: bold">Receivable</td>
                                <td style="text-align: center;font-weight: bold">Collection</td>
                                <td style="text-align: center;font-weight: bold">Due </td>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $total=0;
                                $gtotal=0;
                                $rent=0;
                                $sc=0;
                                $fixed_fine=0;
                                $fine_amount=0;
                                $el=0;
                                $fcsc=0;
                                $due=0;
                                $ad=0;

                            @endphp


                            @foreach($customer as $key=>$row)

                                @php
                               // echo $row['id'].',';
                                    $total += $row['bill_amount'] + $row['vat_amount'];
                                    $gtotal +=$row['paid_amount'] + $row['paid_vatamount'];
                                    $due += ($row['bill_amount'] + $row['vat_amount']) - ($row['paid_amount'] + $row['paid_vatamount']);


                                @endphp
                                <tr>
                                    <td style="text-align: right"> {{ $i++ }} </td>
                                    <td style="text-align: left"> {{  $row['ledger_name']  }}  </td>
                                    <td style="text-align: left"> {{  $row['shop_no']  }}  </td>
                                    <td style="text-align: left"> {{  $row['shop_name']  }}  </td>
                                    <td style="text-align: left"> {{  $row['meter_no']  }}  </td>
                                    <td style="text-align: right"> {{  number_format($row['bill_amount'] ,2)}} </td>
                                    <td style="text-align: right"> {{  number_format($row['paid_amount'],2) }} </td>
                                    <td style="text-align: right"> {{  number_format($row['bill_amount'] -$row['paid_amount'],2) }}  </td>

                                </tr>
                                @if( $row['vat_amount']> 0)
                                    <tr>
                                        <td style="text-align: right"> {{ $i++ }} </td>
                                        <td style="text-align: left"> Sales Vat Payable  </td>
                                        <td style="text-align: left"> {{  $row['shop_no']  }}  </td>
                                        <td style="text-align: left"> {{  $row['shop_name']  }}  </td>
                                        <td style="text-align: left"> {{  $row['meter_no']  }}  </td>
                                        <td style="text-align: right"> {{  number_format($row['vat_amount'] ,2)}} </td>
                                        <td style="text-align: right"> {{  number_format($row['paid_vatamount'],2) }} </td>
                                        <td style="text-align: right"> {{  number_format($row['vat_amount'] -$row['paid_vatamount'],2) }}  </td>

                                    </tr>
                                    @endif


                            @endforeach
                            <tr >
                                <td colspan="5" style="text-align: right;">
                                    <strong> Total </strong>
                                </td>
                                <td style="text-align: right">
                                    <strong>   {{ number_format($total,2) }}  </strong>
                                </td>
                                <td style="text-align: right">
                                    <strong>   {{ number_format($gtotal,2) }}  </strong>
                                </td>
                                <td style="text-align: right">
                                    <strong>   {{ number_format($due,2) }}  </strong>
                                </td>







                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('uncommonExJs')
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
@endsection

@section('uncommonInJs')

@endsection



