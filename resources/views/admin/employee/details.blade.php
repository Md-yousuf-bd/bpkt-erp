@extends('admin.layouts.app')

@section('uncommonExCss')
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"> Employee Details</h5>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 13px; background: white; padding: 10px;  overflow: auto;">
                            <table  class="table table-bordered  table-striped" style="font-size: 14px; width:100%;">
                                <tbody>
                                <tr>
                                    <td style="width:200px !important;" colspan="3"> <strong>Basic Information</strong></td>


                                </tr>

                                <tr>
                                    <td style="width:200px !important;">Employee Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Employee No</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->employee_no }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Phone</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->phone }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">NID</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details-> nid }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Present Address</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->present_address }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Permanent Address</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->permanent_address }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Gender</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->gender }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">DOB</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->dob }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Blood Group</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->blood_grouo }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Father's Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->father_name }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Mother's Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->mother_name }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Spouse Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->spouse_name }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Email</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->email }}</td>
                                </tr>

                                <tr>
                                    <td style="width:200px !important;" colspan="3"><strong>Employment Information</strong></td>

                                </tr>

                               <tr>
                                    <td style="width:200px !important;">Designation</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->designation }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Rank</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->rank_name }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Department</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->dept_name }}</td>
                                </tr><tr>
                                    <td style="width:200px !important;">Branch Name</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->branch_name }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Function</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->task }}</td>
                                </tr>
                                <tr>
                                    <td style="width:200px !important;">Job Status</td>
                                    <td style="width:10px !important;">:</td>
                                    <td>{{ $details->job_status }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .content -->

@endsection

@section('uncommonExJs')
    @include('admin.layouts.commons.dataTableJs')
@endsection

@section('uncommonInJs')

@endsection
