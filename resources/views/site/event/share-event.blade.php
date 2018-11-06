@extends('layouts.app_front')
@section('header')
  @include('site.event_create_header')
@stop
@section('pagetitle', 'Event')
@section('content')

<!-- Share Page -->
<section class="contact share-pg">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				@include('layouts.front-notifications')
				<h2>Share your page with friends and family</h2>
				<p>{{$event_data->description}}</p>
				<?php
					$event_id="0";
					$preview_url="";
					if(count($event_data) > 0)
					{
						$preview_url = url('/'.$event_data->publish_url);
						$event_id= $event_data->id;
					}
					
				?>
				<div class="custom-link">
					<div class="row">
						<div class="col-md-3"><p>Your custom URL</p></div>
						<div class="col-md-6">
							<div class="form-group custom_event_url">
			                  <input type="text" name="url" id="custom_url" class="form-control" placeholder="Enter URL" value="{{$preview_url}}">
			                </div>
			                <label id="url-exists-error" class="hide" for="my_input">Publish url already exists,please try another.</label>
						</div>
						
						<div class="col-md-3"><a href="javascript:void(0)" onclick="copyToClipboard('#custom_url')" class="commont-btn copy_url">COPY LINK</a></div>
					</div>
				</div>
				<ul>
					<li>
						<a target="_blank" id="shareonfacebook" class="fb-share"><img src="{{ asset('front/images/facebook.png') }}" alt="facebook" title=""></a> 
						<!-- <a target="_blank" class="fb-share" href="http://www.facebook.com/sharer.php?
											    s=100
											    &p[url]={{$preview_url}}
											    &p[title]={{$event_data->title}}
											    &p[summary]={{$event_data->description}}">
							<img src="{{ asset('front/images/facebook.png') }}" alt="facebook" title="">
						</a> -->
					</li>
					<li>
						<a target="_blank" class="fb-share" href="http://twitter.com/share?url={{$preview_url}}"><img src="{{ asset('front/images/twitter.png') }}" alt="twitter" title=""></a>
					</li>
				</ul>
				<div class="brd-title">
					<h3>OR</h3>
				</div>
				<?php
				
					$event_img="";
			
					if(count($my_experience) > 0)
					{
						if(isset($my_experience->getEventMappingMdedia) && count($my_experience->getEventMappingMdedia) > 0)
						{
							$event_img= $my_experience->getEventMappingMdedia[0]->image;
						}
					}
					
				?>
				{!! Form::open(array('url'=>'/share-event', 'class'=>'form-horizontal','method'=>'POST','id'=>'event_share_form','files'=>true)) !!}
					<div class="form-group">
						<div class="multiple-val-input">
						    <ul>
						        <input type="text" name="email" class="form-control" id="my_input" placeholder="Friend email address">
						        <span class="input_hidden"></span>
						    </ul>
						</div>
	                </div>
	                <div class="form-group">
	                  <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject">
	                </div>
	                <div class="form-group">
					    <textarea class="form-control" name="description" id="description" rows="9" placeholder="Type here…"></textarea>
					    <input type="hidden" name="publish_url" value="{{$event_data->publish_url}}">
					    <input type="hidden" name="event_name" value="{{$event_data->title}}">
					    <input type="hidden" name="event_id" value="{{$event_id}}">
					    <input type="hidden" class="any_one_img" value="{{$event_img ?? ''}}"> 
					</div>
					<input type="submit" name="send" value="SEND">
				{!! Form::close() !!}
			</div>
		</div>
	</div>
</section>

<script>
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
 	
 	var preview_url='<?php echo $preview_url;?>';
	var title='<?php echo $event_data->title;?>';
	var desc= '<?php echo $event_data->description; ?>';
	var urls= window.base_url;
	var get_img= $('.any_one_img').val();
	var img= window.base_url+'/storage/Event/'+get_img;
	
	if(img=="" || img== undefined)
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
<!-- End -->

{{Html::script("/front/common/event/funding-report.js")}}

@endsection