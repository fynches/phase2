<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
	
	public function sendResetLinkEmail(Request $request)
    {
    	
        $this->validateEmail($request);

        // Check if the user is active, if not then return the response
        $checkFailure = $this->checkUserActive($request->only('email'));
		
        if(!is_null($checkFailure)) {
          return $checkFailure;
        }
	}
	
	public function checkUserActive(array $credentials)
    {
    	//pr($credentials);die;
        // First we will check to see if we found a user at the given
        //  credentials and if we did not we will redirect back to this current
        //  URI with a piece of "flash" data in the session to indicate to the
        //  developers the errors.
        $user = $this->broker()->getUser($credentials);
		//pr($user);die;
        // This check is why we needed this extra method.  We first need to
        // Check with the broker if the user is not null, so that when arent
        //  checking if active on a null object
        if (is_null($user)) {
          return back()->with('error_msg', 'We can\'t find a user with that e-mail
           address.');
        }
		
		if(count($user) > 0 && $user->user_type=="3")
		{
			return back()->with('error_msg', 'You are not authorized to change password');
		}

        // Check if user is active now, before doing anything. In this case, we
        //  just return back with a message, telling them its not active yet.
        if ($user->status=="Inactive") {
            return back()->with('error_msg', 'Your account has not been activated
            yet.');
        }else{
        	
        	$response = $this->broker()->sendResetLink($credentials);
			return back()->with('success_msg', 'We have e-mailed your password reset link!');
        }
    }
}
