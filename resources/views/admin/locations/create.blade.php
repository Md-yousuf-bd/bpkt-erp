@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Add Location</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addLocationForm" action="{{route('settings.location.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="form-control-label">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter Location Name" class="form-control" required="required">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Type</label>
                                <select class="form-control select2" name="type" id="type" onchange="set_parent_type(this.value)">
                                    <option value="thana"> থানা </option>
                                    <option value="zone"> সার্কেল / জোন </option>
                                    <option value="district"> বিভাগ / জেলা </option>
                                    <option value="division"> রেঞ্জ / মেট্রো </option>
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
                                    @foreach($parents as $parent)
                                        <option value="{{$parent->id}}"> {{$parent->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addLocationForm','Add Location Form','Do you really want to reset this form?');return false;">Reset</button>
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
{{--                            <strong>Location Create</strong> Form--}}
{{--                        </div>--}}
{{--                        <form action="{{route('settings.location.store')}}" method="post" class="">--}}
{{--                        <div class="card-body card-block">--}}
{{--                               --}}
{{--                        </div>--}}
{{--                        <div class="card-footer text-right">--}}
{{--                            <button type="submit" class="btn btn-success btn-sm" name="submit" value="Rapid Submit">--}}
{{--                                <i class="fa fa-dot-circle-o"></i> Rapid Submit--}}
{{--                            </button>--}}
{{--                            <button type="submit" class="btn btn-success btn-sm" name="submit" value="Submit">--}}
{{--                                <i class="fa fa-dot-circle-o"></i> Submit--}}
{{--                            </button>--}}
{{--                            <button type="reset" class="btn btn-default btn-sm pull-left">--}}
{{--                                <i class="fa fa-ban"></i> Reset--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div> --}}
    <!-- .content -->
@endsection

@section('uncommonExJs')

@endsection

@section('uncommonInJs')
    <script>
        (function() {
            "use strict";
            set_parent_type('thana');
        })(jQuery);

        function getParent(type) {

            let html="";
            if(type==='0')
            {
                html="<option value='0'>বাংলাদেশ পুলিশ</option>";
                $('#parent_id').html(html);
            }
            else
            {
                $.ajax({
                    url: "get_parent_by_type/"+type+"/true",
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response)
                    {
                        if(response)
                        {
                            console.log(response);
                            for(let i=0; i<response.length; i++)
                            {
                                html+="<option value='"+response[i][0]+"'>"+response[i][1]+"</option>";
                            }
                            $('#parent_id').html(html);
                        }
                    }
                });
            }

        }

        function set_parent_type(val) {
            let opt='';
            if(val==='thana'){
                opt+=' <option value="zone"> সার্কেল / জোন </option> <option value="district"> বিভাগ / জেলা </option>';
            }
            else if(val==='zone'){
                opt='<option value="district"> বিভাগ / জেলা </option>';
            }
            else if(val==='district'){
                opt='<option value="division"> রেঞ্জ / মেট্রো </option>';
            }
            else
            {
                opt='<option value="0">বাংলাদেশ পুলিশ</option>';
            }
            $('#parent_type').html(opt);
            $('#parent_type').select2().trigger('change');
        }
    </script>
@endsection
