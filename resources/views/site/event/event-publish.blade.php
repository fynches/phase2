@extends('layouts.app_front')
@section('header')
	@include('site.header')
@stop

@section('pagetitle', 'Event')
@section('content')
<section class="slider-sec">
	<div class="container">
		<div class="inner-slider">
			<div class="row">
				<div class="col-md-6">
					<?php
						
						$imgPath="";
						$defaultPath = config('constant.imageNotFound');
						
						if(isset($my_experience->getEventMappingMdedia) && count($my_experience->getEventMappingMdedia) > 0)
						{
							if($my_experience->getEventMappingMdedia[0]->image_type=="0" || $my_experience->getEventMappingMdedia[0]->image_type=="2" )
							{ 
								?>
								
								<div class="owl-carousel">
								
							<?php }
							foreach($my_experience->getEventMappingMdedia as $key=>$val)
							{
								if($val['flag_video']=="1" && $val['video']!="") ////for local video show
								{
									$video = $val['video'];                    
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
									<div class="item">
										<li>
											<video height="280px" controls>
													<source src="{{ asset($imgPath) }}" type="video/mp4">
													<source src="{{ asset($imgPath) }}" type="video/ogg">
													Your browser does not support the video tag.
											</video>
										</li>
									</div>	
										
								<?php
								 }
								 else if($val['flag_video']=="0" && $val['video']!="") //for you tube video show
								{ ?>
									<div class="item">
										<li>
											<iframe width="460" height="220" src="{{$val['video']}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
										</li>
									</div>	
							<?php }
								else if($val['image_type']=="2")
								{ //facebook images ?> 
								
								
									<div class="item">
										<img src="{{$val['image']}}" alt="" title="" class="img-fluid">
									</div>
									
								
							<?php } 
							else {
									 //local images 
									 
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
										}
									 
									 ?> 
										<div class="item">
											<img src="{{ asset($imgPath) }}" alt="" title="" class="img-fluid">											
										</div>
									
							<?php }
							}
							if($my_experience->getEventMappingMdedia[0]->image_type=="0" || $my_experience->getEventMappingMdedia[0]->image_type=="2" )
							{?>
							</div>
							<?php 
							}	
						}
					?>
				</div>
				<div class="col-md-6 pl-4">
					<h2>{{ $my_experience->title ?? ''}}</h2>
					<p>{{ $my_experience->description ?? '' }}</p>

					<div class="user-icon">
						<div class="user"><img src="{{ asset('storage/avatar.jpg') }}" alt="" title=""></div>
						<?php
						//pr($my_experience);die;
							$first_name= "";
							$last_name= "";
							if(count($my_experience) > 0)
							{
								$first_name = $my_experience->getUser->first_name;
								$last_name= $my_experience->getUser->last_name;
							}
							
							$event_pub_date="";
							if(isset($my_experience->event_end_date))
							{
								$event_end_date= date('F d, Y',strtotime($my_experience->event_end_date));
							}
						?>
						<span>Hosted by {{ $first_name .' '.$last_name}}</span>
					</div>
					<div class="row">
						<div class="col-sm-9 col-md-9">
							<label>*Fundraising ends {{$event_end_date}}</label>
						</div>
						<div class="col-sm-3 col-md-3">
							<div class="social-sec">
								<a target="_blank" class="fb-share" href="http://twitter.com/share?url={{$event_publish_url}}" title="Click to share this post on Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
								<a target="_blank" id="shareonfacebook" class="fb-share"><i class="fa fa-facebook" aria-hidden="true"></i></a> 
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>

		<div class="accordian-sec">
			<div class="row">
				<div class="col-md-12">
					<ul id="tabs" class="nav nav-tabs" role="tablist">
						<li class="nav-item">
						  <a id="tab-A" href="#pane-A" class="nav-link active" data-toggle="tab" role="tab">My experiences</a>
						</li>
						<li class="nav-item">
						  <a id="tab-B" href="#pane-B" class="nav-link comment-tab" data-toggle="tab" role="tab">Comments ({{ $total_comment }})</a>
						</li>
					</ul>
					<div id="content" class="tab-content" role="tablist">
						<div id="pane-A" class="tab-pane fade show active" role="tabpanel" aria-labelledby="tab-A">
							<div  role="tab" id="heading-A">
								<div class="row">
									<?php
										$funded_cls = "";
									?>
									@if(isset($my_experience)  && isset($my_experience->getEventExperiences))
				          				@if(count($my_experience->getEventExperiences) > 0)
				          				@foreach($my_experience->getEventExperiences as $key=>$val)
				          				
				          				<?php
				          					$funded_cls = "";
									        $defaultPath = 'storage/image-not-found.png';
									        $ExpImage = $val['image'];
											
											$URL=$val['image'];
						          		  	if(strpos($URL, "https://") !== false)
											{
												$flag= '1';
											}else{
												$flag= '2';
											}
											
									        if($flag == "2")
											{
												$defaultPath = 'storage/no-img.jpg';
										        $ExpImage = $val['image'];
										
										        if ($ExpImage && $ExpImage != "") {
										            
										            $imgPath = 'storage/experienceImages/thumb/' . $ExpImage;
										           
										            if (file_exists($imgPath))
										            {
										                $imgPath = $imgPath;
										            } else {
										                $imgPath = $defaultPath;
										            }
										        } else {
										            $imgPath = $defaultPath;
										        }
											}else{
												$imgPath = $val['image'];
											}
											
											
											$received_amount="0";
											$remaining_amount="";
											
											if(isset($my_experience) && count($my_experience['FundingReport']) >0)
											{
												foreach($my_experience['FundingReport'] as $val2)
												{
													//pr($val2);die;
													if($val->id == $val2->experience_id)
													{
														$received_amount += $val2['donated_amount'];	
													}
													
												}
												 $received_amount = $received_amount;
												
												if($received_amount >= $val->gift_needed){
													//$funded_cls = "funded_cls";
													$funded_cls='cart-added';
												} 
											}
									        ?>
									        
									        <div class="col-sm-4 my_experiences publish_exp_{{$val->id}}">
									  			<div class="card">
												  <div class="card-header {{$funded_cls}}">
												  	<img src="{{ asset($imgPath) }}" alt="" title="">
												  	@if($received_amount >= $val->gift_needed)
												  		 <span id="exp_fund">FUNDED!</span> 
												  	@endif
													<div class="verify">
														@if($received_amount < $val->gift_needed)
															<img src="{{ asset('/front/images/verified.png') }}" alt="" title="">
															<div class="cls">
																<a href="javascript:void(0)" class="remove_fund"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;Cancel</a>
															</div>
														@endif	
													</div>
												  </div> 
												  <div class="card-body">
												    <h5 class="card-title">{{$val->exp_name}} </h5>
												    <p class="card-text">{{$val->description}}</p>
												    
												  </div>
												  <div class="card-footer">
												  	<div class="row">
												  		<div class="col-3 col-sm-3">
												  			<h4>${{$received_amount}}</h4>
												  			<input type="hidden" class="received_vals" value="{{$received_amount}}">
												  			<span>Received</span>
												  		</div>
												  		<div class="col-3 col-sm-3">
												  			<!-- <h4>${{$val->gift_needed}}</h4> -->
												  			<!-- <h4>${{$val->gift_needed - $received_amount }}</h4> -->
												  			
												  				@if($received_amount > $val->gift_needed)
																	<h4>$0</h4>
																@else
																	<h4>{{$val->gift_needed - $received_amount}}</h4>
																@endif
												  			
												  			<input type="hidden" class="actual_gift_needed" value="{{$val->gift_needed}}">
												  			<span>Needed</span>
												  		</div>
												  		<div class="col-6 col-sm-6 gift_need_{{$val->id}}">
												  			@if($received_amount >= $val->gift_needed)
												  				<a href="javascript:void(0)"  data-exp-id="{{$val->id}}" class="commont-btn give_gift disable">GIVE THIS GIFT</a>
												  			@else
												  				<a href="javascript:void(0)" onclick="give_gift({{$val->id}})" data-exp-id="{{$val->id}}" class="commont-btn give_gift">GIVE THIS GIFT</a>
												  			@endif	
												  		</div>
												  	</div>
												  </div>
												</div>
							  				</div>
				          				@endforeach
				          				@endif
				          			@endif		
							  	</div>
							</div>
						</div>

						<div id="pane-B" class="tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
							<div  role="tab" id="heading-B">							
								<div class="comment-box ds-comment-box">
									<div class="row">
										<div class="col-sm-12">
											<!-- Form -->
											<?php $defaultPath = 'storage/avatar.jpg';?>
											<div class="addcmt border-btm">
												<div class="row pb-4">
													<div class="col-sm-2">
														<div class="circle-img">
															<img class="circle-img" src="{{ asset($defaultPath) }}" alt="" title="">
														</div>
													</div>
													<div class="col-sm-10 ds-btm-space">
														<div class="form-group">
															<textarea class="form-control comment-desc" id="comment_description"  name="comment_description" rows="5" placeholder="Add a comment" maxlength="1000"></textarea>
															@if (Auth::guard('site')->id())
																<a href="javascript:void(0)" class="commont-btn add-comment">ADD COMMENT</a>
															@else
																<a href="javascript:void(0)" class="commont-btn" data-toggle="modal" data-target="#login">ADD COMMENT</a>
															@endif															
															<label class="custom_msgs">1000 characters remaining</label>
														</div>
													</div>
												</div>
											</div>
											<!-- Form ENDDD -->
											
											<div class="comment-loop">												
											<?php 
											printCategory($tree, 0, Auth::guard('site')->id());
											?>
											</div>
											<div class="load-html"></div>
										</div>
									</div>
									@if($comments->hasMorePages())
									<div class="load-more-div">
										<a href="javascript:void(0);" class="load-more">Load more</a>
									</div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="gift-amount">
			<div class="row">
				<div class="col-sm-3"><img src="{{ asset('front/images/gift-birds.png') }}" alt="" title=""> </div>
				<div class="col-sm-7">
					<h3>Make a gift of any amount</h3>
					<p>Send a cash gift of your choosing.</p>
				</div>
				<div class="col-sm-2">
					<input type="text" class="bonus_amount additional_amts number" name="bonus_amount" id="bonus_amount">
					<span>$</span>
				</div>
			</div>
		</div>

	</div>
