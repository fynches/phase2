@extends('layouts.admin')
@section('content')
<!-- BEGIN MAIN CONTENT -->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a href="{{url('/admin/' . Request::segment(2) . '/create')}}" id="sample_editable_1_new" class="btn sbold green"> Add New
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="staticblock_index">
                    <thead>
                        <tr>
                        	<th style="width: 10px;">
                                <input type="checkbox" class="group-checkable" data-set="#beta_experience_index .checkboxes" /> 
                            </th>
                            <th style="width: 80px;"> Sr. No. </th>
                            <th> Title </th>
                            <th> Slug </th>
                            <th> Created at </th>
                            <th style="text-align: center;"> Action </th>
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
   
</script>
{{Html::script("/assets/common/staticblock/staticblock_index.js")}}
@endsection