        <table style="width: 100%;border: none;" class="table  table-borderless" valign="top">
            <tr style="">
                <td style="text-align: center;font-size:15px;"><strong> Bangladesh Police Kallyan Trust</strong>
                    <br> <strong> Accounts Department</strong>
                </td>
            </tr>

        </table>


        <table class="table table-bordered" style="font-size: 14px; width:100% !important;">


            <tr style="background: #BFBFBF;color:#000;">
                <td  style="width:200px !important;"><strong>Receipts</strong></td>
                <td style="width:200px !important;"><strong>Amount (Tk.)</strong></td>
                <td  style="width:200px !important;"><strong>Payments</strong></td>
                <td style="width:100px !important;"><strong>Amount (Tk.)</strong></td>
            </tr>
            <tr>
                <td  style=""> Cash in hand (Opening Balance)</td>
                <td style="text-align: right"> {{ number_format($cashInHand) }} </td>
                <td  style=""></td>
                <td style="text-align: right"></td>

            </tr>
            <tr>
                <td  style=""> Cash at bank (Opening Balance)</td>
                <td style="text-align: right"> {{ number_format($cashAtBank) }} </td>
                <td  style=""></td>
                <td style="text-align: right"></td>

            </tr>
            <tr>
                <td ><strong> Sub Total</strong></td>
                <td style="text-align: right;"><strong> {{ number_format($cashInHand+$cashAtBank) }}</strong></td>
                <td ><strong> </strong></td>
                <td style="text-align: right;"><strong> </strong></td>


            </tr>

            <tr>
                <td colspan="2" valign="top" style="vertical-align:top;">

                    <table valign="top" class="table table-bordered"
                           style="vertical-align:top; font-size: 14px; width:100% !important;">
                        <tr style="background: #BFBFBF;color:#000;">
                            <td  style="width:200px !important;"><strong>Revenue Receipts</strong></td>
                            <td style="width:200px !important;"><strong>Amount (Tk.)</strong></td>

                        </tr>
                        @php
                            $serviceChargeFixedFine = 0;
                            $serviceChargeFine = 0;
                            $serviceChargeFine = 0;
                            $elctricityFine = 0;
                            $operatingIncomeToTal = 0;
                            $amount = 0 ;
                            $ledger_name = '';
                        @endphp
                        @foreach($operatingIncome as $data)
                            <?php
                                $amount=0;
                                $ledger_head='';
                            ?>
                            @foreach($data as $row)
                               <?php
                                    $ledger_head = $row['ledger_head'];
                                    $amount += $row['credit'];
                                        if($row['paid_fixed_fine'] > 0 && $row['bill_type']=='Service Charge'){
                                        $serviceChargeFixedFine += $row['paid_fixed_fine'];
                                        $operatingIncomeToTal += $row['paid_fixed_fine'];
                                            }
                                         if($row['paid_fine_amount'] > 0 && $row['bill_type']=='Service Charge'){
                                             $serviceChargeFine += $row['paid_fine_amount'];
                                             $operatingIncomeToTal += $row['paid_fine_amount'];
                                         }
                                         if($row['paid_fine_amount'] > 0 && $row['bill_type']=='Electricity'){
                                             $elctricityFine += $row['paid_fine_amount'];
                                             $operatingIncomeToTal += $row['paid_fine_amount'];
                                         }
                                         $operatingIncomeToTal +=$row['credit'];
                               ?>
                            @endforeach
                            <tr>
                                <td  style="text-align: left">{{$ledger_head}}</td>
                                <td style="text-align: right">{{number_format($amount)}}</td>

                            </tr>
                        @endforeach
                        @if($serviceChargeFixedFine>0)
                            <tr>
                                <td  style="text-align: left">Service Charge Fixed Fine</td>
                                <td style="text-align: right">{{number_format($serviceChargeFixedFine)}}</td>

                            </tr>
                        @endif
                        @if($serviceChargeFine>0)
                            <tr>
                                <td style="text-align: left">Service Charge Interest</td>
                                <td style="text-align: right">{{number_format($serviceChargeFine)}}</td>

                            </tr>
                        @endif
                        @if($elctricityFine>0)
                            <tr>
                                <td  style="text-align: left">Electricity Fine</td>
                                <td style="text-align: right">{{number_format($elctricityFine)}}</td>

                            </tr>
                        @endif
                        <tr>
                            <td ><strong> Sub Total</strong></td>
                            <td style="text-align: right;"><strong> {{ number_format($operatingIncomeToTal) }}</strong>
                            </td>


                        </tr>
                    </table>

                </td>
                <td colspan="2" valign="top" style="vertical-align:top;">
                    @php
                        $revenuePaymentTotal=0;
                    @endphp

                    <table valign="top" class="table table-bordered" style="font-size: 14px; width:100% !important;">
                        <tr style="background: #BFBFBF;color:#000;">
                            <td  style="width:200px !important;"><strong>Revenue Payments</strong></td>
                            <td style="width:200px !important;"><strong>Amount (Tk.)</strong></td>

                        </tr>

                        @foreach($revenuePayment as $data)
                            @php
                                $amount=0;
                                $ledger_head='';
                            @endphp
                            @foreach($data as $row)
                                @php
                                    $amount +=$row['debit'];
                                    $ledger_head=$row['ledger_head'];
                                    $revenuePaymentTotal +=$row['debit'];
                                @endphp
                            @endforeach
                            <tr>
                                <td  style="text-align: left">{{$ledger_head}}</td>
                                <td style="text-align: right">{{ number_format($amount)}}</td>

                            </tr>
                        @endforeach
                        <tr>
                            <td ><strong> Sub Total</strong></td>
                            <td style="text-align: right;"><strong> {{ number_format($revenuePaymentTotal) }}</strong>
                            </td>


                        </tr>
                    </table>

                </td>
            </tr>


            <tr>
                <td colspan="2" valign="top" style="vertical-align:top;">
                    @php
                        $capitalReceiptTotal=0;

                        $amount=0;
                        $ledger_head='';
                    @endphp

                    <table valign="top" class="table table-bordered"
                           style="vertical-align:top; font-size: 14px; width:100% !important;">
                        <tr style="background: #BFBFBF;color:#000;">
                            <td style="width:200px !important;" ><strong>Capital Receipts</strong></td>
                            <td style="width:200px !important;"><strong>Amount (Tk.)</strong></td>

                        </tr>
                        @foreach($capitalReceipt as $data)
                            @php
                                $amount=0;
                                $ledger_head='';
                            @endphp
                            @foreach($data as $row)
                                @php
                                    $amount +=$row['credit'];
                                    $ledger_head=$row['ledger_head'];
                                    $capitalReceiptTotal +=$row->credit;
                                @endphp
                            @endforeach
                            <tr>
                                <td  style="text-align: left">{{$ledger_head}}</td>
                                <td style="text-align: right">{{number_format($amount)}}</td>

                            </tr>
                        @endforeach
                        <tr>
                            <td ><strong> Sub Total</strong></td>
                            <td style="text-align: right;"><strong> {{ number_format($capitalReceiptTotal) }}</strong>
                            </td>


                        </tr>
                    </table>

                </td>
                <td colspan="2" valign="top" style="vertical-align:top;">
                    @php
                        $capitalPaymentTotal=0;
                        $amount=0;
                        $ledger_head='';
                    @endphp

                    <table valign="top" class="table table-bordered"
                           style="vertical-align:top;font-size: 14px; width:100% !important;">
                        <tr style="background: #BFBFBF;color:#000;">
                            <td style="width:200px !important;"><strong>Capital Payments</strong></td>
                            <td style="width:200px !important;"><strong>Amount (Tk.)</strong></td>

                        </tr>

                        @foreach($capitalPayment as $data)
                            @php
                                $amount=0;
                                $ledger_head='';
                            @endphp

                            @foreach($data as $row)
                                @php
                                    $amount +=$row['debit'];
                                    $ledger_head=$row['ledger_head'];
                                    $capitalPaymentTotal +=$row->debit;
                                @endphp
                            @endforeach
                            <tr>
                                <td  style="text-align: left">{{$ledger_head}}</td>
                                <td style="text-align: right">{{ number_format($amount) }}</td>

                            </tr>
                        @endforeach
                        <tr>
                            <td ><strong> Sub Total</strong></td>
                            <td style="text-align: right;"><strong> {{ number_format($capitalPaymentTotal) }}</strong>
                            </td>


                        </tr>
                    </table>

                </td>
            </tr>

            <tr style="background: #BFBFBF;color:#000;">
                <td style="width:200px !important;"><strong>Receipts</strong></td>
                <td style="width:200px !important;"><strong>Amount (Tk.)</strong></td>
                <td style="width:200px !important;" ><strong>Payments</strong></td>
                <td style="width:200px !important;"><strong>Amount (Tk.)</strong></td>

            </tr>
            <tr>
                <td style="" ></td>
                <td style="text-align: right;"></td>
                <td style=""> Cash in hand (Closing Balance)</td>
                <td style="text-align: right;"> {{ number_format($cashInHand+$cashDebit-$cashCredit) }} </td>

            </tr>
            <tr>
                <td style="" ></td>
                <td style="text-align: right;"></td>
                <td style="" > Cash at bank (Closing Balance)</td>
                <td style="text-align: right;"> {{ number_format($cashAtBank+$bankDebit-$bankCredit)  }}  </td>

            </tr>
            <tr>
                <td ><strong> </strong></td>
                <td style="text-align: right;"><strong> </strong></td>
                <td ><strong>Sub Total</strong></td>
                <td style="text-align: right;">
                    <strong> {{ number_format(($cashAtBank+$bankDebit-$bankCredit)+($cashInHand+$cashDebit-$cashCredit)) }}</strong>
                </td>


            </tr>
            <tr>
                <td ><strong> Total</strong></td>
                <td style="text-align: right;">
                    <strong> {{ number_format($cashAtBank+$cashInHand+$operatingIncomeToTal+$capitalReceiptTotal) }}</strong>
                </td>
                <td ><strong> Total</strong></td>
                <td style="text-align: right;">
                    <strong> {{ number_format($revenuePaymentTotal+$capitalPaymentTotal+($cashAtBank+$bankDebit-$bankCredit)+($cashInHand+$cashDebit-$cashCredit)) }}</strong>
                </td>


            </tr>


        </table>

        <p></p>


        <div style="height: 3%">&nbsp;</div>
        <div style="height: 3%">&nbsp;</div>
        <div style="height: 3%">&nbsp;</div>
        <table style="width: 100%">
            <tr>
                <td style=" width:15%;text-align: left;">Authorized Signatory</td>
                <td style="width:10px">:</td>
                <td style=" text-align: left;"></td>
            </tr>
            <tr>
                <td colspan="3"> &nbsp;</td>
            </tr>
            <tr>
                <td style=" width:10%;text-align: left;"> Printed by</td>
                <td style="width:10px">:</td>
                <td style=" text-align: left;">{{\Illuminate\Support\Facades\Auth::user()->name ?? ''}}</td>
            </tr>
            <tr>
                <td style=" width:10%;text-align: left;"> Printed Date</td>
                <td style="width:10px">:</td>
                <td style=" text-align: left;">{{date('Y-m-d h:i A')}}</td>
            </tr>
        </table>


<!-- .content -->

