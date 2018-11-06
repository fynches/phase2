@extends('layouts.admin')
@section('content')
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">Cms Details</span>
        </div>
    </div>
    <form class="form-horizontal">
   	
    
     
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Title :</label>
        <div class="col-lg-10">
            <p class="form-control-static"><?php echo $cms['title']; ?></p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-2 control-label">Slug :</label>
        <div class="col-lg-10">
            <p class="form-control-static"><?php echo $cms['slug']; ?></p>
        </div>
    </div>

    @if($cms['featured_image'] != '')
        <div class="line line-dashed line-lg pull-in"></div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Image :</label>
            <div class="col-lg-10"> 
                <?php
                $defaultPath = config('constant.imageNotFound');
                $cmsImage = $cms['featured_image']; 
                  
                if ($cmsImage && $cmsImage != "") {
                   
                    $imgPath = 'storage/Cms/'.$cmsImage;
                     
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
                <a href="{{ asset($imgPath) }}" target="_blank">
                    <img id="preview_image" class="m-r-sm" alt="" src="{{ asset($imgPath) }}" style="max-width: 150px; max-height: 150px;">
                </a>
            </div>       
        </div>
    @endif
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Description :</label>
        <div class="col-lg-10">
            <p class="form-control-static">{!! $cms['description'] !!} </p>
        </div>
    </div> 
     

    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Status:</label>
        <div class="col-lg-10">
            <p class="form-control-static">
            <?php 
                $cms_statuses_array = config('constant.status');
                if(array_key_exists($cms['status'],$cms_statuses_array))
                {
                    echo $cms_statuses_array[$cms['status']];
                }
            ?>
           </p>
        </div>
    </div> 
    
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label"></label>
        <div class="col-lg-10">
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('admin/cms/' . $cms['id'] . '/edit') }}">Change</a>
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('admin/cms/') }}">Cancel</a>
        </div>
    </div>
    </form>
</div>
@endsection