@extends('layouts.app_front')
@section('header')
  @include('site.event_create_header')
@stop
@section('pagetitle', 'Event')
@section('content')

<!-- Creating Event -->
<section class="createvent">
	<div class="container">
		@include('layouts.front-notifications')
		<div class="row">
			
			<div class="col-md-12 text-right">
				
				@if($event_id=="0")
					<a href="javascript:void(0)" class="commont-btn submit_event" id="submit_event">SAVE</a>
				@endif
				@if($stripe_user_id=="" || $stripe_user_id=="0" || count($my_experience->getEventExperiences) == 0)
					<a href="javascript:void(0)" class="commont-btn save_and_publish">SAVE &amp; PUBLISH</a>
				@else
					<a href="javascript:void(0)" onclick="save_and_publish({{$event_id}})"  class="commont-btn">SAVE &amp; UPDATE</a>
				@endif	
			</div>
		</div>
	</div>
</section>
<!-- End -->

<!-- Event Info-->
<section class="event-info">
	<div class="container">
		<div id="modal-loader" class="modal_loader"  style="display: none; text-align: center;">
            <img src="{{ asset('assets/global/img/loading.gif') }}">
        </div>
		<div class="row">
			<div class="col-md-12">
				<div class="brd-title">
					<h3>Event information</h3>
				</div>
			</div>
		</div>
		<div class="row">
			
			<div class="col-sm-12">
				@if(isset($event_id) && $event_id!="0")
					{!! Form::open(array('url'=>'/update-event', 'class'=>'form-horizontal','method'=>'POST','id'=>'event_create_form','files'=>true)) !!}
				@else
					{!! Form::open(array('url'=>'/save-event', 'class'=>'form-horizontal','method'=>'POST','id'=>'event_create_form','files'=>true)) !!}
				@endif
					
					<?php
						$event_edit_flag_upto_days="";
						
						if(isset($data['event_end_date']))
						{
							$event_end_date= date('Y-m-d',strtotime($data['event_end_date']));
							$due_date= date('Y-m-d',strtotime('+30 days',strtotime($event_end_date)));
							$today_date= date('Y-m-d');
							if($due_date < $today_date)
							{
								$event_edit_flag_upto_days = 'disabled="disabled"';
							}
						}
						
						
					?>
				
					<div class="row">
						<div class="col-md-6 col-lg-7">
							<div class="form-group">
			                  <input type="text" name="event_title" <?php echo $event_edit_flag_upto_days; ?> id="event_title" value="{{$data['title'] or '' }}" class="form-control" placeholder="What is the title of your event?">
			                </div>
						</div>
						<div class="col-md-6 col-lg-5">
							<div class="input-group date form-group event_publish_date" id="event_date">
								<?php
									$event_pub_date="";
									$event_end_date="";
									if(isset($data['event_publish_date']))
									{
										$event_pub_date= date('m/d/Y',strtotime($data['event_publish_date']));
										$event_end_date= date('m/d/Y',strtotime($data['event_end_date']));
									}
								?>
			                    <input type="text" autocomplete="off" <?php echo $event_edit_flag_upto_days; ?> class="form-control" name="event_publish_date" value="{{ $event_pub_date }}" id="event_publish_date" placeholder="Event Date">
			                    <input type="hidden" name="edit_event_end_date" id="edit_event_end_date" value="{{$event_end_date}}">
			                    <span class="input-group-addon">
			                        <i class="fa fa-calendar"></i>
			                    </span>
			                </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="img-box custom_video">
								
								@if($event_edit_flag_upto_days=="")
									<div class="upload">
										<label>
											<a href="javascript:void(0)" id="uploads" class="show_modal"  data-toggle="modal" data-target="#upload_photos"> 
												<i class="fa fa-camera" aria-hidden="true"></i>
											 </a>
											 <input id="filebutton" <?php echo $event_edit_flag_upto_days; ?> name="image[]" class="input-file local_uploads" type="file" multiple> 
											 <input type="hidden" name="video_option" id="video_option" value="">
											<span>Edit/add photos</span>
										</label>
									</div>
								@else
									<div class="upload">
										<label>
												<i class="fa fa-camera" aria-hidden="true"></i>
												<span>Edit/add photos</span>
										</label>
									</div>
								@endif	
								
								<?php
									$defaultPath = config('constant.imageNotFound');
									if(isset($data['get_event_mapping_mdedia']) && count($data['get_event_mapping_mdedia']) > 0)
									{?>
										<ul class="dynamic_imgs">
										<?php	
										foreach($data['get_event_mapping_mdedia'] as $key=>$val)
										{
											if($val['image_type']=="2") //facebook images
											{ ?><li class="img-wrap addeventimage"><span class="close">&times;</span><img src="{{$val['image']}}" alt="" title=""><input type="hidden" name="old_facebook_images[]" value="{{$val['image']}}"></li><?php }
													
											else if($val['image_type']=="0")
											{
												
												$event_image = $val['image'];
												
												if ($event_image && $event_image != "") {
													$imgPath = 'storage/Event/' . $event_image;
													//echo $imgPath;
													if (file_exists($imgPath))
							                        {
							                            $imgPath = $imgPath;
							                        } else {
							                            $imgPath = $defaultPath;
							                        }
												}
												else{
														$imgPath = $defaultPath;
													}?>
									
													<li class="img-wrap addeventimage"><span class="close">&times;</span><img src="{{ asset($imgPath) }}" alt="" title=""><input type="hidden" name="old_images[]" value="{{$event_image}}"></li>
													
										<?php					
								 			} 
										}
									//for local video showing
									
									if($data['get_event_mapping_mdedia'][0]['flag_video']=="1" && $data['get_event_mapping_mdedia'][0]['video']!="") //for local uploaded video show
									{
										$video = $data['get_event_mapping_mdedia'][0]['video'];                    
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
										
											<li>
												<video height="120px" controls>
						                              <source src="{{ asset($imgPath) }}" type="video/mp4">
						                              <source src="{{ asset($imgPath) }}" type="video/ogg">
						                                Your browser does not support the video tag.
						                        </video>
											</li>
											<input type="hidden" name="video" id="video" value="{{$video}}">
						                       
										<?php }

										//for youtube video showing
										
										if($data['get_event_mapping_mdedia'][0]['flag_video']=="0" && $data['get_event_mapping_mdedia'][0]['video']!="") //for local uploaded video show
										{?>
												<li>
													<iframe width="260" height="120" src="{{$data['get_event_mapping_mdedia'][0]['video']}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
												</li>
											<input type="hidden" name="video" id="video" value="{{$data['get_event_mapping_mdedia'][0]['video']}}">
									<?php }?>
										
										</ul>
										<input type="hidden" name="flag_video" id="flag_video" value="{{$data['get_event_mapping_mdedia'][0]['flag_video']}}">
									<?php }else{?>
										<ul class="dynamic_imgs" style="display: none;"></ul>
										<input type="hidden" name="video" id="video" value="">
										<input type="hidden" name="flag_video" id="flag_video" value="">
									<?php } ?>
								
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
			                 	<textarea class="form-control" <?php echo $event_edit_flag_upto_days; ?> id="descriptions" maxlength="1000" name="event_description" rows="4" placeholder="Write a short description of your event">{{$data['description'] or '' }}</textarea>
			                 	<div id="charnum">0 / 1000 characters remaining</div>
			                </div>
						</div>
						
						
						<div class=" col-md-6">
				             <div class="input-group event_pub_url">
					            <span class="input-group-addon">
					                {{env('SITE_URL')}}/
					            </span>
				            <input type="text" name="publish_url" <?php echo $event_edit_flag_upto_days; ?> id="publish_url" value="{{$data['publish_url'] or '' }}" class="form-control" placeholder="Event Publish Url">
				            </div>
				        </div>
						
					</div>
					
					<input type="hidden" id="age_range" name="age_range" value="{{ $data['event_age_range'] or '' }}">
					<input type="hidden" id="zipcode" name="zipcode" value="{{ $data['event_zipcode'] or '' }}">
					<input type="hidden" id="favourite_activity" name="favourite_activity" value="{{ $data['event_favourite_activity'] or '' }}">
					<input type="hidden" id="event_end_date" name="event_end_date" value="{{ $data['event_end_date'] or '' }}">
					<input type="hidden" id="other_tags" name="other_tags" value="{{ $data['event_other_tags'] or '' }}">
					<input type="hidden" id="events_id" name="event_id" value="{{ $event_id or '' }}">
					<input type="hidden" id="user_last_event_stripe_id" name="user_last_event_stripe_id" value="{{ $user_last_event_stripe_id or '0' }}">
					@if($stripe_user_id=="" || $stripe_user_id=="0" || count($my_experience->getEventExperiences) == 0)
						<input type="hidden" id="update_stripe_flag" name="update_stripe_flag" value="0">
					@else
						<input type="hidden" id="update_stripe_flag" name="update_stripe_flag" value="1">
					@endif
					<input type="hidden" id="event_publish_flag" name="event_publish_flag" value="0">
					<?php
					$data = session()->all();
					
					if(isset($data['yelp_experience']))
					{
						$update_session_value= "1";
					}else{
						$update_session_value= "0";
					}
					?>
					<input type="hidden" id="home_page_yelp_session" name="home_page_yelp_session" value="{{$update_session_value}}">
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</section>


<!-- End -->

<!-- Experiences -->
@include('site.experience.index')
<!-- End -->

@include('site.modal.event-image-modal')
@include('site.modal.add-banking-info-modal')

{{Html::script("/front/common/event/event_create.js")}}
{{Html::script("/front/common/experience/experience_create.js")}}
{{Html::script("/front/common/comment/comment.js")}}

@endsection
