<div class="modal fade" id="excelmodal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{route("register_excel")}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="largeModalLabel">Register with Excel file. <small class="text-danger">(Only *.xlsx file is Supported )</small></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group text-center col-md-8 col-xs-12 mx-auto">
                        <label><b>Upload File Here</b></label>
                        <input type="file" required class="form-control" style="height: 40px;" name="excel_file" id="excel_file" accept=".xlsx">
                    </div>
                    <div class="col-md-4 col-xs-12 pull-right">
                        <button type="submit" class="btn btn-success" style="margin-top: 30px;">Submit</button>
                        <button type="button" class="btn btn-secondary" style="margin-top: 30px;" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
