<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use Route;
use App\User;
use Session;
use Response;

class SiteLoginController extends Controller
{
   
    public function __construct()
    {
    	//$this->middleware('guest:site', ['except' => ['logout']]);
    }
    
    public function showLoginForm()
    {
      return view('auth.site_login');
    }
	
	public function showRegisterForm()
    {
      return view('auth.site_register');
    }
    
    public function login(Request $request)
    {
    	//echo 'hereeeee';die;
      // Validate the form data
      $checkout_data = Session::get('checkout_data');
	  $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required'
      ]);
      
	  $email = $request->email;
	  $previous_url =  url()->previous();
	  $user = User::where('email', $email)->first();
	  //pr($user->status);die;
	  if(count($user) > 0)
	  {
	  	if($user->user_type =="1" || $user->user_type == "2")
		{
			return Response::json(['status' => 3, 'reirect_url' => $previous_url]);
		}
		else if($user->status =="Inactive" && $user->token!="")
		{
			return Response::json(['status' => 5, 'reirect_url' => $previous_url]);
		}
		else if($user->status =="Inactive" && $user->token=="")
		{
			return Response::json(['status' => 2, 'reirect_url' => $previous_url]);
		}
		else if(count($checkout_data) > 0)
		{
			if (Auth::guard('site')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
      			return Response::json(['status' => 1, 'reirect_url' => $previous_url]);
      		}else{
      			return Response::json(['status' => 3, 'reirect_url' => $previous_url]);
      		}
		}
		else{
			 if (Auth::guard('site')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
      			return Response::json(['status' => 1, 'reirect_url' => $previous_url]);
      		}else{
      			return Response::json(['status' => 3, 'reirect_url' => $previous_url]);
      		}
		}
	  }else{
	  	return Response::json(['status' => 6, 'reirect_url' => $previous_url]);
	  }
	   
    }
	
	public function register(Request $request)
    {
    	 $validator = Validator::make($request->all(), [
                    'first_name'   => 'required',
			      	'last_name'   => 'required',
			      	//'email' => 'required|max:200|unique:users,email,NULL,id,deleted_at,NULL',
			      	'email' => 'required|max:200|unique:users,email,NULL,id,deleted_at,NULL',
			      	'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return redirect('/signup')
                            ->withInput()
                            ->withErrors($validator);
        }
    	
	  //pr($request->all());die;
	  $token = str_random(18);
	  
	  $reg_users= User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'token' =>$token,
            'status' => 'Inactive',
            'user_type' => "3",
            'password' => Hash::make($request->password),
        ]);
		
		//pr($reg_users);die;
		
		//$user = Auth::guard('site')->loginUsingId($reg_users->id);
		
		
        $redirect_url= env('SITE_URL');
		
		$VerificationLink = env('SITE_URL').'/verify/'.$token;
        
		$user_name= $request->first_name.' '.$request->last_name;
		
		$search = array("[USERNAME]","[EMAIL]","[WEBSITE_URL]","[VERIFICATION_LINK]");
        $replace = array($user_name,$request->email, $redirect_url, $VerificationLink);
		
        $emailParams = array(
            'subject' => 'Fynches Signin',
            'from' => config('constant.fromEmail'),
            'to' => $request->email,
            'email'=>$request->email,
            'redirect_url'=>env('SITE_URL'),
            'template'=>'new-register',
            'search' => $search,
            'replace' => $replace
        );
		
		$result = User::SendEmail($emailParams);
		
		// if (!$user)
		// {
		    // throw new Exception('Error logging in');
		// }
		Session::flash('success_msg', 'Email Verify link sent to your mail.Please check your mail.');
		return redirect($redirect_url.'/signup');
	}
	
	public function validate_email(Request $request) {
		
        if ($request->input('email') !== '') {
            if ($request->input('email')) {
                $rule = array('email' => 'required|max:200|unique:users,email,NULL,id,deleted_at,NULL');
                $validator = Validator::make($request->all(), $rule);
            }
            if (!$validator->fails()) {
                die('true');
            }
        }
        die('false');
    }
	
	public function verifyToken($token) {
		
        if (isset($token) && $token !='') {
	        $site_url= env('SITE_URL');
	        $user = User::where('token',$token)
	        		->where('token','!=',NULL)->first();	        
	        //echo '<pre>';print_r($user);exit;
	        if ($user) {
	        	$user->token = NULL;
	        	$user->status = 'Active';
	        	$user->save();
				Session::flash('success_msg', 'Email Verify successfully.');
	            return redirect($site_url.'/signup');
	        } else {
	        	Session::flash('error_msg', 'Invalid token.');
	        	return redirect($site_url.'/signup');	            
    		}
	    }
	}
    
    public function logout()
    {
    	Auth::guard('site')->logout();
        return redirect('/');
    }
}
