<div class="modal fade" id="userPermissionModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largeModalLabel">Assign User Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="user_id" name="user_id">
                <div class="row">
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="role_permissions"><span id="role_name"></span> Role Permissions</label>
                        <div id="role_permissions" style="width:100%; text-align: justify; padding: 5px; border: 1px solid darkgrey;"></div>
                    </div>
                </div>
               <div class="row">
                   <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                       <label for="permissions">Assign individual Permissions</label>
                       <select class="form-control select2" name="permissions[]" id="permissions" multiple></select>
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning float-right" onclick="changePermissions()" >Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    function show_user_permission_modal(id) {
        clear_user_permission_modal();
        set_user_permission_modal(id);
    }

    function set_user_permission_modal(id,show=true) {
        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        let route = '{{route('settings.user.get_permission_modal')}}';
        $.ajax({
            url: route,
            data: {
                id: id,
                _token: csrfToken
            },
            type: 'POST',
            headers : {
                'Accept': 'application/json',
            },
            dataType: 'JSON',
            success: function (response) {
                if (response) {
                    console.log(response);
                    $('#role_name').html(response.role);
                    $('#role_permissions').html(response.role_permissions+'.');
                    $('#user_id').val(id);
                    let opt_html='';
                    for(let i=0; i<response.opt_permissions.length; i++){
                        opt_html+='<option value="'+response.opt_permissions[i].id+'" '+response.opt_permissions[i].selected+'>'+response.opt_permissions[i].name+'</option>';
                    }
                    $('#permissions').html(opt_html);
                    if(show){
                        $('#userPermissionModal').modal('show');
                    }
                    setTimeout(function () {
                        $('.select2').select2();
                    },300)
                }
            }
        });
    }

    function clear_user_permission_modal(){
        $('#userPermissionModal #permissions').val('');
        $('#userPermissionModal #role_permissions').val('');
        $('#userPermissionModal #user_id').val('');
        $('#userPermissionModal #role_name').html('');
    }

    function changePermissions() {
        let userId=$('#user_id').val();
        let permissions=$('#permissions').val();
        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        let route = '{{route('settings.user.permissions_change')}}';
        $.ajax({
            url: route,
            data: {
                user_id: userId,
                permissions: permissions,
                _token: csrfToken
            },
            type: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            dataType: 'JSON',
            success: function (response) {
                console.log(response);
                if(response)
                {
                    userTable.ajax.reload();
                    set_user_permission_modal(userId,false);
                    alertify.notify('User permissions has been updated successfully.', 'success', 5, function(){  });
                }
                else
                {
                    set_user_permission_modal(userId,false);
                    alertify.notify('Failed to change User Permissions', 'error', 5, function(){  });
                }
            }
        });
    }
</script>
