@php $footJquery=false; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="no-js">
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
<body style="font-size: 13px;">
{{--    <div id="app">--}}

{{--        @include('admin.layouts.commons.sidebarMenu')--}}
        <!-- Right Panel -->

            <div id="right-panel" class="right-panel">
{{--             @include('admin.layouts.commons.topNav')--}}
{{--             @include('admin.layouts.commons.breadcumbs')--}}
{{--             @include('admin.layouts.commons.flash')--}}
             @yield('content')
            </div>



{{--    </div>--}}

    <!-- Scripts -->
    @include('admin.layouts.commons.exJs')
    @yield('uncommonExJs')

    @include('admin.layouts.commons.inJs')
    @yield('uncommonInJs')
</body>
</html>
