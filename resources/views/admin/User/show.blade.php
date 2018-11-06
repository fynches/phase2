@extends('layouts.admin')
@section('content')
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">User Details</span>
        </div>
    </div>
    <form class="form-horizontal">
   	
    <div class="form-group">
    	<?php
 		            $defaultPath = 'storage/adminProfileImages/avatar.jpg';
                    $profileImage = $user->profile_image;

		            $defaultPath = 'storage/adminProfileImages/avatar.jpg';	
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
        
        <label class="col-lg-2 control-label">Id :</label>
        <div class="col-lg-10">
            <p class="form-control-static"><?php echo $user->id; ?></p>
        </div>
    </div>
     
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Full Name :</label>
        <div class="col-lg-10">
            <p class="form-control-static"><?php echo $user->first_name." ".$user->last_name; ?></p>
        </div>
    </div>
    
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Profile Image :</label>
        <div class="col-lg-10">
            <p class="form-control-static"><img class="img-circle" alt="Admin" src="{{ asset($imgPath) }}"  id="image_upload_preview" width="100px" height="100px"/></p>
        </div>
    </div>
    
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Email :</label>
        <div class="col-lg-10">
            <p class="form-control-static">{{$user->email}}</p>
        </div>
    </div>

    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">UserType :</label>
        <div class="col-lg-10">
            <p class="form-control-static">
            <?php 
            if($user->user_type=="1")
            {
                echo 'Admin';
            }else{
                echo 'User';
            }
            ?></p>
        </div>
    </div>
    
    
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">User Status :</label>
        <div class="col-lg-10">
            <p class="form-control-static">{{$user->user_status}}></p>
        </div>
    </div>
   
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Registered On :</label>
        <div class="col-lg-10">
            <p class="form-control-static">{{$user->created_at}}</p>
        </div>
    </div>
    
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label"></label>
        <div class="col-lg-10">
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/user/' . $user->id . '/edit') }}">Change</a>
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/user/') }}">Cancel</a>
        </div>
    </div>
    </form>
</div>
@endsection