</section>
<!-- End -->
<!-- Checkout Sec -->
<section class="checkout-sec">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<h3 class="total_amount">Total: $0 </h3>
			</div>
				<div class="col-sm-12 col-md-6">
					{!! Form::open(array('url'=>'/checkout-event', 'class'=>'form-horizontal','method'=>'POST','id'=>'checkout_form')) !!}
						<a href="javascript:void(0)" class="commont-btn checkout_page">CHECKOUT</a>
						<input type="hidden" id="total_items" name="total_items" value="">
						<input type="hidden" class="bonus_amount_value" name="bonus_amount" value="0">
					{!! Form::close() !!}	
				</div>
		</div>
	</div>
</section>
<!-- End -->
<!-- fynches-work Sec -->
<section class="fynches-work">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<img src="{{ asset('front/images/circle-img.png') }}" alt="" title="" class="img-fluid">
			</div>
			<div class="col-md-6">
				<h2>You’re awesome!</h2>
				<p>Choosing to gift a child with an experience is an amazing way to help them learn, grow, and help cultivate a lifetime of adventures.</p>
				<a href="{{ url('how-fynches-work') }}" class="commont-btn">HOW FYNCHES WORKS</a>
			</div>
		</div>
	</div>
</section>

<script>

	// $( document ).ready(function() {
		// var get_img = $('.item img').attr('src');
		// if(get_img==undefined)
		// {
			// get_img=  window.base_url+'/front/images/Fynches_Logo_Teal.png';
		// }
		// alert(get_img);
	// });
	
    window.fbAsyncInit = function() {
	  FB.init({
	    appId      : 2043136885924472,
	    xfbml      : true,
	    version    : 'v2.4'
	  });
	};

