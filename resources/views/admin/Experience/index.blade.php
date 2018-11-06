@extends('layouts.admin')
@section('content')

<div class="portlet light">
    <div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">Event Details</span>
        </div>
    </div>
    <form class="form-horizontal">
	   <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Event Name:</label>
	        <div class="col-lg-10">
	            <p class="form-control-static"> {{$events[0]->title}}  </p>
	        </div>
	    </div>
	
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">User:</label>
	        <div class="col-lg-10">
	            <p class="form-control-static">{{$events[0]->users->first_name.' '.$events[0]->users->last_name}}</p>
	        </div>
	    </div>
	    
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Event Publish Date:</label>
	        <div class="col-lg-10">
	            <p class="form-control-static">{{$events[0]->event_publish_date}} </p>
	        </div>
	    </div>
	
	   
	    <div class="line line-dashed line-lg pull-in"></div>
	    
    </form>
</div>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="table-toolbar">
                	<?php 
                		if($events[0]->status=="1" )
                		{
                	?>
                    <div class="row"> 
                        <div class="col-md-6">
					       <div class="btn-group">
                                <a href="{{url('/admin/experience/create/')}}<?php echo '/'.$event_id;?>" id="sample_editable_1_new" class="btn sbold green"> <i class="fa fa-plus"></i> Add New</a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                @include('errors.common_errors')
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="experience_index">
                    <thead>
                        <tr>
                            <th style="width: 10px;">
                                <input type="checkbox" class="group-checkable" data-set="#beta_experience_index .checkboxes" /> 
                            </th>
                            <th style="width: 80px;"> Sr. No. </th>
                            <th> Title </th>
                            <th> Event Name </th>
                            <th> Gift Needed </th>
                            <th> Amount Received </th>
                            <th> Amount Remaining </th>
                            <th> Status </th>
                            <th style="text-align:center"> Actions </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <input type="hidden" id="event_id" name="event_id" value="{{$event_id}}">
                    <input type="hidden" id="event_status" name="event_status" value="{{$events[0]->status}}">
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>

{{Html::script("/assets/common/experience/experience_index.js")}}
@endsection