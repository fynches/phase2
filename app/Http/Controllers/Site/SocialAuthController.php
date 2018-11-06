<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use Redirect;
use App\User;
use Auth;
use App\GlobalSetting;
use Session;


class SocialAuthController extends Controller {
	
	public function __construct()
    {
      $get_global_setting_data= GlobalSetting::first(); 	
	  //$this->middleware('guest:site', ['except' => ['logout']]);
	  
	  if(count($get_global_setting_data) > 0)
	  {
	  	  config(['services.facebook.client_id' => $get_global_setting_data->fb_client_id]);
		  config(['services.facebook.client_secret' => $get_global_setting_data->fb_client_secret]);
		  config(['services.facebook.redirect' => $get_global_setting_data->fb_redirect]);
		  
		  config(['services.google.client_id' => $get_global_setting_data->google_plus_client_id]);
		  config(['services.google.client_secret' => $get_global_setting_data->google_plus_secret]);
		  config(['services.google.redirect' => $get_global_setting_data->google_plus_redirect]);
	  }
	  
    }
	
	/*   
	 	Added by Devang Mavani
      	redirect to facebook/google plus for login/signup
	*/
	public function redirect($service,$type) {
		session(['type' => $type]);	
		return Socialite::driver ($service)->redirect();
	}
	
	/*   
	 	Added by Devang Mavani
      	facebook/google plus redirect functionality
	*/
	public function callback($service) {
		
		$user = Socialite::driver($service)->user();
		
		$login_type=  session('type');
		
		$chk_users = User::whereEmail($user->email)->first();
		
		
		if($login_type == "login")
		{
			if(count($chk_users) > 0)
			{
				if($chk_users->status=="Inactive")
				{
					Session::flash('error_msg', 'You are inactive by Admin.');
	            	return redirect('signup');
				}
				else
				{
					$user = Auth::guard('site')->loginUsingId($chk_users->id);
					return redirect('dashboard');
				}
			}
			else
			{
				Session::flash('error_msg', 'You are not registered with '.$service.', Please registered.');
	            return redirect('signup');
			}
		}
		else
		{
			if(count($chk_users) > 0)
			{
				$user = Auth::guard('site')->loginUsingId($chk_users->id);
				return redirect('dashboard');
			}
			else
			{
				$user = User::usercreate($user,$service);
				$user = Auth::guard('site')->loginUsingId($user->id);
				
				$redirect_url= env('SITE_URL');
				$VerificationLink = env('SITE_URL');
		        
				$user_name= $user->first_name.' '.$user->last_name;
				
				$search = array("[USERNAME]","[EMAIL]","[WEBSITE_URL]");
		        $replace = array($user_name,$user->email, $VerificationLink);
				
		        $emailParams = array(
		            'subject' => 'Fynches Signin',
		            'from' => config('constant.fromEmail'),
		            'to' => $user->email,
		            'email'=>$user->email,
		            'redirect_url'=>env('SITE_URL'),
		            'template'=>'new-social-register',
		            'search' => $search,
		            'replace' => $replace
		        );
				
				$result = User::SendEmail($emailParams);
				
				return redirect('dashboard');
	    	}
		}
		
		
	}
	
	
}