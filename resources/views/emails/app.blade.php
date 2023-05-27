@php $footJquery=false; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{asset('images/logos/logo2.png')}}">
    <meta name="description" content="{{ config('app.name', 'Laravel') }} Admin.">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="{{ config('app.name', 'Laravel') }} Admin." />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @yield('content')
</body>
</html>
