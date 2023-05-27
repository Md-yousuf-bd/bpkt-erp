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

                    <form id="addGroupAccount" action="{{route('group-account.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="name">Category</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{$editData->name}}" required>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="1" @if($editData->status==1) selected @endif>Active</option>
                                        <option value="2" @if($editData->status==2) selected @endif >In-Active</option>
                                    </select>

                                </div>
                            </div>


                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-success float-right">Update</button>
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addGroupAccount','Edit Group Account Form','Do you really want to reset this form?');return false;">Reset</button>
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
