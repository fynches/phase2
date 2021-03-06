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
                                <a href="{{url('/admin/country/create')}}" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-plus"></i> Add New</a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('errors.common_errors')
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="country_index">
                    <thead>
                        <tr>
                            <th style="width: 10px;">
                                <input type="checkbox" class="group-checkable" data-set="#beta_cms_index .checkboxes" /> 
                            </th>
                            <th style="width: 5px;"> Sr. No. </th>
                            <th style="width: 200px;"> Name </th>
                            <th style="width: 200px; text-align: center;"> Status </th>
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

{{Html::script("/assets/common/country/country.js")}}
<script>
    
        oTable = $('#country_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            sPaginationType: "full_numbers",
            "aaSorting": [[0, '']],
             "ajax":{
                    "url": "/admin/country/getData",
                    "dataType": "json",
                    "type": "post",
                    "data":{ _token: "{{csrf_token()}}"}
                },
            sDom: "<'row'<'col-lg-6  custtom_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'rownum', name: 'rownum'},
                {data: 'name', name: 'name'},
                {data: 'status', name: 'status'},
                {data: 'id', name: 'id'}
            ],
            aoColumnDefs: [
                {
                    bSearchable: false,
                    bSortable: false,
                    aTargets: [0,3]
                },
            ],
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                $('td:eq(4)', nRow).html(
                    
                        //'<a href="'+ window.base_url + '/admin/country/' + aData.id + '"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="'+ window.base_url + '/admin/country/' + aData.id + '/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>&nbsp &nbsp' +
                        '<a href="javascript:deleteCountry(' + aData.id + ');"><i class="fa icon-muted fa-times icon-space"></i></a>');
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
                $('td:eq(0)', nRow).html('<input type="checkbox" class="checkboxes data_remove" name="checkboxes[]" value="' + aData.id + '"/>');
                $('td:eq(1)', nRow).css('text-align','center');
                $('td:eq(3)', nRow).css('text-align','center');
                $('td:eq(4)', nRow).css('text-align','center');
                //$('td:eq(0)', nRow).html(iDisplayIndex + 1);
                //nRow.setAttribute('id', "row_" + aData.id);
            }
        });
         $("div.custtom_filter").html('<div class="dataTables_length custom_filter_header"><select id="filter-user-sort" class="input-sm form-control input-s-sm inline v-middle custom-filter-select"><option value="">Select</option><option value="1">Delete Multiple</option></select><button onclick="bulk_actions()" id="bulk_act" class="btn btn-sm btn-default apply-btn">Apply</button></div>');
        
        $(".group-checkable").change(function () {
            $(".data_remove").prop('checked', $(this).prop("checked"));
        });
</script>
@endsection