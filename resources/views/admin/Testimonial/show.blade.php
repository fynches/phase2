@extends('layouts.admin')
@section('content')
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">Testimonial Details</span>
        </div>
    </div>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-lg-2 control-label">Id :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $testimonial->id; ?></p>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Title :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $testimonial->name; ?></p>
            </div>
        </div>
        <div class="line line-dashed line-lg pull-in"></div>
        
        <?php
			
	        $defaultPath = 'storage/no-img.jpg';
	        $profileImage = $testimonial['image'];
	
	        if ($profileImage && $profileImage != "") {
	            
	            $imgPath = 'storage/testimonialImages/thumb/' . $profileImage;
	           
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
            <label class="col-lg-2 control-label">Description :</label>
            <div class="col-lg-10">
                <div class="form-control-static"><?php echo $testimonial->description; ?></div>
            </div>
        </div> 
        
         <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Author Name :</label>
            <div class="col-lg-10">
                <p class="form-control-static"><?php echo $testimonial->author_name; ?></p>
            </div>
        </div>

        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
        <label class="col-lg-2 control-label"></label>
        <div class="col-lg-10">
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/testimonial/') }}">Cancel</a>
        </div>
    </div>
        
        
    </div>
 </div>   
@endsection