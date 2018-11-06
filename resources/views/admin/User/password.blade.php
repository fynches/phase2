<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"> {{$title_for_layout}} </h4>
</div>
<div class="modal-body">
{!! Form::open(array('url'=>'/admin/changepassword/update-password','files'=>true,'class'=>'form-horizontal','method'=>'POST','id'=>'user_password')) !!}
    
    <div class="form-group">
        <div class="col-md-12">
            {!! Form::label('password', 'New Password'); !!}
            {!! Form::password('password',array('class'=>'form-control')) !!}
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-md-12">
            {!! Form::label('confirmpassword', 'Confirm Password'); !!}
            {!! Form::password('confirmpassword',array('class'=>'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
        	<input type="hidden" name="user_type" value="{{$type}}">
            {!! Form::hidden('user_id',$id,array('class'=>'form-control')) !!}
            {!! Form::submit('Update',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" data-dismiss="modal" aria-hidden="true"> Cancel </a>
        </div>
    </div>

    {!! Form::close() !!}

    
</div>

<script type="text/javascript">
$(document).ready(function () {
    $("#user_password").validate({
        rules: {
            password: {
                required: true,
                minlength: 6
            },
            confirmpassword: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            }
        },
        messages: {
            password: {
                required: "Please enter new password.",
                minlength: "Your password must be at least 6 characters long."
            },
            confirmpassword: {
                required: "Please enter confirm password.",
                minlength: "Your password must be at least 6 characters long.",
                equalTo: "Password and confirm password must be same."
            }
        }
    });
});    
</script>