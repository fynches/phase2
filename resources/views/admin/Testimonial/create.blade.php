@extends('layouts.admin')
@section('content')
<div class="portlet light">
    {!! Form::open(array('url'=>'/admin/testimonial','files'=>true,'class'=>'form-horizontal','method'=>'POST','id'=>'testimonial')) !!}

    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('name', 'Name'); !!}
            {!! Form::text('name',null,array('class'=>'form-control','autocomplete'=>'off')) !!}
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
                <div id="testimonal_img">
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

{{Html::script("/assets/common/testimonial/testimonial_create.js")}}

@endsection