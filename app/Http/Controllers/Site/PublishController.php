<?php
namespace App\Http\Controllers\Site;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\EmailTemplates;
use DB;
use Session;
use Mail;
use Route;
use App\ActivityLog;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;
use App\Tag;
use App\Country;
use App\State;
use App\Event;
use App\BillingInformation;
use App\FundingReport;
use App\Experience;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\MappingEventMedia;
use App\GlobalSetting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Socialite;
use Stripe;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Comment;

class PublishController extends Controller
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

	public function publishPage()
	{
		Session::forget('checkoutform');
		$comments=array();	
		$my_experience=array();
		
		$checkout_data = Session::get('checkout_data');
		if(count($checkout_data) > 0)
		{
			Session::forget('checkout_data');	
		}
		
		$current_url= url()->current();
		$get_slug= explode('/',$current_url);
		
		$publish_url= array_reverse($get_slug);
		$title = $publish_url[0];
		
		$event_publish_url= env('SITE_URL').'/'.$title;
		
		$event_data = Event::where('publish_url',$title)->first();
		
		$global_data= GlobalSetting::first();
		$event_id = 0; 
		//pr($event_data);die;
		if(count($event_data) > 0)
		{
			$event_id= $event_data->id;
			
			$my_experience = Event::with(['getUser' => function ($q) {
		            $q->select('first_name', 'last_name','id','email','stripe_user_id');
		        }])->with(['getEventMappingMdedia' => function ($q) {
		            $q->select('video', 'image','image_type','id','event_id','flag_video');
		        }])->with(['getEventExperiences' => function ($q) {
		            $q->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status');
		        }])->with(['FundingReport' => function ($q) {
		            $q->select('id', 'event_id','experience_id','user_id','donated_amount');
					$q->where('status','succeeded');
		        }])->with('getEventTags')->select('id','user_id','title','stripe_user_id','publish_url','description','age_range','event_publish_date','event_end_date','zipcode','status')
		        ->find($event_id);	
		        
		}else{
			return view('site.404',['global_data' =>$global_data]);
		}
		
		
		if(count($my_experience) >0)
		{
			//echo $my_experience->status;die;
			if($my_experience->status== "4" || $my_experience->stripe_user_id=="")
			{
				return view('site.404');
			}
		}
		$comment_limit = env('COMMENT_PER_PAGE');
		$comments = Comment::where('status', 'Active')
			->where('event_id', $event_id)
			->orderBy('created_at','DESC')
			->paginate($comment_limit);
		
		//pr($comments);exit;
		//$comments = $comments->getComments->toArray();
		$comments_array = array();

		if(count($comments)>0){			
			foreach($comments as $row){
				$comment_data = $row->toArray();
				$comment_data['human_date'] = $row->created_at->diffForHumans();
				$comment_data['name'] = $row->getUser->first_name.' '.$row->getUser->last_name;
				$comments_array[] = $comment_data;
			}
		}
		//pr($comments_array); exit;
		//exit;		
		$tree = Comment::buildTree($comments_array,0);
		$comments_total="0";
		
		if(empty($tree))
		{
			$comments_total="0";
		}else{
			$comments_total=count($tree);
		}
		//pr($tree);exit;
		return view('site.event.event-publish', ['title_for_layout' => 'Event','my_experience' =>$my_experience,'event_publish_url' =>$event_publish_url,'global_data' =>$global_data, 'event_id' => $event_id, 'tree' => $tree, 'total_comment' => $comments_total,'comments' => $comments]);
	}
	

	public function CheckoutEvent(Request $request)
	{
			
		$data= $request->all();
		//pr($data);die;
		$total_items=array();
		$to_user_details=array();
		$my_experience=array();
		$to_user_id="0";
		$bonus_amount = 0;
		$previous_url =  url()->previous();
		$get_slug= explode('/',$previous_url);
		
		$publish_url= array_reverse($get_slug);
		$title = $publish_url[0];
		
		$checkout_form_data = Session::get('checkoutform');
		//pr($checkout_form_data);die;
		
		if(count($data) > 0)
		{
			
			$data['publish_url']=$title;
			
			$total_item_json= $data['total_items'];
			$bonus_amount = $data['bonus_amount'];
			Session::put('checkout_data', $data);
			$total_items= json_decode($data['total_items']);
			$event_publish_url = $title;
			Session::save();
		}else{
			
			$checkout_data = Session::get('checkout_data');
			//pr($checkout_data);die;
			$total_items= json_decode($checkout_data['total_items']);
			$event_publish_url = $checkout_data['publish_url'];
			$bonus_amount = $checkout_data['bonus_amount'];
			$total_item_json= $checkout_data['total_items'];
		}
		//pr($checkout_data);die;
		$user_id = Auth::guard('site')->id();
		
		$country = Country::where(['status' => 'Active'])->get()->toArray();
		
		$state = State::with(['country' => function($query)
		{
		    $query->select('id', 'name');
		
		}])->get();  
		
		//pr($state->toArray());die;
		if(count($total_items) > 0)
		{
			foreach($total_items as $key=>$val)
			{
				$exp_id[]= $val->exp_id;
			}
			
			$my_experience = Experience::with(['getEvent' => function ($q) {
                    $q->select('title', 'user_id','id','stripe_user_id');
                }])->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status')
                ->whereIn('id', $exp_id)->get();
		}
		
		$user_details = User::find($user_id);
		
		if(count($my_experience) > 0)
		{
			$to_user_id= $my_experience[0]->getEvent->user_id;
			$to_user_details = User::find($to_user_id);
		}else{
			return view('site.404');
		}
		
		$event_for = User::find($user_id);
		
		return view('site.event.event-checkout', ['title_for_layout' => 'Event','to_user_details' =>$to_user_details,'country' => $country,'state' =>$state,'my_experience' =>$my_experience,'total_items' =>$total_items,'bonus_amount' => $bonus_amount,'user_details' => $user_details,'publish_url' =>$event_publish_url,'total_item_json' =>$total_item_json,'get_session_form_data' =>$checkout_form_data]);
	}

	public function getStatesByCountry($country_id)
	{
		$state=array();
		
		$state = State::with(['country' => function($query) use($country_id)
		{
		    $query->select('id', 'name');
		
		}])->where('country_id',$country_id)->get()->toArray(); 
		
		
		return $state;
		
	}

	public function DuplicateEmailCheck(Request $request)
	{
		if ($request->input('email') !== '') {
            if ($request->input('email')) {
                //$rule = array('email' => 'Required|email|unique:users');
				$rule = array('email' => 'required|max:200|unique:users,email,NULL,id,deleted_at,NULL');
                $validator = Validator::make($request->all(), $rule);
            }
            if (!$validator->fails()) {
                die('true');
            }
        }
        die('false');
	}
	
	
	public function StoreCheckoutData(Request $request)
    {
    	Session::forget('checkoutform');
		$data= $request->all();	
		//pr($data);die;	
		$request->session()->put('checkoutform', $data);
		return redirect('/checkout_confirm');
    }
	
	public function checkout_confirm()
	{
		$data = Session::get('checkoutform');
		//pr($data);die;
		$final_amounts= array();
		$checkout_data = Session::get('checkout_data');
		$total_items= json_decode($checkout_data['total_items']);
		$total_items=array();
		$my_experience= array();
		
		
		if(count($data) > 0)
		{
			$checkout_amount= array_combine($data['exp_id'], $data['gift_val']);
			
			$i="0";
			
			foreach($checkout_amount as $key=>$val)
			{
				$final_amounts[$i]['exp_id']=$key;
				$final_amounts[$i]['value']=$val;
				$final_amounts[$i]['actual_amount_value']=$data['actual_gift_needed'][$i];
				$i++;
			}
			//pr($final_amounts);die;
			if(isset($data['bonus_amout_val']))
			{
				$bonus_amount = $data['bonus_amout_val'];
				$bonus_amount_value = $data['bonus_amout_val'];
			}else{
				$bonus_amount = "0";
				$bonus_amount_value = "0";
			}
			
			$event_publish_url = $data['event_publish_url'];
			$final_amt_val=  $final_amounts;
			$total_items= $final_amt_val;
			$title= $data['event_publish_url'];
			$update_session= json_encode($total_items);
		}
		
		//here we update session value of confirm payment page
		$session_data=array();
		$session_data['publish_url']=$title;
		$session_data['bonus_amount']=$bonus_amount_value;
		$session_data['total_items']=$update_session;
			
		Session::put('checkout_data', $session_data);
		
		
		$checkout_data = Session::get('checkout_data');
		//pr($checkout_data);die;
		
		
		$user_id = Auth::guard('site')->id();
		
		$country = Country::where(['status' => 'Active'])->get()->toArray();
		
		$state = State::with(['country' => function($query)
		{
		    $query->select('id', 'name');
		
		}])->get()->toArray();  
		
		//pr($state->toArray());die;
		if(count($total_items) > 0)
		{
			foreach($total_items as $key=>$val)
			{
				$exp_id[]= $val['exp_id'];
			}
			
			$my_experience = Experience::with(['getEvent' => function ($q) {
                    $q->select('title', 'user_id','id');
                }])->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status')
                ->whereIn('id', $exp_id)->get();
		}
		
		$user_details = User::find($user_id);
		
		if(count($my_experience) > 0)
		{
			$to_user_id= $my_experience[0]->getEvent->user_id;
			$to_user_details = User::find($to_user_id);
		}else{
			return view('site.404');
		}
		
		$event_for = User::find($user_id);
		
		return view('site.event.event-checkout-confirm', ['title_for_layout' => 'Event','to_user_details' =>$to_user_details,'country' => $country,'state' =>$state,'my_experience' =>$my_experience,'total_items' =>$total_items,'bonus_amount' => $bonus_amount_value,'user_details' => $user_details,'publish_url' =>$event_publish_url,'data' => $data]);
	}
	
	public function ConfirmPayment(Request $request)
	{
		$data= $request->all();
		
		$final_amounts= array();
		
		$checkout_data = Session::get('checkout_data');
		$total_items= json_decode($checkout_data['total_items']);
		
		$total_items=array();
		$my_experience= array();
		
		if(count($data) > 0)
		{
			$checkout_amount= array_combine($data['exp_id'], $data['gift_val']);
			
			$i="0";
			
			foreach($checkout_amount as $key=>$val)
			{
				$final_amounts[$i]['exp_id']=$key;
				$final_amounts[$i]['value']=$val;
				$final_amounts[$i]['actual_amount_value']=$data['actual_gift_needed'][$i];
				$i++;
			}
			//pr($final_amounts);die;
			if(isset($data['bonus_amout_val']))
			{
				$bonus_amount = $data['bonus_amout_val'];
				$bonus_amount_value = $data['bonus_amout_val'];
			}else{
				$bonus_amount = "0";
				$bonus_amount_value = "0";
			}
			
			$event_publish_url = $data['event_publish_url'];
			$final_amt_val=  $final_amounts;
			$total_items= $final_amt_val;
			$title= $data['event_publish_url'];
			$update_session= json_encode($total_items);
		}
		
		//here we update session value of confirm payment page
		$session_data=array();
		$session_data['publish_url']=$title;
		$session_data['bonus_amount']=$bonus_amount_value;
		$session_data['total_items']=$update_session;
			
		Session::put('checkout_data', $session_data);
		
		
		$checkout_data = Session::get('checkout_data');
		//pr($checkout_data);die;
		
		
		$user_id = Auth::guard('site')->id();
		
		$country = Country::where(['status' => 'Active'])->get()->toArray();
		
		$state = State::with(['country' => function($query)
		{
		    $query->select('id', 'name');
		
		}])->get()->toArray();  
		
		//pr($state->toArray());die;
		if(count($total_items) > 0)
		{
			foreach($total_items as $key=>$val)
			{
				$exp_id[]= $val['exp_id'];
			}
			
			$my_experience = Experience::with(['getEvent' => function ($q) {
                    $q->select('title', 'user_id','id');
                }])->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status')
                ->whereIn('id', $exp_id)->get();
		}
		
		$user_details = User::find($user_id);
		
		if(count($my_experience) > 0)
		{
			$to_user_id= $my_experience[0]->getEvent->user_id;
			$to_user_details = User::find($to_user_id);
		}else{
			return view('site.404');
		}
		
		$event_for = User::find($user_id);
		
		return view('site.event.event-checkout-confirm', ['title_for_layout' => 'Event','to_user_details' =>$to_user_details,'country' => $country,'state' =>$state,'my_experience' =>$my_experience,'total_items' =>$total_items,'bonus_amount' => $bonus_amount_value,'user_details' => $user_details,'publish_url' =>$event_publish_url,'data' => $data]);
		
		//return view('site.event.event-checkout-confirm', ['title_for_layout' => 'Event','data' =>$data]);
	}
	
	public function ConfirmGift(Request $request)
	{
		$data= $request->all();
		$checkout_data = Session::get('checkoutform');
		//pr($data);die;
		
		if(count($data) > 0)
		{
			if($data['payment_method'] == "1")
			{
				if($data['already_fynches_user']=="0")
				{
					$registerd_new_user = $this->NewUserRegistered($data);
					$funding_record= $this->userFundingData($data,$registerd_new_user,0,0);	
					
					
					if(isset($data) && $data['description']!="")
					{
						$comment = new Comment;
						$comment->event_id = $data['event_id'];
						$comment->user_id = $registerd_new_user;
						$comment->comment = $data['description'];
						$comment->parent_id = "0";
						$save = $comment->save();
					}
					
					$msg = "Email Verify link sent to your mail.Please check your mail.";
			        $log = ActivityLog::createlog($registerd_new_user, "Payment", $msg, $registerd_new_user);
					$email= $data['email'];
					$user_name= $data['first_name'].' '.$data['last_name'];
					$redirect_url= env('SITE_URL');
					$VerificationLink = $redirect_url;
					
					$search = array("[USERNAME]","[EMAIL]");
			        $replace = array($user_name,$email);
					
					$emailParams = array(
						'subject' => 'Fynches Payment Successful',
						'from' => config('constant.fromEmail'),
						'to' => $email,
						'email'=>$email,
						'redirect_url'=>env('SITE_URL'),
						'template'=>'pay-by-check-payment-success',
						'search' => $search,
						'replace' => $replace
					);

					
					$result = User::SendEmail($emailParams);
					
			        Session::flash('success_msg', $msg);
					return redirect('/success-payment/')->with( ['email' => $data['email']]);
				}
				else
				{
					//echo 'asdf';die;
					$user_id = Auth::guard('site')->id();
					$user = User::find($user_id);
					$funding_record= $this->userFundingData($data,$user_id,0,0);	
					
					if(isset($data) && $data['description']!="")
					{
						$comment = new Comment;
						$comment->event_id = $data['event_id'];
						$comment->user_id = $user_id;
						$comment->comment = $data['description'];
						$comment->parent_id = "0";
						$save = $comment->save();
					}
					
					$redirect_url= env('SITE_URL');
					$VerificationLink = $redirect_url;
			        
					$user_name= "User";
					$email="";
		        	$users= Auth::guard('site')->user();
					$user_name= $users->first_name.' '.$users->last_name;
					$email= $users->email;
					
					
					$search = array("[USERNAME]","[EMAIL]");
			        $replace = array($user_name,$email);
					
					$emailParams = array(
								            'subject' => 'Fynches Payment Successful',
								            'from' => config('constant.fromEmail'),
								            'to' => $email,
								            'email'=>$email,
								            'redirect_url'=>env('SITE_URL'),
								            'template'=>'pay-by-check-payment-success',
								            'search' => $search,
								            'replace' => $replace
			        					);
					
					
					$result = User::SendEmail($emailParams);
					
					
					//echo $funding_record;die;
					$msg = "Payment Sent Successfully.";
			        $log = ActivityLog::createlog($user_id, "Payment", $msg, $user_id);
			        Session::flash('success_msg', $msg);
					return redirect('/success-payment/')->with( ['email' => $user->email]);
				}
			}
			else 
			{
				
				if($data['already_fynches_user']=="0")
				{
					$registerd_new_user = $this->NewUserRegistered($data);
					$payment = $this->sendPayment($data,$registerd_new_user);
					
					if(isset($data) && $data['description']!="")
					{
						$comment = new Comment;
						$comment->event_id = $data['event_id'];
						$comment->user_id = $registerd_new_user;
						$comment->comment = $data['description'];
						$comment->parent_id = "0";
						$save = $comment->save();
					}
					
					if($payment=="1")
					{
						$msg = "Email Verify link sent to your mail.Please check your mail.";
				        $log = ActivityLog::createlog($registerd_new_user, "Payment", $msg, $registerd_new_user);
						$email= $data['email'];
						
				        Session::flash('success_msg', $msg);
						return redirect('/success-payment/')->with( ['email' => $data['email']]);
					}else{
						$msg = $payment[0]['message'];
				        $log = ActivityLog::createlog($registerd_new_user, "Payment", $msg, $registerd_new_user);
				        Session::flash('error_msg', $msg);
						return redirect('/checkout-event/');
					}
				}
				else
				{
					$user_id = Auth::guard('site')->id();
					$user = User::find($user_id);
					$payment = $this->sendPayment($data,$user_id);
					
					if(isset($data) && $data['description']!="")
					{
						$comment = new Comment;
						$comment->event_id = $data['event_id'];
						$comment->user_id = $user_id;
						$comment->comment = $data['description'];
						$comment->parent_id = "0";
						$save = $comment->save();
					}
					//pr($payment);die;
					if($payment=="1")
					{
						$msg = "Payment Sent Successfully.";
				        $log = ActivityLog::createlog($user_id, "Payment", $msg, $user_id);
				        Session::flash('success_msg', $msg);
						return redirect('/success-payment/')->with( ['email' => $user->email]);
					}else{
						$msg = $payment[0]['message'];
				        $log = ActivityLog::createlog($user_id, "Payment", $msg, $user_id);
				        Session::flash('error_msg', $msg);
						return redirect('/checkout-event/');
					}
				}
			}
		}
	}
	
	public function NewUserRegistered($data)
	{
		if(count($data) > 0)
		{
			//pr($data);die;
			$user_details = User::where('email',$data['email'])->where('status', '=', "Active")->first();
			
			if(count($user_details) > 0)
			{
				$user_id= $user_details->id; 
			}else{
				
				$user = new User;
		        $user->first_name = $data['first_name'];
		        $user->last_name = $data['last_name'];
		        $user->email = $data['email'];
				$token = str_random(18);
				$user->token = $token;
		        $user->password = bcrypt($data['password']);
		        $user->profile_image = "";
		        $user->status = "Inactive";
				$user->user_type = "3";
				
				$redirect_url= env('SITE_URL');
				$token = str_random(18);
				$VerificationLink = env('SITE_URL').'/verify/'.$token;
		        
				$user_name= $data['first_name'].' '.$data['last_name'];
				
				$search = array("[USERNAME]","[EMAIL]","[WEBSITE_URL]","[VERIFICATION_LINK]");
        		$replace = array($user_name,$user->email, $redirect_url, $VerificationLink);
				
				$emailParams = array(
					'subject' => 'Fynches Signin',
					'from' => config('constant.fromEmail'),
					'to' => $data['email'],
					'email'=>$data['email'],
					'password'=>$data['password'],
					'redirect_url'=>env('SITE_URL'),
					'template'=>'new-register',
					'search' => $search,
					'replace' => $replace
				);
				//pr($emailParams);die;
				$user->save();
				$user_id= $user->id;
				//$user_autologin = Auth::guard('site')->loginUsingId($user_id);
				$result = $user->SendEmail($emailParams);
			}
		}
		return $user_id;
	}

	public function userBillingInfo($data)
	{
		//pr($data);exit;
		$billing_information = new BillingInformation;				
		$billing_information->event_id=$data['event_id'];
		$billing_information->country=$data['country'];
		$billing_information->floor=$data['floor'];
		$billing_information->address=$data['address'];
		$billing_information->city=$data['city'];
		$billing_information->state=$data['state'];
		$billing_information->zipcode=$data['zipcode'];
		$billing_information->phone_no=$data['phone_no'];
		$billing_information->save();
	}
	
	public function userFundingData($data,$user_id,$transaction_id,$status)
	{
		//pr($data);die;
		if(count($data) > 0)
		{
			$funding_data = array_combine($data['exp_id'], $data['gift_val']);
			
			if(count($data['bonus_amt'] > 0))
			{
				 $bonus_amt_array= $data['bonus_amt'];
			}
			
			if(count($data['actual_gift_needed'])> 0)
			{
				$actual_gift_needed= $data['actual_gift_needed'];
			}
				
			$i=0;
			foreach($funding_data as $key=>$val)
			{
				$funding_report = new FundingReport;
				
				$funding_report->event_id = $data['event_id'];
				$funding_report->experience_id =$key;
				
				// echo "@@@@@@@@@@@@".$bonus_amt_array[$i].'==='.$val;
				// echo '<br/>';
				
				if($bonus_amt_array[$i]!="0" && $bonus_amt_array[$i]!="")
				{
					$amount= $bonus_amt_array[$i];
					$flag="1";
				}
				else {
					$amount= "0";
					$flag="0";
				}
				$funding_report->donated_amount = $val;
				$funding_report->bonus_flag = $flag;
				$funding_report->bonus_amount = $amount;
				$funding_report->user_id = $user_id;
				
				if($data['payment_method']=="0")
				{
					$funding_report->transaction_id = $transaction_id;
					$funding_report->status = $status;
				}else{
					$funding_report->status = "pending";
				}
				
				$funding_report->payment_method = $data['payment_method'];
				$funding_report->description = $data['description'];
				$funding_report->make_annoymas = $data['make_annoymas'];
				$funding_report->sent_mail = "0";
				$funding_report->save();	
				$i++;
				
			}
			
			if(isset($data['bonus_amout_val']) && $data['bonus_amout_val'] > 0)
			{
				$funding_report = new FundingReport;
				$funding_report->event_id = $data['event_id'];
				$funding_report->experience_id =NULL;
				$funding_report->donated_amount = "0";
				$funding_report->bonus_amount=$data['bonus_amout_val'];
				$funding_report->bonus_flag = "1";
				$funding_report->user_id = $user_id;
				$funding_report->description = 'Additional Amount';
				if($data['payment_method']=="0")
				{
					$funding_report->transaction_id = $transaction_id;
					$funding_report->status = $status;
				}else{
					$funding_report->status = "pending";
				}
				$funding_report->payment_method = $data['payment_method'];
				$funding_report->make_annoymas = $data['make_annoymas'];
				$funding_report->sent_mail = "0";
				$funding_report->save();	
			}
		}	
	}
	
	public function sendPayment($data,$user_id)
	{
		//exit('here');
		$credit_card_no="";
		$exp_month="";
		$exp_year="";
		$cvv_no="";
		$admin_Fee="0";
		$final_amount ="0";
			
		if(count($data) > 0 )
		{
			$credit_card_no = $data['credit_card_number'];
			$exp_month = $data['expiration_date'];
			$exp_year = $data['expiration_year'];
			$cvv_no = $data['cvv_no'];
			$final_amount = $data['final_amount'];
			$stripe_user_id= $data['to_stripe_user_id'];
		}	
		$global_data= GlobalSetting::first();
		
		if(count($global_data) > 0)
		{
			$admin_Fee= $global_data->commission;
			$application_fee = $final_amount * $admin_Fee / 100;
		}
		try{
	        \Stripe\Stripe::setApiKey(env('STRIPE_SK'));
	
	        $returnResult = \Stripe\Token::create(array(	            
	             "card" => array(
	                "number" => $credit_card_no,    // 4242424242424242
	                "exp_month" => $exp_month,   // 08
	                "exp_year" => $exp_year,     //2019
	                "cvc" => $cvv_no                //325*/ 
	            )
	            
	        ));
	        
			//pr($returnResult);die;	
	        $tokenize = $returnResult->id; 
	           
	        $charge = \Stripe\Charge::create(array(
	          "amount" => $final_amount * 100,
	          "currency" => "usd",
	          "source" => $tokenize,
	          "application_fee" => $application_fee * 100,
	        ), array("stripe_account" => $stripe_user_id));
			
			
			$transaction_id= $charge->id;
			$status = $charge->status;
			
			$checkout_data = Session::get('checkoutform');
			$this->userBillingInfo($checkout_data);
			$this->userFundingData($data,$user_id,$transaction_id,$status);
			
			$redirect_url= env('SITE_URL');
			$VerificationLink = $redirect_url;
	        
			
        	$users= Auth::guard('site')->user();			
			
			if(count($data) > 0 && $data['already_fynches_user']=="0")
			{
				$user_name= $data['first_name'].' '.$data['last_name'];
				$email= $data['email'];
			}else{
				$user_name= $users->first_name.' '.$users->last_name;
				$email= $users->email;
			} 
			
			$search = array("[USERNAME]","[EMAIL]","[PAYMENT_ID]");
	        $replace = array($user_name,$email,$transaction_id);
			
			$emailParams = array(
				'subject' => 'Fynches Payment Successful',
				'from' => config('constant.fromEmail'),
				'to' => $email,
				'email'=>$email,
				'redirect_url'=>env('SITE_URL'),
				'template'=>'payment-success',
				'search' => $search,
				'replace' => $replace
			);
			
			
			$result = User::SendEmail($emailParams); 
			
			return "1";
			
	      } catch (\Stripe\Error\Card $e) {
	      	  return array(['status' => 0,'message' => $e->getMessage()]);
	      } catch (Exception $e) {
	      	  return array(['status' => 0,'message' => $e->getMessage()]);
	      } catch (\Stripe\Error\InvalidRequest $e) {
	      	  return array(['status' => 0,'message' => $e->getMessage()]);
	      } catch (\Stripe\Error\Authentication $e) {
	      	  return array(['status' => 0,'message' => $e->getMessage()]);
	      } catch (\Stripe\Error\ApiConnection $e) {
	      	  return array(['status' => 0,'message' => $e->getMessage()]);
	      } catch (\Stripe\Error\Base $e) {
	      	  return array(['status' => 0,'message' => $e->getMessage()]);
	      }
	}
	
	public function PaymentSuccess()
	{
		Session::forget('checkout_data');	
		return view('site.payment.success-payment');
	}

	public function searchExperience(Request $request)
	{
		
		$data= $request->all();	
		
		$Events=array();
		$yelp_ids=array();
		$search_title="";
		$current_location="";
		
		
		$user_id = Auth::guard('site')->id();
		
		if(count($data) > 0)
		{
			$search_title = $data['search_for_an_experience'];	
			$current_location = $data['searchInput'];
		}
		//echo $current_location;die;
		$Events = Event::where(['user_id' => $user_id])->whereIn('status', array('1', '4'))->pluck('title', 'id');
		
		if(count($Events) > 0)
		{
		 	$Events = $Events->toArray();
		}
		 
		$yelpExperiences = Experience::whereHas('getAllEvent', function($q) use($user_id){
    				 $q->where('user_id', $user_id);
		})->where('yelp_exp_id','!=',NULL)->get();       
		
		 
		 if(count($yelpExperiences) > 0)
		 {
		 	foreach($yelpExperiences as $key=>$val)
			{
				$yelp_ids []= $val->yelp_exp_id;
			}
		 }
		
		$accessToken=env('YELP_SECRET_KEY');
		 $client = new \Stevenmaguire\Yelp\v3\Client(array(
		    'accessToken' => $accessToken,
		    'apiHost' => 'api.yelp.com' // Optional, default 'api.yelp.com'
		 ));
		 
		 
		$parameters = [
		   
		    'location' => $current_location,
		    'categories' => [$search_title],
		    //'limit' => 9,
		    //'offset' => 0
		];
		
		
		$results= $client->getBusinessesSearchResults($parameters);
		
		//pr($results);die;
		if(count($results) > 0)
		{
			
			$total_count = count($results->businesses);
			
			$items = json_decode(json_encode($results->businesses), True);
 		
			//pr($items);die;
	        // Get current page form url e.x. &page=1
	        $currentPage = LengthAwarePaginator::resolveCurrentPage();
	 
	        // Create a new Laravel collection from the array data
	        $itemCollection = collect($items);
	 
	        // Define how many items we want to be visible in each page
	        $perPage = env('PER_PAGE_RECORDS');;
	 
	        // Slice the collection to get the items to display in current page
	        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
	 
	        // Create our paginator and pass it to the view
	        $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
	 
	        // set url path for generted links
	        $paginatedItems->setPath($request->url());
	 		
			//pr($paginatedItems);die;
		}
		
        return view('site.experience.search', ['results' => $paginatedItems,'search_title' =>$search_title,'location' =>$current_location,'total_count' =>$total_count,'perPage' =>$perPage,'user_events' =>$Events,'yelpIds' =>$yelp_ids]);
	}
	
	public function FindPerfectExperience($title,$location="",$search_flag="", Request $request)
    {
    	
    	 Session::forget('yelp_experience');
    	 $Events=array();
		 $yelpExperiences = array();
		 $yelp_ids = array();
		 $user_id = Auth::guard('site')->id();
		 $event_id="0";
		 $Events = Event::where(['user_id' => $user_id])->whereIn('status', array('1', '4'))->pluck('id', 'title');
		 
		 $data= $request->all();
		 
		//pr($data);die;
		 if(count($Events) > 0)
		 {
		 	$Events = $Events->toArray();
		 }
		 
		// $yelpExperiences = Experience::whereHas('getAllEvent', function($q) use($user_id){
    				 // $q->where('user_id', $user_id);
		// })->where('yelp_exp_id','!=',NULL)->get();       
// 		
// 		 
		 // if(count($yelpExperiences) > 0)
		 // {
		 	// foreach($yelpExperiences as $key=>$val)
			// {
				// $yelp_ids []= $val->yelp_exp_id;
			// }
		 // }
		 
		  //check yelp id in session
			 
		 $yelpExperiences = Session::get('yelp_experience');       
		
		 if(count($yelpExperiences) > 0)
		 {
		 	foreach($yelpExperiences as $key=>$val)
			{
				$yelp_ids []= $val['yelp_id'];
			}
		 }
		 
		 $ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
		 $data = \Location::get($ip); //66.102.0.0
		 $total_count= "0";
		 
		 if($location!="")
		 {
		 	$current_location=$location;
			$latitude="40.730610";
			$longitude= "-73.935242";
		 }else{
		 	$current_location='Chicago, IL';
			$latitude="40.730610";
			$longitude= "-73.935242";
		 }
		 //pr($data);die;
		 $accessToken=env('YELP_SECRET_KEY');
		 $client = new \Stevenmaguire\Yelp\v3\Client(array(
		    'accessToken' => $accessToken,
		    'apiHost' => 'api.yelp.com' // Optional, default 'api.yelp.com'
		 ));
		
		 $page = 1;
		 $perPage = 6; //env('PER_PAGE_RECORDS');;
		 $offset = ($page - 1) * $perPage;
		 
		 $parameters = [			   
				'term' => $title,
				'location' => $current_location,
				'limit' => $perPage,
			    'offset' => $offset
			];
		$results= $client->getBusinessesSearchResults($parameters);
		
		if(count($results) > 0)
		{
			
			$total = $results->total;
			$total_count = $total;
			if($total>=996){
				$total = 996;	
			}
			$items = json_decode(json_encode($results->businesses), True);	 						
			$itemCollection = collect($items);
	        $paginatedItems= new LengthAwarePaginator($itemCollection , $total, $perPage);
			//$url = "/recommended-ajax";
			$url = "/search-experience-ajax";
			$paginatedItems->setPath($url);
		}
		$pagination = '<div>'.$paginatedItems->links('pagination.fynches').'</div>';
		
		return view('site.experience.search', ['results' => $paginatedItems,'search_title' =>$title,'pagination' =>$pagination,'current_location' =>$current_location,'total_count' =>$total_count,'perPage' =>$perPage,'user_events' =>$Events,'yelpIds' =>$yelp_ids,'event_id' => $event_id]);
    }

	public function ContactUs(Request $request)
	{
		$data= $request->all();
		$user_id = Auth::guard('site')->id();
		$previous_url =  url()->previous();
		
		$contact_us = Cms::where('slug', 'contact-us')->first();
		//return view('site.about-us', ['aboutUs' => $aboutUs]);
		
		if(empty($user_id))
		{
			$user_id="0";
		}
		//pr($data);die;
		//echo $previous_url;die;
		if(count($data) > 0)
		{
			
			$search = array("[DESCRIPTION]","[EMAIL]");
        	$replace = array($data['description'],$data['email']);
			
			$emailParams = array(
	            'subject' => $data['subject'],
	            'from' => config('constant.fromEmail'),
	            // 'to' => 'team@fynches.com',
	            // 'email'=> 'team@fynches.com',
	            'to' => 'devangm@techuz.com',
	            'email'=> 'devangm@techuz.com',
	            'url'=>env('SITE_URL'),
	            'template'=>'contact-us',
	            'search' => $search,
	            'replace' => $replace
	        );
			
			
	     	$result = User::SendEmail($emailParams);  
		}
		//echo $result;die;
		if($result)
		{
			$msg = "Mail Sent Successfully.";
			$log = ActivityLog::createlog($user_id, "contact Us mail", $msg, $user_id);
	        Session::flash('success_msg', $msg);
			return redirect($previous_url);
		}else{
			$msg = "Something went wrong.";
			$log = ActivityLog::createlog($user_id, "Contact Us mail", $msg, $user_id);
	        Session::flash('error_msg', $msg);
			return redirect($previous_url);
		}
	}

	public function UpdatePaymentStatus($status,$id)
	{
		$user_id = Auth::guard('site')->id();
		if($status!="" && $id!="")
		{
			FundingReport::where('id', $id)->update(array('status' => $status));
		}
		
		$msg = "Payment status updated Successfully.";
        $log = ActivityLog::createlog($user_id, "Payment Status", $msg, $user_id);
        Session::flash('success_msg', $msg);
		Session::save();
		echo 1;
	 	exit;
		
	}
	
}
