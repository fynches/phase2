@extends('layouts.admin')
@section('content')
{{Html::script("/assets/common/cms/cms-form-validation.js")}}
<div class="portlet light">
<!-- <script src="//cdn.ckeditor.com/4.4.3/basic/ckeditor.js"></script>
<script src="//cdn.ckeditor.com/4.4.3/basic/adapters/jquery.js"></script> -->
    
    {!! Form::model($cms,array('route' => array('cms.update', $cms->id),'class'=>'form-horizontal','method'=>'PUT','id'=>'cms','files'=>true)) !!}
    
    <div class="form-group">
        <div class=" col-md-6">
            {!! Form::label('title', 'Title'); !!}
            {!! Form::text('title',$cms->title,array('class'=>'form-control')) !!}
        </div> 
         
        <div class=" col-md-6">
            {!! Form::label('slug', 'Slug'); !!}
            {!! Form::text('slug',$cms->slug,array('class'=>'form-control')) !!}
        </div>

    </div> 

    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('image_type', 'Featured image position'); !!}
            {!! Form::select('image_type', config('constant.featured_image_position'), $cms->image_type, array('class' => 'form-control uploadBanner')) !!}
           
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
                    
					$cms_image = $cms['featured_image'];
		            
                    if ($cms_image && $cms_image != "") {
                        
                       $imgPath = 'storage/Cms/' . $cms_image;
                       
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
                            <input type="file" name="cms_image" value="<?php echo $cms_image; ?>" id="cms_image">
                            <input type="hidden" name="old_images" value="<?php echo $cms_image; ?>" id="old_images">
                        </span>
                    </div>
                </div>                    
            </div>
        </div>
     </div> 

    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('description', 'Description'); !!}
            {!! Form::textarea('description', $cms->description, array('class'=>'ckeditor form-control', 'rows' => '6', 'cols' => '30','id'=>'description')) !!}
        </div>
    </div>
 
        
    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('status', 'Status'); !!}
            {!! Form::select('status', config('constant.status'), $cms->status, array('class' => 'form-control')) !!}
        </div> 
    </div> 
      
     
    <div class="form-group">
        <div class="col-md-12">
            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/cms')}}">Cancel</a>
        </div>
    </div>           
    {!! Form::close() !!}
</div>
 
@endsection