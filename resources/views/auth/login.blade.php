<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login -  {{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/logos/logo2.png')}}">

    <!-- Themify icons -->
    <link rel="stylesheet" href="{{asset('admin/dist/icons/themify-icons/themify-icons.css')}}" type="text/css">

    <!-- Main style file -->
    <link rel="stylesheet" href="{{asset('admin/dist/css/app.min.css')}}" type="text/css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="auth">

<!-- begin::preloader-->
<div class="preloader">
    <div class="preloader-icon"></div>
</div>
<!-- end::preloader -->


<div class="form-wrapper">
    <div class="container">
        <div class="card">
            <div class="row g-0">
                <div class="col">
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            <div class="d-block d-lg-none text-center text-lg-start">
                                <img width="200" src="{{asset('images/logos/logo2.png')}}" alt="logo">
                            </div>
                            <div class="my-5 text-center text-lg-start">
                                <h1 class="display-8">Sign In</h1>
                                <p class="text-muted">Sign in to  {{ config('app.name', 'Laravel') }} to continue</p>
                            </div>
                            @include('admin.layouts.commons.flash')
                            <form class="mb-5" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <input id="login" type="text" class="form-control {{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Username or Email') }}"
                                           name="login" value="{{ old('username') ?: old('email') }}" required autofocus>
                                    @if ($errors->has('username') || $errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div>
                                <span class="focus-input100 text-danger">
                                    @if ($errors->has('username') || $errors->has('email'))
                                          <strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
                                      @endif
                                </span>
                                </div>
                                <div class="text-center text-lg-start">
                                    <button type="submit" class="btn btn-primary">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col d-none d-lg-flex border-start align-items-center justify-content-between flex-column text-center">
                    <div class="logo">
                        <img width="180" src="{{asset('images/logos/logo2.png')}}" alt="logo">
                    </div>
                    <div class="logo">
                        <img width="200" src="{{asset('admin/assets/images/admin-settings-male.png')}}" alt="Admin" >
                    </div>
                    <div>
                        <h3 class="fw-bold">Welcome to {{ config('app.name', 'Laravel') }}!</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Bundle scripts -->
<script src="{{asset('admin/libs/bundle.js')}}"></script>

<!-- Main Javascript file -->
<script src="{{asset('admin/dist/js/app.min.js')}}"></script>

<script>
    $('.btn-close').on('click',function (e){
        this.closest('.toast').remove();
    });
</script>
</body>
</html>
