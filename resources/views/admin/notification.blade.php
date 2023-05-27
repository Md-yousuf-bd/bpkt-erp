@extends('admin.layouts.app')

@section('uncommonExCss')
    <link rel="stylesheet" type="text/css" href="{{asset('bower/pikaday/pikaday.css')}}">
    @include('admin.layouts.commons.dataTableCss')
@endsection

@section('content')
    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12 ">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Notifications</strong>
                            <form action="{{route('filtered_notifications')}}" method="POST" class="pull-right" id="notification_search_form">
                                {{csrf_field()}}
                                &nbsp;&nbsp;<span>From</span>&nbsp;&nbsp;<input type="text" name="from" id="from" class="from-control" value="{{$from}}">
                                &nbsp;&nbsp;<span>To</span>&nbsp;&nbsp;<input type="text" name="to" id="to" class="from-control" value="{{$to}}">
                                &nbsp;&nbsp;<span>User</span>&nbsp;&nbsp;
                                <select class="select2" name="user_id" style="min-width: 200px;">
                                    @role(['Normal'])
                                    <option value="{{Auth::user()->id}}">{{Auth::user()->name}}</option>
                                    @endrole
                                    @role(['Developer','Admin'])
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}" @if($user->id==$user_id) selected @endif >{{$user->name}}</option>
                                        @endforeach
                                    @endrole
                                </select>
                                &nbsp;&nbsp;<button type="submit" class="btn btn-sm btn-success">Submit</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="notificationTable" class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                        <th>Actor</th>
                                        <th style="width: 23%;">Updated At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $i=0; @endphp
                                    @foreach($notifications as $notification)
                                        @php $i++; @endphp
                                        <tr class="table-{{$notification->type}}" style="@if($notification->status==0) font-weight:bold; @endif">
                                            <td>{{$i}}</td>
                                            <td>
                                                {{$notification->description ?? ''}}
                                            </td>
                                            <td>{{$notification->action ?? ''}}</td>
                                            <td>{{$notification->user->name ?? ''}}</td>
                                            <td>{{date('d-M-Y h:i:s A', strtotime($notification->updated_at))}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- .content -->
@endsection

@section('uncommonExJs')
    <script src="{{asset('bower/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('bower/pikaday/pikaday.js')}}"></script>
    @include('admin.layouts.commons.dataTableJs')
@endsection

@section('uncommonInJs')
    <script>
        (function() {
            "use strict";
            var thisConfig={
                aoColumns: [null, null, null, null, null],
                fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    //debugger;
                    var index = iDisplayIndexFull + 1;
                    $("td:first", nRow).html(index);
                    return nRow;
                },
                buttons: [
                    {
                        extend: 'print',
                        title:'{{ config('app.name', 'Laravel') }}: All Notifications',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'excel',
                        title:'{{ config('app.name', 'Laravel') }}: All Notifications',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'pdf',
                        title:'{{ config('app.name', 'Laravel') }}: All Notifications',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'copy',
                        title:'{{ config('app.name', 'Laravel') }}: All Notifications',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    {
                        extend: 'csv',
                        title:'{{ config('app.name', 'Laravel') }}: All Notifications',
                        footer: true,
                        exportOptions: {
                            columns: ':visible'
                        }

                    },
                    'colvis'
                ]
            };
            var config=$.extend({}, configBasic, thisConfig);
            var notificationTable= $('#notificationTable').DataTable(config);
            var from = new Pikaday({
                field: $('#from')[0] ,
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

            var to = new Pikaday({
                field: $('#to')[0] ,
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

        })(jQuery);
    </script>
@endsection
