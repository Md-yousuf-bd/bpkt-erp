@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Location</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="editLocationForm" action="{{route('settings.location.update',[$location->id])}}" method="post" class="">
                        <input name="_method" type="hidden" value="PATCH">
                        <input name="location_id" type="hidden" value="{{$location->id ?? 0}}">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter Location Name" class="form-control" required="required" value="{{$location->name}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Display Name</label>
                                <input type="text" id="display_name" name="display_name" placeholder="Enter Location Display Name" class="form-control" value="{{$location->display_name}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">English Name</label>
                                <input type="text" id="english_name" name="english_name" placeholder="Enter Location English Name" class="form-control" value="{{$location->english_name}}">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Type</label>
                                <select class="form-control select2" name="type" id="type" onchange="set_parent_type(this.value)">
                                    <option value="thana" @if($location->type=='thana') selected @endif> থানা </option>
                                    <option value="zone" @if($location->type=='zone') selected @endif> সার্কেল / জোন </option>
                                    <option value="district" @if($location->type=='district') selected @endif> বিভাগ / জেলা </option>
                                    <option value="division" @if($location->type=='division') selected @endif> রেঞ্জ / মেট্রো </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Parent Type</label>
                                <select class="form-control select2" name="parent_type" id="parent_type" onchange="getParent(this.value);">

                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Parent</label>
                                <select class="form-control select2" name="parent_id" id="parent_id">

                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-warning float-right">Update</button>
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('editLocationForm','Edit Location Form','Do you really want to reset this form?');return false;">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


{{--    <div class="content mt-3">--}}
{{--        <div class="animated fadeIn">--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-8 col-sm-12 col-xs-12 mx-auto">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header">--}}
{{--                            <strong>Location Edit</strong> Form--}}
{{--                        </div>--}}
{{--                        <form action="{{route('settings.location.update',[$location->id])}}" method="post" class="">--}}
{{--                            <input name="_method" type="hidden" value="PATCH">--}}
{{--                            <input name="location_id" type="hidden" value="{{$location->id ?? 0}}">--}}
{{--                        <div class="card-body card-block">--}}
{{--                                {{ csrf_field() }}--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-control-label">Name</label>--}}
{{--                                <input type="text" id="name" name="name" placeholder="Enter Location Name" class="form-control" required="required" value="{{$location->name}}">--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-control-label">Display Name</label>--}}
{{--                                <input type="text" id="display_name" name="display_name" placeholder="Enter Location Display Name" class="form-control" value="{{$location->display_name}}">--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-control-label">English Name</label>--}}
{{--                                <input type="text" id="english_name" name="english_name" placeholder="Enter Location English Name" class="form-control" value="{{$location->english_name}}">--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-control-label">Type</label>--}}
{{--                                <select class="form-control select2" name="type" id="type" onchange="set_parent_type(this.value)">--}}
{{--                                    <option value="thana" @if($location->type=='thana') selected @endif> থানা </option>--}}
{{--                                    <option value="zone" @if($location->type=='zone') selected @endif> সার্কেল / জোন </option>--}}
{{--                                    <option value="district" @if($location->type=='district') selected @endif> বিভাগ / জেলা </option>--}}
{{--                                    <option value="division" @if($location->type=='division') selected @endif> রেঞ্জ / মেট্রো </option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-control-label">Parent Type</label>--}}
{{--                                <select class="form-control select2" name="parent_type" id="parent_type" onchange="getParent(this.value);">--}}
{{--                                    <option value="zone"@if($parent_type=='zone') selected @endif> সার্কেল / জোন </option>--}}
{{--                                    <option value="district" @if($parent_type=='district') selected @endif> বিভাগ / জেলা </option>--}}
{{--                                    <option value="division" @if($parent_type=='division') selected @endif> রেঞ্জ / মেট্রো </option>--}}
{{--                                    <option value='0' @if($parent_type=='') selected @endif>বাংলাদেশ পুলিশ হেডকোয়ার্টার্স</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="form-control-label">Parent</label>--}}
{{--                                <select class="form-control select2" name="parent_id" id="parent_id">--}}
{{--                                    @if(count($parents)>0)--}}
{{--                                        @foreach($parents as $parent)--}}
{{--                                            <option value="{{$parent->id}}" @if($location->parent_id==$parent->id) selected @endif> {{$parent->name}} </option>--}}
{{--                                        @endforeach--}}
{{--                                    @else--}}
{{--                                        <option value="0"> None </option>--}}
{{--                                    @endif--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="card-footer text-right">--}}
{{--                            <button type="submit" class="btn btn-success btn-sm">--}}
{{--                                <i class="fa fa-dot-circle-o"></i> Update--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div> <!-- .content -->--}}
@endsection

@section('uncommonExJs')

@endsection

@section('uncommonInJs')
    <script>
        (function() {
            "use strict";
            set_parent_type('{{$location->type}}','{{$parent_type}}');
        })(jQuery);

        function getParent(type,selected=null) {
            let html = "";
            if (type === '0') {
                html = "<option value='0'>বাংলাদেশ পুলিশ</option>";
                $('#parent_id').html(html);
            } else {
                $.ajax({
                    url: "{{URL::to('/')}}/settings/location/get_parent_by_type/" + type + "/true",
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {
                        if (response) {
                            for (let i = 0; i < response.length; i++) {
                                if(selected===response[i][0]){
                                    html += "<option value='" + response[i][0] + "' selected>" + response[i][1] + "</option>";
                                }
                                else
                                {
                                    html += "<option value='" + response[i][0] + "'>" + response[i][1] + "</option>";
                                }
                            }
                            $('#parent_id').html(html);
                            $('#parent_id').select2().trigger('change');
                        }
                    }
                });
            }
        }
        function set_parent_type(val,selected=null) {
            let opt='';
            if(val ==='thana'){
                if(selected ==="zone"){
                    opt+=' <option value="zone" selected > সার্কেল / জোন </option> <option value="district"> বিভাগ / জেলা </option>';
                }
                else if(selected ==="district")
                {
                    opt+=' <option value="zone"> সার্কেল / জোন </option> <option value="district" selected > বিভাগ / জেলা </option>';
                }
                else
                {
                    opt+=' <option value="zone"> সার্কেল / জোন </option> <option value="district"> বিভাগ / জেলা </option>';
                }
            }
            else if(val==='zone'){
                if(selected ==="zone"){
                    opt+=' <option value="zone" selected > সার্কেল / জোন </option>';
                }
                else
                {
                    opt='<option value="district"> বিভাগ / জেলা </option>';
                }
            }
            else if(val==='district'){
                if(selected ==="district"){
                    opt = '<option value="division" selected > রেঞ্জ / মেট্রো </option>';
                }
                else {
                    opt = '<option value="division"> রেঞ্জ / মেট্রো </option>';
                }
            }
            else
            {
                if(selected ==="division"){
                    opt = '<option value="0" selected >বাংলাদেশ পুলিশ</option>';
                }
                else {
                    opt = '<option value="0">বাংলাদেশ পুলিশ</option>';
                }
            }
            $('#parent_type').html(opt);
            $('#parent_type').select2().trigger('change');
            if(selected){
                getParent(selected,{{$location->parent_id}});
            }
        }
    </script>
@endsection
