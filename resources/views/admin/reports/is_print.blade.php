

                    <div class="card-header" style="padding-bottom: 0px;padding-top: 0px !important;">
                        <h5 class="card-title"> <div > <strong> For the period of {{ $date }} </strong> </div></h5>

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



                            <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
                                <tr style="background: #BFBFBF;color:#000;">
                                    <td style=""><strong>Particulars</strong></td>
                                    @foreach($dynamicCols as $col)
                                        <td><strong>{{ $col }}</strong></td>
                                    @endforeach
                                    <td><strong>Total</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Income</strong></td>
                                    @foreach($dynamicCols as $col)
                                        <td><strong></strong></td>
                                    @endforeach
                                    <td><strong></strong></td>
                                </tr>

                                @php
                                    $income_total=array();
                                    $expense_total=0;
                                    $debit=0;
                                    $credit=0;
                                    $i=1;
                                    $rowTotal = 0;
                                    krsort($incomeStatement['income']);
                                @endphp


                                @foreach($incomeStatement['income'] as $key=>$row)
                                    @php    $rowTotal = 0; @endphp
                                    <tr>
                                        <td style="">{{$incomeStatement['ledger'][$key]}}</td>
                                        @foreach($dynamicCols as $keyD=>$col)

                                            @php
                                            if(isset($incomeStatement['income'][$key][$keyD])){
                                                 $rowTotal += $incomeStatement['income'][$key][$keyD];
                                            }

                                             @endphp
                                            <td style="text-align: right">{{ isset($incomeStatement['income'][$key][$keyD])?
                                        number_format($incomeStatement['income'][$key][$keyD]):0.00 }}</td>
                                        @endforeach
                                        <td style="text-align: right"> {{ number_format($rowTotal,2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td  style="text-align: right;" ><strong> Total </strong> </td>
                                    @php    $rowTotal = 0; @endphp
                                @foreach($dynamicCols as $key=>$amt)
                                        @php
                                            if(isset($incomeStatement['total_i'][$key])){
                                                 $rowTotal += $incomeStatement['total_i'][$key];
                                            }

                                        @endphp
                                    <td style="text-align: right"><strong>{{isset($incomeStatement['total_i'][$key])?number_format($incomeStatement['total_i'][$key],2):0.00}}</strong></td>

                                @endforeach
                                    <td style="text-align: right"><strong> {{ number_format($rowTotal,2) }} </strong> </td>
                                </tr>
                                <tr>
                                    <td><strong>Expense</strong></td>
                                    @foreach($dynamicCols as $col)
                                        <td><strong></strong></td>
                                    @endforeach
                                </tr>
                                @foreach($incomeStatement['expense'] as $key=>$row)
                                    @php

                                        $rowTotal = 0;


                                        @endphp
                                <?php
                                    if(trim($key)==45){
                                        continue;
                                    }
                                    ?>
                                    <tr>
                                        <td style="">{{$incomeStatement['ledger'][$key]}}</td>
                                        @foreach($dynamicCols as $keyD=>$col)
                                            @php
                                                if(isset($incomeStatement['expense'][$key][$keyD])){
                                                     $rowTotal += $incomeStatement['expense'][$key][$keyD];
                                                }

                                            @endphp
                                            <td style="text-align: right;">{{ isset($incomeStatement['expense'][$key][$keyD])?
                                        number_format($incomeStatement['expense'][$key][$keyD]):0.00 }}</td>
                                        @endforeach
                                        <td style="text-align: right;"> {{ number_format($rowTotal,2) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td  style="text-align: right;" ><strong> Total </strong> </td>

                                          @php    $rowTotal = 0; @endphp
                                @foreach($dynamicCols as $key=>$amt)
                                        @php
                                            if(isset($incomeStatement['total_e'][$key])){
                                                 $rowTotal += $incomeStatement['total_e'][$key];
                                            }
                                            @endphp
                                        <td style="text-align: right"><strong>{{isset($incomeStatement['total_e'][$key])?number_format($incomeStatement['total_e'][$key],2):0.00}}</strong></td>

                                    @endforeach
                                    <td style="text-align: right"><strong> {{ number_format($rowTotal,2) }} </strong> </td>
                                </tr>

                                <tr>
                                    <td>
                                      <strong> Net Profit before income tax (NPBT) </strong>
                                    </td>
                                    @php $rowTotal=0; @endphp
                                    @foreach($dynamicCols as $keyD=>$col)
                                        @php
                                            if(isset($incomeStatement['total_e'][$keyD]) && isset($incomeStatement['total_i'][$keyD])){
                                                 $rowTotal += ($incomeStatement['total_i'][$keyD] - $incomeStatement['total_e'][$keyD]);
                                            }elseif(isset($incomeStatement['total_e'][$keyD])){
                                                     $rowTotal +=- $incomeStatement['total_e'][$keyD];
                                            }elseif(isset($incomeStatement['total_i'][$keyD])){
                                            $rowTotal +=  $incomeStatement['total_i'][$keyD];
                                            }
                                        @endphp
                                        <td style="text-align: right;"> <strong>
                                        @if(isset($incomeStatement['total_i'][$keyD]) && isset($incomeStatement['total_e'][$keyD]))
                                                {{  number_format($incomeStatement['total_i'][$keyD]-$incomeStatement['total_e'][$keyD] ,2) }}
                                              @elseif(isset($incomeStatement['total_i'][$keyD]))
                                                    {{  number_format($incomeStatement['total_i'][$keyD],2)}}
                                                   @elseif(isset($incomeStatement['total_e'][$keyD]))
                                                    -{{ number_format($incomeStatement['total_e'][$keyD],2)}}
                                                    @else
                                                   {{0.00}}
                                                  @endif
                                            </strong>
                                                    </td>
                                    @endforeach
                                    <td style="text-align: right;">  <strong> {{ number_format($rowTotal,2) }} </strong> </td>
                                </tr>
                                <tr>
                                    <td>
                                       Provision for Income Tax
                                    </td>
                                    @php $provisionArr = array();$rowTotal=0; @endphp;
                                    @foreach($dynamicCols as $keyD=>$col)
                                        @php
                                            if(isset($incomeStatement['expense'][45][$keyD])){
                                                 $rowTotal += $incomeStatement['expense'][45][$keyD];

                                            }
                                        @endphp
                                        <td style="text-align: right;">{{ isset($incomeStatement['expense'][45][$keyD])?
                                        number_format($incomeStatement['expense'][45][$keyD]):0.00 }}</td>
                                    @endforeach
                                    <td style="text-align: right;"> {{ number_format($rowTotal,2) }}  </td>

                                </tr>
                                <tr>
                                    <td>
                                        <strong> Net Profit after tax (NPAT) </strong>
                                    </td>
                                    @php $rowTotal=0; @endphp
                                    @foreach($dynamicCols as $keyD=>$col)

                                        @php
                                            if(isset($incomeStatement['net'][$keyD])){
                                                 $rowTotal += $incomeStatement['net'][$keyD];
                                            }
                                        @endphp

                                    <td style="text-align: right;">
                                        <strong>
                                            @if(isset($incomeStatement['net'][$keyD] ))
                                                {{  number_format($incomeStatement['net'][$keyD],2) }}

                                            @else
                                                {{0.00}}

                                            @endif
                                        </strong>
                                    </td>
                                    @endforeach
                                    <td style="text-align: right;">  <strong>{{ number_format($rowTotal,2) }} </strong> </td>

                                </tr>
                            </table>
                            <p></p>


                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <div style="height: 3%">&nbsp;</div>
                            <table style="width: 100%;display: none;">
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

