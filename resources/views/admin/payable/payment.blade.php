@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Add Payments</h5>
                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addIncome" action="{{route('payable.payments')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}

                            <div class="row">
                                <input type="hidden" value="{{$payment->id}}" name="payment_id" id="payment_id">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Mode of Payment <span style="color:red">*</span></label>
                                    <select class="form-control select2" name="payment_mode" id="payment_mode" required
                                            onchange="Payment1.loadLedger(this.value,'')">
                                        <option value="">None</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Cash">Cash</option>


                                    </select>

                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Journal Date<span style="color:red">*</span></label>
                                    <input autocomplete="off"  type="text" id="journal_date" name="journal_date"  class="form-control" required>


                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Issue Date<span style="color:red">*</span></label>
                                    <input autocomplete="off" type="text" id="issue_date" name="issue_date"  class="form-control" required>


                                </div>

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Payment Reference/Cheque Number</label>
                                    <input  type="text" id="payment_reference" name="payment_reference" autocomplete="off"  class="form-control" >

                                </div>

                            </div>

                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Paid Amount (Tk.)</label>
                                    <input readonly  type="text" value="{{$payment->total}}" id="paid_amount" name="paid_amount" autocomplete="off"  class="form-control" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Purpose/ Narration</label>
                                    <input autocomplete="off" type="text" id="remarks" name="remarks"  class="form-control" >
                                </div>
                            </div>
                            <div class="row sp_check"  style="display: none">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Cheque Date <span style="color:red">*</span></label>
                                    <input  type="text" value="{{date('Y-m-d')}}" id="cheque_date" name="cheque_date" autocomplete="off"  class="form-control" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Cheque Number <span style="color:red">*</span></label>
                                    <input autocomplete="off" id="cheque_no" name="cheque_no"  class="form-control" >
                                </div>

                            </div>
                            <div class="row sp_check" style="display: none">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label class="form-control-label">Deposit Account Ledger </label>
                                    <select class="form-control select2" name="ledger_id" id="ledger_id" >
                                        <option value="">None</option>
                                        @foreach($ledger as $row)
                                            <option value="{{$row->id}}">{{$row->head}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">

                                    <label class="form-control-label">Cheque Bank Name</label>
                                    <input  type="text" id="cheque_bank_name" name="cheque_bank_name" autocomplete="off"  class="form-control" >

                                </div>

                            </div>



                            <br>



                            <div class="card-footer">
                                <div class="row">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-sm btn-success float-right">Submit</button>
                                        {{--                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addGodown','Add add Godown Form','Do you really want to reset this form?');return false;">Reset</button>--}}
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
        let customerInfo=[];
        jQuery(function() {
            jQuery('form').bind('submit', function() {
               if($('#journal_date').val()==''){
                    alert("Please journal date");
                    return false;
                }
                if($('#vendor_id').val()==''){
                    alert("Please Select Vendor Name");
                    return false;
                }
                if($('#paid_amount').val()==''){
                    alert("Please Enter Paid Amount");
                    return false;
                }
                if($('#invoice_no').val()==''){
                    alert("Please Select Invoice");
                    return false;
                }
                jQuery(this).find(':disabled').removeAttr('disabled');
                customerInfo=[];
            });

        });
        $(document).ready(function () {
            jqueryCalendar('payment_date');
            jqueryCalendar('issue_date');
            jqueryCalendar('due_date');
            jqueryCalendar('journal_date');
            jqueryCalendar('ad_payment_date');
        });



        let Payment1 = function (){



            let loadLedger = function (v,ref){
                if(v=='Cheque'){
                    $('.chk_no').show()
                    $(".preloader").show();
                    $.ajax({url: "../get-coa/"+v, success: function(result){
                            let ar = JSON.parse(result);
                            console.log(ar);
                            $(".preloader").hide();

                            let html='<option value="" >None</option>';
                            $.each(ar.ledger, function(index, item) {
                                html +='<option value="'+item.id+'">' + item.head  +'</option >';
                            });
                            $("#ledger_head").html(html);


                        }});
                    $(".sp_check").show();
                }else if(v=='Cash'){
                    $.ajax({url: "../get-coa/"+v, success: function(result){
                            let ar = JSON.parse(result);
                            console.log(ar);
                            $(".preloader").hide();

                            let html='<option value="" >None</option>';
                            $.each(ar.ledger, function(index, item) {
                                html +='<option value="'+item.id+'">' + item.head  +'</option >';
                            });
                            $("#ledger_head").html(html);
                        }});
                    $('.chk_no').hide()
                    $(".sp_check").hide();
                }else{
                    $.ajax({url: "../get-payment-coa/"+v, success: function(result){
                            let ar = JSON.parse(result);
                            console.log(ar);
                            $(".preloader").hide();

                            let html='<option value="" >None</option>';
                            $.each(ar, function(index, item) {
                                html +='<option value="'+item.id+'">' + item.head  +'</option >';
                            });
                            $("#"+ref).html(html);
                        }});
                    // $('.chk_no').hide();
                }

            }
            return {
                loadLedger: loadLedger
            }

        }();


    </script>
@endsection
