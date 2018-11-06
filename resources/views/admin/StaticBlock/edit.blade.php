@extends('layouts.admin')
@section('content')
<div class="portlet light">

    {!! Form::model($page,array('route' => array('staticblock.update', $page->id),'files'=>true,'class'=>'form-horizontal','method'=>'PUT','id'=>'staticblock')) !!}
    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('title', 'Title'); !!}
            {!! Form::text('title',null,array('class'=>'form-control')) !!}
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
            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/staticblock')}}">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}
</div>

{{Html::script("/assets/common/staticblock/staticblock_create.js")}}
@endsection