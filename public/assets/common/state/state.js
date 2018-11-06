$(document).ready(function () {
    $("#state").validate({
        ignore: [],
        highlight: function (element) {
            $(element).parent('div').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).parent('div').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        errorElement: 'div',
        rules: {
            name: {required: true},
            country: {required: true}
        },
        messages: {
            name: {required: "Please enter state name."},
            country: {required: "Please select country."}
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        success: function (element) {
            $(element).parent('.form-group').removeClass('has-error');
        },
    });
});

function deleteState(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                type: "GET",
                url: '/admin/state/delete/' + id, //resource
                success: function (affectedRows) {
                    //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
                    if (affectedRows > 0)
                    {
                        window.location = 'state';
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
                url: 'delete_multiple_State/'+selected_row_array, 
                dataType: "html",
                success:function(result)
                    {       
                        if(result == 1)
                        {      
                            window.location = 'state';              
                        }
                    }
                });
            }
        }
        
    }  
