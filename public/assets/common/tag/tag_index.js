 var html_addimage = $(".file_input_div").html();

 $(document).ready(function () {
	
  var old_img= $('#old_images').val();
  
  if(old_img!="" && old_img!=undefined)
  {
  	$('#tag').validate({
	        ignore: [],   
	        rules: {
	            tag_name: "required",
	            image: {extension: 'jpg|jpeg|bmp|png|gif'},
	        },
	        messages: {
	            tag_name: "Please enter tag.",
	            image: {extension: "Please upload valid image."},
	        },
	        errorPlacement: function(error, element) 
	        {
	            if (element.attr("name") == "image"){ 
	                error.insertAfter("#tag_img");
	            }else{
	                error.insertAfter(element);
	            }  
	        }
	    });
  }	else{
  	$('#tag').validate({
	        ignore: [],   
	        rules: {
	            tag_name: "required",
	            image: {required: true, extension: 'jpg|jpeg|bmp|png|gif'},
	        },
	        messages: {
	            tag_name: "Please enter tag.",
	            image: {required: "Please upload an image.", extension: "Please upload valid image."},
	        },
	        errorPlacement: function(error, element) 
	        {
	            if (element.attr("name") == "image"){ 
	                error.insertAfter("#tag_img");
	            }else{
	                error.insertAfter(element);
	            }  
	        }
	    });	
  }
        
    
        oTable = $('#tag_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            "aaSorting": [[0,'desc']],
            sPaginationType: "full_numbers",
            ajax: window.base_url + '/admin/tag/getData',
            sDom: "<'row'<'col-lg-6 custtom_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'rownum', name: 'rownum'},
                {data: 'tag_name', name: 'tag_name'},
                {data: 'image', name: 'image'},
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
                    aTargets: [5]
                }
            ],
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                
                var img="";
                if(aData.image!=null)
                {
                	img= window.base_url+'/storage/tagImages/'+aData.image;	
                }else{
                	img= window.base_url+'/storage/image-not-found.png';
                }
                     
                $('td:eq(1)', nRow).css('text-align','center');
                $('td:eq(0)', nRow).html('<input type="checkbox" class="checkboxes data_remove" name="checkboxes[]" value="' + aData.id + '"/>');
                $('td:eq(4)', nRow).css('text-align','center');
                $('td:eq(3)', nRow).html('<img src="'+img+'" width="80px" height="80px">');
                $('td:eq(3)', nRow).css('text-align','center');
				$('td:eq(5)', nRow).css('text-align','center');
                $('td:eq(5)', nRow).html(
                        '<a href="'+ window.base_url + '/admin/tag/' + aData.id + '/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="javascript:deleteTag(' + aData.id + ');"><i class="fa icon-muted fa-times icon-space"></i></a>');
               
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                 // $('td:eq(1)', nRow).html(iDisplayIndex + aData.rownum);
                 // nRow.setAttribute('id', "row_" + aData.id);
            }
        });
        $("div.custtom_filter").html('<div class="dataTables_length custom_filter_header"><select id="filter-user-sort" class="input-sm form-control input-s-sm inline v-middle custom-filter-select"><option value="">Select</option><option value="1">Delete Multiple</option></select><button onclick="bulk_actions()" id="bulk_act" class="btn btn-sm btn-default apply-btn">Apply</button></div>');
        
        $(".group-checkable").change(function () {
            $(".data_remove").prop('checked', $(this).prop("checked"));
        });
    });

    function deleteTag(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                type: "GET",
                url: '/admin/tag/delete/' + id, //resource
                success: function (affectedRows) {
                    //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
                    if (affectedRows > 0)
                    {
                        window.location = window.base_url + '/admin/tag';
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
                url: window.base_url + '/admin/delete_multiple_Tag/'+selected_row_array, 
                dataType: "html",
                success:function(result)
                    {       
                        if(result == 1)
                        {      
                           window.location = window.base_url + '/admin/tag';              
                        }
                    }
                });
            }
        }
        
    } 
