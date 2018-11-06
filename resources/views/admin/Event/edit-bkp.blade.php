@extends('layouts.admin')
@section('content')
{{Html::script("/assets/common/event/edit_event.js")}}
<div class="portlet light">

    
    {!! Form::model($event,array('route' => array('event.update', $event[0]->id),'class'=>'form-horizontal','method'=>'PUT','id'=>'events','files'=>true)) !!}
    
    <div class="form-group">
        <div class="col-md-6">
        <?php //pr($filesa);exit; ?>
            {!! Form::label('user_id', 'User'); !!}
            {!! Form::select('user_id', ['' => '--- Select User Type ---']+$user, $event[0]->user_id, array('class' => 'form-control')) !!}
        </div>

        <div class=" col-md-6">
            {!! Form::label('title', 'Title'); !!}
            {!! Form::text('title',$event[0]->title,array('class'=>'form-control')) !!}
        </div> 
    </div>

    <div class="form-group">
        <div class="col-md-6">
        	
            {!! Form::label('image_type', 'Image/Video'); !!}
            {!! Form::select('image_type', array('Image','Video'), $event[0]['getEventMappingMdedia'][0]['image_type'], array('class' => 'form-control uploadBanner')) !!}
           
        </div>                 
    </div> 
    
     
     <div class="row fileupload-buttonbar imageFields" id="img_upload_div">
        <div class="col-lg-7">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>Add files...</span>
                <input type="file" name="files[]"  multiple>
            </span>
            <button type="button" class="btn btn-primary start">
                <i class="glyphicon glyphicon-upload"></i>
                <span>Start upload</span>
            </button>
            <button type="reset" class="btn btn-warning cancel_all">
                <i class="glyphicon glyphicon-ban-circle"></i>
                <span>Cancel upload</span>
            </button> 
            <!-- The global file processing state -->
            <!-- <span class="fileupload-process"></span> -->
        </div>
        <!-- The global progress state -->
        <div class="col-lg-5 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            <!-- The extended global progress state -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    	<!-- The table listing the files available for upload/download -->
    	<table role="presentation" class="table table-striped" id="display_img">
    		<tbody class="files">
    			<?php 
    			if(count($filesa) > 0)
    			{
    				foreach($filesa as $key=>$val)
    				{?>
    				<tr class="template-download fadein">
    					<td>
    			            <span class="preview">
    			                <a href="<?php echo $val['url'];?>" title="<?php echo $val['name'];?>" download="<?php echo $val['name'];?>" data-gallery><img src="<?php echo $val['thumbnailUrl'];?>"></a>
    			                <input type="hidden" value="<?php echo $val['name'];?>" id="event_image" name="event_image[]">
    			            </span>
    			        </td>
    			        
    			        <td>
    			            <p class="name">
    			                <a href="<?php echo $val['url'];?>" title="<?php echo $val['name'];?>" download="<?php echo $val['name'];?>"><?php echo $val['name'];?></a>
    			            </p>
    			        </td>
    			        
    			        <td>
    			        	<span class="size"><?php echo $val['img_size'];?></span>
    			        </td>
    			        
    			        <td>
    			            <button type="button" class="btn btn-warning edit_cancel" data-id="<?php echo $val['mapping_id'];?>"><i class="glyphicon glyphicon-ban-circle"></i><span>Delete</span></button>
    			        </td>
    				</tr>		
    				<?php }
    			}
    			?>	
    		</tbody>
    	</table>
    </div>
      

    <div class="videoFields">
        <div class="form-group">
            <div class="col-md-6"> 
            <?php 
             $flagVideo = $event[0]['getEventMappingMdedia'][0]['flag_video']; 
            ?>
                <input name="flag_video" type="radio" id="url" value="0" <?php if($event[0]['getEventMappingMdedia'][0]['flag_video'] == '0'){echo 'checked';}?> >
                <label for="url">You tube Url</label>
                <br>
                <input name="flag_video" type="radio" value="1" id="file" <?php if($event[0]['getEventMappingMdedia'][0]['flag_video'] == '1'){echo 'checked';}?> >
                <label for="file">File</label>
            </div>
            
             
            <div class="col-md-6 video_url" style="<?php if($flagVideo == '1') echo 'display:none';?>">
                {!! Form::label('video', 'You Tube Url( Embeded Url )'); !!}
                {!! Form::text('video',$event[0]['getEventMappingMdedia'][0]['video'],array('id'=>'banner_video','class' => 'form-control images')) !!}
                <i>https://www.youtube.com Click on share and copy like and paste Like:'https://www.youtube.com/embed/xS8IvGxOuwQ' the embade url </i>
            </div>
             
            <div class="col-md-12 video_file" style="<?php if($flagVideo == '0') echo 'display:none';?>">  
                <?php 
                $defaultPath = config('constant.imageNotFound');
                if($event[0]['getEventMappingMdedia'][0]['flag_video'] == '1'){ ?>
                    <div class="col-md-6 exist_video">
                        <?php
                        
                        
                        $video = $event[0]['getEventMappingMdedia'][0]['video'];                    
                        if ($video && $video != "") {                        
                           $imgPath = 'storage/Videos/' . $video;                       
                            if (file_exists($imgPath)){
                                $imgPath = $imgPath;
                            } else {
                                $imgPath = $defaultPath;
                            }
                        } else {
                            $imgPath = $defaultPath;
                        }
                        ?>
                        <video height="200px" controls>
                              <source src="{{ asset($imgPath) }}" type="video/mp4">
                              <source src="{{ asset($imgPath) }}" type="video/ogg">
                                Your browser does not support the video tag.
                        </video>
                    </div>
                <?php } ?>
               
                <div class="col-md-6">  
                    <div class="fileinput fileinput-new image_sec" data-provides="fileinput">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                            <img width="200" height="150" src="{{ asset($defaultPath) }}" class="img-circles">
                        </div>
                        <div id="tag_img">
                            <span class="btn default btn-file">
                                <span class="fileinput-new" data-trigger="fileinput"> Select </span>
                                <span class="fileinput-exists" data-trigger="fileinput"> Change </span>
                                {!! Form::file('video_files',array('class'=>'form-control','id'=>'video')) !!}
                            </span> 
                        </div>
                    </div> 
                </div> 
            </div>
            
            <!-- <div class="col-md-6">
                {!! Form::label('video', 'You Tube Url( Embeded Url )'); !!}
                {!! Form::text('video',$event[0]['getEventMappingMdedia'][0]['video'],array('id'=>'banner_video','class' => 'form-control images')) !!}
                <i>https://www.youtube.com Click on share and copy like and paste Like:'https://www.youtube.com/embed/xS8IvGxOuwQ' the embade url </i>
            </div> -->
            <div class="clear"></div> 
        </div>                 
    </div> 

    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('description', 'Description'); !!}
            {!! Form::textarea('description', $event[0]->description, array('class'=>'ckeditor form-control', 'rows' => '6', 'cols' => '30','id'=>'description')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class='col-md-6'>
            {!! Form::label('age_range', 'Age Range'); !!}
             {!! Form::text('age_range',$event[0]->age_range,array('id'=>'age_range','class' => 'form-control')) !!}
        </div>
         <div class='col-md-6'>
            {!! Form::label('event_publish_date', 'Event publish date'); !!}
            {!! Form::text('event_publish_date', date('d-m-Y',strtotime($event[0]->event_publish_date)), array('class' => 'form-control','id'=>'datepicker')) !!}
        </div>
    </div>
 
    <div class="form-group">
        <div class='col-md-6'>
            {!! Form::label('event_end_date', 'Event end date'); !!}
            {!! Form::text('event_end_date', date('d-m-Y',strtotime($event[0]->event_end_date)), array('class' => 'form-control','id'=>'datepicker2')) !!}
        </div>

        <div class='col-md-6'>
            {!! Form::label('zipcode', 'Zipcode'); !!}
            {!! Form::number('zipcode', $event[0]->zipcode, array('class' => 'form-control')) !!}
        </div>
    </div>
    
    <div class="form-group">
        <div class='col-md-6'>
            {!! Form::label('keywords and favorite activities', 'Keywords and Favorite activities'); !!}
            <div id="fav_activites">
            	<?php
            		$defaultPath = config('constant.imageNotFound');
                    $edit_mapping_tag_id=array();
					if(count($event[0]['getEventTags']) > 0)
					{
						foreach($event[0]['getEventTags'] as $key=>$val)
						{
							$edit_mapping_tag_id[$key] = $val->tag_id;
						}
					}
					//pr($edit_mapping_tag_id);die;
					if(count($tags) > 0)
					{
						foreach($tags as $key=>$val)
						{
							$tag_image = $val->image;
							if ($tag_image && $tag_image != "") {
								$imgPath = 'storage/tagImages/' . $tag_image;
								
								if (file_exists($imgPath))
		                        {
		                            $imgPath = $imgPath;
		                        } else {
		                            $imgPath = $defaultPath;
		                        }
							}else{
								$imgPath = $defaultPath;
							}
							
							$chkbox="";
							
							if (in_array($val->id, $edit_mapping_tag_id))
							{
								$chkbox="checked='checked'";
							}
						?>
							<div class="activity-box">
							    <label class="image-checkbox">
							      <img width="200" height="150" src="{{ asset($imgPath) }}" class="img-responsive">{{$val->tag_name}}
							      <input type="checkbox" name="keywords_activities[]" value="{{$val->id}}"  <?php echo $chkbox;?>/>
							      <i class="fa fa-check hidden"></i>
							    </label>
							</div>
						<?php }
					}
            	?>
            	 
            	<?php
            		//pr($tags);
            	?>
            </div>	
        </div>
    </div> 
        
    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('status', 'Event Status'); !!}
            {!! Form::select('status', config('constant.event_statuses'), $event[0]->status, array('class' => 'form-control')) !!}
        </div> 
    </div>  
     
    <div class="form-group">
        <div class="col-md-12">
        	{!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/event')}}">Cancel</a>
        </div>
    </div>           
    {!! Form::close() !!}
</div>

<script type="text/javascript">

 $(".image-checkbox").each(function () {
	  if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
	    	$(this).addClass('image-checkbox-checked');
	  }
	  else {
	    $(this).removeClass('image-checkbox-checked');
	  }
	});
	
	
	$(".image-checkbox").on("click", function (e) {
	  $(this).toggleClass('image-checkbox-checked');
	  var $checkbox = $(this).find('input[type="checkbox"]');
	  $checkbox.prop("checked",!$checkbox.prop("checked"))
	
	  e.preventDefault();
	});
</script>

<style>
.preview img{
	height: 80px;
	width: 80px;
}
.nopad {
	padding-left: 0 !important;
	padding-right: 0 !important;
}
/*image gallery*/
.image-checkbox {
	cursor: pointer;
	box-sizing: border-box;
	-moz-box-sizing: border-box;
	-webkit-box-sizing: border-box;
	border: 4px solid transparent;
	margin-bottom: 0;
	outline: 0;
}
.image-checkbox input[type="checkbox"] {
	display: none;
}

.image-checkbox-checked {
	border-color: #4783B0;
}
.image-checkbox .fa {
  position: absolute;
  color: #4A79A3;
  background-color: #fff;
  padding: 10px;
  top: 0;
  right: 0;
}
.image-checkbox-checked .fa {
  display: block !important;
}
</style>
 
@endsection
