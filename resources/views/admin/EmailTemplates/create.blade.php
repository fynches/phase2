@extends('layouts.admin')
@section('content')
<div class="portlet light">
    {!! Form::open(array('url'=>'/admin/emailtemplates','files'=>true,'class'=>'form-horizontal','method'=>'POST','id'=>'emailtemplate')) !!}

    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('subject', 'subject'); !!}
            {!! Form::text('subject',null,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('content', 'Content'); !!}
            {!! Form::textarea('content',null,array('class'=>'ckeditor form-control', 'rows' => '6', 'cols' => '30')) !!}
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
            <a class="btn btn-default" href="{{ url('/admin/emailtemplates')}}">Cancel</a>
        </div>
    </div>
    
    

    {!! Form::close() !!}
</div>

{{Html::script("/assets/common/email_template/email_template_create.js")}}

@endsection