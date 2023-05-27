@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Add Advertisement Space</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addGroupAccount" action="{{route('advertisement.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Space Name</label>
                                    <input type="text" value="{{ $editData->space_name }}" id="space_name" name="space_name"  class="form-control" required="required">
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Code</label>
                                    <input class="form-control" value="{{ $editData->code }}" name="code" id="code">

                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Area </label>
                                    <input type="text" id="area" name="area" value="{{ $editData->area }}"  class="form-control" onchange="Advertisement.showTotal()">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rate (Sft) </label>
                                    <input type="text" id="rate" name="rate" value="{{ $editData->rate }}"  class="form-control" onchange="Advertisement.showTotal()">
                                </div>

                            </div>

                            <div class="row" >
                                <div style="display: none;" class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Customer Name</label>
                                    <select class="form-control select2" name="customer_id" id="customer_id">
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}" @if($row->id==$editData->customer_id) selected @endif>{{$row->shop_name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Shop No</label>
                                    <select class="form-control select2" name="asset_no" id="asset_no">
                                        <option value="">None</option>
                                        @foreach($shop_no as $row)
                                            <option value="{{$row->asset_no}}" @if($row->asset_no==$editData->asset_no) selected @endif>{{$row->asset_no}} - {{$row->customer->shop_name??"None"}} </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label"> Total Amount </label>
                                    <input class="form-control" autocomplete="off" name="total_amount" id="total_amount" required>
                                </div>


                            </div>



                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Contract/Deed Start Date </label>
                                    <input class="form-control" autocomplete="off" value="{{ $editData->date_s }}" name="date_s" id="date_s" >

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Contract/Deed End Date </label>
                                    <input type="text" id="date_e" autocomplete="off" value="{{ $editData->date_e }}" name="date_e"  class="form-control"   >
                                </div>

                            </div>
{{--                            <div class="row">--}}
{{--                             --}}
{{--                            </div>--}}
                            <br>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button id="btnAdvertisementInfo" onclick="Advertisement.submitValue()" type="button" class="btn btn-sm btn-success float-right">Update</button>
                                    </div>
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
        (function() {
            jqueryCalendar('date_s');
            jqueryCalendar('date_e');

        })(jQuery);

        $(document).ready(function (){
            Advertisement.showTotal();
        });
        function resetForm(id,header,body,okMessage='From reset successful.',cancelMessage=null) {
            alertify.confirm('<strong>'+header+'</strong>',body,
                function(){
                    document.getElementById(id).reset();
                    if(okMessage){
                        alertify.success(okMessage);
                    }
                },
                function(){
                    if(cancelMessage){
                        alertify.success(cancelMessage);
                    }
                });
        }
        let Advertisement = function (){
            let submitValue = function (){
                $("#btnAdvertisementInfo").attr('type', 'submit');
            }
            let showTotal =()=>{

                if($("#rate").val()!='' && $("#area").val()!=''){
                    let temp = parseFloat($("#rate").val()) * parseFloat($("#area").val());
                    $("#total_amount").val(temp.toFixed());
                }
            }
            return {
                submitValue: submitValue,
                showTotal: showTotal
            }

        }();


    </script>
@endsection
