

                    <div class="card-header" style="padding-bottom: 0px;padding-top: 0px !important;">
                        <h5 class="card-title">Trial Balance Report</h5>

                    </div>
                    <div class="card-body card-block" style="padding-top: 0px;">
                        <div class="table-responsive" style="font-size: 14px; background: white; padding: 10px;  overflow: auto;">
                            <table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
                                <tr style="display: none">
                                    <td  style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
                                        <br> <strong> Accounts Department</strong>
                                    </td>
                                </tr>


                            </table>


                            <div > <strong> For the period of {{ $date }} </strong> </div>
                            <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tr style="background: #000;color:#fff;">
                                    <td style="width:20px !important;"><strong>S.L.</strong></td>
                                    <td style=""><strong>Ledger Name</strong></td>
                                    <td style="width:90px !important;"><strong>Type</strong></td>
                                    <td style="width:100px !important;"><strong>Debit Amount(Tk.)</strong></td>
                                    <td style="width:100px !important;"><strong>Credit Amount(Tk.)</strong></td>
                                </tr>

                                @php
                                    $total=0;
                                    $debit=0;
                                    $credit=0;
                                    $i=1;
                                @endphp
                                @foreach($trialBalance as $row)
                                    @php
                                        $total =0;
                                        $debit +=round($row['debit']);
                                        $credit +=round($row['credit']);

                                    @endphp
                                    <tr>
                                        <td style="width:20px !important;"> {{ $i++ }}</td>
                                        <td style="">{{$row['ledger_head']}}</td>
                                        <td style="">{{ $row['ledger_type'] }}</td>
                                        <td style="text-align: right">{{ number_format(round($row['debit'],2),2) }}</td>
                                        <td style="text-align: right">{{ number_format(round($row['credit'],2),2) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="3" style="text-align: right;"><strong> Total </strong> </td>

                                    <td style="text-align: right"><strong>{{number_format($debit,2)}}</strong></td>
                                    <td style="text-align: right"><strong>{{number_format($credit,2)}}</strong></td>
                                </tr>



                            </table>
{{--                            <div>  {{ curInWord($g_total,'Amount in Taka: ') }} Only </div>--}}
                            <p></p>


                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <table style="width: 100%">
                                <tr>
                                    <td  style=" width:15%;text-align: left;">Authorized Signatory  </td>
                                    <td style="width:10px">:</td>
                                    <td style=" text-align: left;"></td>
                                </tr>
                                <tr>
                                    <td colspan="3"> &nbsp;</td>
                                </tr>
                                <tr>
                                    <td  style=" width:10%;text-align: left;"> Printed by </td>
                                    <td style="width:10px">:</td>
                                    <td style=" text-align: left;">{{\Illuminate\Support\Facades\Auth::user()->name ?? ''}}</td>
                                </tr>
                                <tr>
                                    <td  style=" width:10%;text-align: left;"> Printed Date  </td>
                                    <td style="width:10px">:</td>
                                    <td style=" text-align: left;">{{date('Y-m-d h:i A')}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

    <!-- .content -->

