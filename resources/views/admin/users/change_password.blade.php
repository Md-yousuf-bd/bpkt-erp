@extends('admin.layouts.app')

@section('uncommonExCss')

@endsection

@section('content')
    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-10 col-sm-10 col-xs-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <strong>Change Password</strong> Form
                        </div>
                        <form action="{{route('update_password')}}" method="post" id="password_form" class="">
                            <input name="_method" type="hidden" value="PATCH">
                            <input name="id" type="hidden" value="{{$user->id}}">
                            <div class="card-body card-block">
                                {{ csrf_field() }}
                                <div class="form-group col-md-12 col-sm-12">
                                    <label class="form-control-label">Old Password</label>
                                    <input type="password" id="old_password" name="old_password" placeholder="Enter Old Password" class="form-control">
                                </div>
                                <div class="form-group col-md-12 col-sm-12">
                                    <label class="form-control-label">New Password</label>
                                    <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" class="form-control">
                                </div>
                                <div class="form-group col-md-12 col-sm-12">
                                    <label class="form-control-label">Confirm Password</label>
                                    <input type="password" id="confirm_password"  placeholder="Enter New Password Again" class="form-control">
                                    <span class="text-danger" style="font-size: 12px; font-weight: bold" id="confirm_error"></span>
                                </div>
                            </div>
                            <div class="card-footer col-md-12">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- .content -->
@endsection

@section('uncommonExJs')

@endsection

@section('uncommonInJs')
   <script>
       $('#new_password').on('keyup',function () {
           $('#confirm_error').html('');
       });
       $('#confirm_password').on('keyup',function () {
           $('#confirm_error').html('');
       });

       $("#password_form").submit(function (e) {

           if($('#new_password').val()===$('#confirm_password').val())
           {
               $('#password_form').submit();
           }
           else
           {
               $('#confirm_error').html('Password is not Matching.');
               $('#new_password').val('');
               $('#confirm_password').val('');
           }
           e.preventDefault();
       });
   </script>
@endsection
