$(document).ready(function () {
        
    oTable = $('#users_index').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        bRetrieve: true,
        "aaSorting": [[0,'desc']],
        sPaginationType: "full_numbers",
        ajax: window.base_url + '/admin/user/list_admins',
        sDom: "<'row'<'col-lg-6 custtom_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'rownum', name: 'rownum'},
            {data: 'first_name', name: 'first_name'},
            {data: 'last_name', name: 'last_name'},
            {data: 'email', name: 'email'},
            {data: 'user_status', name: 'status'},
            {data: 'id', name: 'id'}
        ],
        aoColumnDefs: [
            {
                bSortable: false,
                aTargets: [0]
            },
            {
                bSortable: false,
                aTargets: [6]
            },
        ],
        fnCreatedRow: function (nRow, aData, iDataIndex) {
            $('td:eq(0)', nRow).html('<input type="checkbox" class="checkboxes data_remove" name="checkboxes[]" value="' + aData.id + '"/>');
            $('td:eq(1)', nRow).css('text-align','center');

            $('td:eq(6)', nRow).html(
                    '<a href="/admin/changepassword/password/' + aData.id + '/'+"2"+'" data-target="#ajax" data-toggle="modal" title="change-password"><i class="icon-lock"></i></a>&nbsp;&nbsp;' +
                    '<a href="'+ window.base_url + '/admin/user/show_admin_info/' + aData.id + '" title="show-userprofile"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>&nbsp &nbsp' +
                    '<a href="'+ window.base_url + '/admin/user/edit_admin/' + aData.id + '" title="edit-user"><i class="fa icon-muted fa-pencil icon-space"></i></a>&nbsp &nbsp' +
                    '<a href="javascript:deleteUser(' + aData.id + ');" title="delete-user"><i class="fa icon-muted fa-times icon-space"></i></a>');
            
           
            $('td:eq(6)', nRow).css('text-align','center');           
        }
    });

    $("div.custtom_filter").html('<div class="dataTables_length custom_filter_header"><select id="filter-user-sort" class="input-sm form-control input-s-sm inline v-middle custom-filter-select"><option value="">Select</option><option value="1">Delete Multiple</option></select><button onclick="bulk_actions()" id="bulk_act" class="btn btn-sm btn-default apply-btn">Apply</button></div>');
    
    $(".group-checkable").change(function () {
        $(".data_remove").prop('checked', $(this).prop("checked"));
    });
});

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        $.ajax({
            type: "GET",
            url: '/admin/user/delete/' + id, //resource
            success: function (affectedRows) {
                //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
                if (affectedRows > 0)
                {
                    window.location = 'admin_index';
                }
            }
        });
    }
}

function bulk_actions()          
{
    var bulk_value = $('#filter-user-sort').val();
    
    if(bulk_value == 1)
    {
        var n = $( "input.data_remove:checked" ).length;
        if(n == 0)
        {
            alert('You did not check any record');
            return false;
        }
        var selected_row_array = [];
        $("input.data_remove:checked").each(function()
        {
            selected_row_array.push($(this).val());
        });
        var confirm_flag = confirm("Are you sure you want to delete selected record?");
        if(confirm_flag === true)
        {
            $.ajax({
            type: "GET",
            url: 'delete_multiple_User/'+selected_row_array, 
            dataType: "html",
            success:function(result)
                {       
                    if(result == 1)
                    {      
                        window.location = 'user';              
                    }
                }
            });
        }
    }
    
}  
