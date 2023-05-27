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
                        <h5 class="card-title">Edit Rate Info</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addGroupAccount" action="{{route('rate.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="type">Item Name</label>
                                    <input type="hidden" name="name" id="name">
                                    <select class="form-control select2" name="type" id="type">
{{--                                        <option value="29" @if(29==$editData->type) selected @endif>Rent</option>--}}
                                        <option value="31" @if(31==$editData->type) selected @endif>Service Charge</option>
                                        <option value="33" @if(33==$editData->type) selected @endif>Electricity</option>
                                        <option value="34" @if(34==$editData->type) selected @endif>Food Court SC</option>
                                        <option value="43" @if(43==$editData->type) selected @endif>Special Service Charge</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rate/sft</label>
                                    <input type="text" value="{{$editData->rate}}" id="rate" name="rate" onkeypress="return filterKeyNumber(this,event,'e_ra')"  class="form-control" required="required">
                                    <p id="e_ra" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                </div>
                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_id">Effective Date</label>
                                    <input class="form-control" value="{{$editData->effective_date}}" type="text" name="effective_date" id="effective_date">

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Vat</label>
                                    <input type="text" value="{{$editData->vat}}" id="vat" name="vat" onkeypress="return filterKeyNumber(this,event,'e_rv')"  class="form-control" >
                                    <p id="e_rv" style="display: none;color: red; margin-top: -11px; text-align: left;">  Please Enter Number Only</p>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="off_type">Type</label>
                                    <select class="form-control select2" name="off_type" id="off_type">
                                        <option value="Shop" @if('Shop'==$editData->off_type) selected @endif>Shop</option>
                                        <option value="Office" @if('Office'==$editData->off_type) selected @endif>Office</option>
                                        <option value="Adv" @if('Adv'==$editData->off_type) selected @endif>Adv</option>
                                        <option value="Others" @if('Others'==$editData->off_type) selected @endif>Others</option>
                                    </select>

                                </div>
                            </div>
                            <br>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
                                        <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addRateInfo','Add Rate Info Form','Do you really want to reset this form?');return false;">Reset</button>
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
            jQuery('form').bind('submit', function() {
                $("#name").val($("#type").find('option:selected').text());

            });
            jqueryCalendar('effective_date');
        })(jQuery);

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


    </script>
@endsection
