<?php  $i=1 ; $bill_amount = 0; $due_amount=0;$moth=0; ?>
<table style="width: 100%;border: none;"  class="table  table-borderless" valign="top">
    <img style=" float: left; width: 100px; height: 100px;margin-bottom: -93px;margin-top: 8px;margin-left: 10px;" src="{{ URL::asset('images/logos/logo-in.png') }}">
    <tr>
        <td colspan="2" style="text-align: center;font-size:15px;"> <strong> Bangladesh Police Kallyan Trust</strong>
            <p> <span> Police Headquarters, Phoenix Road, Dhaka-1000</span> <br>
                <span> Contact No: 01321142060, 01321142063</span> <br>
                <span> <strong> Dues Statement </strong> </span>


            </p>
        </td>
    </tr>



</table>
<table  class="table table-bordered " style="font-size: 14px; width:100%;">
    <thead>


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
        $spsc=0;
        $ad=0;
        $fine=0;
        $gfine=0;
        $el_fine=0;
        $serviceWithFine=0;
        $totalOtherIncome=0;

echo "sdfsdf";

    @endphp



    @foreach($customer as $k=>$rowData)
    <?php
    
    
    echo "<pre>";
    print_r($rowData['bill2']);
    
    ?>


            <tr>
                <td colspan="6">
                    <table style="width: 100%">
                        <tr>
                            <td style="padding: 0px;"><strong>Shop/Office NO: {{$k}}</strong></td>
                            <td style="padding: 0px;"><strong>Allotted From: {{$rowData['bill2']['contact_s_date']??""}}</strong></td>
                            <td style="padding: 0px;"><strong>Rent: {{$rowData['bill2']['rate']??""}}</strong></td>

                        </tr>
                        <tr>
                            <td style="padding: 0px;"><strong>Service Charge Area: </strong></td>
                            <td style="padding: 0px;"><strong>Rent Start From: {{$rowData['bill2']['date_s']??""}}</strong></td>
                            <td style="padding: 0px;"><strong>Service Charge From: </strong></td>

                        </tr>
                        <tr>
                            <td style="padding: 0px;"><strong>Meter No: </strong></td>
                            <td style="padding: 0px;"><strong>Rent Area: {{$rowData['bill2']['area_sft']??""}}</strong></td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 3%;" >S.L.</td>

                <td >Bill Type</td>
                <td >Month</td>
                <td >Amount</td>
                <td >Fine </td>


                <td  style="text-align: center;"> Total </td>


            </tr>
            @php
            $i=1;
            @endphp
        @foreach($customer1  as $row1)
        @foreach($rowData['bill']  as $key=>$row)
            @if($row1!= $row['bill_type'])
                @continue
                @endif

        @php
            $total=$row['bill_amount']+$row['fine'];
            if($total==0){
                continue;
            }

        @endphp


        <tr>
            <td style="text-align: right"> {{ $i++ }} </td>
            <td style="text-align: right"> {{  $row['bill_type']  }}  </td>
            <td style="text-align: right"> {{  $row['month']  }}  </td>
            <td style="text-align: right"> {{  number_format($row['bill_amount'])  }}  </td>
            <td style="text-align: right"> {{  number_format($row['fine'])  }}  </td>
            <td style="text-align: right"> {{  number_format($total)  }}  </td>

        </tr>

    @endforeach
    @endforeach
    @endforeach
    <tr>
        <td colspan="2" style="text-align: right;">
            <strong> Total </strong>
        </td>


        <td style="text-align: right">
            <strong>   {{ number_format($totalOtherIncome) }}  </strong>
        </td> <td style="text-align: right">
            <strong>   {{ number_format($gtotal) }}  </strong>
        </td>

        <td style="text-align: right">
            <strong>   {{ number_format($gfine) }}  </strong>
        </td>
        <td style="text-align: right">
            <strong>   {{ number_format($gfine+$gtotal) }}  </strong>
        </td>



    </tr>
    </tbody>
</table>

