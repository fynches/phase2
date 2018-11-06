<?php
namespace App\Http\Controllers\Site;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\EmailTemplates;
use App\Cms;
use DB;
use Session;
use Mail;
use Route;
use App\ActivityLog;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	//$this->middleware('guest:site');
    }

   	/*   
	 	Added by Devang Mavani
      	listing about-us page of fynches
	*/
    public function getaboutUs(Request $request)
    {
    	$aboutUs = Cms::where('slug', 'about-us')->first();
		return view('site.about-us', ['aboutUs' => $aboutUs]);
    }
	
	/*   
	 	Added by Devang Mavani
      	listing contact-us page of fynches
	*/
	public function getcontactUs(Request $request)
    {
    	$contactUs = Cms::where('slug', 'contact-us')->first();
		return view('site.contact-us', ['contactUs' => $contactUs]);
	}
	
	/*   
	 	Added by Devang Mavani
      	listing terms and condition page of fynches
	*/
	public function getTermsCondition(Request $request)
    {
    	//return view('site.terms-condition');
    	$terms_and_condition = Cms::where('slug', 'terms-and-condition')->first();
		return view('site.terms-condition', ['terms_and_condition' => $terms_and_condition]);	
	}
	
	/*   
	 	Added by Devang Mavani
      	listing privacy policy page of fynches
	*/
	public function getPrivacyPolicy(Request $request)
    {
    	$privacyPolicy = Cms::where('slug', 'privacy-policy')->first();
		return view('site.privacy-policy', ['privacyPolicy' => $privacyPolicy]);
	}
	
	
	public function getFAQ(Request $request)
    {
    	$faq = Cms::where('slug', 'faq')->first();
		return view('site.faq', ['faq' => $faq]);
	}

	public function NeedHelp(Request $request)
    {
    	$need_help = Cms::where('slug', 'need-help')->first();
		return view('site.need-help', ['need_help' => $need_help]);
	}

	public function getHowFynchesWorks(Request $request)
    {
    	$howfynchesworks = Cms::where('slug', 'how-fynches-works')->first();
		return view('site.how-fynches-work', ['how_fynches_works' => $howfynchesworks]);
    }
	
	
	
	
	/*   
	 	Added by Devang Mavani
      	listing reset password form
	*/
	public function reset_password($token)
	{
		return view('site.reset-password',['token' => $token]);	
	}
	
	/*   
	 	Added by Devang Mavani
      	update passwod functionality 
	*/
	public function update_password(Request $request)
	{
		$user = User::where('email', $request->email)->first();
	
		if(count($user) > 0)
		{
			$db_token= $user->verify_forgot_password;
			
			if($request->_token == $db_token)
			{
				$user->password=bcrypt($request->password);
				$user->verify_forgot_password='';
				$user->save();
				Session::flash('success_msg', 'Your password has been successfully updated.');
            	return redirect('signup');
			}else{
				Session::flash('error_msg', 'Invalid token.');
				Session::save();
            	return redirect('password_reset/'.$request->_token);
			}
		}else{
			Session::flash('error_msg', 'Invalid token.');
			Session::save();
			return back()->with('error_msg','Invalid email id for this token request.');
		}
	}
	
	/*   
	 	Added by Devang Mavani
      	Send mail for forgot password to user functionality
	*/
	public function forgot_password(Request $request)
	{
		try {
        	
			$user = User::where('email', $request->email)->where('user_type','3')->first();
			
			if(count($user) > 0)
			{
				$link = url( "/password/resets/" . $request->token );
			
				$email_template = EmailTemplates::where('slug', '=','forgot-password' )->first();
				
				$search = array("[WEBSITE_URL]");
	        	$replace = array($link);
				
				$message = str_replace($search, $replace, $email_template["content"]);
				
	            $sent_mail_to= $request->email; 
				$from_email= config('constant.fromEmail');
				
	            $avatar = public_path()."/assets/pages/img/Fynches_Logo_Teal.png"; 
	            $facebooklogo = public_path()."/assets/pages/img/facebook-logo.png"; 
	            $twitterlogo = public_path()."/assets/pages/img/twitter-logo.png"; 
	            $instagramlogo = public_path()."/assets/pages/img/instagram-logo.png"; 
	             
	            $mail_data = array('content' => $message,'toEmail' => $request->email, 'subject' => 'Reset Password', 'from' => $from_email
	                                ,'avatar'=>$avatar,'facebooklogo'=>$facebooklogo,'twitterlogo'=>$twitterlogo,'instagramlogo'=>$instagramlogo,'link'=>$link);
				//pr($mail_data);die;
	            $sent = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
	                        $message->from($mail_data['from'], 'Fynches');
	                        $message->to($mail_data['toEmail']);
	                        $message->subject($mail_data['subject']);
	                    });
						
				
				$user->verify_forgot_password=$request->token;
				$user->save();
				
				if(count($user) > 0)
				{
					if($user->status=="Active")
					{
						Session::flash('success_msg', 'Reset Password Link sent to your email id.');
				        Session::save();
						// on success then flag 1
				        echo 1;
				        exit;
					}else{
						// Inactive user then flag 2
						Session::flash('error_msg', 'You are Inactive by admin.Please contact to admin.');
				        Session::save();
						echo '2';
						exit;
					}
					
				}else{
					// Email id not found in database then flag 3
					Session::flash('error_msg', 'Email id not found.');
				    Session::save();
					echo '3';
					exit;
				}
			}
			else{
				// Email id not found in database then flag 3
				Session::flash('error_msg', 'Email id not found.');
				Session::save();
				echo '3';
				exit;
			}		 
        } catch(Exception $e){
            echo 'Message: ' .$e->getMessage();
        }
		
	}
}
