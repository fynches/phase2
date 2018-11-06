@extends('layouts.admin')
@section('content')
<div class="portlet light">
    {!! Form::open(array('url'=>'/admin/state','class'=>'form-horizontal','method'=>'POST','id'=>'state')) !!}
    <div class="form-group">
        <div class='col-md-6'>
            {!! Form::label('name', 'State Name'); !!}
        	{!! Form::text('name',null,array('class'=>'form-control')) !!}
        </div>
        
        <div class="col-md-6">
        <?php //pr($user);exit; ?>
            {!! Form::label('country', 'Country'); !!}
            {!! Form::select('country', ['' => '--- Select Country ---']+$country, null, array('class' => 'form-control')) !!}
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
            <a class="btn btn-default" href="{{ url('/admin/state')}}">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>

{{Html::script("/assets/common/state/state.js")}}

@endsection