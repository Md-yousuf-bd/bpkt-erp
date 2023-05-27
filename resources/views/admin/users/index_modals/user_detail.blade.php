<div class="modal fade" id="userDetailModal" tabindex="-1" role="dialog" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="largeModalLabel">User Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-hover" style="font-size: 14px;">
                    <tr>
                        <td id="td_image_role" rowspan="4" class="text-center" style="width: 40%;">
                        </td>
                        <td>Name</td>
                        <td id="td_name">
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td id="td_email">
                        </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td id="td_phone">
                        </td>
                    </tr>
                </table>
                <table class="table table-striped table-hover" style="font-size: 14px;">
                    <tr>
                        <td>Date of Birth</td>
                        <td id="td_dob">
                        </td>
                        <td>Age</td>
                        <td id="td_age">
                        </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td colspan="3" id="td_address">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function show_user_detail_modal(id) {
        clear_user_detail_modal();
        set_user_detail_modal(id);
    }

    function set_user_detail_modal(id,show=true) {
        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        let route = '{{route('settings.user.get_detail_modal')}}';
        $.ajax({
            url: route,
            data: {
                id: id,
                _token:csrfToken
            },
            type: 'POST',
            headers : {
                'Accept': 'application/json',
            },
            dataType: 'JSON',
            success: function (response) {
                if (response) {
                    // console.log(response);
                    let image='<img src="'+response.picture+'" id="image_profile" class="image rounded" style="width: auto; height: 130px; cursor:pointer;"><br><br>';
                    let role=response.role_html;
                    $('#userDetailModal #td_image_role').html(image+role);
                    if(response.select){
                        $('.select2').select2();
                    }
                    $('#userDetailModal #td_name').html(response.user.name);
                    $('#userDetailModal #td_email').html(response.user.email);
                    $('#userDetailModal #td_phone').html(response.user.detail.phone);
                    $('#userDetailModal #td_address').html(response.user.detail.address);
                    $('#userDetailModal #td_dob').html(response.dob);
                    $('#userDetailModal #td_age').html(response.age);
                    if(show){
                        $('#userDetailModal').modal('show');
                    }
                }
            }
        });
    }

    function clear_user_detail_modal(){
        $('#userDetailModal #td_image_role').html('');
        $('#userDetailModal #td_name').html('');
        $('#userDetailModal #td_email').html('');
        $('#userDetailModal #td_phone').html('');
        $('#userDetailModal #td_dob').html('');
        $('#userDetailModal #td_age').html('');
        $('#userDetailModal #td_address').html('');
    }

    function changeRole(userId,roleId) {
        var csrfToken= $('meta[name="csrf-token"]').attr('content');
        let route = '{{route('settings.user.role_change')}}';
        $.ajax({
            url: route,
            data: {
                user_id: userId,
                role_id: roleId,
                 _token: csrfToken
            },
            type: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            dataType: 'JSON',
            success: function (response) {
                if(response)
                {
                    userTable.ajax.reload();
                    alertify.notify('User Role has been changed successfully.', 'success', 5, function(){  });
                }
                else
                {
                    set_user_detail_modal(userId,false);
                    alertify.notify('Failed to change User Role', 'error', 5, function(){  });
                }
            }
        });
    }
</script>
