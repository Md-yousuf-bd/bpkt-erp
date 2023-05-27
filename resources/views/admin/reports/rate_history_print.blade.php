



<div class="card-body card-block" style="padding-top: 0px;">
    <div class="table-responsive" style="font-size: 14px; background: white; padding: 10px;  overflow: auto;">

        <table class="table table-bordered" style="font-size: 14px; width:50% !important;">
            <tr style="background: #ddd;color:#000;">
                <td  style="width:20px !important;"><strong>S.L.</strong></td>
                <td  style=""><strong>Shop No</strong></td>
                <td  style=""><strong>Customer Name</strong></td>
                <td  style=""><strong>Owner Type</strong></td>
                <td  style=""><strong>Shop Status</strong></td>
                <td  style=""><strong>Tenant Contact</strong></td>
                <td  style=""><strong>Owner Contact</strong></td>
                <td  style=""><strong>Area</strong></td>
                <td  style=""><strong>Rate/sft</strong></td>
                <td  style=""><strong>Allotted From</strong></td>
                <td  style=""><strong>Allotted End</strong></td>
                <td  style=""><strong>Allotment Status</strong></td>

            </tr>


            <?php
            $toalAdvance=0;
            $securityDeposit=0;

            $i=1;
            ?>
            @foreach($result as $row)

                <tr>
                    <td style="width:20px !important;"> {{ $i++ }}</td>
                    <td style="">{{$row['asset_no']??""}}</td>
                    <td style="">{{$row['shop_name']??""}}</td>
                    <td style="">{{$row['owner_type']??""}}</td>
                    <td style="">{{$row['cus_status']==1?"Active":"In Active"}}</td>
                    <td style="">{{$row['contact_person_phone']??""}}</td>
                    <td style="">{{$row['owner_contact']??""}}</td>
                    <td style="">{{$row['area_sft']??""}}</td>
                    <td style="">{{$row['area_sft']??""}}</td>
                    <td style="">{{$row['contact_s_date']??""}}</td>
                    <td style="">{{$row['date_e']=='0000-00-00'?"":$row['date_e']}}</td>
                    <td style="">{{$row['status']??""}}</td>
{{--                    <td style="">{{ date( 'd-m-Y h:i A',strtotime($row['updated_at'])) }}</td>--}}

                </tr>
            @endforeach




        </table>
        <p></p>


        <div style="height: 3%">&nbsp;</div>
        <div style="height: 3%">&nbsp;</div>
        <div style="height: 3%">&nbsp;</div>
        <table style="width: 100% ; display: none;">
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


<?php
function calculateDate($sdate, $edate) {
    $earlier = new DateTime("2010-07-06");
    $later = new DateTime("2010-07-09");

    $abs_diff = $later->diff($earlier)->format("%a"); //3
}
function daysBetween($dt1, $dt2) {


    $dt1 = $dt2;
    $dt2 = date('Y-m-d');
    $day =  date_diff(
        date_create($dt1),
        date_create($dt2)
    )->format('%R%a');
    if($day> 0){
        return $day;
    }else{
        return 0;
    }
}

?>
