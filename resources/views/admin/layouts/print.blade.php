@php $footJquery=false; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="no-js">
<head>
    @include('admin.layouts.commons.meta')
    @yield('uncommonMeta')

    <title>{{ config('app.name', 'STORE') }}</title>

    <!-- Styles -->
    @include('admin.layouts.commons.exCss')
    @yield('uncommonExCss')

    @include('admin.layouts.commons.inCss')
    @yield('uncommonInCss')

    @yield('headJS')



</head>
<body>

    @yield('content')

    @include('admin.layouts.commons.exJs')
    @yield('uncommonExJs')
    @include('admin.layouts.commons.inJs')
    @yield('uncommonInJs')
</body>
</html>
