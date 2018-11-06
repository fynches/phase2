<?php
namespace App\Http\Controllers\Site;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Offer;
use DB;
use Session;

use Route;
use App\ActivityLog;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Yajra\Datatables\Datatables;
use App\Testimonial;
use App\StaticBlock;
use App\Beta_Signup;
use Response;
use Mail;
use App\EmailTemplates;

class HomeController extends Controller
{
    /*   
	 	Added by Devang Mavani
      	listing home page of fynches
	*/ 
   /*public function index()
    {
    	Session::forget('yelp_experience');
    	$banner_section_block = StaticBlock::where('slug','banner-section')->first();
		$work_section_block = StaticBlock::where('slug','how-work-section')->first();
		$happiness_section_block = StaticBlock::where('slug','happiness-home-section')->first();
		
        $testimonails= Testimonial::orderBy('updated_at','desc')->where('status', "Active")->limit(2)->get();
		return view('site.index', ['banner_section_block' => $banner_section_block, 'work_section_block' =>$work_section_block,'happiness_section_block' =>$happiness_section_block ,'testimonails' => $testimonails]);
    }*/

    public function index()
    {        
        return view('site.beta-signup');
    }

    public function betaSignup()
    {
        $testimonails= Testimonial::orderBy('updated_at','desc')->where('status', "Active")->limit(5)->get();        
        $ourMission = StaticBlock::where('slug','happiness-is-our-mission')->first();
        $gifting = StaticBlock::where('slug','gifting-made-easy-peasy')->first();
        $giftsExperiences = StaticBlock::where('slug','gifts-and-experiences')->first();
        $howItWorks = StaticBlock::where('slug','how-it-works')->first();
        //pr($ourMission);exit;
        return view('site.beta-signup.beta-signup',  [
            'testimonails' => $testimonails,
            'ourMission' => $ourMission,
            'gifting' => $gifting,
            'giftsExperiences' => $giftsExperiences,
            'howItWorks' => $howItWorks
            ]);
    }
    public function storeBetaUser(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($request->all(), [
            'firstName' => 'required',
            'email' => 'required|email|unique:beta_signup,email',
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all(), 'status' => 0]);
        }

        $user = Beta_Signup::create([
            'first_name' => $data['firstName'],
            'email' => $data['email'],
        ]);
        $this->betaSignupMail($data['email']);
        return Response::json($user);
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
