@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Tax Info</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addTax" action="{{route('tax.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="tax_type">Tax Type</label>
                                    <select class="form-control select2" name="tax_type" id="tax_type" onchange="getTaxType(this.value)">
                                        <option value="TDS" @if($editData->tax_type == 'TDS')selected @endif>TDS</option>
                                        <option value="VDS" @if($editData->tax_type == 'VDS')selected @endif>VDS</option>
                                        <option value="Sales VAT" @if($editData->tax_type == 'Sales VAT')selected @endif>Sales VAT</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="year">Financial Year</label>
                                    <select class="form-control select2" name="year" id="year">
                                        <option value="0">None</option>
                                        <option value="2021-2022" @if($editData->year == '2021-2022')selected @endif>2021-2022</option>
                                        <option value="2022-2023" @if($editData->year == '2022-2023')selected @endif>2022-2023</option>
                                        <option value="2023-2024" @if($editData->year == '2023-2024')selected @endif>2023-2024</option>
                                        <option value="2024-2025" @if($editData->year == '2024-2025')selected @endif>2024-2025</option>
                                    </select>

                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Accounts Head</label>
                                    <input type="text" value="{{ $editData->account_head }}" id="account_head" name="account_head"  class="form-control" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="section">Section</label>
                                    <input type="text" value="{{ $editData->section }}" id="section" name="section"  class="form-control" >


                                </div>

                            </div>
                            <div class="row tds">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Lower Limit</label>
                                    <input type="text" value="{{ $editData->lower_limit }}" id="lower_limit" name="lower_limit"  class="form-control" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Upper Limit</label>
                                    <input type="text" value="{{ $editData->upper_limit }}" id="upper_limit" name="upper_limit"  class="form-control" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rate</label>
                                    <input type="text" value="{{ $editData->rate }}" id="rate" name="rate"  class="form-control" >
                                </div>
                                <div id="sp_b" class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label"><span id="sp_com">Basis</span></label>
                                    <input type="text" value="{{ $editData->basis }}" id="basis" name="basis"  class="form-control" >
                                </div>
                                <div id ="sp_c" style="display:none;" class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Compulsory VDS?</label>


                                    <div class="form-check">
                                        <input class="form-check-input" @if($editData->compulsory_vds == 'Yes')checked @endif  type="radio" name="compulsory_vds" id="compulsory_vds" value="Yes">
                                        <label class="form-check-label" for="compulsory_vds">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" @if($editData->compulsory_vds == 'No')checked @endif type="radio" name="compulsory_vds" id="compulsory_vds_1" value="No">
                                        <label class="form-check-label" for="compulsory_vds_1">
                                            No
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
                                        <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addTax','Add add Tax Form','Do you really want to reset this form?');return false;">Reset</button>
                                    </div>
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


    </script>
@endsection
