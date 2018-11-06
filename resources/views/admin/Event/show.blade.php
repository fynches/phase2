@extends('layouts.admin')
@section('content')
<div class="portlet light">
    <div class="portlet-title">
        <div class="caption caption-md">
            <i class="icon-globe theme-font hide"></i>
            <span class="caption-subject font-blue-madison bold uppercase">Event Details</span>
        </div>
    </div>
    <form class="form-horizontal">
   	
    <div class="form-group"> 
        <label class="col-lg-2 control-label">User :</label>
        <div class="col-lg-10">
            <p class="form-control-static">{{$event['get_user']['first_name'].' '.$event['get_user']['last_name']}}</p>
        </div>
    </div>
     
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Title :</label>
        <div class="col-lg-10">
            <p class="form-control-static"><?php echo $event['title']; ?></p>
        </div>
    </div>
    <?php 
    if(count($event['get_event_mapping_mdedia'])>0){ ?>             
        @if($event['get_event_mapping_mdedia'][0]['image_type'] == '0')
            <div class="line line-dashed line-lg pull-in"></div>
            <div class="form-group">
                <label class="col-lg-2 control-label">Image :</label>
                <?php 
                $defaultPath = config('constant.imageNotFound');
                foreach ($event['get_event_mapping_mdedia'] as $key => $value) { 
                ?>
                <div class="col-lg-2" style="height: 150px;"> 
                    <?php  
                    $eventImage = $value['image']; 
                      
                    if ($eventImage && $eventImage != "") {
                        ///var/www/html/fynches-laravel/public/storage/Event
                        $imgPath = 'storage/Event/'.$eventImage;
                         
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
                    <a href="{{ asset($imgPath) }}" target="_blank" >
                        <img id="preview_image" class="m-r-sm" alt="" src="{{ asset($imgPath) }}" style="width: 100%; height: 100px;object-fit: cover;">
                    </a>
                </div>
                <?php } ?>       
            </div>
        @elseif($event['get_event_mapping_mdedia'][0]['image_type'] == '1')
            <div class="line line-dashed line-lg pull-in"></div>
            <div class="form-group">
                <label class="col-lg-2 control-label">Video :</label>
                <div class="col-lg-10">
                @if($event['get_event_mapping_mdedia'][0]['flag_video']=='0')
                    <p class="form-control-static">
                    <iframe width="560" height="315" src="{{$event['get_event_mapping_mdedia'][0]['video']}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </p>
                @elseif($event['get_event_mapping_mdedia'][0]['flag_video'] == '1')
                    <?php 
                    $defaultPath = config('constant.imageNotFound');
                    $video = $event['get_event_mapping_mdedia'][0]['video'];                    
                    if ($video && $video != "") {                        
                       $imgPath = 'storage/Videos/' . $video;                       
                        if (file_exists($imgPath)){
                            $imgPath = $imgPath;
                        } else {
                            $imgPath = $defaultPath;
                        }
                    } else {
                        $imgPath = $defaultPath;
                    }
                    ?>
                    <video height="200px" controls>
                          <source src="{{ asset($imgPath) }}" type="video/mp4">
                          <source src="{{ asset($imgPath) }}" type="video/ogg">
                            Your browser does not support the video tag.
                    </video>
                @endif
                </div>
            </div>
        @endif
    <?php     
        } 
    ?>
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Description :</label>
        <div class="col-lg-10">
            <p class="form-control-static">{!! $event['description'] !!} </p>
        </div>
    </div>

    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Age range :</label>
        <div class="col-lg-10">
            <p class="form-control-static">
            {{$childInfo[0]['age_range']}}
            </p>
        </div>
    </div> 

    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Event publish date:</label>
        <div class="col-lg-10">
            <p class="form-control-static">{{date('d-m-Y',strtotime($event['event_publish_date']))}}</p>
        </div>
    </div>

    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Event end date:</label>
        <div class="col-lg-10">
            <p class="form-control-static">{{date('d-m-Y',strtotime($event['event_end_date']))}}</p>
        </div>
    </div>

    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Zipcode:</label>
        <div class="col-lg-10">
            <p class="form-control-static">{{$event['zipcode']}}</p>
        </div>
    </div>

    <div class="form-group">
     
     {!! Form::label('keywords and favorite activities', 'Keywords and Favourite activities',array('class' => 'col-lg-2 control-label')); !!}
        <div class='col-lg-6'>
           
            <div id="fav_activites">
                <?php
                 
                    $defaultPath = config('constant.imageNotFound');
                    $edit_mapping_tag_id=array();
                    if(count($event['get_event_tags']) > 0)
                    {
                        foreach($event['get_event_tags'] as $key=>$val)
                        {    
                            $edit_mapping_tag_id[$key] = $val['tag_id'];
                        }
                    }
                     
                    if(count($tags) > 0)
                    {
                        foreach($tags as $key=>$val)
                        {
                            $tag_image = $val->image;
                            if ($tag_image && $tag_image != "") {
                                $imgPath = 'storage/tagImages/' . $tag_image;
                                
                                if (file_exists($imgPath))
                                {
                                    $imgPath = $imgPath;
                                } else {
                                    $imgPath = $defaultPath;
                                }
                            }else{
                                $imgPath = $defaultPath;
                            }
                            
                            $chkbox="";
                            
                            if (in_array($val->id, $edit_mapping_tag_id))
                            {
                                $chkbox="checked='checked'";
                            }
                        ?>
                            <div class="activity-box">
                                <label class="image-checkbox">
                                  <img width="200" height="150" src="{{ asset($imgPath) }}" class="img-responsive">{{$val->tag_name}}
                                  <input type="checkbox" name="keywords_activities[]" value="{{$val->id}}"  <?php echo $chkbox;?>/>
                                  <i class="fa fa-check hidden"></i>
                                </label>
                            </div>
                        <?php }
                    }
                ?>
                 
                <?php
                    //pr($tags);
                ?>
            </div>  
        </div>
    </div>

    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label">Status:</label>
        <div class="col-lg-10">
            <p class="form-control-static">
            <?php 
                $event_statuses_array = config('constant.event_statuses');
                if(array_key_exists($event['status'],$event_statuses_array))
                {
                    echo $event_statuses_array[$event['status']];
                }
            ?>
           </p>
        </div>
    </div> 
    
    <div class="line line-dashed line-lg pull-in"></div>
    <div class="form-group">
        <label class="col-lg-2 control-label"></label>
        <div class="col-lg-10">
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/event/' . $event['id'] . '/edit') }}">Change</a>
            <a class="btn btn-default btn-sm btn-dark" href="{{ URL::to('/admin/event/') }}">Cancel</a>
        </div>
    </div>
    </form>
</div>
@endsection