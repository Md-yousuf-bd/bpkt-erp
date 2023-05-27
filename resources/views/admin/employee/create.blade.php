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
                        <h5 class="card-title" style="font-weight: 700;font-size: 19px;">Basic Info
                        </h5>
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-item nav-link active basicInfo" onclick="showTabPage('basic');" data-toggle="tab" >Basic Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link employeeInfo" onclick="showTabPage('regulatory');" data-toggle="tab" >Employment Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bankInfo" onclick="showTabPage('bank');" data-toggle="tab" >Bank Info</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link taxVat"  onclick="showTabPage('tax-vat');" data-toggle="tab" >Payment Info</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link supplyInfo" onclick="showTabPage('supply');" data-toggle="tab" >Others Info</a>
                            </li>
                        </ul>


                        <div class="card-tools">
                        </div>
                    </div>
                    <form id="addVendor" action="{{route('employee.store')}}" method="post" class="">
                        <div class="card-body card-block">
                            {{ csrf_field() }}
                            <input type="hidden"  id="rank_name" name="rank_name">
                            <input type="hidden"  id="branch_name" name="branch_name">
                            <input type="hidden"  id="dept_name" name="dept_name">
                            <span id="basic">


                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="shop_name">Employee Name</label>
                                    <input type="text" class="form-control" name="name" id="name" required>

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="owner_address">Employee No</label>
                                    <input type="text" class="form-control" name="employee_no" id="employee_no" >

                                </div>
                            </div>

                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="phone">Mobile No</label>
                                    <input type="text" class="form-control" name="phone" id="phone" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="owner_contact">E-Mail ID</label>
                                <input type="text" class="form-control" name="email" id="email" >

                            </div>
                            </div>
                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="nid"> National ID</label>
                                    <input type="text" class="form-control" name="nid" id="nid" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="gender">Gender
                                    </label>
                                     <select class="form-control select2" name="gender" id="gender">
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>

                                </div>
                            </div>

                            <div class="row">


                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="dob">DOB</label>
                                    <input type="text" class="form-control" name="dob" id="dob" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="blood_group">Blood Group</label>
                                    <input type="text" class="form-control" name="blood_group" id="blood_group" >
                                </div>
                            </div>
                                 <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="father_name">Present Address</label>
                                 <input type="text" class="form-control" name="present_address" id="present_address" >

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="permanent_address">Permanent Address</label>
                                    <input type="text" class="form-control" name="permanent_address" id="permanent_address" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="father_name">Father's Name</label>
                                 <input type="text" class="form-control" name="father_name" id="father_name" >

                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="mother_name">Mother's Name</label>
                                    <input type="text" class="form-control" name="mother_name" id="mother_name" >
                                </div>
                            </div>

                            <div class="row">
                            <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="spouse_name">Spouse Name</label>
                                    <input type="text" class="form-control" name="spouse_name" id="spouse_name" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="type">Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                        <option value="1">Active</option>
                                        <option value="2">In-Active</option>
                                    </select>

                                </div>

                            </div>
</span>
                            <span id="bank" style="display: none">



{{--                            <div class="row">--}}
{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--                                    <label for="payment_method">Mode of Payment</label>--}}
{{--                                    <select class="form-control select2" name="payment_method" id="payment_method">--}}
{{--                                        <option value="">Select</option>--}}
{{--                                        <option value="EFT">EFT</option>--}}
{{--                                        <option value="Cheque">Cheque</option>--}}
{{--                                        <option value="Cash">Cash</option>--}}
{{--                                    </select>--}}

{{--                                </div>--}}
{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--                                    <label for="bank_account_title">Bank Account Title</label>--}}
{{--                                    <input type="text" class="form-control" name="bank_account_title" id="bank_account_title" >--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row">--}}



{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--                                    <label for="bank_name">Bank Name</label>--}}
{{--                                    <input type="text" class="form-control" name="bank_name" id="bank_name" >--}}
{{--                                </div>--}}
{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--                                    <label for="branch_name">Branch Name</label>--}}
{{--                                    <input type="text" class="form-control" name="branch_name" id="account_no" >--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="row">--}}
{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--                                    <label for="account_no">Account Number</label>--}}
{{--                                    <input type="text" class="form-control" name="account_no" id="account_no" >--}}
{{--                                </div>--}}

