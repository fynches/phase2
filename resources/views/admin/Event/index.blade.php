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
                                <a href="{{url('/admin/event/create')}}" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-plus"></i> Add New</a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('errors.common_errors')
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="events_index">
                    <thead>
                        <tr>
                            <th style="width: 10px;">
                                <input type="checkbox" class="group-checkable" data-set="#beta_events_index .checkboxes" /> 
                            </th>
                            <th style="width: 80px;"> Sr. No. </th>
                            <th> Title </th>
                            <th style="width: 160px;"> User </th>
                            <th style="width: 80px;"> Status </th>
                            <th style="text-align:center;width: 250px;"> Actions </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
{{Html::script("/assets/common/event/event_index.js")}}
    <script>
        $(document).ready(function () {
            oTable = $('#events_index').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                bRetrieve: true,
                "aaSorting": [[0,'desc']],
                sPaginationType: "full_numbers",
                "ajax":{
                    "url": "/admin/event/getData",
                    "dataType": "json",
                    "type": "post",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                sDom: "<'row'<'col-lg-6 custtom_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'title', name: 'title'},
                    {
                        data: 'user',
                        render: function (data, type, row) {
                            return row.first_name +' '+ row.last_name;

                        }
                    },
                    {data: 'status', name: 'status'},
                    {
                        data: 'id',
                        render: function(data, meta ) {
                            return '<a target="_blank" href="'+window.base_url+'/admin/event/experience/' + data+ '" class="btn btn-sm blue"><i class="fa fa-file-o"></i> Experience </a>' +
                                '<a href="'+window.base_url+'/admin/event/' + data + '"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>' +
                                '<a href="'+window.base_url+'/admin/event/' + data.id + '/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>'+
                                '<a href="javascript:deleteEvent(' + data.id + ');"><i class="fa icon-muted fa-times icon-space"></i></a>';
                        }
                    }
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
                    if(aData.status == '1'){
                        $('td:eq(4)', nRow).html("Active");
                    }else if(aData.status == '2'){
                        $('td:eq(4)', nRow).html("Completed");
                    }else if(aData.status == '3'){
                        $('td:eq(4)', nRow).html("Closed");
                    }else if(aData.status == '4'){
                        $('td:eq(4)', nRow).html("Draft");
                    }

                    if(aData.users == null || aData.users.first_name=='undefined'){
                        $('td:eq(3)', nRow).html("-");
                    }

                    $('td:eq(5)', nRow).html(
                        '<a target="_blank" href="'+window.base_url+'/admin/event/experience/' + aData.id + '" class="btn btn-sm blue"><i class="fa fa-file-o"></i> Experience </a>'+
                        '<a href="'+window.base_url+'/admin/event/' + aData.id + '"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="'+window.base_url+'/admin/event/' + aData.id + '/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="javascript:deleteEvent(' + aData.id + ');"><i class="fa icon-muted fa-times icon-space"></i></a>');
                    $('td:eq(5)', nRow).css('text-align','center');
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex) {
                    $('td:eq(1)', nRow).html(iDisplayIndex + aData.rownum);
                    nRow.setAttribute('id', "row_" + aData.id);
                }
            });
                oTable.on( 'order.dt search.dt', function () {
                oTable.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            });
            $("div.custtom_filter").html('<div class="dataTables_length custom_filter_header"><select id="filter-user-sort" class="input-sm form-control input-s-sm inline v-middle custom-filter-select"><option value="">Select</option><option value="1">Delete Multiple</option></select><button onclick="bulk_actions()" id="bulk_act" class="btn btn-sm btn-default apply-btn">Apply</button></div>');

            $(".group-checkable").change(function () {
                $(".data_remove").prop('checked', $(this).prop("checked"));
            });
        });
    </script>
@endsection