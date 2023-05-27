@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Owner Info</h5>
                        <div class="card-tools">
                        </div>
                    </div>

                    <form id="addCOA" action="{{route('owner.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_name">Owner Name</label>
                                    <input type="text" class="form-control" value="{{$editData->name}}" name="name" id="name" required>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" value="{{$editData->address}}" name="address" id="address" required>

                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="phone">Phone No</label>
                                    <input type="text" class="form-control" value="{{$editData->phone}}" name="phone" id="phone" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="email">E-Mail ID</label>
                                    <input type="text" class="form-control" value="{{$editData->email}}" name="email" id="email" >

                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="head">Contact Person Name</label>
                                    <input type="text" class="form-control" value="{{$editData->contact_person_name}}" name="contact_person_name" id="contact_person_name" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="head">Contact Person Phone No</label>
                                    <input type="text" class="form-control" value="{{$editData->contact_person_phone}}" name="contact_person_phone" id="contact_person_phone" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Type</label>
                                    <select class="form-control select2" name="type" id="type">
                                        <option value="Owner Info"  @if($editData->status=='Owner Info') selected @endif>Owner Info</option>
                                        <option value="Sister Company Info"  @if($editData->status=='Sister Company Info') selected @endif>Sister Company Info</option>
                                    </select>


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
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addCOA','Add Chart of Accounts Form','Do you really want to reset this form?');return false;">Reset</button>
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
        })(jQuery);



    </script>
@endsection
