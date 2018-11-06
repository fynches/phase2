@extends('layouts.admin')
@section('content')
<div class="portlet light">

    {!! Form::open(array('url'=>'/admin/user/create_admin', 'class'=>'form-horizontal','method'=>'POST','id'=>'user','name'=>'user_form')) !!}

    <div class="form-group">
        <div class=" col-md-6">
            {!! Form::label('firstname', 'First Name'); !!}
            {!! Form::text('firstname',null,array('class'=>'form-control')) !!}
        </div>
        <div class=" col-md-6">
            {!! Form::label('lastname', 'Last Name'); !!}
            {!! Form::text('lastname',null,array('class'=>'form-control')) !!}
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('email', 'Email'); !!}
            {!! Form::text('email',null,array('class'=>'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('password', 'Password'); !!}
            {!! Form::password('password',array('class'=>'form-control')) !!}
        </div>
        
        
        <div class="col-md-6">
            <div class="btn-group">
            <input type="button" class="btn sbold green" value="Auto Generate" onClick="generate();" tabindex="2" style="margin-top:22px;">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('status', 'User Status'); !!}
            {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), null, array('class' => 'form-control')) !!}
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-12">
            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/user/admin_index')}}">Cancel</a>
        </div>
    </div>           
    {!! Form::close() !!}
</div>

{{Html::script("/assets/common/admin/admin_create.js")}}

@endsection