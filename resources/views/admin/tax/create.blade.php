@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Add Tax Info</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addTax" action="{{route('tax.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="tax_type">Tax Type</label>
                                    <select class="form-control select2" name="tax_type" id="tax_type" onchange="getTaxType(this.value)">
                                        <option value="TDS">TDS</option>
                                        <option value="VDS">VDS</option>
                                        <option value="Sales VAT">Sales VAT</option>
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="year">Financial Year</label>
                                    <select class="form-control select2" name="year" id="year">
                                        <option value="0">None</option>
                                        <option value="2021-2022">2021-2022</option>
                                        <option value="2022-2023">2022-2023</option>
                                        <option value="2023-2024">2023-2024</option>
                                        <option value="2024-2025">2024-2025</option>
                                    </select>

                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Accounts Head</label>
                                    <input type="text" id="account_head" name="account_head"  class="form-control" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="section">Section</label>
                                    <input type="text" id="section" name="section"  class="form-control" >


                                </div>

                            </div>
                            <div class="row tds">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Lower Limit</label>
                                    <input type="text" id="lower_limit" name="lower_limit"  class="form-control" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Upper Limit</label>
                                    <input type="text" id="upper_limit" name="upper_limit"  class="form-control" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Rate</label>
                                    <input type="text" id="rate" name="rate"  class="form-control" >
                                </div>
                                <div id="sp_b" class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label"><span id="sp_com">Basis</span></label>
                                    <input type="text" id="basis" name="basis"  class="form-control" >
                                </div>
                                <div id ="sp_c" style="display:none;" class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Compulsory VDS?</label>


                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="compulsory_vds" id="compulsory_vds" value="Yes">
                                        <label class="form-check-label" for="compulsory_vds">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="compulsory_vds" id="compulsory_vds_1" value="No">
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
       function getTaxType(ref){
            if(ref=='TDS'){
                $('#sp_b').show();
                $('.tds').show();
                $('#sp_c').hide();
            }
            else if(ref=='VDS'){
                $('#sp_b').hide();
                $('#sp_c').show();
                $('.tds').hide();
            }else{
                $('#sp_b').hide();
                $('#sp_c').hide();
                $('.tds').hide();
            }

        }

    </script>
@endsection