{{--                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">--}}
{{--                                    <label for="routing_number">Routing Number</label>--}}
{{--                                    <input type="text" class="form-control" name="routing_number" id="routing_number" >--}}
{{--                                </div>--}}

{{--                            </div>--}}
                                </span>
                            <span id="regulatory" style="display: none;">



                            <div class="row">

                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="designation">Designation</label>
                                    <input type="text" class="form-control" name="designation" id="designation" >
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <label for="rank">Rank</label>
                               <select class="form-control select2" name="rank" id="rank">
                                        <option value="">Select</option>
                                       @foreach($rank as $row)
                                           <option value="{{$row->id}}"> {{ $row->name }}</option>
                                   @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <label for="department">Department</label>
                                    <select class="form-control select2" name="department" id="department">
                                        <option value="">Select</option>
                                          @foreach($department as $row)
                                            <option value="{{$row->id}}"> {{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="branch_id">Branch Name</label>
<select class="form-control select2" name="branch_id" id="branch_id">
                                        <option value="">Select</option>
                                          @foreach($branch as $row)
        <option value="{{$row->id}}"> {{ $row->name }}</option>
    @endforeach
                                    </select>                                    </div>
                            </div>




                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="task">Function</label>
                                        <input type="text" class="form-control" name="task" id="task" >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="job_status">Job Status</label>
                                            <select class="form-control select2" name="job_status" id="job_status">
                                        <option value="">Select</option>
                                        <option value="Permanent">Permanent</option>
                                        <option value="Probationary">Probationary</option>
                                        <option value="Hired">Hired</option>
                                        <option value="Contractual">Contractual</option>
                                    </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="joining_date">Date of Joining</label>
                                        <input type="text" class="form-control" name="joining_date" id="joining_date" >
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="confirmation_date">Confirmation Date</label>
                                        <input type="text" class="form-control" name="confirmation_date" id="confirmation_date" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <label for="resignation_date">Resignation Date</label>
                                        <input type="text" class="form-control" name="resignation_date" id="resignation_date" >
                                    </div>

                                </div>

                                </span>
                            <span id="supply" style="display: none;">






                        </span>
                        <div class="card-footer" style="margin-top: 20px;">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button id="btnEmpSubmit"  type="button" class="btn btn-sm btn-success float-right" onclick="Employee.addEmployee()">Submit</button>
                                    <button type="reset" class="btn btn-sm btn-default" onclick="resetForm('addVendor','Add Owner Info','Do you really want to reset this form?');return false;">Reset</button>
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
        (function() {
            "use strict";
            jqiueryCalendar('confirmation_date');
            jqiueryCalendar('joining_date');
            jqiueryCalendar('resignation_date');
            jqiueryCalendar('dob');
        })(jQuery);
        function filterKeyNumber(ref_v) {
                if (((event.which != 46 || (event.which == 46 && $(ref_v).val() == '')) ||
                    $(ref_v).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
        }

        let Employee = function (){


            let addEmployee = function () {
                if($("#name").val()==''){
                    alert("Please Enter Name");
                    return false;
                }
                if($("#employee_no").val()==''){
                    alert("Please Select Employee No");
                    return false;
                }
                $("#rank_name").val($("#rank").find('option:selected').text());
                $("#branch_name").val($("#branch_id").find('option:selected').text());
                $("#dept_name").val($("#department").find('option:selected').text());

                $("#btnEmpSubmit").attr('type', 'submit');
            }


            return {
                addEmployee:addEmployee
            }

        }();

        function jqiueryCalendar (ref_id) {
           return new Pikaday({
                field: $('#'+ref_id)[0] ,
                firstDay: 1,
                format: 'YYYY-MM-DD',
                toString: function (date, format) {
                    var day   = date.getDate();
                    var month = date.getMonth() + 1;
                    var year  = date.getFullYear();
                    var yyyy = year;
                    var mm   = ((month > 9) ? '' : '0') + month;
                    var dd   = ((day > 9)   ? '' : '0') + day;

                    return yyyy + '-' + mm + '-' + dd;
                },
                position: 'bottom right',
                minDate: new Date('1900-01-01'),
                maxDate: new Date('2040-12-31'),
                yearRange: [1900, 2040]
            });
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
        function showTabPage(ref) {

            if(ref=='basic'){
                $(".card-title").html('Basic Info');
                $("#basic").show();
                $("#regulatory").hide();
                $("#bank").hide();
                $("#tax-vat").hide();
                $("#supply").hide();
                $(".nav li a").removeClass('active');
                $(".basicInfo").addClass('active');

            }else if(ref == 'regulatory'){
                $(".card-title").html('Employment Info');
                $("#basic").hide();
                $("#regulatory").show();
                $("#bank").hide();
                $("#tax-vat").hide();
                $("#supply").hide();
                $(".nav li a").removeClass('active');
                $(".employeeInfo").addClass('active');

            }else if(ref == 'bank'){
                $(".card-title").html('Bank Information');
                $("#basic").hide();
                $("#regulatory").hide();
                $("#bank").show();
                $("#tax-vat").hide();
                $("#supply").hide();
                $(".nav li a").removeClass('active');
                $(".bankInfo").addClass('active');
            }else if(ref == 'tax-vat'){
                $(".card-title").html('Tax-Vat Information');
                $("#basic").hide();
                $("#regulatory").hide();
                $("#bank").hide();
                $("#tax-vat").show();
                $("#supply").hide();
                $(".nav li a").removeClass('active');
                $(".taxVat").addClass('active');
            }else if(ref == 'supply'){
                $(".card-title").html('Supply/Product Info');
                $("#basic").hide();
                $("#regulatory").hide();
                $("#bank").hide();
                $("#tax-vat").hide();
                $("#supply").show();
                $(".nav li a").removeClass('active');
                $(".supplyInfo").addClass('active');
            }

        }


        function checkServiceType(val) {
            if(val=='Product'){
                $(".pro").show();
                $(".ser").hide();
            }else{
                $(".pro").hide();
                $(".ser").show();
            }

        }


    </script>
@endsection
