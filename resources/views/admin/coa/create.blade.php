@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Add Chart of Accounts</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addCOA" action="{{route('coa.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div  class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="head">Accounting Head</label>
                                    <input type="text" class="form-control" name="head" id="head" >
                                    <input type="hidden" class="form-control" name="cat_txt" id="cat_txt" >
                                    <input type="hidden" class="form-control" name="sub_cat_txt" id="sub_cat_txt" >
                                    <input type="hidden" class="form-control" name="sub_sub_cat_txt" id="sub_sub_cat_txt" >
                                    <input type="hidden" class="form-control" name="type_id" id="type_id" >
                                    <input type="hidden" class="form-control" name="group_name" id="group_name" >

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="type">Type</label>
                                    <select class="form-control select2" name="type" id="type" onchange="getSubCategory(this.value,2)">
                                        <option value="2">Asset</option>
                                        <option value="16">Liability</option>
                                        <option value="21">Expense</option>
                                        <option value="20">Income</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="group_id">Group</label>
                                    <select onchange="getSubCategory(this.value,3);"  class="form-control select2" name="group_id" id="group_id">
                                        <option value="0">None</option>
                                        @foreach($category as $name)
                                            <option value="{{$name->id}}">{{$name->name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="category">Category</label>
                                    <select onchange="getSubCategory(this.value,4);" class="form-control select2" name="category" id="category">
                                        <option value="">Select</option>

                                    </select>

                                </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="sub_category">Sub Category</label>
                                    <select onchange="getSubCategory(this.value,5);" class="form-control select2" name="sub_category" id="sub_category">
                                        <option value="">Select</option>

                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="sub_sub_category">Sub Sub Category</label>
                                    <select onchange="getSubCategory(this.value,6);" class="form-control select2" name="sub_sub_category" id="sub_sub_category">
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
                                        <option value="1">Active</option>
                                        <option value="2">In-Active</option>
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

     function getSubCategory(id,ref){
         $(".preloader").show();
         $.ajax({
             type: 'GET',
             url: '../settings/lookup/get-child/'+id+'/'+ref,
             dataType: 'json',
             success: function (data) {
                 $(".preloader").hide();
                 console.log(data);
                 let html='<option value="0" >None</option>';
                 $.each(data.category, function(index, item) {
                     console.log(item);
                     html +='<option value="'+item.id+'">' + item.name  +'</option >';

                 });
                 if(ref==2){
                     $("#group_id").html(html);

                 }
                 else if(ref==3){
                     $("#category").html(html);
                     $("#type_id").val($("#type").find('option:selected').text());
                     $("#group_name").val($("#group_id").find('option:selected').text());
                 }else if(ref==4){
                     $("#sub_category").html(html);
                     $("#cat_txt").val($("#category").find('option:selected').text());
                 }else if(ref==5){
                     $("#sub_sub_category").html(html);
                     $("#sub_cat_txt").val($("#sub_category").find('option:selected').text());

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
