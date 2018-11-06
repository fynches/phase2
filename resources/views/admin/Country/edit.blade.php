@extends('layouts.admin')
@section('content')
<div class="portlet light">
    {!! Form::model($country,array('route' => array('country.update', $country->id),'files'=>true,'class'=>'form-horizontal','method'=>'PUT','id'=>'country')) !!}
    
    <div class="form-group">
        <div class='col-md-6'>
            {!! Form::label('name', 'Country Name'); !!}
        	{!! Form::text('name',null,array('class'=>'form-control')) !!}
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
            <a class="btn btn-default" href="{{ url('/admin/country')}}">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>

{{Html::script("/assets/common/country/country.js")}}

@endsection
