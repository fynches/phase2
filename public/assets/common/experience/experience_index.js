 $(document).ready(function () {
        
        var event_id= $('#event_id').val();
        var event_status= $('#event_status').val();
        
        oTable = $('#experience_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            "aaSorting": [[1,'desc']],
            sPaginationType: "full_numbers",
            //ajax: '{{ url('/experience/getData/') }}',
            ajax: window.base_url + '/admin/experience/getData/'+event_id,
            sDom: "<'row'<'col-lg-6 custtom_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'id', name: 'id'},
                {data: 'exp_name', name: 'title'},
                {data: 'events.title'},
                {data: 'gift_needed', name: 'gift_needed'},
                {data: 'gift_needed', name: 'gift_needed'},
                {data: 'gift_needed', name: 'gift_needed'},
                {data: 'status', name: 'status'},
                {data: 'id', name: 'id'},
            ],
            aoColumnDefs: [
                {
                    bSortable: false,
                    aTargets: [0]
                },
                {
                    bSortable: false,
                    aTargets: [8]
                }
            ],
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                     
                $('td:eq(1)', nRow).css('text-align','center');
                $('td:eq(0)', nRow).html('<input type="checkbox" class="checkboxes data_remove" name="checkboxes[]" value="' + aData.id + '"/>');
                
                $('td:eq(5)', nRow).html('0');
                $('td:eq(6)', nRow).html('0');
				
				if(event_status =="1")
				{
					$('td:eq(8)', nRow).html(
                        '<a href="'+ window.base_url + '/admin/experience/' + aData.id + '"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="'+ window.base_url + '/admin/experience/' + aData.id + '/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="javascript:deleteExperience(' + aData.id + ');"><i class="fa icon-muted fa-times icon-space"></i></a>');
				}else{
					$('td:eq(8)', nRow).html(
                        '<a href="'+ window.base_url + '/admin/experience/' + aData.id + '"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="javascript:deleteExperience(' + aData.id + ');"><i class="fa icon-muted fa-times icon-space"></i></a>');
				}
                
                $('td:eq(8)', nRow).css('text-align','center');
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                 $('td:eq(1)', nRow).html(iDisplayIndex + aData.rownum);
                 nRow.setAttribute('id', "row_" + aData.id);
            }
        });
        $("div.custtom_filter").html('<div class="dataTables_length custom_filter_header"><select id="filter-user-sort" class="input-sm form-control input-s-sm inline v-middle custom-filter-select"><option value="">Select</option><option value="1">Delete Multiple</option></select><button onclick="bulk_actions()" id="bulk_act" class="btn btn-sm btn-default apply-btn">Apply</button></div>');
        
        $(".group-checkable").change(function () {
            $(".data_remove").prop('checked', $(this).prop("checked"));
        });
    });

    function deleteExperience(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                type: "GET",
                url: '/admin/experience/delete/' + id, //resource
                success: function (affectedRows) {
                    //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
                    if (affectedRows > 0)
                    {
                    	var url      = window.location.href;     
						window.location = url;
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
                url: 'delete_multiple_Experience/'+selected_row_array, 
                dataType: "html",
                success:function(result)
                    {       
                        if(result == 1)
                        {      
                            var url      = window.location.href;     
							window.location = url;         
                        }
                    }
                });
            }
        }
        
    } 
