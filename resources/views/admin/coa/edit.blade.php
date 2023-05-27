@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Chart of Accounts</h5>
                        <div class="card-tools">
                        </div>
                    </div>

                    <form id="addCOA" action="{{route('coa.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div  class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="head">Accounting Head</label>
                                    <input type="text" value="{{$editData->head}}" class="form-control" name="head" id="head" >
                                    <input type="hidden" class="form-control" value="{{$editData->category}}" name="cat_txt" id="cat_txt" >
                                    <input type="hidden" class="form-control" value="{{$editData->sub_category}}" name="sub_cat_txt" id="sub_cat_txt" >
                                    <input type="hidden" class="form-control" value="{{$editData->sub_sub_category}}" name="sub_sub_cat_txt" id="sub_sub_cat_txt" >
                                    <input type="hidden" class="form-control" value="{{$editData->type}}" name="type_id" id="type_id" >
                                    <input type="hidden" class="form-control" value="{{$editData->group_name}}" name="group_name" id="group_name" >

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="type">Type</label>
                                    <select class="form-control select2" name="type" id="type" onchange="getEditSubCategory(this.value,2)">
                                        <option value="2" @if($editData->type_id==2)selected @endIf>Asset</option>
                                        <option value="16" @if($editData->type_id==16)selected @endIf>Liability</option>
                                        <option value="21" @if($editData->type_id==21)selected @endIf>Expense</option>
                                        <option value="20" @if($editData->type_id==20)selected @endIf>Income</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="group_id">Group</label>
                                    <select onchange="getEditSubCategory(this.value,3);"  class="form-control select2" name="group_id" id="group_id">
                                        @foreach($category as $name)
                                            <option value="{{$name->id}}" @if($editData->group_id==$name->id)selected @endIf>{{$name->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="category">Category</label>
                                    <select onchange="getEditSubCategory(this.value,4);" class="form-control select2" name="category" id="category">
                                        <option value="">Select</option>

                                    </select>

                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="sub_category">Sub Category</label>
                                    <select onchange="getEditSubCategory(this.value,5);" class="form-control select2" name="sub_category" id="sub_category">
                                        <option value="">Select</option>

                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="sub_sub_category">Sub Sub Category</label>
                                    <select onchange="getEditSubCategory(this.value,6);" class="form-control select2" name="sub_sub_category" id="sub_sub_category">
                                        <option value="">Select</option>

                                    </select>
                                </div>
                            </div>
                            <div class="row">



                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="reference">Reference </label>
                                    <select class="form-control select2" name="reference" id="reference">
                                        <option value="0">Common For All Unit</option>
                                        @foreach($unit as $u)
                                            <option value="{{$u->id}}">{{$u->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="1" @if($editData->status==1) selected @endIf>Active</option>
                                        <option value="2" @if($editData->status==2) selected @endIf>In-Active</option>
                                    </select>

                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
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
           getEditSubCategory({!! $editData->type_id !!},2)
       })(jQuery);
       function getEditSubCategory(id,ref){
console.log(id);
           $.ajax({
               type: 'GET',
               url: '../../settings/lookup/get-child/'+id+'/'+ref,
               dataType: 'json',
               success: function (data) {

                   console.log(data);
                   let html='<option value="0" >None</option>';
                   $.each(data.category, function(index, item) {
                       console.log(item);
                       html +='<option value="'+item.id+'">' + item.name  +'</option >';

                   });

                   if(ref==2){
                       $("#group_id").html(html);
                       $("#group_id").val({!! $editData->group_id !!});
                       getEditSubCategory({!! $editData->group_id !!},3)
                   }
                   else if(ref==3){
                       $("#category").html(html);
                       $("#type_id").val($("#type").find('option:selected').text());
                       $("#group_name").val($("#group_id").find('option:selected').text());
                       $("#category").val({!! $editData->category_id !!});
                       getEditSubCategory({!! $editData->category_id !!},4)
                   }else if(ref==4){
                       $("#sub_category").html(html);
                       $("#cat_txt").val($("#category").find('option:selected').text());
                       $("#sub_category").val({!! $editData->sub_category_id !!});
                       getEditSubCategory(parseInt({!! $editData->sub_category_id !!}),5)

                   }else if(ref==5){
                       $("#sub_sub_category").html(html);
                       $("#sub_cat_txt").val($("#sub_category").find('option:selected').text());
                       $("#sub_sub_category").val({!! $editData->sub_sub_category_id !!});

                       getEditSubCategory(parseInt({!! $editData->sub_sub_category_id !!}),6)
                   }else {
                       $("#sub_sub_cat_txt").val($("#sub_sub_category").find('option:selected').text());

                   }


               },error:function(){
                   console.log(data);
               }
           });
       }
    </script>
@endsection
