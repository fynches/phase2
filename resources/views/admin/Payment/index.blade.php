@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <!-- <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a href="{{url('/admin/' . Request::segment(2) . '/create')}}" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-plus"></i> Add New</a>
                            </div>
                        </div>
                    </div>
                </div> -->
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="payment_index">
                    <thead>
                        <tr>
                            <th style="width: 10px;">
                                <input type="checkbox" class="group-checkable" data-set="#payment_index .checkboxes" /> 
                            </th>
                            <th style="width: 80px;"> Sr. No. </th>
                            <th> Event Name </th>
                            <th> Experience Name </th>
                            <th style="text-align: center;"> User Name </th>
                            <th style="text-align: center;"> Additional Amount </th>
                            <th style="text-align: center;"> Annoymas </th>
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

{{Html::script("/assets/common/payment/payment.js")}}

@endsection