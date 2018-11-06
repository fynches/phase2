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
                                <a href="{{url('/admin/state/create')}}" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-plus"></i> Add New</a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('errors.common_errors')
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="state_index">
                    <thead>
                        <tr>
                            <th style="width: 10px;">
                                <input type="checkbox" class="group-checkable" data-set="#beta_cms_index .checkboxes" /> 
                            </th>
                            <th style="width: 5px;"> Sr. No. </th>
                            <th style="width: 200px;"> State Name </th>
                            <th style="width: 200px;"> Country Name </th>
                            <th style="text-align:center;width: 200px;"> Status </th>
                            <th style="text-align:center;width: 200px;"> Actions </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>

{{Html::script("/assets/common/state/state.js")}}
    <script>
        $(document).ready(function () {
            oTable = $('#state_index').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                bRetrieve: true,
                "aaSorting": [[1,'desc']],
                sPaginationType: "full_numbers",
                "ajax":{
                    "url": "/admin/state/getData",
                    "dataType": "json",
                    "type": "post",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                sDom: "<'row'<'col-lg-6  custtom_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {
                        "data": "country.name", // can be null or undefined
                        "defaultContent": ""
                    },
                    {data: 'status', name: 'status'},
                    {data: 'id', name: 'id'}
                ],
                aoColumnDefs: [
                    {
                        bSearchable: false,
                        bSortable: false,
                        aTargets: [0,5]
                    },
                ],
                fnCreatedRow: function (nRow, aData, iDataIndex) {
                    $('td:eq(5)', nRow).html(

                        // '<a href="'+ window.base_url + '/admin/state/' + aData.id + '"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="'+ window.base_url + '/admin/state/' + aData.id + '/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="javascript:deleteState(' + aData.id + ');"><i class="fa icon-muted fa-times icon-space"></i></a>');
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex) {
                    $('td:eq(0)', nRow).html('<input type="checkbox" class="checkboxes data_remove" name="checkboxes[]" value="' + aData.id + '"/>');
                    $('td:eq(1)', nRow).css('text-align','center');
                    $('td:eq(5)', nRow).css('text-align','center');
                    $('td:eq(4)', nRow).css('text-align','center');
                    $('td:eq(1)', nRow).html(iDisplayIndex + aData.rownum);
                    nRow.setAttribute('id', "row_" + aData.id);

                }
            });
            oTable.on( 'order.dt search.dt', function () {
                oTable.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } );
            $("div.custtom_filter").html('<div class="dataTables_length custom_filter_header"><select id="filter-user-sort" class="input-sm form-control input-s-sm inline v-middle custom-filter-select"><option value="">Select</option><option value="1">Delete Multiple</option></select><button onclick="bulk_actions()" id="bulk_act" class="btn btn-sm btn-default apply-btn">Apply</button></div>');

            $(".group-checkable").change(function () {
                $(".data_remove").prop('checked', $(this).prop("checked"));
            });
        });
    </script>

@endsection