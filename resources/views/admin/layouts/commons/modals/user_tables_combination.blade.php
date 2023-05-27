<style>
    .utcm-row{
        cursor: pointer;
        font-weight: bold;
    }
</style>
<div class="modal fade" id="userTablesCombinationModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="largeModalLabel" style="font-size: 12px !important;"> <span id="utcmTableName"></span> টেবিল এর Column Combination পরিবর্তন করুন।</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="utcm_table_id" value="">
                <input type="hidden" id="utcm_default_combination" value="">
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <label style="margin-left: 10px;">Display</label>
                        <div class="col-sm-12">
                            <div class="table-responsive" style="overflow: auto; height: 350px; border:1px solid grey;">
                                <table class="table table-bordered table-hover table-sm" style="width:100%; max-height: 90%;" id="utcmDisplay">
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="text-center" style="padding-top: 10px;">
                                <button class="btn btn-dark btn-sm" id="utcm-at" title="Take Selected at Top"><i class="fa fa-caret-square-up"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button class="btn btn-dark btn-sm" id="utcm-ou" title="Take Selected one Up"><i class="fa fa-angle-up"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button class="btn btn-dark btn-sm" id="utcm-od" title="Take Selected one Down"><i class="fa fa-angle-down"></i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button class="btn btn-dark btn-sm" id="utcm-ab" title="Take Selected at Bottom"><i class="fa fa-caret-square-down"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center" style="padding-top: 12%;">
                        <button class="btn btn-dark btn-sm" id="utcm-dnda" title="Do Not Display All"><i class="fa fa-caret-square-right"></i></button><br><br>
                        <button class="btn btn-dark btn-sm" id="utcm-dnds" title="Do Not Display Selected"><i class="fa fa-angle-right"></i></button><br><br>
                        <button class="btn btn-dark btn-sm" id="utcm-ds" title="Display Selected"><i class="fa fa-angle-left"></i></button><br><br>
                        <button class="btn btn-dark btn-sm" id="utcm-da" title="Display All"><i class="fa fa-caret-square-left"></i></button>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <label style="margin-left: 10px;">Do Not Display</label>
                        <div class="col-sm-12">
                            <div class="table-responsive" style="overflow: auto; height: 350px; border:1px solid grey;">
                                <table class="table table-bordered table-hover table-sm" style="width:100%; max-height: 90%;" id="utcmDoNotDisplay">
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div style="padding-top: 10px;">
                                <button style="margin-left: 5px;" class="btn btn-success btn-sm float-right" id="utcm-save">Save</button>
                                <button style="margin-left: 5px;" class="btn btn-danger btn-sm float-right" id="utcm-reset">Reset</button>
                                <button style="margin-left: 5px;" class="btn btn-dark btn-sm float-right" id="utcm-close" data-dismiss="modal" aria-label="Close">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    // var combs;
    $(document).keydown(function(event){
        if(event.which=="17")
            cntrlIsPressed = true;
    });

    $(document).keyup(function(){
        cntrlIsPressed = false;
    });

    var cntrlIsPressed = false;

    $('#utcm-at').on('click',function () {
        let tableTbodyRows = document.querySelectorAll('#utcmDisplay tbody .selected');
        for(let i=tableTbodyRows.length-1; i>=0; i--){
            $('#utcmDisplay tbody').prepend(tableTbodyRows[i]);
        }
    });
    $('#utcm-ou').on('click',function () {
        let tableTbodyRows = document.querySelectorAll('#utcmDisplay tbody .selected');
        let pre=tableTbodyRows[0].previousSibling;
        if(pre){
            for(let i=0; i<tableTbodyRows.length; i++){
                tableTbodyRows[i].parentNode.insertBefore(tableTbodyRows[i],pre);
            }
        }
    });
    $('#utcm-od').on('click',function () {
        let tableTbodyRows = document.querySelectorAll('#utcmDisplay tbody .selected');
        let next=tableTbodyRows[tableTbodyRows.length-1].nextSibling;
        if(next){
            for(let i=0; i<tableTbodyRows.length; i++){
                next.parentNode.insertBefore(tableTbodyRows[i], next.nextSibling);
            }
        }
    });
    $('#utcm-ab').on('click',function () {
        let tableTbodyRows = document.querySelectorAll('#utcmDisplay tbody .selected');
        for(let i=0; i<tableTbodyRows.length; i++){
            $('#utcmDisplay tbody').append(tableTbodyRows[i]);
        }
    });
    $('#utcm-da').on('click',function () {
        let tableTbodyRows = document.querySelectorAll('#utcmDoNotDisplay tbody .utcm-nodisplay');
        for(let i=0; i<tableTbodyRows.length; i++){
            $('#utcmDisplay tbody').append(tableTbodyRows[i]);
        }
        tableTbodyRows = document.querySelectorAll('#utcmDisplay tbody .utcm-nodisplay');
        for(let i=0; i<tableTbodyRows.length; i++){
            tableTbodyRows[i].classList.add("utcm-display");
            tableTbodyRows[i].classList.remove("utcm-nodisplay");
        }
    });
    $('#utcm-ds').on('click',function () {
        let tableTbodyRows = document.querySelectorAll('#utcmDoNotDisplay tbody .selected');
        for(let i=0; i<tableTbodyRows.length; i++){
            $('#utcmDisplay tbody').append(tableTbodyRows[i]);
        }
        tableTbodyRows = document.querySelectorAll('#utcmDisplay tbody .utcm-nodisplay');
        for(let i=0; i<tableTbodyRows.length; i++){
            tableTbodyRows[i].classList.add("utcm-display");
            tableTbodyRows[i].classList.remove("utcm-nodisplay");
        }
    });
    $('#utcm-dnds').on('click',function () {
        let tableTbodyRows = document.querySelectorAll('#utcmDisplay tbody .selected');
        for(let i=0; i<tableTbodyRows.length; i++){
            $('#utcmDoNotDisplay tbody').append(tableTbodyRows[i]);
        }
        tableTbodyRows = document.querySelectorAll('#utcmDoNotDisplay tbody .utcm-display');
        for(let i=0; i<tableTbodyRows.length; i++){
            tableTbodyRows[i].classList.add("utcm-nodisplay");
            tableTbodyRows[i].classList.remove("utcm-display");
        }
    });
    $('#utcm-dnda').on('click',function () {
        let tableTbodyRows = document.querySelectorAll('#utcmDisplay tbody .utcm-display');
        for(let i=0; i<tableTbodyRows.length; i++){
            $('#utcmDoNotDisplay tbody').append(tableTbodyRows[i]);
        }
        tableTbodyRows = document.querySelectorAll('#utcmDoNotDisplay tbody .utcm-display');
        for(let i=0; i<tableTbodyRows.length; i++){
            tableTbodyRows[i].classList.add("utcm-nodisplay");
            tableTbodyRows[i].classList.remove("utcm-display");
        }
    });
    $('#utcm-reset').on('click',function () {
        $('#userTablesCombinationModal').modal('hide');

        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        let route = '{{route('settings.user_tables_combination.setCombination')}}';
        $.ajax({
            url: route,
            data: {
                url: window.location.href,
                table_id: $('#utcm_table_id').val(),
                combination_str: $('#utcm_default_combination').val(),
                _token: csrfToken
            },
            type: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            dataType: 'JSON',
            success: function (response) {
                if(response){
                    window.location.reload();
                }
            }
        });
    });
    $('#utcm-save').on('click',function () {
        let tableTbodyRows = document.querySelectorAll('#utcmDisplay tbody tr');
        let combinations=[];
        for(let i=0; i<tableTbodyRows.length; i++){
            combinations[i]=tableTbodyRows[i].dataset.combination;
        }
        let combination=combinations.join();

        $('#userTablesCombinationModal').modal('hide');

        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        let route = '{{route('settings.user_tables_combination.setCombination')}}';
        $.ajax({
            url: route,
            data: {
                url: window.location.href,
                table_id: $('#utcm_table_id').val(),
                combination_str: combination,
                _token: csrfToken
            },
            type: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            dataType: 'JSON',
            success: function (response) {
                if(response){
                    window.location.reload();
                }
            }
        });
    });


    function utcmRowSelectToogle(e) {
        if(!cntrlIsPressed){
            if( $(e).hasClass('utcm-display')){
                $('.utcm-display').removeClass('selected table-active');
                $(e).addClass('selected table-active');
            }
            else if($(e).hasClass('utcm-nodisplay')){
                $('.utcm-nodisplay').removeClass('selected table-active');
                $(e).addClass('selected table-active');
            }
        }
        else{
            if( $(e).hasClass('selected')){
                $(e).removeClass('selected table-active');
            }
            else{
                $(e).addClass('selected table-active');
            }
        }
    }

    function utcm_make_rows(columnNames,combination,display=true){
        let disClass='';
        if(display){
            disClass='utcm-display';
        }
        else{
            disClass='utcm-nodisplay';
        }
        let html='';
        for(let i=0; i<combination.length; i++){
            html+='<tr class="utcm-row '+disClass+'" data-combination="'+combination[i]+'" onclick="utcmRowSelectToogle(this)"><td>'+columnNames[combination[i]]+'</td></tr>';
        }
        return html;
    }

    function get_set_combination(table,table_id,columnNames){
        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        let route = '{{route('settings.user_tables_combination.getCombination')}}';
        $.ajax({
            url: route,
            data: {
                url: window.location.href,
                table_id: table_id,
                _token: csrfToken
            },
            type: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            dataType: 'JSON',
            success: function (response) {
                if(response.combination){
                    let combination_str=response.combination;
                    let columnOrder=[];
                    for(let i=0; i<columnNames.length;i++){
                        columnOrder[i]=i;
                    }
                    let combinations= combination_str.split(',');
                    for(let i=0; i<combinations.length; i++){
                        combinations[i]=parseInt(combinations[i]);
                    }
                    let doNotDisplay=[];
                    for(let i=0; i<columnOrder.length; i++){
                        if(!combinations.includes(columnOrder[i])){
                            doNotDisplay.push(columnOrder[i]);
                            combinations.push(columnOrder[i]);
                        }
                    }
                    table.colReorder.order(combinations);
                    // combs=combinations;
                    let noDisplay=[];
                    for(let i=combinations.length-1; i>=(combinations.length-doNotDisplay.length); i--){
                        noDisplay.push(i);
                    }
                    table.columns(noDisplay).visible(false, false);
                    table.columns.adjust();
                }
            }
        });
    }


    function showUserTablesCombinationModal(columnNames,tableName,tableId) {
        let columnOrder=[];
        for(let i=0; i<columnNames.length;i++){
            columnOrder[i]=i;
        }
        $('#utcmTableName').html(tableName);
        $('#utcm_table_id').val(tableId);
        $('#utcm_default_combination').val(columnOrder.join(','));
        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        let route = '{{route('settings.user_tables_combination.getCombination')}}';
        $.ajax({
            url: route,
            data: {
                url: window.location.href,
                table_id: tableId,
                _token: csrfToken
            },
            type: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            dataType: 'JSON',
            success: function (response) {
                if(response.combination){
                    let combination_str=response.combination;
                    let savedOrder=combination_str.split(',');
                    for(let i=0; i<savedOrder.length; i++){
                        savedOrder[i]=parseInt(savedOrder[i]);
                    }
                    $('#utcmDisplay tbody').html(utcm_make_rows(columnNames,savedOrder));
                    let notDisplayOrder=[];
                    for(let i=0; i<columnOrder.length; i++){
                        if(!savedOrder.includes(columnOrder[i])){
                            notDisplayOrder.push(columnOrder[i]);
                        }
                    }
                    $('#utcmDoNotDisplay tbody').html(utcm_make_rows(columnNames,notDisplayOrder,false));
                }
                else{
                    $('#utcmDisplay tbody').html(utcm_make_rows(columnNames,columnOrder));
                }
                $('#userTablesCombinationModal').modal('show');
            }
        });
    }
</script>
