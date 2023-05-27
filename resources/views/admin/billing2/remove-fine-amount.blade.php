@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Remove Fine Amount From Billing</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addIncome" action="{{route('billing.storeM')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}

                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Billing Id </label>
                                    <input  id="billing_id"  name="billing_id"
                                           class="form-control" placeholder="Enter Billing Id" >
                                </div>
                                <div style="margin-top: 22px;" class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-sm btn-success float-right" onclick="removeFintAmount();">Submit</button>



                                </div>


                            </div>


                        </div>
                    </form>
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
        let customerInfo=[];
        let cdAre = [];
        function removeFintAmount()  {
            id = $("#billing_id").val();
            if(id==''){
                alert('Please enter Billing id');
                return;
            }
            $.ajax({url: "remove-fine-amount-bill/"+id, success: function(result){
                alert(result);
                console.log(result);
                if(result=='Remove Fine Amount Success'){
                    $("#billing_id").val('');
                }

                }});
        }

    </script>
@endsection
