

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
                                </tr>
                                <tr>
                                    <td><strong>Non-Current Assets                                        </strong></td>
                                    @foreach($dynamicCols as $col)
                                        <td><strong></strong></td>
                                    @endforeach
                                </tr>

                                @php
                                    $income_total=array();
                                    $expense_total=0;
                                    $debit=0;
                                    $credit=0;
                                    $i=1;
                                    $assetBalance['non']=array();
                                    $assetBalance['cur']=array();

                                @endphp
                                <?php
//                                echo "<pre>";
//                                print_r($bsStatement);
                                ?>
                                @if(isset($bsStatement['asset'][22]))
{{-- for non current assets--}}
                                @foreach($bsStatement['asset'][22] as $key=>$row)
                                        @php  $carrayForward = array(); @endphp
                                    <tr>
                                        <td style="">{{$key}}</td>
                                        @foreach($dynamicCols as $keyD=>$col)
                                            @php  array_push($carrayForward,$keyD) ; $total=0; @endphp
                                            <td style="text-align: right">
                                                @foreach($carrayForward as $month)
                                                    @php
                                                        if(isset($bsStatement['asset'][22][$key][$month])){
                                                              $total += $bsStatement['asset'][22][$key][$month];

                                                        }

                                                    @endphp
                                                @endforeach
                                                @php

                                                             if(isset($assetBalance['non'][$keyD])){
                                                                      $assetBalance['non'][$keyD] += $total;
                                                                  }else{
                                                                      $assetBalance['non'][$keyD] = $total;
                                                            }


                                                         @endphp
                                                {{ number_format($total,2) }}</td>

                                        @endforeach
                                    </tr>
                                @endforeach
                                @endif
{{--                                end for non current assets --}}

                                <tr>
                                    <td  style="text-align: right;" ><strong> Sub Total </strong> </td>

                                @foreach($dynamicCols as $key=>$amt)
                                    <td style="text-align: right"><strong>{{isset($assetBalance['non'][$key])?number_format($assetBalance['non'][$key],2):0.00}}</strong></td>

                                @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Current Assets       </strong></td>
                                    @foreach($dynamicCols as $col)
                                        <td><strong></strong></td>
                                    @endforeach
                                </tr>
                                @if(isset($bsStatement['asset'][6]))
                                    {{-- for non current assets--}}



                                    @foreach($bsStatement['asset'][6] as $key=>$row)

                                      @php  $carrayForward = array(); @endphp
                                        <tr>
                                            <td style="">{{$key}}</td>
                                            @foreach($dynamicCols as $keyD=>$col)
                                                @php  array_push($carrayForward,$keyD) ; $total=0; @endphp
                                                <td style="text-align: right">
                                                    @foreach($carrayForward as $month)
                                                        @php
                                                        if(isset($bsStatement['asset'][6][$key][$month])){
                                                              $total += $bsStatement['asset'][6][$key][$month];
                                                        }

                                                        @endphp
                                                    @endforeach
                                                        @php

                                                            if(isset($assetBalance['cur'][$keyD])){
                                                                     $assetBalance['cur'][$keyD] += $total;
                                                                 }else{
                                                                     $assetBalance['cur'][$keyD] = $total;
                                                           }


                                                        @endphp
                                                    {{ number_format($total,2) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                                {{--                                end for non current assets --}}
                                <tr>
                                    <td  style="text-align: right;" ><strong> Sub Total </strong> </td>

                                    @foreach($dynamicCols as $key=>$amt)
                                        <td style="text-align: right"><strong>{{isset($assetBalance['cur'][$key])?number_format($assetBalance['cur'][$key],2):0.00}}</strong></td>

                                    @endforeach
                                </tr>
{{-- start grand total asset --}}

                                <tr>
                                    <td  style="text-align: right;" ><strong>Total Assets</strong> </td>

                                    @foreach($dynamicCols as $key=>$amt)

                                            @php
                                                $total=0;
                                                    if(isset($assetBalance['cur'][$key]) && isset($assetBalance['non'][$key])){
                                                          $total += $assetBalance['cur'][$key] + $assetBalance['non'][$key];
                                                    }else if(isset($assetBalance['cur'][$key]) && !isset($assetBalance['non'][$key])){
                                                        $total += $assetBalance['cur'][$key] ;
                                                    }else if(isset($assetBalance['non'][$key])) {
                                                         $total += $assetBalance['non'][$key] ;
                                                    }

                                            @endphp
                                        <td style="text-align: right"><strong>
                                                {{  number_format($total,2) }}
                                            </strong></td>

                                    @endforeach
                                </tr>
{{--                                end asset total   --}}
                                <tr>
                                    <td><strong>Current Liability</strong></td>
                                    @foreach($dynamicCols as $col)
                                        <td><strong></strong></td>
                                    @endforeach
                                </tr>
                                @php
                                    $balance['curl'] = array();
                                    $balance['equity'] = array();
                                @endphp
                                @if(isset($bsStatement['liability'][47]))

                                @foreach($bsStatement['liability'][47] as $key=>$row)
                                        @php  $carrayForward = array() @endphp
                                    <tr>
                                        <td style="">{{$key}} </td>
                                        @foreach($dynamicCols as $keyD=>$col)
                                            @php  array_push($carrayForward,$keyD) ; $total=0; @endphp

                                            @foreach($carrayForward as $month)

                                                @php
                                                    if(isset($bsStatement['liability'][47][$key][$month])){
                                                          $total += $bsStatement['liability'][47][$key][$month];


      }
                                                @endphp
                                            @endforeach
                                            @php
                                                if(isset($balance['curl'][$keyD])){
                                                                                   $balance['curl'][$keyD] += $bsStatement['liability'][47][$key][$keyD]??0;
                                                                                       } else{
                                                                                                $balance['curl'][$keyD] =  $bsStatement['liability'][47][$key][$keyD]??0 ;

                                                                                                    }
                                            @endphp
                                            <td style="text-align: right;">  {{number_format($total,2)}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                @endif

                                <tr>
                                    <td  style="text-align: right;" ><strong> Sub Total </strong> </td>
                                    @php  $carrayForward = array(); @endphp
                                    @foreach($dynamicCols as $key=>$amt)
                                        @php
                                            array_push($carrayForward,$key) ;
                                            $total=0;
                                        @endphp
                                        @foreach($carrayForward as $month)
                                            @php
                                                if(isset($bsStatement['total_l'][47][$month])){
                                                      $total += $bsStatement['total_l'][47][$month] ;

                                                   /*   if(isset($bsStatement['total_l'][$month])){
                                                          $bsStatement['total_l'][$month] +=$bsStatement['total_l'][47][$month];
                                                      }else{
                                                          $bsStatement['total_l'][$month] = $bsStatement['total_l'][47][$month];
                                                      }
                                                      */
                                                }

                                            @endphp
                                        @endforeach

                                        <td style="text-align: right"><strong>
                                                {{  number_format($total,2) }}
                                            </strong></td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td><strong>Shareholders Equity</strong></td>
                                    @foreach($dynamicCols as $col)
                                        <td><strong></strong></td>
                                    @endforeach
                                </tr>

                                <?php
//                                echo '<pre>';
//                                print_r($bsStatement);
                                ?>
                                @if(isset($bsStatement['liability'][42]))
                                    {{-- for non current assets--}}
                                    @foreach($bsStatement['liability'][42] as $key=>$row)
                                        @php  $carrayForward = array(); @endphp
                                        <tr>
                                            <td style="">{{$key}}</td>
                                            @foreach($dynamicCols as $keyD=>$col)
                                                @php  array_push($carrayForward,$keyD) ; $total=0; @endphp
                                                <td style="text-align: right">
                                                    @foreach($carrayForward as $month)

                                                        @php
                                                            if(isset($bsStatement['liability'][42][$key][$month])){
                                                                  $total += $bsStatement['liability'][42][$key][$month];


                                                            }

                                                        @endphp
                                                    @endforeach
                                                    @php
                                                        if(isset($bsStatement['liability'][42][$key][$month])){
                                                                     if(isset($balance['equity'][$keyD])){
                                                                          $balance['equity'][$keyD] += $bsStatement['liability'][42][$key][$keyD];
                                                                    } else{
                                                                          $balance['equity'][$keyD] = $bsStatement['liability'][42][$key][$keyD];
                                                                    }
                                                               }

                                                        @endphp
                                                    {{ number_format($total,2) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach

                                @else

                                @endif
                                <tr>
                                    <td> Retained Earnings </td>

                                    @php   $total = 0; @endphp
                                    @foreach($dynamicCols as $keyD=>$col)
                                        <td style="text-align: right;">
                                            @php

                                                    if(isset($isStatement['total_i'][$keyD]) && isset($isStatement['total_e'][$keyD])){
                                                                    if(isset($balance['equity'][$keyD])){
                                                                            $balance['equity'][$keyD] += $isStatement['total_i'][$keyD]-$isStatement['total_e'][$keyD];
                                                                    } else{
                                                                            $balance['equity'][$keyD] = $isStatement['total_i'][$keyD]-$isStatement['total_e'][$keyD];
                                                                    }

                                                              $total += $isStatement['total_i'][$keyD]-$isStatement['total_e'][$keyD];
                                                    }
                                                       elseif(isset($isStatement['total_i'][$keyD])) {
                                                        if(isset($balance['equity'][$keyD])){
                                                                            $balance['equity'][$keyD] += $isStatement['total_i'][$keyD];
                                                                    } else{
                                                                            $balance['equity'][$keyD] = $isStatement['total_i'][$keyD];
                                                                    }
                                                          $total += $isStatement['total_i'][$keyD];
                                                       } elseif(isset($isStatement['total_e'][$keyD])) {
                                                         if(isset($balance['equity'][$keyD])){
                                                                            $balance['equity'][$keyD] += -$isStatement['total_e'][$keyD];
                                                                    } else{
                                                                            $balance['equity'][$keyD] = -$isStatement['total_e'][$keyD];
                                                                    }
                                                          $total += -$isStatement['total_e'][$keyD];
                                                       }


                                            @endphp
                                        {{ number_format($total,2) }}
                                        </td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td  style="text-align: right;" ><strong> Sub Total </strong> </td>


                                    @php  $carrayForward = array(); @endphp
                                    @foreach($dynamicCols as $key=>$amt)

                                        @php  array_push($carrayForward,$key) ; $total=0; @endphp
                                        @foreach($carrayForward as $month)
                                            @php
                                                if(isset($balance['equity'][$month]) ){
                                                      $total +=  $balance['equity'][$month];
                                                }
                                            @endphp
                                        @endforeach
                                        <td style="text-align: right;"><strong>
                                                {{  number_format($total,2) }}
                                            </strong></td>
                                    @endforeach

                                </tr>


                                {{-- start grand total Total Liability --}}
                                <?php
//                                echo '<pre>';
//                                print_r($balance);

                                ?>
                                <tr>
                                    <td  style="text-align: right;" ><strong>Total Liability</strong> </td>
                                    @php  $carrayForward = array(); @endphp
                                    @foreach($dynamicCols as $key=>$amt)
                                        @php  array_push($carrayForward,$key) ;  $total=0;@endphp
                                        @foreach($carrayForward as $month)
                                            @php
                                                $total +=($balance['equity'][$month]??0)+($balance['curl'][$month]??0);


                                            @endphp
                                        @endforeach
                                        <td style="text-align: right"><strong>
                                                {{  number_format($total,2) }}
                                            </strong></td>
                                    @endforeach
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

