@extends('layouts.admin')
@section('content')
<?php

	$received_amount="0";
	$remaining_amount="";
	
	//pr($funding_report['funding_report']);die;
	if(count($funding_report['funding_report']) >0)
	{
		foreach($funding_report['funding_report'] as $val)
		{
			$received_amount += $val['donated_amount'];
		}
		 $received_amount = $received_amount;
		 $remaining_amount = ($experience->gift_needed - $received_amount);
		 
		 if($experience->gift_needed < $received_amount)
		 {
		 	$remaining_amount = $received_amount - $experience->gift_needed.' (Amount is more then gift needed)';
		 }
	}
	
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
	        	 <p class="form-control-static" style="display: none;" id="actual_gift_needed">{{$experience->gift_needed }}</p>
	            <p class="form-control-static" id="total_amt_remaining">{{$remaining_amount}}</p>
	        </div>
	    </div>
	    
	    <div class="line line-dashed line-lg pull-in"></div>
	    
    </form>
</div>

<div class="portlet light">
	
	<div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">Experience Details</span>
        </div>
    </div>

    {!! Form::model($experience,array('route' => array('experience.update', $experience->id),'class'=>'form-horizontal','method'=>'PUT','id'=>'experience','files'=>true)) !!}
   
    <div class="form-group">
        <div class=" col-md-6">
            {!! Form::label('title', 'Title'); !!}
            {!! Form::text('exp_name',$experience->exp_name,array('class'=>'form-control')) !!}
        </div> 
        
        <div class='col-md-6'>
            {!! Form::label('gift_needed', 'Gift Needed'); !!}
            {!! Form::text('gift_needed', $experience->gift_needed, array('class' => 'form-control number')) !!}
        </div>
    </div>
    
    
    
	<div class="form-group">
        <div class="col-md-6">
            {!! Form::label('image', 'Image'); !!}
        </div>
        
    </div>

    <div class="form-group">
        <div class="col-md-4">
            <div class="image-upload">                
                <?php
                    $defaultPath = config('constant.imageNotFound');
                    
					$user_profile_image = $experience['image'];
		            
                    if ($user_profile_image && $user_profile_image != "") {
                        
                       $imgPath = 'storage/experienceImages/' . $user_profile_image;
                       
                        if (file_exists($imgPath))
                        {
                            $imgPath = $imgPath;
                        } else {
                            $imgPath = $defaultPath;
                        }
                    } else {
                        $imgPath = $defaultPath;
                    }
                    ?>
                <div class="fileinput fileinput-exists image_sec" data-provides="fileinput">
                    <div class="fileinput-preview thumbnail"  style="width: 200px; height: 150px;">
                        <a rel="gallery" class="fancybox" href="{{ asset($imgPath) }}" target='_blank'>
                            <img src="{{ asset($imgPath) }}"  alt="" />
                        </a>
                    </div>
                    <div id="exp_img">
                        <span class="btn red btn-outline btn-file">
                            <span class="fileinput-new"> Select image </span>
                            <span class="fileinput-exists"> Change </span>
                            <input type="file" name="image" value="<?php echo $user_profile_image; ?>" id="image" onchange='readURL(this);'>
                            <input type="hidden" name="old_images" value="<?php echo $user_profile_image; ?>" id="old_images">
                        </span>
                    </div>
                </div>                    
            </div>
        </div>
     </div> 
     
     <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('description', 'Description'); !!}
            {!! Form::textarea('description', $experience->description, array('class'=>'ckeditor form-control', 'rows' => '6', 'cols' => '30','id'=>'description')) !!}
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
        	<input type="hidden" id="event_id" name="event_id" value="{{$experience->event_id}}">
            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/event/experience/') }}/{{$experience->event_id}} ">Cancel</a>
        </div>
    </div>           
    {!! Form::close() !!}
</div>
 
 {{Html::script("/assets/common/experience/experience_edit.js")}}
@endsection
