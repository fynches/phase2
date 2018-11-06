@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row"> 
                        <div class="col-md-6">

                            <div class="btn-group">
                                <a href="{{url('/admin/cms/create')}}" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-plus"></i> Add New</a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('errors.common_errors')
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="cms_index">
                    <thead>
                        <tr>
                            <th style="width: 10px;">
                                <input type="checkbox" class="group-checkable" data-set="#beta_cms_index .checkboxes" /> 
                            </th>
                            <th style="width: 80px;"> Sr. No. </th>
                            <th> Title </th>
                            <th style="width: 120px;"> Slug </th>
                            <th style="width: 120px;"> Status </th>
                            <th style="text-align:center;width: 120px;"> Actions </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<script>
    $(document).ready(function () {
        //var age_range = <?php //echo $age_range; ?>;
        oTable = $('#cms_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            "aaSorting": [[0,'desc']],
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/cms/getData') }}',
            sDom: "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'rownum', name: 'id'},
                {data: 'title', name: 'title'},
                {data: 'slug', name: 'slug'},
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
                     
                $('td:eq(1)', nRow).css('text-align','center');
                $('td:eq(0)', nRow).html('<input type="checkbox" class="checkboxes data_remove" name="checkboxes[]" value="' + aData.id + '"/>');
                 

                $('td:eq(5)', nRow).html(
                        '<a href="<?php echo url("/admin/cms") ?>/' + aData.id + '"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="<?php echo url("/admin/cms") ?>/' + aData.id + '/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>');
                        //'<a href="javascript:deleteCMS(' + aData.id + ');"><i class="fa icon-muted fa-times icon-space"></i></a>');
                $('td:eq(5)', nRow).css('text-align','center');
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                $('td:eq(1)', nRow).html(iDisplayIndex + aData.rownum);
                nRow.setAttribute('id', "row_" + aData.id);
            }
        });
        //$("div.custtom_filter").html('<div class="dataTables_length custom_filter_header"><select id="filter-user-sort" class="input-sm form-control input-s-sm inline v-middle custom-filter-select"><option value="">Select</option><option value="1">Delete Multiple</option></select><button onclick="bulk_actions()" id="bulk_act" class="btn btn-sm btn-default apply-btn">Apply</button></div>');
        
        $(".group-checkable").change(function () {
            $(".data_remove").prop('checked', $(this).prop("checked"));
        });
    });

    function deleteCMS(id) {
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                type: "GET",
                url: '/admin/cms/delete/' + id, //resource
                success: function (affectedRows) {
                    //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
                    if (affectedRows > 0)
                    {
                        window.location = 'cms';
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
                url: '/admin/delete_multiple_Cms/'+selected_row_array, 
                dataType: "html",
                success:function(result)
                    {       
                        if(result == 1)
                        {      
                            window.location = 'cms';              
                        }
                    }
                });
            }
        }
        
    } 
</script>
@endsection