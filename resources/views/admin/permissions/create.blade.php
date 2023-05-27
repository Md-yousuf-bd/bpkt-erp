@extends('admin.layouts.app')

@section('uncommonExCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Add Permission</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addPermissionForm" method="POST" action="{{ route('settings.permission.store') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="guard_name">Guard Name</label>
                                    <select class="form-control select2" name="guard_name" id="guard_name">
                                        <option value="web">web</option>
                                        <option value="customer">customer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="roles">Assign Permission to Roles</label>
                                    <select class="form-control select2" name="roles[]" id="roles" multiple>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{ucwords(str_replace('-',' ',$role->name))}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addPermissionForm','Add Permission Form','Do you really want to reset this form?');return false;">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- .content -->

@endsection

@section('uncommonExJs')
@endsection

@section('uncommonInJs')
    <script>
        (function() {
            "use strict";
        })(jQuery);
    </script>
@endsection
