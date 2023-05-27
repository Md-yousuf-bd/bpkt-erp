@extends('admin.layouts.app')



@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Show Wrong Data</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                        <div class="card-body card-block">

                                <table  class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr>
                                        <td colspan="7">In Complete Bill</td>
                                    </tr>
                                    <tr>

                                        {{--                                        <input type="hidden" value="" name="total" id="total">--}}
                                        <td style="width:5%">#</td>
                                        <td style="width: 16% !important;">Billing Id</td>
                                        <td style="width: 12% !important;">Shop No</td>
                                        <td style="width: 12% !important;">Customer Name</td>
                                        <td style="width: 8% !important;">Invoice</td>
                                        <td style="width: 8% !important;">Amount</td>

                                        <td style="width: 9% !important;"></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($wrongData as $key=>$row)
                                    <tr>
                                        <td> {{ $key+1 }} </td>
                                        <td> {{ $row['id'] }}         </td>
                                        <td> {{ $row['shop_no'] }}         </td>
                                        <td> {{ $row['shop_name'] }}         </td>
                                        <td> {{ $row['invoice_no'] }}         </td>
                                        <td> {{ $row['grand_total'] }}         </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            <table  class="table table-bordered">
                                <thead class="table-dark">
                                <tr>
                                    <td colspan="7">Journal Not created</td>
                                </tr>
                                <tr>

                                    {{--                                        <input type="hidden" value="" name="total" id="total">--}}
                                    <td style="width:5%">#</td>
                                    <td style="width: 16% !important;">Billing Id</td>
                                    <td style="width: 12% !important;">Shop No</td>
                                    <td style="width: 12% !important;">Customer Name </td>
                                    <td style="width: 8% !important;">Invoice</td>
                                    <td style="width: 9% !important;"></td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($journal_wrong2 as $key=>$row)
                                    <tr>
                                        <td> {{ $key+1 }} </td>
                                        <td> {{ $row['ref_id'] }}         </td>
                                        <td>   {{ $row['shop_no'] }}       </td>
                                        <td>   {{ $row['customer_name'] }}       </td>
                                        <td> {{ $row['invoice_no'] }}         </td>
                                        <td>
                                            <span class="{{$row['ref_id'] }}">
 <button  class="btn btn-sm btn-outline-primary
                                             pull-right" type="button" onclick="journalCreate2({{ $row['ref_id'] }})">Create Journal</button>

                                            </span>


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>





                        </div>
            </div>
        </div>
    </div>



@endsection

@section('uncommonExJs')
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
@endsection
@section('uncommonInJs')
    <script>

         function   journalCreate2(id){

             $.ajax({url: "bill-wrong-data-fill/"+id, success: function(result){
                     // let ar = JSON.parse(result);
                     $('.'+id).html(result);
                     // alert(result);
                 }});
          }

    </script>

@endsection


