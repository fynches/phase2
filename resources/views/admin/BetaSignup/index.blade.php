@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group pull-right">
                                <a href="{{url('/admin/export_csv')}}" id="sample_editable_1_new" class="btn sbold green"> Export User
                                    <i class="fa fa-file-excel-o"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('errors.common_errors')
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="beta_users_index">
                    <thead>
                        <tr>
                            <th style="width: 10px;">
                                <input type="checkbox" class="group-checkable" data-set="#beta_users_index .checkboxes" /> 
                            </th> 
                            <th style="width: 80px;"> Sr. No. </th>
                            <th> FirstName </th>
                            <th> Email </th>
                            <th> Created Date </th>
                            <th style="text-align:center"> Actions </th>
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
        oTable = $('#beta_users_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            aoColumnDefs: [
                {
                    bSortable: false,
                    aTargets: [0]
                },
                {
                    bSortable: false,
                    aTargets: [5]
                },
            ],
            "aaSorting": [[1,'desc']],
            sPaginationType: "full_numbers",
            "ajax":{
                "url": "/admin/user/getBetaData",
                "dataType": "json",
                "type": "post",
                "data":{ _token: "{{csrf_token()}}"}
            },
            sDom: "<'row'<'col-lg-6 custtom_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'rownum', name: 'id'},
                {data: 'first_name', name: 'first_name'},
                {data: 'email', name: 'email'},
                {data: 'created_date', name: 'created_date'},
                {
                    data: 'id',
                    render: function(data, meta ) {

                        return '<a href="javascript:deleteUser(' + data + ');"><i class="fa icon-muted fa-times icon-space"></i></a>';
                    }
                }

            ],

            fnCreatedRow: function (nRow, aData, iDataIndex) {
                $('td:eq(0)', nRow).html('<input type="checkbox" class="checkboxes data_remove" name="checkboxes[]" value="' + aData.id + '"/>');
                $('td:eq(1)', nRow).css('text-align','center');

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
                url: 'delete_betaUser/' + id, 
                success: function (affectedRows) {
                    //if something was deleted, we redirect the user to the users page, and automatically the user that he deleted will disappear
                    if (affectedRows > 0)
                    {
                        window.location = 'betaSignup';
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
            url: 'delete_multiple_betaUser/'+selected_row_array, 
            dataType: "html",
            success:function(result)
                {       
                    if(result == 1)
                    {      
                        window.location = 'betaSignup';              
                    }
                }
            });
        }
    }
    
}    
</script>

<style>

</style>
@endsection
