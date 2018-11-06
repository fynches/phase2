@extends('layouts.admin')
@section('content')
{{Html::script("/assets/common/cms/cms-form-validation.js")}}
<div class="portlet light">

    {!! Form::open(array('url'=>'/admin/cms', 'class'=>'form-horizontal','method'=>'POST','id'=>'cms','files'=>true)) !!}

    <div class="form-group"> 
        <div class=" col-md-6">
            {!! Form::label('title', 'Title'); !!}
            {!! Form::text('title',null,array('class'=>'form-control')) !!}
        </div> 

        <div class=" col-md-6">
            {!! Form::label('slug', 'Slug'); !!}
            {!! Form::text('slug',null,array('class'=>'form-control')) !!}
        </div> 
    </div>

    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('image_type', 'Featured image position'); !!}
            {!! Form::select('image_type', config('constant.featured_image_position'), null, array('class' => 'form-control uploadBanner')) !!}
           
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
                        {!! Form::file('cms_image',array('class'=>'form-control','id'=>'cms_image')) !!}
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
            {!! Form::select('status', config('constant.status'), null, array('class' => 'form-control')) !!}
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