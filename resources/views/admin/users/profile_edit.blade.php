@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
    <link rel="stylesheet" href="{{asset('bower/select2/dist/css/select2.min.css')}}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit User Form</h5>
                        <div class="card-tools">

                        </div>
                    </div>
                    <form action="{{route('profile_update')}}" method="post" class="" enctype="multipart/form-data">
                        <input name="_method" type="hidden" value="PATCH">
                    <div class="card-body">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-md-4 col-sm-12" style="min-height: 200px;float: left;">
                                <label class="form-control-label up-image"><div class="container">
                                        @php $profile_image='images/defaults/user_profile_picture.png'; @endphp
                                        @if(isset($user->detail->picture)&&$user->detail->picture!='')
                                            @php $profile_image= 'storage/images/users/'.$user->detail->picture; @endphp
                                        @endif
                                        <img src="{{asset($profile_image)}}" class="image rounded" id="image_profile" class="image rounded"  style="width: auto; height: 150px;">
                                        <input type="file" name="profile_image" id="profile_image" accept="image/png, image/jpg, image/jpeg" style="display:none;">
                                        <div class="middle">
                                            <div class="text">Click To Change Picture</div>
                                        </div>
                                    </div></label><br>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <table class="table table-striped table-hover" style="font-size: 14px; margin-bottom:0px; width:100%;">
                                    <tr>
                                        <td style="padding-top: 5px; padding-bottom: 5px;">Email Address</td>
                                        <td style="padding-top: 5px; padding-bottom: 5px;"><b>{{$user->email ?? ''}}</b></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px; padding-bottom: 5px;">Name</td>
                                        <td style="padding-top: 5px; padding-bottom: 5px;"><b>{{$user->name ?? ''}}</b></td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 5px; padding-bottom: 5px;">Role</td>
                                        <td style="padding-top: 5px; padding-bottom: 5px;"><b>{{ucwords(str_replace('-',' ',$user->roles->first()->name)) ?? ''}}</b></td>
                                    </tr>
                                    @if(auth()->user()->can('edit-user-role'))
                                        <tr>
                                            <td style="padding-top: 5px; padding-bottom: 5px;" colspan="2">
                                                <select id="role" name="role" class="form-control select2" >
                                                    @foreach($roles as $role)
                                                        <option value="{{$role->id}}" @if($role->id==$user->roles->first()->id) selected @endif >{{ucwords(str_replace('-',' ',$role->name))}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-control-label">Phone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">+88</span>
                                    </div>
                                    <input type="text" pattern="\d*" maxlength="11" id="phone" name="phone" placeholder="Enter Your 11 Digit Phone Number like 017********" class="form-control" value="{{$user->detail->phone ?? ''}}">
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-control-label">Date of Birth</label>
                                <input type="text" name="dob" id="dob" readonly="readonly" class="form-control" value="{{$user->detail->dob ?? ''}}">
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-control-label">Gender</label>
                                <select id="gender" name="gender" class="form-control select2" >
                                    <option value="2" @if($user->detail->gender==2) selected @endif >Female</option>
                                    <option value="1" @if($user->detail->gender==1) selected @endif >Male</option>
                                    <option value="3" @if($user->detail->gender==3) selected @endif >Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-control-label">Address</label>
                                <textarea name="address" id="address" style="width: 100%;">{{$user->detail->address ?? ''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer col-md-12 text-right">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-dot-circle-o"></i> Update
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- .content -->

@endsection

@section('uncommonExJs')
    <script src="{{asset('bower/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
    <script src="{{asset('bower/select2/dist/js/select2.min.js')}}"></script>
@endsection

@section('uncommonInJs')
    <script>
        (function() {
            "use strict";
            $("#profile_image").change(function() {
                readURL(this,'image_profile');
            });
            var dob = new Pikaday({
                field: $('#dob')[0] ,
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
            // CKEDITOR.replace( 'address');
        })(jQuery);

        $(window).on('load',function(){
            $('.select2').select2();
        });

    </script>
@endsection
