@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Head Office/ Group Accounts</h5>
                        <div class="card-tools">
                        </div>
                    </div>

                    <form id="EditUnit" action="{{route('unit.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Name</label>
                                    <input type="text" id="name" name="name" value="{{$editData->name}}" placeholder="Enter Unit Name" class="form-control" required="required">
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Short Name</label>
                                    <input type="text" id="short_name" value="{{$editData->short_name}}" name="short_name" placeholder="Enter Unit Name" class="form-control" required="required">
                                </div>

                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-success float-right">Update</button>
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('EditUnit','Edit Group Account Form','Do you really want to reset this form?');return false;">Reset</button>
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
            "use strict";
            set_parent_type('thana');
        })(jQuery);



    </script>
@endsection
