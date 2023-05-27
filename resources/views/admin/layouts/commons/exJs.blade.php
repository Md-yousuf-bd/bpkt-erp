@if($footJquery)
    <script src="{{asset('js/jquery/dist/jquery.min.js')}}"></script>
{{--<script src="{{asset('js/jquery_3.4.1/dist/jquery.min.js')}}"></script>--}}

@endif

{{--<script src="{{asset('bower/select2/dist/js/select2.full.js')}}"></script>--}}

{{--<!-- REQUIRED SCRIPTS -->--}}
{{--<!-- jQuery -->--}}
{{--<script src="plugins/jquery/jquery.min.js"></script>--}}
{{--<!-- Bootstrap -->--}}
{{--<script src="{{asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
{{--<!-- overlayScrollbars -->--}}
{{--<script src="{{asset('template/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>--}}
{{--<!-- AdminLTE App -->--}}
{{--<script src="{{asset('template/dist/js/adminlte.js')}}"></script>--}}
{{--<script src="{{asset('bower/alertifyjs/alertify.js')}}"></script>--}}

<!-- Websocket -->
{{--<script>--}}
{{--    window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT",6001)}}';--}}
{{--</script>--}}
{{--<script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT',6001)}}/socket.io/socket.io.js"></script>--}}
{{--<script src="{{ url('/js/laravel-echo-setup.js') }}" type="text/javascript"></script>--}}

<!-- Bundle scripts -->
<script src="{{asset('admin/libs/bundle.js')}}"></script>

<!-- Apex chart -->
<script src="{{asset('admin/libs/charts/apex/apexcharts.min.js')}}"></script>

<!-- Slick -->
<script src="{{asset('admin/libs/slick/slick.min.js')}}"></script>

<!-- Examples -->
<script src="{{asset('admin/dist/js/examples/dashboard.js')}}"></script>

<!-- Main Javascript file -->
<script src="{{asset('admin/dist/js/app.min.js')}}"></script>

<script src="{{asset('bower/select2/dist/js/select2.full.js')}}"></script>
