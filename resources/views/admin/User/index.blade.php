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
                                <a href="{{url('/admin/user/create')}}" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-plus"></i> Add New </a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('errors.common_errors')
                <div class="modal fade popup_model" id="ajax" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <!-- <img src="../resources/assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                                <span> &nbsp;&nbsp;Loading... </span> -->
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="users_index">
                    <thead>
                        <tr>
                            <th style="width: 10px;">
                                <input type="checkbox" class="group-checkable" data-set="#beta_users_index .checkboxes" /> 
                            </th>
                            <th style="width: 80px;"> Sr. No. </th>
                            <th> Firstname </th>
                            <th> Lastname </th>
                            <th> Email </th>
                            <th> Status </th>
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

{{Html::script("/assets/common/user/user_index.js")}}
<script>
    $(document).ready(function () {

        var user_type='<?php echo Auth::user()->user_type;?>';

        oTable = $('#users_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            "aaSorting": [[0,'desc']],
            sPaginationType: "full_numbers",
            "ajax":{
                "url": "/admin/user/getData",
                "dataType": "json",
                "type": "post",
                "data":{ _token: "{{csrf_token()}}"}
            },
            sDom: "<'row'<'col-lg-6 custtom_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'rownum', name: 'id'},
                {data: 'first_name', name: 'first_name'},
                {data: 'last_name', name: 'last_name'},
                {data: 'email', name: 'email'},
                {data: 'user_status', name: 'user_status'},
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
                    // $('td:eq(7)', nRow).html('<a href="' + window.base_url + '/admin/user/' + aData.id + '/edit">
                    '<a href="/admin/changepassword/password/' + aData.id + '/'+"1"+'" data-target="#ajax" data-toggle="modal" title="change-password"><i class="icon-lock"></i></a>&nbsp;&nbsp;' +
                    '<a href="'+ window.base_url + '/admin/user/' + aData.id + '" title="show-userprofile"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>&nbsp &nbsp' +
                    '<a href="'+ window.base_url + '/admin/user/' + aData.id + '/edit" title="edit-user"><i class="fa icon-muted fa-pencil icon-space"></i></a>&nbsp &nbsp' +
                    '<a href="javascript:deleteUser(' + aData.id + ');" title="delete-user"><i class="fa icon-muted fa-times icon-space"></i></a>');


                $('td:eq(6)', nRow).css('text-align','center');
            }
        });

        $("div.custtom_filter").html('<div class="dataTables_length custom_filter_header"><select id="filter-user-sort" class="input-sm form-control input-s-sm inline v-middle custom-filter-select"><option value="">Select</option><option value="1">Delete Multiple</option></select><button onclick="bulk_actions()" id="bulk_act" class="btn btn-sm btn-default apply-btn">Apply</button></div>');

        $(".group-checkable").change(function () {
            $(".data_remove").prop('checked', $(this).prop("checked"));
        });
    });

</script>
@endsection