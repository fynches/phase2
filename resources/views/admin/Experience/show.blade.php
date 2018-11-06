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
    <form class="form-horizontal">
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Title :</label>
	        <div class="col-lg-10">
	            <p class="form-control-static"><?php echo $experience['exp_name']; ?></p>
	        </div>
	    </div>
	    	<?php
			
	        $defaultPath = 'storage/no-img.jpg';
	        $profileImage = $experience['image'];
	
	        if ($profileImage && $profileImage != "") {
	            
	            $imgPath = 'storage/experienceImages/thumb/' . $profileImage;
	           
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
	    
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Image :</label>
	        <div class="col-lg-10">
	            <p class="form-control-static"><img class="img-circle" alt="Admin" src="{{ asset($imgPath) }}"  id="image_upload_preview" width="100px" height="100px"/></p>
	        </div>
	    </div>
	
	    
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Description:</label>
	        <div class="col-lg-10">
	            <p class="form-control-static"> {!!html_entity_decode($experience['description'])!!} </p>
	        </div>
	    </div>
	
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Gift Needed:</label>
	        <div class="col-lg-10">
	            <p class="form-control-static">$ {{$experience['gift_needed']}}</p>
	        </div>
	    </div>
	
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Experience from:</label>
	        <div class="col-lg-10">
	        	<?php
	        	$exp_from="";
	        	if($experience['experience_from']=="0")
	        	{
	        		$exp_from="Custom";
	        	}else{
	        		$exp_from="Yelp";
	        	}
				?>
	        	<p class="form-control-static">{{$exp_from}}</p>
	        </div>
	    </div>
	
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label">Status :</label>
	        <div class="col-lg-10">
	            <p class="form-control-static"><?php echo $experience['status']; ?></p>
	        </div>
	    </div>
	    
	    <div class="line line-dashed line-lg pull-in"></div>
	    <div class="form-group">
	        <label class="col-lg-2 control-label"></label>
	        <div class="col-lg-10">
	            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/experience/' . $experience['id'] . '/edit') }}">Change</a>
	            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/event/experience/')}}<?php echo '/'.$experience['event_id'];?>" >Back</a>
	        </div>
	    </div>
    </form>
</div>
@endsection