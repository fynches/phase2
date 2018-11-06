@extends('layouts.admin')
@section('content')
<div class="portlet light">
    {!! Form::open(array('url'=>'/admin/changepassword/update','class'=>'form-horizontal','method'=>'POST','id'=>'changePasswordForm')) !!}
    <div class="form-group">
        {!! Form::label('old_password', 'Old Password',array('class'=>'col-sm-2 control-label')); !!}
        <div class="col-sm-10">
            {!! Form::password('old_password',array('class'=>'form-control')) !!}
        </div>
    </div>
    <div class="line line-dashed line-lg pull-in"></div>

    <div class="form-group">
        {!! Form::label('password', 'New Password',array('class'=>'col-sm-2 control-label')); !!}
        <div class="col-sm-10">
            {!! Form::password('password',array('class'=>'form-control')) !!}
        </div>
    </div>
    <div class="line line-dashed line-lg pull-in"></div>

    <div class="form-group">
        {!! Form::label('conform_password', 'Conform Password',array('class'=>'col-sm-2 control-label')); !!}
        <div class="col-sm-10">
            {!! Form::password('conform_password',array('class'=>'form-control')) !!}
        </div>
    </div>
    <div class="line line-dashed line-lg pull-in"></div>

    <div class="form-group">
        <div class="col-sm-4 col-sm-offset-2">
            <a href="{{ URL::to('/admin/user')}}" class="btn btn-default">Back</a>
            {!! Form::submit('Save Changes',array('class'=>'btn btn-primary')); !!}
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    //on click change password form
    $("#changePasswordForm").validate({
        rules: {
            old_password: {
                required: true,
                minlength: 6
            },
            password: {
                required: true,
                minlength: 6
            },
            conform_password: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            }
        },
        messages: {
            old_password: {
                required: "Please enter existing password."
            },
            password: {
                required: "Please enter new password.",
                minlength: "Your password must be at least 6 characters long."
            },
            conform_password: {
                required: "Please enter confirm password.",
                minlength: "Your password must be at least 6 characters long.",
                equalTo: "Password and confirm password must be same."
            }
        }
    });
</script>
@endsection
