@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Cash Collection</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addIncome" action="{{route('cash-collection.update',[$editData->id])}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="customer_id">Shop No/Shop Name <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="customer_id" id="customer_id" onchange="getDueInvoice(this.value)" required>
                                        <option value="">None</option>
                                        @foreach($customer as $row)
                                            <option value="{{$row->id}}" @if($row->id == $editData->customer_id) selected @endif >{{$row->shop_no}} - {{$row->shop_name}}</option>
                                        @endforeach

                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="status">Invoice No <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="invoice_no" id="invoice_no" onchange="getInvoiceDetails(this.value)" required>
                                        <option value="">None</option>

                                    </select>


                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Mode of Payment <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="payment_mode" id="payment_mode" required>
                                        <option value="">None</option>
                                        @foreach($ledger as $row)
                                            <option value="{{$row->head}}" @if($row->head == $editData->payment_mode) selected @endif>{{$row->head}} </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Cheque Number <span style="color:red">*</span></label>
                                    <input autocomplete="off" id="cheque_no" value="{{ $editData->cheque_no }}" name="cheque_no"  class="form-control" required>


                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Cheque Date <span style="color:red">*</span></label>
                                    <input  type="text" id="cheque_date"  value="{{ $editData->cheque_date }}"  name="cheque_date" autocomplete="off"  class="form-control" required>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Money Receipt No</label>
                                    <input  type="text" id="money_receipt_no"  value="{{ $editData->money_receipt_no }}"  name="money_receipt_no" autocomplete="off"  class="form-control" >

                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Balance Due</label>
                                    <input  type="text" id="balance_due"  value="{{ $editData->balance_due }}"  name="balance_due" autocomplete="off"  class="form-control" >

                                </div>


                            </div>


                            <div class="row showTbl" >


                                <table  class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr>
                                        <input type="hidden" value="" name="accountRecord" id="accountRecord">
                                        <input type="hidden" value="" name="total" id="total">
                                        <td style="width:5%">#</td>
                                        <td style="width: 40% !important;">Collection Head</td>
                                        <td style="width: 12% !important;">Month</td>
                                        <td style="width: 10% !important;">Bill Amount(Tk.)</td>
                                        <td style="width: 10% !important;">Due Amount(Tk.) </td>
                                        <td style="width: 11% !important;">Payment Amount(Tk.) </td>
                                        <td style="width: 5% !important;"></td>
                                    </tr>
                                    </thead>
                                    <tbody id="collection_details">


                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <tbody id="subTbl" >
                                    </tbody>
                                    <input type="hidden" id="payment_amount" name="payment_amount" value="0">
                                </table>


                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
                                        <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addGodown','Add add Godown Form','Do you really want to reset this form?');return false;">Reset</button>
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
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
@endsection

@section('uncommonInJs')
    <script>
        $(document).ready(function () {
            jqueryCalendar('cheque_date');
        });

        function checkAmount(v,id) {
            if (id != '') {


                let due = parseFloat($("#rd_" + id).text());
                if (v > due) {
                    $("#in_" + id).val('').change();
                    $("#r_" + id).show();
                    $("#r_" + id).html("Please Enter Due Amount.");
                } else {
                    $("#r_" + id).hide();
                }
            }
            let total = 0;
            $("input[id^='amt_']").each(function(){
                let id = $(this).attr('id');
                let temp = 0;
                temp = parseFloat($("#"+id).val()).toFixed(2);
                if(isNaN(temp)) {
                    temp = 0;
                }
                total +=  +temp;

                console.log(temp);
                // console.log(total);
            });
            let gtotal = total;
            if($("#paid_vat_amount").val()!=''){
                gtotal += +$("#paid_vat_amount").val();
            }

            console.log(gtotal);
            $("#sp_total").html(gtotal.toFixed(2));
            $("#payment_amount").val(total.toFixed(2));
        }
        function getInvoiceDetails(id) {
            $(".preloader").show();
            $.ajax({
                type: 'GET',
                url: 'get-invoice-details/'+id,
                dataType: 'json',
                success: function (data) {
                    $(".preloader").hide();
                    // console.log(data);
                    $("#collection_details").html(data.html);


                },error:function(){
                    console.log(data);
                }
            });

        }

        function getDueInvoice(id) {
            $(".preloader").show();
            $.ajax({url: "due-invoice/"+id, success: function(result){
                    $(".preloader").hide();
                    let ar = JSON.parse(result);
                    let html='<option value="" >None</option>';
                    $.each(ar, function(index, item) {
                        html +='<option value="'+item.id+'">' + item.invoice_no  +'</option >';
                    });
                    $("#invoice_no").html(html);
                }});
        }


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
