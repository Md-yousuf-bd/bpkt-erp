@php $footJquery=false; @endphp

@php $primaryColor="#ff470d;" @endphp
        <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('admin.layouts.commons.meta')
    @yield('uncommonMeta')

    <title>{{$page_name}} | {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @include('admin.layouts.commons.exCss')
    @yield('uncommonExCss')

    @include('admin.layouts.commons.inCss')
    @yield('uncommonInCss')

    @yield('headJS')



</head>
<body class="hidden-menu">

<!-- preloader -->
<div class="preloader">
    <img src="{{asset('images/logos/logo2.png')}}" alt="logo">
    <div class="preloader-icon"></div>
</div>
<!-- ./ preloader -->

@include('admin.layouts.commons.controlSidebar')
@include('admin.layouts.commons.sidebarMenu')

<div class="layout-wrapper">
    @include('admin.layouts.commons.topNav')

    <div class="content ">
        @include('admin.layouts.commons.contentHeader')
        @include('admin.layouts.commons.flash')
        @yield('content')

    </div>
    @include('admin.layouts.commons.footer')

</div>



{{--    </div>--}}

    <!-- Scripts -->
    @include('admin.layouts.commons.exJs')
    @yield('uncommonExJs')

    @include('admin.layouts.commons.inJs')
    @include('admin.layouts.commons.validationJs')
    @yield('uncommonInJs')
</body>
</html>
