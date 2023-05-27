@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Add Lookup</h5>
                            <div class="card-tools">
                            </div>
                        </div>
                        <form id="addLookupForm" action="{{route('settings.lookup.store')}}" method="post" class="">
                        <div class="card-body card-block">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="form-control-label">Name</label>
                                    <input type="text" id="name" name="name" placeholder="Enter Lookup Name" class="form-control" required="required">
                                </div>
                            <div class="form-group">
                                <label class="form-control-label">Category Under</label>
                                <select class="form-control select2" name="priority" id="priority" onchange="getChild(this.value,1);showHide(this.value)">
                                    <option value="0">None</option>
                                    <option value="1">Type</option>
                                    <option value="2">Group</option>
                                    <option value="3">Category</option>
                                    <option value="4">Sub Category</option>
                                    <option value="5">Sub Sub Category</option>


                                </select>
                            </div>

                            <div class="form-group parent_id" style="display: none;">
                                <label class="form-control-label">Type</label>
                                <select class="form-control select2" name="parent_id" id="parent_id" onchange="getChild(this.value,2)">
                                    <option value="0"> None </option>
                                    @foreach($parents as $parent)
                                        <option value="{{$parent->id}}"> {{$parent->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group group_id" style="display: none;">
                                <label class="form-control-label">Group</label>
                                <select class="form-control select2" name="group_id" id="group_id" onchange="getChild(this.value,3)">
                                    <option value="0"> None </option>
                                    @foreach($parents as $parent)
                                        <option value="{{$parent->id}}"> {{$parent->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="form-group category_id" style="display: none;">
                                    <label class="form-control-label">Category</label>
                                    <select class="form-control select2" name="category_id" id="category_id" onchange="getChild(this.value,4)">
                                        <option value="0"> None </option>
                                        @foreach($parents as $parent)
                                            <option value="{{$parent->id}}"> {{$parent->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            <div class="form-group child_id" style="display: none;">
                                <label class="form-control-label">Sub Category</label>
                                <select class="form-control select2" name="child_id" id="child_id" onchange="getChild(this.value,5)">
                                    <option value="0"> None </option>

                                </select>
                            </div>
                            <div class="form-group child_id_2" style="display: none;">
                                <label class="form-control-label">Sub Sub Category</label>
                                <select class="form-control select2" name="child_id_2" id="child_id_2">
                                    <option value="0"> None </option>

                                </select>
                            </div>
                                <div class="form-group">
                                    <label class="form-control-label">Description</label>
                                    <textarea name="description" id="description" style="width: 100%;"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="1"> Active </option>
                                        <option value="0"> Inactive </option>
                                    </select>
                                </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addLookupForm','Add Lookup Form','Do you really want to reset this form?');return false;">Reset</button>
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

        function getChild(id,ref) {

            $.ajax({
                type: 'GET', //THIS NEEDS TO BE GET
                url: 'get-child/'+id+'/'+ref,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    let html='<option value="0" >None</option>';
                    $.each(data.category, function(index, item) {
                        console.log(item);
                      html +='<option value="'+item.id+'">' + item.name  +'</option >';

                    });
                    if(ref==1){
                     $("#child_id").html(html);
                    }else if(ref==2){
                        $("#group_id").html(html);
                    }else if(ref==3){
                        $("#category_id").html(html);
                    }else if(ref==4){
                        $("#child_id").html(html);
                    }else {
                        $("#child_id_2").html(html);
                    }
                },error:function(){
                    console.log(data);
                }
            });
        }
        function showHide(ref) {
            console.log(ref);
            if(ref==1){
                $(".parent_id").show();
                $(".group_id").hide();
                $(".child_id").hide();
                $(".child_id_2").hide();
                $("#group_id").val(0);
                $("#category_id").val(0);
                $("#child_id").val(0);
                $("#child_id_2").val(0);

            }else if(ref==2){
                $(".parent_id").show();
                $(".group_id").show();
                $(".child_id").hide();
                $(".child_id_2").hide();
                $("#category_id").val(0);
                $("#child_id").val(0);
                $("#child_id_2").val(0);
            }else if(ref==3){
                $(".parent_id").show();
                $(".group_id").show();
                $(".category_id").show();
                $(".child_id").hide();
                $(".child_id_2").hide();
                $("#child_id").val(0);
                $("#child_id_2").val(0);
            }else if(ref==4){
                $(".parent_id").show();
                $(".group_id").show();
                $(".category_id").show();
                $(".child_id").show();
                $(".child_id_2").hide();
                $("#child_id_2").val(0);
            }else if(ref==5){
                $(".parent_id").show();
                $(".group_id").show();
                $(".category_id").show();
                $(".child_id").show();
                $(".child_id_2").show();
            }

        }
    </script>
@endsection
