@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Asset Info</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addGroupAccount" action="{{route('assets.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Asset No.</label>
                                    <input type="text" value="{{$editData->asset_no}}" id="asset_no" name="asset_no"  class="form-control" required="required">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Customer</label>
                                    <select class="form-control select2" name="customer_id" id="customer_id">
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}" @if($row->id==$editData->customer_id) selected @endif >{{$row->shop_name}}</option>
                                        @endforeach
                                    </select>

                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Floor Name</label>
                                    <select class="form-control select2" name="floor_name" id="floor_name">
                                        <option value="">None</option>
                                        @foreach($floor as $row)
                                            <option value="{{$row->name}}" @if($row->name==$editData->floor_name) selected @endif>{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Area (Sft)</label>
                                    <input type="text" value="{{$editData->area_sft}}" id="area_sft" name="area_sft"  class="form-control" >
                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_id">Owner Name</label>
                                    <select class="form-control select2" name="owner_id" id="owner_id">
                                        <option value="">None</option>
                                        @foreach($owner as $row)
                                            <option value="{{$row->id}}" @if($row->id==$editData->owner_id) selected @endif>{{$row->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="1" @if(1==$editData->status) selected @endif>Active</option>
                                        <option value="2" @if(2==$editData->status) selected @endif>In-Active</option>
                                    </select>

                                </div>

                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
                                        <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addGroupAccount','Add Group Account Form','Do you really want to reset this form?');return false;">Reset</button>
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

@endsection

@section('uncommonInJs')
    <script>
        (function() {

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
