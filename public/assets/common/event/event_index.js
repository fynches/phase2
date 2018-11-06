function deleteEvent(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                type: "GET",
                dataType:'json',
                url: '/admin/event/delete/' + id, //resource
                success: function (affectedRows) {
                    //console.log(affectedRows);return false;
                    if(affectedRows.status == '0'){ 
                        window.location = 'event'; 
                    }else if(affectedRows.status == '1'){
                        window.location = 'event';
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
                url: 'delete_multiple_Event/'+selected_row_array, 
                dataType: "html",
                success:function(result)
                    {       
                        if(result == 1)
                        {      
                            window.location = 'event';              
                        }
                    }
                });
            }
        }
        
    } 
