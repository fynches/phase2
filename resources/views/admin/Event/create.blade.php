@extends('layouts.admin')
@section('content')

{{Html::script("/assets/common/event/common-form-validation.js")}}
<div class="portlet light">

    {!! Form::open(array('url'=>'/admin/event', 'class'=>'form-horizontal','method'=>'POST','id'=>'event','files'=>true)) !!}
    {{ csrf_field() }}

    <div class="form-group">
        <div class="col-md-6">
        <?php //pr($user);exit; ?>
            {!! Form::label('user_id', 'User'); !!}
            {!! Form::select('user_id', ['' => '--- Select User Type ---']+$user, null, array('class' => 'form-control')) !!}
        </div>

        <div class=" col-md-6">
            {!! Form::label('title', 'Title'); !!}
            {!! Form::text('title',null,array('class'=>'form-control','id'=>'eventTitle','onkeyup'=>'populateSecondTextBox();')) !!}
        </div> 
    </div>

    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('image_type', 'Image'); !!}
            {!! Form::select('image_type', array('Image'), null, array('class' => 'form-control uploadBanner')) !!}
           
        </div>  
        
        <div class=" col-md-6">
            {!! Form::label('publish_url', 'Publish Url'); !!}
             <div class="input-group">
            <span class="input-group-addon">
                {{ url('/') }}/
            </span>
            {!! Form::text('publish_url',null,array('class'=>'form-control','id'=>'publishUrl')) !!}
            </div>
        </div>
    </div> 

    <div id="img_upload_start">
        <div class="imageFields">
        	<div class="form-group">
                <div class="col-md-6">
                    {!! Form::label('image', 'Image'); !!}
                </div>
            </div>
            
            <?php $defaultPath = config('constant.imageNotFound');?>
            
            <div class="row fileupload-buttonbar " id="img_upload_div">
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
            </div>
        	<!-- The table listing the files available for upload/download -->
        	<table role="presentation" class="table table-striped" id="display_img"><tbody class="files"></tbody></table>
        </div>
    </div>
    <div class="form-group">
        <div class='col-md-12'> 
            {!! Form::label('description', 'Description'); !!}
            {!! Form::textarea('description',null,array('class'=>'ckeditor form-control', 'rows' => '6', 'cols' => '30','id'=>'description')) !!}
        </div>
    </div>
    <div class="form-group">
    <div class=" col-md-6">
        {!! Form::label('first_name', 'Host First Name'); !!}
        {!! Form::text('first_name',null,array('class'=>'form-control')) !!}
    </div>
        <div class=" col-md-6">
            {!! Form::label('last_name', 'Host Last Name'); !!}
            {!! Form::text('last_name',null,array('class'=>'form-control')) !!}
        </div>
    </div>
    <div class="form-group">
        <div class='col-md-6'>
            {!! Form::label('child_name', 'First Name'); !!}
            {!! Form::text('child_name','',array('id'=>'child_name','class' => 'form-control','Placeholder'=>'Enter Child First Name')) !!}
        </div>
        <div class='col-md-6'>
            {!! Form::label('age_range', 'Age'); !!}
            {!! Form::text('age_range','',array('id'=>'age_range','class' => 'form-control','Placeholder'=>'Enter Child Age')) !!}
        </div>

    </div>
 
    <div class="form-group">
        <div class='col-md-6'>
            {!! Form::label('event_publish_date', 'Event publish date'); !!}
            {!! Form::text('event_publish_date', null, array('class' => 'form-control','id'=>'event_publish_date')) !!}
        </div>
        <div class='col-md-6'>
            {!! Form::label('event_end_date', 'Event end date'); !!}
            {!! Form::text('event_end_date', null, array('class' => 'form-control','id'=>'event_end_date')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class='col-md-6'>
            {!! Form::label('zipcode', 'Zipcode'); !!}
            {!! Form::text('zipcode', null, array('class' => 'form-control')) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label('status', 'Event Status'); !!}
            {!! Form::select('status', config('constant.event_statuses'), null, array('class' => 'form-control')) !!}
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
<script>
    function populateSecondTextBox() {
        document.getElementById('publishUrl').value = document.getElementById('eventTitle').value;
    }
</script>
@endsection

