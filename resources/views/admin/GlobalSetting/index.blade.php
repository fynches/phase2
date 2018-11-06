@extends('layouts.admin')
@section('content')

<?php

		$global_setting_id="";
		$global_secret_key="";
		$global_publish_key="";
		$global_stripe_client_key="";
		$global_commission="";
		
		$global_fb_client_id="";
		$global_fb_client_secret="";
		$global_fb_redirect="";
		
		$global_google_plus_client_id="";
		$global_google_plus_secret="";
		$global_google_plus_redirect="";
		
		if(count($global_setting) > 0)
		{
			$global_setting_id=$global_setting->id;
			
			$global_secret_key=$global_setting->secret_key;
			$global_publish_key=$global_setting->publish_key;
			$global_stripe_client_key= $global_setting->stripe_client_id;
			
			$global_commission=$global_setting->commission;
			
			$global_fb_client_id=$global_setting->fb_client_id;
			$global_fb_client_secret=$global_setting->fb_client_secret;
			$global_fb_redirect=$global_setting->fb_redirect;
			
			$global_google_plus_client_id=$global_setting->google_plus_client_id;
			$global_google_plus_secret=$global_setting->google_plus_secret;
			$global_google_plus_redirect=$global_setting->google_plus_redirect;
		}
?>
<div class="portlet light">
	
	{!! Form::open(array('url'=>'/admin/globalsetting/update','class'=>'form-horizontal','method'=>'POST','id'=>'global_setting')) !!}
    
    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('secret_key', 'Stripe Secret Key'); !!}
            {!! Form::text('secret_key',$global_secret_key,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
     <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('publish_key', 'Stripe Publish Key'); !!}
            {!! Form::text('publish_key',$global_publish_key,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
    <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('stripe_client_id', 'Stripe Client Id'); !!}
            {!! Form::text('stripe_client_id',$global_stripe_client_key,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
     <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('commission', 'Commission'); !!}
            {!! Form::text('commission',$global_commission,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
     <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('fb_client_id', 'Facebook Client Id'); !!}
            {!! Form::text('fb_client_id',$global_fb_client_id,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
     <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('fb_client_secret', 'Facebook Secret Id'); !!}
            {!! Form::text('fb_client_secret',$global_fb_client_secret,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
     <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('fb_redirect', 'Facebook Redirect Url'); !!}
            {!! Form::text('fb_redirect',$global_fb_redirect,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
     <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('google_plus_client_id', 'Google Plus Client Id'); !!}
            {!! Form::text('google_plus_client_id',$global_google_plus_client_id,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
     <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('google_plus_secret', 'Google Plus Secret Id'); !!}
            {!! Form::text('google_plus_secret',$global_google_plus_secret,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>
    
     <div class="form-group">
        <div class='col-md-12'>
            {!! Form::label('google_plus_redirect', 'Google Plus Redirect Url'); !!}
            {!! Form::text('google_plus_redirect',$global_google_plus_redirect,array('class'=>'form-control','autocomplete'=>'off')) !!}
        </div>
    </div>


    <div class="form-group">
        <div class='col-md-12'>
        	<input type="hidden" id="global_setting_id" name="global_setting_id" value="{{$global_setting_id}}">
            {!! Form::submit('Update',array('class'=>'btn btn-primary')); !!}
        </div>
    </div>
    
    

    {!! Form::close() !!}
</div>

{{Html::script("/assets/common/global_setting/global_setting.js")}}

@endsection