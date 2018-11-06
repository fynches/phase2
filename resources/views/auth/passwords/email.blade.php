@extends('layouts.app')

@section('content')


@if(Session::has('error_msg'))
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fa fa-ban-circle"></i> {{Session::get('error_msg')}}
</div>
@endif
 
@if(Session::has('success_msg'))
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fa fa-ok-sign"></i> {{Session::get('success_msg')}}
</div>
@endif

<form class="forget-form" action="{{ url('/admin/password/email') }}" method="POST">
    {!! csrf_field() !!}
    <div class="form-title">
        <span class="form-title">Forget Password ?</span>
        <span class="form-subtitle">Enter your e-mail to reset it.</span>
    </div>
    <div class="form-group">
    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" value="{{ old('email') }}" placeholder="Email" name="email" /> </div>
    <div class="form-actions">
        <a id="back-btn" href="{{ url('admin/login') }}" class="btn btn-default">Back</a>
        <button type="submit" class="btn red btn-primary uppercase pull-right">Submit</button>
    </div>
</form>
@endsection
