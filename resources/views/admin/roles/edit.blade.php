@extends('admin.layouts.app')

@section('uncommonExCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Role</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="editRoleForm" method="POST" action="{{ route('settings.role.update',[$role->id]) }}">
                        @csrf
                        <input name="_method" type="hidden" value="PATCH">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ucwords(str_replace('-',' ', $role->name))}}" readonly required>
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="guard_name">Guard Name</label>
                                    <select class="form-control select2" name="guard_name" id="guard_name">
                                        <option value="web" @if($role->guard_name=='web') selected @endif>web</option>
                                        <option value="customer" @if($role->guard_name=='customer') selected @endif>customer</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                    <label for="level">Level</label>
                                    <input type="number" min="1" class="form-control" name="level" id="level"  value="{{$role->level}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label for="permissions">Assign Permissions to Role</label>
                                    <select class="form-control select2" name="permissions[]" id="permissions" multiple>
                                        @foreach($permissions as $permission)
                                            <option value="{{$permission->id}}" @if($role->hasPermissionTo($permission->name)) selected @endif>{{ucwords(str_replace('-',' ',$permission->name))}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-warning float-right">Update</button>
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('editRoleForm','Edit Role Form','Do you really want to reset this form?');return false;">Reset</button>
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
