@extends('layouts.admin')
@section('content')
    <?php
        $authId = Auth::user()->id;
        $edit_user_id= $user->id;
     ?>

<div class="portlet light">
    {!! Form::model($user,array('route' => array('user.update', $user->id),'class'=>'form-horizontal','method'=>'PUT','id'=>'user','files'=>true,'enctype="multipart/form-data"')) !!}
    <?php $user_type= Auth::user()->user_type;?>
    
    <?php
        $defaultPath = config('constant.imageNotFound');
        $profileImage = $user->profile_image;

        if ($profileImage && $profileImage != "") {
            
            $imgPath = 'storage/adminProfileImages/thumb/' . $profileImage;
           
            if (file_exists($imgPath))
            {
                $imgPath = $imgPath;
            } else {
                $imgPath = $defaultPath;
            }
        } else {
            $imgPath = $defaultPath;
        }
		?>
    <div class="form-group">
        <div class="col-md-4">
            <div class="fileinput fileinput-new image_sec" data-provides="fileinput">
                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                    <img width="200" height="150" src="{{ asset($imgPath) }}" class="img-circles">
                </div>
                <div>
                    <span class="btn default btn-file">
                        <span class="fileinput-new" data-trigger="fileinput"> Select </span>
                        <span class="fileinput-exists" data-trigger="fileinput"> Change </span>
                        {!! Form::file('profile_image',array('class'=>'form-control','id'=>'profile_image')) !!}
                    </span>
                   
                </div>
            </div>
        </div>
    </div> 
    
    <div class="form-group">
        <div class=" col-md-6">
            {!! Form::label('firstname', 'First Name'); !!}
            {!! Form::text('firstname',$user->first_name,array('class'=>'form-control')) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label('lastname', 'Last Name'); !!}
            {!! Form::text('lastname',$user->last_name,array('class'=>'form-control')) !!}
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('email', 'Email'); !!}
            {!! Form::text('email',$user->email,array('class'=>'form-control')) !!}
        </div>

          @if($edit_user_id!=$authId)                
            <div class="col-md-6">
                    {!! Form::label('status', 'User Status'); !!}
                    {!! Form::select('user_status', array('Active' => 'Active', 'Inactive' => 'Inactive'), null, array('class' => 'form-control')) !!}
            </div>                  
        @endif
    </div>  
     
    <div class="form-group">
        <div class="col-md-12">
            {!! Form::hidden('ischngeprofile', $ischngeprofile, array('id' => 'ischngeprofile')) !!}
            {!! Form::submit('Save',array('class'=>'btn btn-primary')); !!}
            <a class="btn btn-default" href="{{ url('/admin/user')}}">Cancel</a>
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{Html::script("/assets/common/user/user_edit.js")}}


@endsection
