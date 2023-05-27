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
                        <h5 class="card-title">Product Info List</h5>
                        <div class="card-tools">
                            @if(auth()->user()->can('create-product'))
                                <a href="{{route('product.create')}}" class="btn btn-sm btn-default pull-right"><span class="fa fa-plus-circle"></span> Add Product Info</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="product_table_responsive" style="font-size: 13px; background: white;   overflow: auto;">
                            <table id="productTbl" class="table table-bordered table-hover table-striped" >
                                <thead>
                                <tr>
                                    <th>S.L.</th>
                                    <th style="width:215px !important;">Action</th>

                                    <th>Product id</th>
                                    <th>Product Name</th>
                                    <th>Vendor Name</th>
                                    <th>Brand Name</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Update By</th>
                                    <th >@lang('commons/table_header.Last Updated At')</th>
                                </tr>
                                </thead>
                                <tbody>

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
    <script>
        $(document).ready(function () {
            // btnSearch = $('.btn-save');
            // $('#product_table_responsive').width($(window).width()*0.90);
            listView({data:{},_token: "{{csrf_token()}}"});
            {{--btnSearch.click(function (e) {--}}
                {{--var data = $("#myForm").serializeArray();--}}
                {{--console.log(data)--}}
                {{--$('#shopProductTable').DataTable().destroy();--}}
                {{--listView({data,_token: "{{csrf_token()}}"});--}}

            {{--});--}}
            function listView(data) {
                $('#productTbl').DataTable({
                    "processing": true,
                    "serverSide": true,
                    // destroy: true,
                    "searching": true,
                    fixedHeader: true,
                    "ajax":{
                        "url": '{!! route('product.list') !!}',
                        "dataType": "json",
                        "type": "POST",
                        "data": data
                    },
                    "sScrollY" : "400",
                    "sScrollX" : true,
                    oLanguage: {sProcessing: "<div  id='loader' style='background: black;padding:10px;color:#fff; font-size:15px;z-index: 5999999 !important;' > Loading.... </div>"},
                    "columns": [
                        { "data": "id", "orderable":false},
                        {"data":"options","orderable":false},
                        { "data": "product_id","orderable":false },
                        { "data": "product_name","orderable":false },
                        { "data": "vendor_name","orderable":false },
                        { "data": "brand_name","orderable":false },
                        { "data": "size" ,"orderable":false},
                        { "data": "status","orderable":false },
                        { "data": "updated_user","orderable":false },
                        { "data": "updated_at" ,"orderable":false},

                    ],
                    "dom": 'lBfrtip',

                    buttons: [
                        'excel',
                        'csv',
                        'pdf',
                        'print'
                    ],
                    scrollX: true,


                });
            }


        });


    </script>

@endsection
