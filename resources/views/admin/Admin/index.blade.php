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
                                <a href="{{url('/admin/user/admin_create')}}" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-plus"></i> Add New </a>
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

{{Html::script("/assets/common/admin/admin_index.js")}}

@endsection