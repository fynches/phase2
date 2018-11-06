@extends('layouts.admin')
@section('content')
{{Html::script("/assets/common/experience/experience_create.js")}}

<?php

	$received_amount="0";
	$remaining_amount="0";
?>
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
	            <p class="form-control-static"> {{$event[0]->title}}  </p>
	        </div>
	    </div>
	
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">User:</label>
	        <div class="col-lg-10">
	            <p class="form-control-static">{{$event[0]->users->first_name.' '.$event[0]->users->last_name}}</p>
	        </div>
	    </div>
	    
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Event Publish Date:</label>
	        <div class="col-lg-10">
	            <p class="form-control-static">{{$event[0]->event_publish_date}} </p>
	        </div>
	    </div>
	
	   
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Amount Received :</label>
	        <div class="col-lg-10">
	            <p class="form-control-static" id="actual_received_amt">{{$received_amount}}</p>
	        </div>
	    </div>
	    
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Amount Remaining :</label>
	        <div class="col-lg-10">
	        	
	            <p class="form-control-static" id="total_amt_remaining">{{$remaining_amount}}</p>
	        </div>
	    </div>
	    
	    <div class="line line-dashed line-lg pull-in"></div>
	    
    </form>
</div>

<div class="portlet light">

    {!! Form::open(array('url'=>'/admin/experience','files'=>true,  'class'=>'form-horizontal','method'=>'POST','id'=>'experience')) !!}

    <div class="form-group">
        <div class=" col-md-6">
            {!! Form::label('title', 'Title'); !!}
            {!! Form::text('exp_name',null,array('class'=>'form-control')) !!}
        </div> 
        
        <div class='col-md-6'>
            {!! Form::label('gift_needed', 'Gift Needed ($)'); !!}
            {!! Form::text('gift_needed', null, array('class' => 'form-control number')) !!} 
       </div>
    </div>

    
    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('image', 'Image'); !!}
        </div>
    </div>
    
    <?php $defaultPath = config('constant.imageNotFound');?>
   
    <div class="form-group">
        <div class="col-md-4">
            <div class="fileinput fileinput-new image_sec" data-provides="fileinput">
                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                    <img width="200" height="150" src="{{ asset($defaultPath) }}" class="img-circles">
                </div>
                <div id="exp_img">
                    <span class="btn default btn-file">
                        <span class="fileinput-new" data-trigger="fileinput"> Select </span>
                        <span class="fileinput-exists" data-trigger="fileinput"> Change </span>
                        {!! Form::file('image',array('class'=>'form-control','id'=>'image')) !!}
                    </span>
                   
                </div>
            </div>
        </div>
    </div>  
    
    <div class="form-group">
        <div class='col-md-12'> 
            {!! Form::label('description', 'Description'); !!}
            {!! Form::textarea('description',null,array('class'=>'ckeditor form-control', 'rows' => '6', 'cols' => '30','id'=>'description')) !!}
        </div>
    </div> 
         
    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('status', 'Status'); !!}
            {!! Form::select('status', array('In progress' => 'In progress','Fully funded' => 'Fully funded'), null, array('class' => 'form-control')) !!}
        </div>
    </div> 
      
     
    <div class="form-group">
        <div class="col-md-12">
        	<input type="hidden" id="event_id" name="event_id" value="{{$event_id}}">
            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/event/experience/') }}/{{$event_id}} ">Cancel</a>
        </div>
    </div>           
    {!! Form::close() !!}

</div>  
@endsection