@extends('layouts.admin')
@section('content')
<div class="portlet light">
    {!! Form::model($testimonial,array('route' => array('testimonial.update', $testimonial->id),'files'=>true,'class'=>'form-horizontal','method'=>'PUT','id'=>'testimonial')) !!}
    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('name', 'Name'); !!}
            {!! Form::text('name',null,array('class'=>'form-control')) !!}
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
                    
					$testimonial_image = $testimonial['image'];
		            
                    if ($testimonial_image && $testimonial_image != "") {
                        
                       $imgPath = 'storage/testimonialImages/' . $testimonial_image;
                       
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
                    <div id="testimonal_img">
                        <span class="btn red btn-outline btn-file">
                            <span class="fileinput-new"> Select image </span>
                            <span class="fileinput-exists"> Change </span>
                            <input type="file" name="image" value="<?php echo $testimonial_image; ?>" id="image" onchange='readURL(this);'>
                            <input type="hidden" name="old_images" value="<?php echo $testimonial_image; ?>" id="old_images">
                        </span>
                    </div>
                </div>                    
            </div>
        </div>
     </div> 

    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('description', 'Description'); !!}
            {!! Form::textarea('description',null,array('class'=>'ckeditor form-control', 'rows' => '6', 'cols' => '30')) !!}
        </div>
    </div> 
    
    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('author_name', 'Author Name'); !!}
            {!! Form::text('author_name',null,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-6">
                {!! Form::label('status', 'Status'); !!}
                {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), null, array('class' => 'form-control')) !!}
        </div>  
	</div> 

    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/testimonial')}}">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>

{{Html::script("/assets/common/testimonial/testimonial_edit.js")}}

@endsection