(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));
 
 
 $('#shareonfacebook').on('click', function() {
 	
 	var preview_url='<?php echo $event_publish_url;?>';
	var title='<?php echo $my_experience->title;?>';
	var desc= '<?php echo $my_experience->description; ?>';
	var urls= window.base_url;
	var img=  $('.item img').attr('src');
	
	if(img==undefined)
	{
		img=  window.base_url+'/front/images/Fynches_Logo_Teal.png';
	}
	
    FB.ui({
        method: 'share_open_graph',
        action_type: 'og.shares',
        action_properties: JSON.stringify({
            // object : {
               // 'og:url': 'https://devfynches.com',
               // 'og:title': 'devangmavani',
               // 'og:description': 'birthday event',
               // 'og:og:image:width': '2560',
               // 'og:image:height': '960',
               // 'og:image': 'https://s3-media4.fl.yelpcdn.com/bphoto/rxzd7I7H1zVm0bWrDBCvKw/o.jpg'
            // }
            
             object : {
               'og:url': preview_url,
               'og:title': title,
               'og:description': desc,
               'og:og:image:width': '2560',
               'og:image:height': '960',
               'og:image': img
            }
        })
    });
  });

</script>
<input type="hidden" id="event_id" name="event_id" value="{{$event_id}}">
{{Html::script("/front/common/event/event_publish.js")}}
{{Html::script("/front/common/comment/comment.js")}}
@endsection