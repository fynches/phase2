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
use Response;
use App\ActivityLog;
use App\Event;
use App\Experience;
use App\Comment;
use App\GlobalSetting;
use App\FundingReport;
use Auth;
use Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\MappingEventMedia;


class ExperienceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->middleware('guest:site');
	}
	
	public function index(Request $request)
    {
    	$users = Auth::guard('site')->user();
		return view('site.experience.index', ['users' => $users]);	
	} 

   /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    	$users = Auth::guard('site')->user();
		return view('site.experience.create', ['users' => $users]);	
	}
	
	public function createExperience($id,Request $request){
		
		$logged_user_id = Auth::guard('site')->id();
		$favourite_activityKeywords="";
		$search_terms="";
		$final_keyword= array();
		$paginatedItems= array();
		$yelp_ids=array();
		$TagKewords= array();
		$other_keywords = array();
		$comment=array();
		$pagination="";
		$user_id = Auth::guard('site')->id();
		
		$data = Event::with(['getUser' => function ($q) {
            $q->select('first_name', 'last_name','id');
        }])->with(['getEventMappingMdedia' => function ($q) {
            $q->select('video', 'image','image_type','id','event_id','flag_video');
        }])->with('getEventTags')->select('id','user_id','stripe_user_id','title','publish_url','description','age_range','event_publish_date','event_end_date','zipcode','status')
        ->find($id);
		
		
		$global_data= GlobalSetting::first();
		$funding_report_url ="";
		$search_terms="";
		$stripe_user_id="0";
		
		$results= array();
		
		if(count($data) > 0)
		{
			$data= $data->toArray();
			if($data['user_id'] != $logged_user_id)
			{
				return view('site.404',['global_data' =>$global_data]);
			}
		}else{
			return view('site.404',['global_data' =>$global_data]);
		}

		$my_experience = Event::with(['getEventExperiences' => function ($q) {
                    $q->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status');
                }])->with(['getEventTags' => function ($q) {
		            $q->select('*');
					$q->with('getTags');
		        }])->with(['getCustomTags' => function ($q) {
		            $q->select('*');
		        }])->with(['FundingReport' => function ($q) {
                    $q->select('id', 'event_id','experience_id','user_id','donated_amount');
                }])->select('title', 'user_id','id','stripe_user_id')
                ->find($id);	
			
			//pr($my_experience);die;
				//pr($my_experience->getEventTags[0]->getTags);die;
		if(count($my_experience) > 0 && count($my_experience->getEventTags) > 0)
		{
			if(count($my_experience->getEventTags[0]) > 0)
			{
				foreach($my_experience->getEventTags as $key=>$val)
				{
					$TagKewords[]= $val->getTags->tag_name;
				}
				if($my_experience->stripe_user_id!="")
				{
					$stripe_user_id= $my_experience->stripe_user_id;	
				}
				
			}
		}	
		
		if(count($my_experience) > 0 && count($my_experience->getCustomTags) > 0)
		{
			foreach($my_experience->getCustomTags as $key=>$val)
			{
				$other_keywords[]= $val->name;
			}
		}
		
		if(count($TagKewords) > 0 && count($other_keywords) > 0)
		{
			$final_keyword = array_merge($TagKewords,$other_keywords);
		}
		
		else if(count($TagKewords) > 0 && count($other_keywords) <= 0)
		{
			$final_keyword = $TagKewords;
		}
		else if(count($other_keywords) > 0 && count($TagKewords) <= 0)
		{
			$final_keyword = $other_keywords;
		}
		
		
		if(count($final_keyword) > 0)
		{
			 $search_terms= json_encode($final_keyword);
			 $ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
			 $location_data = \Location::get($ip); //66.102.0.0
			 $total_count= "0";
			 
			 if(count($location_data) > 0)
			 {
			 	//$current_location = $location_data->cityName.', '.$location_data->countryCode;
			 	// $latitude=$location_data['latitude'];
				// $longitude= $location_data['longitude'];
			 	$current_location='Chicago, IL';
				$latitude="40.730610";
				$longitude= "-73.935242";
				
			 }else{
			 	$current_location = 'Chicago, IL';
				$latitude="40.7128";
				$longitude= "74.0060";
			 }
			
	         $accessToken=env('YELP_SECRET_KEY');
			 $client = new \Stevenmaguire\Yelp\v3\Client(array(
			    'accessToken' => $accessToken,
			    'apiHost' => 'api.yelp.com' // Optional, default 'api.yelp.com'
			 ));
			
			$page = 1;
			$perPage = 6; //env('PER_PAGE_RECORDS');;
			$offset = ($page - 1) * $perPage;
			 
		 	$parameters = [
		   			'location' => $current_location,
		   			'latitude' => $latitude,
		    		'longitude' => $longitude,
		   			'categories' => $final_keyword,
		   			'limit' => $perPage,
				    'offset' => $offset,

		    		//'term' => ['sandwich','pizza'],
		    ];
			
			$results= $client->getBusinessesSearchResults($parameters);
			
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
		        //$perPage = env('PER_PAGE_RECOMANDED_RECORDS');;
		 
		        // Slice the collection to get the items to display in current page
		        //$currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
		 
		        // Create our paginator and pass it to the view
		        $paginatedItems= new LengthAwarePaginator($itemCollection , $results->total, $perPage);
		 
		        // set url path for generted links
		        $url = "/recommended-ajax";
		        $paginatedItems->setPath($url);
		 		$pagination = '<div>'.$paginatedItems->links('pagination.fynches').'</div>';
				//pr($paginatedItems);die;
			}
		}

		// $yelpExperiences = Experience::whereHas('getAllEvent', function($q) use($logged_user_id){
    				 // $q->where('user_id', $logged_user_id);
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
		
		// $comments = Event::with(['getComments' => function ($q) {
		// 				$q->select('id','comment', 'user_id','parent_id','event_id','status','created_at');
		// 				$q->where('status', 'Active');
		// 				$q->orderBy('created_at', 'desc');
		// 			}])
		// 			->where('id',$id)
		// 			->paginate(2);
		
		$comment_limit = env('COMMENT_PER_PAGE');
		$comments = Comment::where('status', 'Active')
			->where('event_id', $id)
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
		$tree = Comment::buildTree($comments_array);
		//pr($tree[0]getUser->first_name); exit;
		//$html = $this->printCategory($tree);		
		// pr($tree);
		// exit;
		//pr($$comments);
		
		$global_data= GlobalSetting::first();
		
		$funding_report_url = url('/funding-report/'.$id);
		$event_status= $data['status'];
		
		$preview_url = url('/'.$data['publish_url']);
		//echo $preview_url;die;
		 $ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
		 $location_data = \Location::get($ip); //66.102.0.0
		 $total_count= "0";
		 
		 if(count($location_data) > 0)
		 {
		 	//$current_location = $location_data->cityName.', '.$location_data->countryCode;
		 	$current_location='Chicago, IL';
		 }else{
		 	$current_location = 'Chicago, IL';
		 }
		 
		 $user_last_event_stripe_id="0";
		
	 	 if(count($my_experience) > 0 && $my_experience->stripe_user_id!="")
		 {
			$user_last_event_stripe_id = $my_experience->stripe_user_id;
		 }else{
		 	$get_user_stripe_id =  Event::orderBy('updated_at', 'desc')->where('user_id',$user_id)->where('stripe_user_id','!=',NULL)->first();
			//pr($get_user_stripe_id);die;
			if(count($get_user_stripe_id) > 0)
			{
				$user_last_event_stripe_id= $get_user_stripe_id->stripe_user_id;
			}else{
				$user_last_event_stripe_id="0";
			}
		 }
		
		// pr($my_experience);die;
		 
		return view('site.event.event-create', ['title_for_layout' => 'Event','results' => $paginatedItems,'yelpIds' =>$yelp_ids,'data'=>$data,'search_terms'=>$search_terms,'my_experience' =>$my_experience,'comments' =>$comments,'global_data' =>$global_data,'event_id'=>$id,'fundingRepotUrl' =>$funding_report_url,'current_location' =>$current_location,'pagination' =>$pagination,'current_event_id' => $id,'preview_url' =>$preview_url,'event_status' =>$event_status,'stripe_user_id' =>$stripe_user_id, 'tree'=>$tree,'user_last_event_stripe_id' =>$user_last_event_stripe_id]);
	}

	public function SaveExperience(Request $request){
		
		$data= $request->all();		
		$event_id = $data['event_id'];
		$user_id = Auth::guard('site')->id();
		$get_yelp_session = Session::get('yelp_experience');
		
		// Event create
		if($event_id == "" || $event_id==0){
			$event = new Event;		
			if($data['publish_url']=="")
			{
				$slug = str_replace(' ', '-', strtolower($data['event_title']));	 
				$publish_url = $slug; 	
			}else{
				$slug = str_replace(' ', '-', strtolower($data['publish_url']));	 	
				$publish_url = $slug;
			}
			
			$event->user_id = $user_id;
			$event->status="4";
			$event->publish_url=$publish_url;
			
			$event->title=$data['event_title'];
			$event->description=$data['description'];
			$event->age_range=$data['age_range'];
			$event->event_publish_date=date('Y-m-d',strtotime($data['event_publish_date'])).' 00:00:00';
			$event->event_end_date=date('Y-m-d',strtotime($data['event_end_date'])).' 00:00:00';
			$event->zipcode=$data['zipcode'];
			$event->save();
			$event_id = $event->id;
			
			if(isset($data['event_images']) && $data['flag_video']=="0"){
    		
				for ($i=0; $i<count($data['event_images']); $i++){
					
					$event_image = $data['event_images'][$i];
					$destinationPath = 'storage/Event/';
					$timestamp = time() . uniqid();
					//$filename = $timestamp . '_' . trim($event_image->getClientOriginalName());
					$filename = "event-".$timestamp.".png";
					$path_thumb =  'storage/Event/thumb/';
				
					if (!File::exists($path_thumb)) {
						mkdir($path_thumb, 0777, true);
						chmod($path_thumb, 0777);
					}
					
					Image::make(file_get_contents($event_image))->save('storage/Event/' . $filename); 
					
					Image::make(file_get_contents($event_image))->resize(110, 90, function($constraint) {
						$constraint->aspectRatio();
					})->save('storage/Event/thumb/' . $filename); 
					
					$MappingEventMedia = new MappingEventMedia;
					$MappingEventMedia->image = $filename;
					$MappingEventMedia->image_type = '0';
					$MappingEventMedia->event_id = $event_id;
					$MappingEventMedia->status = 'Active';
					$MappingEventMedia->save();
				}
			}
			
			if(isset($data['facebook_images'])){    		
				for ($i=0; $i<count($data['facebook_images']); $i++){    	    	
					$event_image = $data['facebook_images'][$i];				
					$MappingEventMedia = new MappingEventMedia;
					$MappingEventMedia->image = $event_image;
					$MappingEventMedia->image_type = '2';
					$MappingEventMedia->event_id = $event_id;
					$MappingEventMedia->status = 'Active';
					$MappingEventMedia->save();
				}
			}
			
			if(isset($data['video']) && $data['flag_video']=="0"){			
				$MappingEventMedia = new MappingEventMedia;
				$MappingEventMedia->video = $data['video'];
				$MappingEventMedia->image_type = '1';
				$MappingEventMedia->flag_video = $data['flag_video'];
				$MappingEventMedia->event_id = $event_id;
				$MappingEventMedia->status = 'Active';
				$MappingEventMedia->save();
			}
			
			if(isset($data['image']) && $data['flag_video']=="1"){			
				$file = Input::file('image');
				$timestamp = time();
				$filename = $timestamp . '_' . trim($file[0]->getClientOriginalName());
				
				$path_thumb =  'storage/Videos/';
				if (!file_exists($path_thumb)) {
					mkdir($path_thumb, 0777, true);
					chmod($path_thumb, 0777);
				} 
				$path = 'storage/Videos/';			
				$upload_success = $file[0]->move($path_thumb, $filename);			
				$MappingEventMedia = new MappingEventMedia;
				$MappingEventMedia->video = $filename;
				$MappingEventMedia->image_type = '1';
				$MappingEventMedia->flag_video = $data['flag_video'];
				$MappingEventMedia->event_id = $event_id;
				$MappingEventMedia->status = 'Active';
				$MappingEventMedia->save();
			}
	
			if(isset($data['favourite_activity'])){
				if(count($data['favourite_activity']) > 0){
					$fav_activitiy= explode(',',$data['favourite_activity']);
					foreach($fav_activitiy as $key=>$val){
						DB::table('mapping_custom_tag')->insert(
							array(
							'user_id'     =>   $user_id, 
							'tag_id'   =>   $val,
							'event_id'   =>   $event_id
							)
						);
					}
				}
			}
			
			if(isset($data['other_tags'])){
				if(count($data['other_tags']) > 0){	  	  	
					$other_tags= explode(',',$data['other_tags']);			  
				foreach($other_tags as $key=>$val){
						DB::table('custom_tag')->insert(
							array(
							'name'   =>   $val,
							'user_id'     =>   $user_id, 
							'event_id'   =>   $event_id
							)
						);
					}
				}
			}
			
			if(count($get_yelp_session) > 0)
			{
				foreach($get_yelp_session as $key=>$val)
				{
					$experience = new Experience;
					$experience->event_id=$event_id;
					$experience->yelp_exp_id=$val['yelp_id'];
					$experience->exp_name=$val['exp_name'];
					$experience->image=$val['image'];
			      	$experience->experience_from="1";
			      	$experience->gift_needed=$val['amount'];
			      	$experience->status="In progress";
				  	$experience->save();
				}
			}
		}
		// Event create ENDDDD

		$user_id = Auth::guard('site')->id();		
		$files = Input::file('exp_image');
	    if ($files && !empty($files)) {	        
	        $rules = array('file' => 'mimes:jpg,jpeg,png,gif');
	        $validator = Validator::make(array('file' => $files), $rules);
	        if ($validator->passes()) {
	            $destinationPath = 'storage/experienceImages/';
	            $timestamp = time();
	            $filename = $timestamp . '_' . trim($files->getClientOriginalName());
	            //echo $filename;
	            $path_thumb =  'storage/experienceImages/thumb/';
	            if (!file_exists($path_thumb)) {
	                mkdir($path_thumb, 0777, true);
	                chmod($path_thumb, 0777);
	            }
	            
	            Image::make($files->getRealPath())->resize(360, 360)->save('storage/experienceImages/thumb/' . $filename);
	            $upload_success = $files->move($destinationPath, $filename);
	
	            if (file_exists('storage/experienceImages/' . $filename)) {
	                chmod('storage/experienceImages/' . $filename, 0777);
	            }
	            if (file_exists('storage/experienceImages/thumb/' . $filename)) {
	                chmod('storage/experienceImages/thumb/' . $filename, 0777);
	            }
	            
	            
	        } else {
	           return redirect('/create-experience/'.$event_id.'')->withInput()->withErrors($validator);
	        }
	        $image_name = $filename;
	    }
        
		
		$yelpimage = $data["yelpimage"];
		if($yelpimage && (!$files && empty($files))){
			$image_name = $yelpimage;			
			$yelpimage_url = public_path().'/storage/temp-ex/'.$yelpimage;
			
			Image::make($yelpimage_url)->resize(360, 360, function($constraint) {
				$constraint->aspectRatio();
			})->save('storage/experienceImages/thumb/' . $yelpimage);
			Image::make($yelpimage_url)->save('storage/experienceImages/' . $yelpimage);
		}

		$experience = new Experience;
      	$experience->event_id=$event_id;
		$experience->custom_url=$data['experience_custom_url'];
      	$experience->exp_name=$data['exp_name'];
		if(isset($image_name))
		{
			$experience->image=$image_name;	
		}
      	      
      	$experience->description=$data['description'];
      	$experience->experience_from="0";
      	$experience->gift_needed=$data['gift_needed'];
      	$experience->status="In progress";
	  	$experience->save();
		
		// $global_data= GlobalSetting::first();
		// if(count($global_data) > 0)
		// {
			// $stripe_client_id= $global_data->stripe_client_id;
			// $stripe_url= "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=".$stripe_client_id."&scope=read_write";
			// return redirect($stripe_url);
		// }
		
		$msg = "Experience Created Successfully.";
        $log = ActivityLog::createlog($user_id, "Experience", $msg, $user_id);
        Session::flash('success_msg', $msg);
		
		return redirect('/create-experience/'.$event_id.'');
		
	} 

	public function UpdateExperience(Request $request)
	{
		$data= $request->all();
		
		$user_id = Auth::guard('site')->id();
		
		$id= $data['edit_exp_id'];
		$experience = Experience::find($id);
		
		$event_id= $experience['event_id'];
		
		//pr($data);die;
		if (key_exists('update_img', $data)) {
            $user_profile_image = Input::file('update_img');
			$experience_record = Experience::where('id',$id)->first();
			 
            if ($user_profile_image) {
				
				if(count($experience_record)>0){
                    $oldPicture = $experience_record['image'];
                    $data['image'] =  $oldPicture;
                }
				
                $rules = array('file' => 'mimes:jpg,jpeg,png,bmp,gif');
                $validator = Validator::make(array('file' => $user_profile_image), $rules);


                $profile_image = '';
                if ($validator->passes()) {
                    $destinationPath = 'storage/experienceImages/';
                    $timestamp = time() . uniqid();
                    $filename = $timestamp . '_' . trim($user_profile_image->getClientOriginalName());
                    $path_thumb =  'storage/experienceImages/thumb/';
				
                    if (!File::exists($path_thumb)) {
                    	mkdir($path_thumb, 0777, true);
                        chmod($path_thumb, 0777);
                    }

                    Image::make($user_profile_image->getRealPath())->resize(360, 360, function($constraint) {
                        $constraint->aspectRatio();
                    })->save('storage/experienceImages/thumb/' . $filename);
					
                    $upload_success = $user_profile_image->move($destinationPath, $filename);
					
					if ($upload_success) {
                        if (file_exists($destinationPath. $oldPicture)) {
                            $unlink_success = File::delete($destinationPath . $oldPicture);
                        }
                        if (file_exists($path_thumb . $oldPicture)) {
                            $unlink_success = File::delete($path_thumb . $oldPicture);
                        }                             
                	} 

                    $profile_image = $filename;
                }
            }
        } else {
            if (isset($data['old_images'])) {
                $profile_image = $data['old_images'];
            } else {
                $profile_image = '';
            }
        }
            
		$data['image'] = $profile_image;
		
		$experience->exp_name=$data['edit_exp_name'];
        $experience->image=$profile_image;      
        $experience->description=$data['edit_description'];
        $experience->experience_from=$data['exp_from'];
        $experience->gift_needed=$data['edit_gift_needed'];
        $experience->save();
		
		$msg = "Experience Updated Successfully.";
        $log = ActivityLog::createlog($user_id, "Experience", $msg, $user_id);
        Session::flash('success_msg', $msg);
		
		return redirect('/create-experience/'.$event_id.'');
	}

	public function DeleteExperience($id)
	{
		$user_id = Auth::guard('site')->id();
		$experience = Experience::findOrFail($id);
		
		if(count($experience) > 0)
		{
			$chk_fund_this_exp = FundingReport::where('experience_id', $id)->first();
			if(!empty($chk_fund_this_exp))
			{
				$msg = "This Experience cannot be deleted due to already received the fund.";
				$log = ActivityLog::createlog($user_id, "Experience", $msg,$user_id);
		        Session::flash('error_msg', $msg);
		        Session::save();
		        echo 1;
				exit;
			}
		}
        $experience->delete();
        $msg = "Experience Deleted Successfully.";
		$log = ActivityLog::createlog($user_id, "Experience", $msg,$user_id);
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
	}
	
	public function AddComment(Request $request){
		
		$data= $request->all();
		
		$user_id = Auth::guard('site')->id();
		$event_id = $data['event_id'];
		
		$comment = new Comment;
      	$comment->event_id=$event_id;
		$comment->user_id=$user_id;
		$comment->comment=$data['comment_description'];
      	$comment->parent_id="0";
		$comment->save();
		
		$msg = "Comment Added Successfully.";
        $log = ActivityLog::createlog($user_id, "Comment", $msg, $user_id);
        Session::flash('success_msg', $msg);
		
		return redirect('/create-experience/'.$event_id.'');
		//pr($data);die;
	} 

	public function stripeCurl(Request $request){ 
		
		
        $data = $request->all();
		$event_id="0";
		$redirect_Url= url()->previous(); //after success redirect to current page
		if($redirect_Url!="")
		{
			$get_id= explode('/', $redirect_Url);
			$reverse_array= array_reverse($get_id);
			$event_id= $reverse_array[0];
		}
		
		session(['previous_url' => $redirect_Url]);
		$user_id = Auth::guard('site')->id();
        $code = $data['code'];
        $post = [
            'client_secret' => env('STRIPE_SK'),
            'code' => $code,
            'grant_type'   => 'authorization_code',
        ];

        $ch = curl_init('https://connect.stripe.com/oauth/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        // execute!
        $response = curl_exec($ch);
		$result= json_decode($response, true);
		// close the connection, release resources used
        curl_close($ch);
		
		
        if(count($response) > 0)
		{
			$redirect_page=  session('previous_url');
			//echo $redirect_page;die;
			if(array_key_exists('error', $result))		
			{
				$msg= $result['error_description'];
				Session::flash('error_msg', $msg);
				
				return redirect($redirect_page);
			}
				
			$user_id = Auth::guard('site')->id();
			
			
			$event =  Event::orderBy('id', 'desc')->first();
			
			Event::where('id', $event->id)->update(['stripe_user_id' => $result['stripe_user_id'], 'status' => 1]);
			
			if(!empty($event))
			{
				$preview_url = url('/create-experience/'.$event->id);
				$msg = "Event Added Successfully.";
				Session::flash('success_msg', $msg);
				return redirect($preview_url);
			}else{
				$msg = "Something went wrong.";
				Session::flash('error_msg', $msg);
				return redirect($redirect_page);
			}
			
		}
       
   }
	
	
	
	public function AddYelpExperience(Request $request)
	{
		$data= $request->all();
		
		$user_id = Auth::guard('site')->id();
		$previous_page_url = url()->previous();
		
		$experience = new Experience;
		
      	$experience->event_id=$data['my_event_id'];
		$experience->yelp_exp_id=$data['yelp_exp_id'];
		$experience->exp_name=$data['exp_name'];
		$experience->image=$data['image'];
      	$experience->description=$data['description'];
      	$experience->experience_from="1";
      	$experience->gift_needed="0";
      	$experience->status="In progress";
	  	$experience->save();
		
		$msg = "Experience Added Successfully.";
        Session::flash('success_msg', $msg);
        $log = ActivityLog::createlog($user_id, "Experience", $msg, $user_id);
        return redirect($previous_page_url);

	}
	/* Use: get exprience detail from url Yelp only */
	public function ScrapExperience(Request $request){
		$data= $request->all();
		$yelp_slug = $data["yelpurl"];
		$path = parse_url($yelp_slug, PHP_URL_PATH);
		$pathComponents = explode("/", trim($path, "/"));
		if(count($pathComponents)>1){
			$slug = $pathComponents[1];
		}else{
			return Response::json([
				'detail' => '',
				'image'=> ''
			], 200);
		}

		$accessToken=env('YELP_SECRET_KEY');
		$client = new \Stevenmaguire\Yelp\v3\Client(array(
			'accessToken' => $accessToken,
			'apiHost' => 'api.yelp.com'
		));

		$parameters = [
			'locale' => 'en_US',
		];		
		$business = $client->getBusiness($slug, $parameters);
		if(count($business)>0){
			$url = $business->image_url;
			$filename = time()."_".basename($url);
			$path_thumb =  'storage/temp-ex/';
			if (!file_exists($path_thumb)) {
				mkdir($path_thumb, 0777, true);
				chmod($path_thumb, 0777);
			}
			Image::make($url)->save('storage/temp-ex/' . $filename);
		}
		

		return Response::json([
			'detail' => $business,
			'image'=> $filename
		], 200);
	}
	
	public function searchAndAddWithEventId(Request $request)
	{
		$data= $request->all();
		//pr($data);die;
		$user_id = Auth::guard('site')->id();
		
		if(count($data) > 0)
		{
			$chk_yelp_data = Experience::where('yelp_exp_id', $data['yelp_id'])->where('event_id',$data['event_id'])->first();
			if(count($chk_yelp_data) > 0)
			{
				DB::table('experience')->where('yelp_exp_id',$data['yelp_id'])->where('event_id',$data['event_id'])->delete();	
			}
		
			$experience = new Experience;
			$experience->event_id=$data['event_id'];
			$experience->yelp_exp_id=$data['yelp_id'];
			$experience->exp_name=$data['exp_name'];
			$experience->image=$data['image'];
	      	$experience->description="";
	      	$experience->experience_from="1";
	      	$experience->gift_needed=$data['amount'];
	      	$experience->status="In progress";
		  	$experience->save();
			$msg = "Experience Added Successfully.";
	        $log = ActivityLog::createlog($user_id, "Event", $msg, $user_id);
	        Session::flash('success_msg', $msg);
			Session::save();
			echo '1';
			exit();
			
		}
		
	}
	/* Use : remove yelp record from session  */
	public function searchAndRemove(Request $request){
		
		$data= $request->all();
		$get_yelp_session = Session::get('yelp_experience');	
		
		if (!empty($get_yelp_session)) 
		{
			$key = array_search($data["yelp_id"], array_column($get_yelp_session, 'yelp_id'));
			
			
			if ($key !== false) {
				unset($get_yelp_session[$key]);
				//$get_yelp_session=array();
				Session::put('yelp_experience', $get_yelp_session);
				Session::save();
			}
		}
		
		$get_yelp_session = Session::get('yelp_experience');	
		//pr($get_yelp_session);
		return Response::json([
			'message' => 'Removed exprience from session',
			'count' => count($get_yelp_session)			
		], 200);
		//pr($get_yelp_session);
	}
	public function KeyExits($products, $field, $value)
	{
		foreach($products as $key => $product)
		{
			if ( $product[$field] == $value ){
				return $key;
			}
				
		}
		return false;
	}
	
	public function searchAndAdd(Request $request)
	{
		 $data= $request->all();
		 $get_yelp_session = Session::get('yelp_experience');
       //pr($data);die;
        if (!empty($get_yelp_session)) 
		{
			$key = array_search($data["yelp_id"], array_column($get_yelp_session, 'yelp_id'));
			
			
			if ($key !== false) {
				unset($get_yelp_session[$key]);
				//$get_yelp_session=array();
				Session::put('yelp_experience', $get_yelp_session);
				Session::save();
			}
		}
		
		if (!empty($get_yelp_session)) {
            $a1 = array_values($get_yelp_session);
        } else {
            $a1 = array();
        }
		
		//pr($get_yelp_session);die;
        $current_exp = array('yelp_id' => $request->yelp_id, 'exp_name' => $request->exp_name, 'image' => $request->image,'amount' => $request->amount,'session_flag' =>$request->flag);
        $a2 = $current_exp;

     	if (!empty($a1)) {
            $new_data_arrs = $a1;
        }

        $new_data_arrs[] = $a2;

        Session::put('yelp_experience', $new_data_arrs);
        Session::save();
		
		$get_yelp_session = Session::get('yelp_experience');
		 
		// pr($get_yelp_session);
		 
		 return Response::json([
			'message' => 'Removed exprience from session',
			'count' => count($get_yelp_session)			
		], 200);
	}
	
	public function searchAndAdd_old(Request $request)
	{
		//$request->session()->forget('yelp_experience');
		$data= $request->all();
		//pr($data);
		$get_yelp_session = Session::get('yelp_experience');
		//pr($get_yelp_session);
		if (!empty($get_yelp_session)) { //echo "sss";
		
			$key = $this->KeyExits($get_yelp_session,'yelp_id',$data["yelp_id"]);
			//echo $key = array_search($data["yelp_id"], array_column($get_yelp_session, 'session_flag'));die;
			//echo $key;die;
			if($key!=""){ //echo "eee";
				unset($get_yelp_session[$key]);
			}
			/* Remove old flag value */
			//pr($data);
			$flag = $data["flag"] ?? 0;
			if($flag == 0){
				$old_flag = 1;
			}else if($flag == 1){
				$old_flag = 0;
			}
			//echo "old".$old_flag;
			$flag_key = array_search($old_flag, array_column($get_yelp_session, 'session_flag'));
			//echo $flag_key;die;
			foreach($get_yelp_session as $key => $val){					
				if($val["session_flag"] == $old_flag){ 
					unset($get_yelp_session[$key]);		
				}
			}
			
            $a1 = $get_yelp_session;
        } else {
            $a1 = array();
		}
		
		$current_exp = array('yelp_id' => $request->yelp_id, 'exp_name' => $request->exp_name, 'image' => $request->image,'amount' => $request->amount,'session_flag' =>$request->flag);
		$a2 = $current_exp;
		$newarray = array_merge($a1,$a2);
		//pr($newarray);
        // echo 'old session array';
        // pr($a1);
// 			
        // echo 'new session array';
        // pr($a2);

        if (!empty($a1)) {
            $new_data_arrs = $a1;
        }

        $new_data_arrs[] = $a2;


        // echo 'final array';
        //pr($new_data_arrs);
        Session::put('yelp_experience', $new_data_arrs);
        Session::save();


        $get_yelp_session = Session::get('yelp_experience');			
		
		return Response::json([
			'message' => 'Removed exprience from session',
			'count' => count($get_yelp_session)			
		], 200);
		
		
	}

	public function searchCustomExp($title="")
	{
		$user_id = Auth::guard('site')->id();
		$user_events =  Event::with(['getEventExperiences' => function ($q) use ($user_id,$title){
                    $q->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status');
					//$q->where('exp_named', 'like', '%' . $title . '%');
                }])->with(['getEventMappingMdedia' => function ($q) {
		            $q->select('video', 'image','image_type','id','event_id','flag_video');
		        }])->with(['FundingReport' => function ($q) use ($user_id){
                	$q->select('*');
					$q->where('user_id','=',$user_id);
				}])->select('*')->where('user_id',$user_id)->whereIn('status', array('1', '4'))->where('title', 'like', '%' . $title . '%')
				->orderBy('created_at', 'desc')
				->paginate(5);
				
		return view('site.dashboard-load',compact('user_events','user_contribution','static_block','testimonails'));		
	}

	public function DeleteYelpExp(Request $request)
	{
		$user_id = Auth::guard('site')->id();
		$data= $request->all();
		if(count($data) > 0)
		{
			$event_id= $data['event_id'];
			$yelp_id= $data['yelp_id'];
			
			DB::table('experience')->where('yelp_exp_id',$yelp_id)->where('event_id',$event_id)->delete();
		}
		echo 1;
        exit;
	}
	
	public function DeleteYelpExpSession(Request $request)
	{
		$user_id = Auth::guard('site')->id();
		$data= $request->all();
		
		if(count($data) > 0)
		{
			$delete_yelp_id= $data['yelp_id'];
			$get_yelp_session = Session::get('yelp_experience');
			
			//echo $delete_yelp_id;
			// echo '<br/>';
			// echo 'BEFORE';
			//pr($get_yelp_session);
			
			if(count($get_yelp_session) > 0 && $delete_yelp_id!="")
			{
				foreach ($get_yelp_session as $key => $value) {
		            if($value['yelp_id'] == $delete_yelp_id) {
		            	unset($get_yelp_session[$key]);
		            }
		        }
				
			}
			// echo '=====================================================';
			// $get_yelp_session = Session::get('yelp_experience');
			// echo 'AFTER';
			// pr($get_yelp_session);
			
		}
	}

	/* Save comment */
	public function SaveComment(Request $request)
	{
		$data= $request->all();		
		$user_id = Auth::guard('site')->id();
		$event_id = $data['event_id'];
		

		try {
			$comment = new Comment;
			$comment->event_id = $event_id;
			$comment->user_id = $user_id;
			$comment->comment = $data['comment_description'];
			$comment->parent_id = $data['parent_id'];
			$save = $comment->save();

			$view = $this->LoadComment($data);

			return Response::json([
				'message' => 'Comment saved successfully',
				'html' => $view
			], 200);
		} catch(\Exception $e){		
			return Response::json([
				'message' => $e->getMessage()
			], 200);
		}
	}

	/* Delete comment */
	public function DeleteComment(Request $request)
	{
		$data= $request->all();		
		$user_id = Auth::guard('site')->id();
		$event_id = $data['event_id'];
		$comment_id = $data['comment_id'];
		$limit = $data['total_div'];
		try {
			$comment = Comment::find($comment_id);
			$comment->delete();
			
			$view = $this->LoadComment($data);

			return Response::json([
				'message' => 'Comment delete successfully',
				'html' => $view
			], 200);
		} catch(\Exception $e){		
			return Response::json([
				'message' => $e->getMessage()
			], 200);
		}
	}

	/* Load more comment */

	public function LoadmoreComment(Request $request){
		$data= $request->all();
		$view = $this->LoadComment($data, true);
		//pr($view);exit;
        return response()->json(['html'=>$view['html'],'morepage' => $view['morepage']]);
	}

	function LoadComment($data, $paging=false){		
		$comments_array = array();
		$comments = Comment::where('status', 'Active');
		$comments->where('event_id', $data['event_id']);
		$comments->orderBy('created_at','DESC');
		if($paging){
			$comment_limit = env('COMMENT_PER_PAGE');
			$res = $comments->paginate($comment_limit);
		}else{
			$limit = $data['total_div'];
			if($limit){
				$limit = ($limit+1);
				$comments->limit($limit);
			}
			$res = $comments->get();
		}
		
		if(count($res)>0){			
			foreach($res as $row){
				$comment_data = $row->toArray();
				$comment_data['human_date'] = $row->created_at->diffForHumans();
				$comment_data['name'] = $row->getUser->first_name.' '.$row->getUser->last_name;
				$comments_array[] = $comment_data;
			}
		}			
		$tree = Comment::buildTree($comments_array);
		$view = view('site.comment.load-comment', compact('tree'))->render();
		if($paging){
			return array('html' => $view, 'morepage' => $res->hasMorePages());
		}else{
			return $view;
		}
		
	}
	
}
