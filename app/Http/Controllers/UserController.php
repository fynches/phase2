<?php

namespace App\Http\Controllers;

use App\Products;
use App\Registration;
use App\Beta_Signup;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use DB;
use Hash;
use Socialite;
use Mail;
use App\EmailTemplates;

class UserController extends Controller {
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	


	public function betaSignup(Request $request) {
		
		$beta_signup_users = new Beta_Signup;
		$result= $request->json()->all();
		$validator = Validator::make($request->json()->all(), [
			'email' => 'required',
		]);

		if ($validator->fails()) {
			return redirect()
				->back()
				->withInput()
				->withErrors($validator);
		}

		$beta_signup_users->email = $result['email'];

		$user = Beta_Signup::where("email",$result['email'])->first();

		if (count($user) > 0) {
			return response()->json(['status' => '2']);
		}else{
			if ($beta_signup_users->save()) {
				$this->betaSignupMail($result['email']);
				return response()->json(['status' => 'success']);
			}
		}
	}

	//Added by Devang Mavani
	//send mail to beta signup user
	public function betaSignupMail($email) {
		
        if ($email && $email != "") {
            
	        $email = $email;
			$search = array("[EMAIL]");
            $replace = array($email);
			
	        $params = array(
	            'subject' => 'Fynches Beta Signup',
	            'from' => "amits@techuz.com",
	            'to' => $email,
	            'search' => $search,
	            'replace' => $replace
	        );
	        
			$result = $this->SendEmail($params);
	
	        if ($result == true) {
				return true;
	        } else {
	            return false;
	        }
        } 
    }
	
	public function SendEmail($params) {
		

		try {
			$sent_mail_to= $params['to'];
		
			/*$config = array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.gmail.com',
				'smtp_port' => 465,
				'smtp_user' => "techuzit@gmail.com", //SITE_EMAIL_ID_SHK change it to yours
				'smtp_pass' => "techuzit123456", // SITE_PASSWORD_SHK change it to yours 
				'mailtype' => 'html',
				'charset' => 'iso-8859-1',
				'wordwrap' => TRUE
			);*/
			$avatar = public_path()."/assets/pages/img/Fynches_Logo_Teal.png"; 
			$facebooklogo = public_path()."/assets/pages/img/facebook-logo.png"; 
			$twitterlogo = public_path()."/assets/pages/img/twitter-logo.png"; 
			$instagramlogo = public_path()."/assets/pages/img/instagram-logo.png";
			
			$email_template = EmailTemplates::where('slug', '=','beta-signup' )->first();
			
			$search = array("[EMAIL]");
	        $replace = array($sent_mail_to);
				
			$message = str_replace($search, $replace, $email_template["content"]);

			//$message="Thanks for signing up. We’ll keep you up to date on the latest and greatest with Fynches.";
			
			$mail_data = array('content' => $message, 'toEmail' => $params["to"], 'subject' => $params["subject"], 'from' => "team@fynches.com",
					'avatar'=>$avatar,'facebooklogo'=>$facebooklogo,'twitterlogo'=>$twitterlogo,'instagramlogo'=>$instagramlogo);


			$sent = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
						$message->from($mail_data['from'], 'Fynches');
						$message->to($mail_data['toEmail']);
						$message->subject($mail_data['subject']);
					});
		
		
			if ($sent == true) {
				return true;
			} else {
				return false;
			}
		} catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}
		
    }
	

	
}
