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
use App\Event;
use App\Experience;
use App\GlobalSetting;
use App\FundingReport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use App\MappingEventMedia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Socialite;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use DateTime;
use Response;

class EventController extends Controller
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

   	/*   
	 	Added by Devang Mavani
      	create event page of fynches
	*/
    public function create(Request $request)
    {
    	$tags = Tag::where(['status' => 'Active'])->offset(0)->limit(11)->get(); 
		return view('site.event.create', ['title_for_layout' => 'Add Event','tags'=>$tags]);
	}
	
	public function StoreAllSteps(Request $request)
    {
		$data= $request->all();		
		$request->session()->put('steps', $data);
		return redirect('/create-event');
    }
	
	public function createEvent(Request $request)
	{
		Session::forget('yelp_experience');
		$data= $request->all();
		$get_yelp_session = Session::get('yelp_experience');
		
		
		$steps = $request->session()->get('steps');
		$user_id = Auth::guard('site')->id();		
		$event_status="0";
		//pr($get_yelp_session);die;
		if($steps){
			$data= $steps;
		}else{
			$data = $request->all();
		}
		//pr($get_yelp_session);die;
		if(count($get_yelp_session) > 0)
		{
			foreach($get_yelp_session as $key=>$val)
			{
				if($val['session_flag'] =="0")
				{
					Session::forget('yelp_experience');
				}
			}
		}
		$previous_url = url()->previous();
		
		$favourite_activityKeywords="";
		$search_terms="";
		$final_keyword= array();
		$paginatedItems= array();
		$yelp_ids=array();
		$current_location='Chicago, IL';
		$my_experience= array();  
		$comment= array();    
		$event_id="0";
		$TagKewords= array();
		
		//pr($data);die;
		if(count($data) > 0)
		{
			$other_keywords= array();
			$favourite_activityKeywords="";
			$favourite_activity= $data['event_favourite_activity'];			
			
			if($favourite_activity!="")
			{
				$favourite_tags= explode(',',$favourite_activity);		
				$favourite_activityKeywords=  Tag::whereIn('id', $favourite_tags)->get();
			}
				//echo count($favourite_activityKeywords);die;
			if(!empty($favourite_activity) && count($favourite_activityKeywords) > 0)
			{
				foreach($favourite_activityKeywords as $key=>$val)
				{
					$TagKewords[]= $val->tag_name;
				}
			}
			//echo count($other_keywords);die;
			$other_tags= $data['event_other_tags'];
			if($other_tags!="")
			{
				$other_keywords= explode(',', $other_tags);	
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
			
			//pr($final_keyword);die;
			if(count($final_keyword) > 0)
			{
				 $search_terms= json_encode($final_keyword);
				 $ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
				 $zipcode= $data['event_zipcode'];
				 $location_data = \Location::get($ip); //66.102.0.0
				 //pr($location_data);die;
				 $total_count= "0";
				 $page = 1;
				 $accessToken=env('YELP_SECRET_KEY');
				 $client = new \Stevenmaguire\Yelp\v3\Client(array(
				    'accessToken' => $accessToken,
				    'apiHost' => 'api.yelp.com' // Optional, default 'api.yelp.com'
				 ));
				 
				$perPage = 6; //env('PER_PAGE_RECORDS');;
				$offset = ($page - 1) * $perPage;
			 	
			    $parameters = [			   
					'term' =>$final_keyword,
					//'categories' => $final_keyword,
					//'categories' => ['Boxing','hotdogs','pizza','sandwich'],
					//'categories' => ['Barbers'],
					'location' => $zipcode,
					'limit' => $perPage,
				    'offset' => $offset
				];
				
				//pr($parameters);die;
				$results= $client->getBusinessesSearchResults($parameters);
				//pr($results);die;
				if(count($results) > 0)
				{
					$total_count = count($results->businesses);
					$total = $results->total;
					if($total>=996){
						$total = 996;	
					}

					$items = json_decode(json_encode($results->businesses), True);	 						
					$itemCollection = collect($items);
			        $paginatedItems= new LengthAwarePaginator($itemCollection , $total, $perPage);
					$url = "/recommended-ajax";
					$paginatedItems->setPath($url);
				}
			}

			//$html = view('site.search.index', ['results' => $paginatedItems,'yelpIds' =>$yelp_ids])->render();  
			$pagination = '<div>'.$paginatedItems->links('pagination.fynches').'</div>';
			 
			
			  //check yelp id in session
			 
			 $yelpExperiences = Session::get('yelp_experience');       
			
			 if(count($yelpExperiences) > 0)
			 {
			 	foreach($yelpExperiences as $key=>$val)
				{
					$yelp_ids []= $val['yelp_id'];
				}
			 }
			 
			 $Events = Event::where(['user_id' => $user_id])->whereIn('status', array('1', '4'))->pluck('id', 'title');
		
			if(count($Events) > 0)
			{
			 	$Events = $Events->toArray();
			}
		}

		$get_user_stripe_id =  Event::orderBy('updated_at', 'desc')->where('user_id',$user_id)->where('stripe_user_id','!=','0')->first();
		$user_last_event_stripe_id="0";
		
		if(count($get_user_stripe_id) > 0)
		{
			$user_last_event_stripe_id= $get_user_stripe_id->stripe_user_id;
		}
		
		
		$global_data= GlobalSetting::first();
		$funding_report_url="";
		$stripe_user_id="0";
		$stripe_url="";
           
		return view('site.event.event-create', ['title_for_layout' => 'Event','pagination' =>$pagination,'results' => $paginatedItems,'event_id'=>$event_id,'data'=>$data,'my_experience' =>$my_experience,'comment' =>$comment,'global_data' =>$global_data,'fundingRepotUrl' =>$funding_report_url,'yelpIds' =>$yelp_ids,'user_events' =>$Events,'search_terms' =>$search_terms,'current_location' =>$current_location,'current_event_id' =>"0",'preview_url' =>"",'event_status' =>$event_status,'stripe_user_id' =>$stripe_user_id,'stripe_url' =>$stripe_url,'user_last_event_stripe_id' =>$user_last_event_stripe_id]);
	}
	
	
	/*   
	 	Added by Devang Mavani
      	save event
	*/
	
	public function saveEvent(Request $request)
	{
		
		$data= $request->all();
		$get_yelp_session = Session::get('yelp_experience');
		$user_id = Auth::guard('site')->id();
		//pr($get_yelp_session);die;
		// pr($_FILES);die;
		
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
		
		if($data['event_publish_flag']=="0")
		{
			$event->status="4";	
		}else{
			$event->status="1";
		}
		
		$event->publish_url=$publish_url;
		
		$event->title=$data['event_title'];
		
		if($data['update_stripe_flag']!="")
		{
			$event->stripe_user_id=$data['user_last_event_stripe_id'];	
		}
		
		$event->description = $data['event_description'];
		$event->age_range=$data['age_range'];
      	$event->event_publish_date=date('Y-m-d',strtotime($data['event_publish_date'])).' 00:00:00';
      	$event->event_end_date=date('Y-m-d',strtotime($data['event_end_date'])).' 00:00:00';
      	$event->zipcode=$data['zipcode'];
      
	  	//pr($event);die;
      	$event->save();
      	$EventId = $event->id;
		
		
		if(count($get_yelp_session) > 0)
		{
			foreach($get_yelp_session as $key=>$val)
			{
				$experience = new Experience;
				$experience->event_id=$EventId;
				$experience->yelp_exp_id=$val['yelp_id'];
				$experience->exp_name=$val['exp_name'];
				$experience->image=$val['image'];
		      	$experience->experience_from="1";
		      	$experience->gift_needed=$val['amount'];
		      	$experience->status="In progress";
			  	$experience->save();
			}
		}
		//pr($get_yelp_session);die;
		
		/* Create exprince */
		if($data["gift_needed"]!="" && $data["exp_name"]!=""){

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
					return Redirect::to('/experience/create/'.$event_id)->withInput()->withErrors($validator);
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
			$experience->event_id=$EventId;
			$experience->exp_name=$data['exp_name'];
			$experience->image = $image_name;
			$experience->description = $data['description'];
			$experience->experience_from="0";
			$experience->gift_needed = $data['gift_needed'];
			$experience->status="In progress";
			$experience->save();
		}

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
              	$MappingEventMedia->event_id = $EventId;
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
              	$MappingEventMedia->event_id = $EventId;
              	$MappingEventMedia->status = 'Active';
              	$MappingEventMedia->save();
			}
        }
		
		if(isset($data['video']) && $data['flag_video']=="0"){			
			$MappingEventMedia = new MappingEventMedia;
			$MappingEventMedia->video = $data['video'];
         	$MappingEventMedia->image_type = '1';
			$MappingEventMedia->flag_video = $data['flag_video'];
          	$MappingEventMedia->event_id = $EventId;
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
          	$MappingEventMedia->event_id = $EventId;
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
						'event_id'   =>   $EventId
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
						'event_id'   =>   $EventId
						)
	    			);
	    		}
	  	  }
	    }
		
		if ($event) {
			Session::forget('yelp_experience');
			//pr($data);
			//echo count($get_yelp_session);die;
			//echo $data["update_stripe_flag"];die;
			if(count($get_yelp_session) > 0 || $data["gift_needed"]!="" && $data["exp_name"]!="")
			{
				$global_data= GlobalSetting::first();
				
				if(count($global_data) > 0 && $data["update_stripe_flag"]=="0")
				{
					$stripe_client_id= $global_data->stripe_client_id;
					$stripe_url= "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=".$stripe_client_id."&scope=read_write";
					return redirect($stripe_url);
				}else{
					 $msg = "Event Created Successfully.";
		             $log = ActivityLog::createlog($user_id, "Event", $msg, $user_id);
		             Session::flash('success_msg', $msg);
					 Session::forget('yelp_experience');
		             return redirect('/create-experience/'.$EventId.'');
				}
			}else{
				 $msg = "Event Created Successfully.";
	             $log = ActivityLog::createlog($user_id, "Event", $msg, $user_id);
	             Session::flash('success_msg', $msg);
				 Session::forget('yelp_experience');
	             return redirect('/create-experience/'.$EventId.'');
			}
		 } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/dashboard');
        }
	}

	public function AddFindPerfectExp($event_id)
	{
		$get_yelp_session = Session::get('yelp_experience');
		$user_id = Auth::guard('site')->id();
		
		if(count($get_yelp_session) > 0 && $event_id!="0")
		{
			foreach($get_yelp_session as $key=>$val)
			{
				$chk_exp_already_added= Experience::where('event_id',$event_id)->where('yelp_exp_id','=',$val['yelp_id'])->first();
				if(count($chk_exp_already_added)=="0")
				{
					$experience = new Experience;
					$experience->event_id=$event_id;
					$experience->yelp_exp_id=$val['yelp_id'];
					$experience->exp_name=$val['exp_name'];
					$experience->image=$val['image'];
			      	$experience->description="";
			      	$experience->experience_from="1";
			      	$experience->gift_needed=$val['amount'];
			      	$experience->status="In progress";
				  	$experience->save();
				}
				
			}
		}
		$msg = "Experience Added Successfully.";
        $log = ActivityLog::createlog($user_id, "Event", $msg, $user_id);
        Session::flash('success_msg', $msg);
		Session::forget('yelp_experience');
        return redirect('/create-experience/'.$event_id.'');
	}

	public function UpdateEvent(Request $request)
	{
		$data= $request->all();
		
		//pr($_FILES);
		//pr($data);die;
		$event_id= $data['event_id'];
		$user_id = Auth::guard('site')->id();
		
		
		$event = Event::find($event_id);
		$event->user_id=$user_id;
		$event->title=$data['event_title'];
		
		if($data['update_stripe_flag']!="")
		{
			$event->stripe_user_id=$data['user_last_event_stripe_id'];	
		}
		
		$event->description = $data['event_description'];
		$event->event_publish_date=date('Y-m-d',strtotime($data['event_publish_date'])).' 00:00:00';
		
		if($data['publish_url']=="")
		{
			$slug = str_replace(' ', '-', strtolower($data['event_title']));
			$publish_url = $slug; 		
		}
		else
		{
		  	$publish_url = $data['publish_url'];
		}
		
		if($data['event_publish_flag']=="1")
		{
			$event->status="1";	
		}
		
		$event->publish_url=$publish_url;
		$event->save();
		
		DB::table('mapping_event_media')->where('event_id', '=', $event_id)->delete(); 
		
		if(isset($data['old_images']) && $data['flag_video']=="0"){
			
			for ($i=0; $i<count($data['old_images']); $i++){
				
				$MappingEventMedia = new MappingEventMedia;
				$event_image = $data['old_images'][$i];
      		  	$MappingEventMedia->image = $event_image;
             	$MappingEventMedia->image_type = '0';
              	$MappingEventMedia->event_id = $event_id;
              	$MappingEventMedia->status = 'Active';
              	$MappingEventMedia->save();
			}
		}
		
		if(isset($data['old_facebook_images']) && $data['flag_video']=="0"){
			
			for ($i=0; $i<count($data['old_facebook_images']); $i++){
				
				$MappingEventMedia = new MappingEventMedia;
				$event_image = $data['old_facebook_images'][$i];
      		  	$MappingEventMedia->image = $event_image;
             	$MappingEventMedia->image_type = '2';
              	$MappingEventMedia->event_id = $event_id;
              	$MappingEventMedia->status = 'Active';
              	$MappingEventMedia->save();
			}
		}
		
		
		/* Create exprince */
		if($data["gift_needed"]!="" && $data["exp_name"]!=""){

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
					return Redirect::to('/experience/create/'.$event_id)->withInput()->withErrors($validator);
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
			$experience->exp_name=$data['exp_name'];
			$experience->image = $image_name;
			$experience->description = $data['description'];
			$experience->experience_from="0";
			$experience->gift_needed = $data['gift_needed'];
			$experience->status="In progress";
			$experience->save();
		}
		
		if(isset($data['event_images']) && $data['flag_video']=="0"){
    		
		    for ($i=0; $i<count($data['event_images']); $i++){
    	    	
				$event_image = $data['event_images'][$i];
				$destinationPath = 'storage/Event/';
                $timestamp = time() . uniqid();
                //$filename = $timestamp . '_' . trim($event_image->getClientOriginalName());
                
                $info = $this->getB64Type($event_image);
				
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
    		
			//DB::table('mapping_event_media')->where('event_id', '=', $event_id)->delete(); 
			
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
			
			//DB::table('mapping_event_media')->where('event_id', '=', $event_id)->delete(); 
			
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
		
		
		if ($event) {
			
				$global_data= GlobalSetting::first();
				
				if(count($global_data) > 0 && $data["update_stripe_flag"]=="0")
				{
					$stripe_client_id= $global_data->stripe_client_id;
					$stripe_url= "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=".$stripe_client_id."&scope=read_write";
					return redirect($stripe_url);
				}else{
					 $msg = "Event Updated Successfully.";
		             $log = ActivityLog::createlog($user_id, "Event", $msg, $user_id);
		             Session::flash('success_msg', $msg);
					 Session::forget('yelp_experience');
		             return redirect('/create-experience/'.$event_id.'');
				}
			
		 } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/dashboard');
        }
		 
	     // if ($event) {
            // $msg = "Event Updated Successfully.";
            // $log = ActivityLog::createlog($user_id, "Event", $msg, $user_id);
            // Session::flash('success_msg', $msg);
            // return redirect('/create-experience/'.$event_id.'');
        // } else {
            // Session::flash('error_msg', 'Something went wrong!');
            // return redirect('/dashboard');
        // }
		
	}
	
	public function DeleteEvent($id)
	{
		$user_id = Auth::guard('site')->id();
		$event = Event::with(['getEventExperiences' => function($query) use($id)
        {
            $query->select('event_id','exp_name','gift_needed','id');
        
        }])->with(['getEventMappingMdedia' => function($query) use($id)
        {
            $query->select('video', 'image','image_type','id','event_id','flag_video');
        
        }])->with(['getEventFundingReport' => function($query) use($id)
        {
			$query->select(DB::raw("SUM(donated_amount) as donated_amount"),'id','event_id','user_id');
			$query->groupby('event_id');
        
        }])->where('id',$id)->first();
		
		if(count($event) > 0)
		{
			$event = $event->toArray();
		}
		//pr($event); die;
		if(count($event['get_event_funding_report'])>0){  
			if($event['get_event_funding_report'][0]['donated_amount'] > 0){ 
				$msg = 'Your event does not delete due to your event have some fund.';
				Session::flash('error_msg', $msg);
				return redirect('/dashboard');
			}
		}          
         
        $eventDelete = Event::findOrFail($id);
		
        if (count($eventDelete)>0) { 
            if(count($event['get_event_mapping_mdedia'])>0){
                foreach ($event['get_event_mapping_mdedia'] as $key => $value) { 
                    $oldPicture = $value['image'];
                    $path_thumb =  'storage/Event/thumb/';
                    $destinationPath = 'storage/Event/'; 
                    if (file_exists($destinationPath . $oldPicture)) {
                        $unlink_success = File::delete($destinationPath . $oldPicture);
                    }
                    if (file_exists($path_thumb . $oldPicture)) {
                        $unlink_success = File::delete($path_thumb . $oldPicture);
                    }
                    if($value['image_type']=='1'){
                        $oldVideo = $value['video'];
                        $StorageVideoPath = 'storage/Videos/';
                        if (file_exists($StorageVideoPath . $oldVideo)) {
                            $unlink_success = File::delete($StorageVideoPath . $oldVideo);
                        }
                    }
                }
            }
        }
		
		$get_event_experiences = Experience::where(['event_id' => $id])->get();
		
		if(count($get_event_experiences) > 0)
		{
			if(count($get_event_experiences) > 0)
			{
				foreach($get_event_experiences as $key=>$value)
				{
					$oldPicture = $value['image'];
                    $path_thumb =  'storage/experienceImages/thumb/';
                    $destinationPath = 'storage/experienceImages/'; 
                    if (file_exists($destinationPath . $oldPicture)) {
                        $unlink_success = File::delete($destinationPath . $oldPicture);
                    }
                    if (file_exists($path_thumb . $oldPicture)) {
                        $unlink_success = File::delete($path_thumb . $oldPicture);
                    }
					$experience = Experience::findOrFail($value['id']);
					$experience->delete();
                   
				}
			}
			$mapping_event_media_delete = DB::table('mapping_event_media')->where('event_id', '=', $id)->delete();
			$commentsDelete = DB::table('comments')->where('event_id', '=', $id)->delete();	
		}
		
        
        $eventDelete->delete();
        $msg = "Event Deleted Successfully.";
		$log = ActivityLog::createlog($user_id, "Event", $msg, $user_id);
        Session::flash('success_msg', $msg);
        return redirect('/dashboard');
	}
	
	public function getB64Type($str) {
    // $str should start with 'data:' (= 5 characters long!)
    
    	$img = explode(',', $str);
		$ini =substr($img[0], 11);
		$type = explode(';', $ini);
		return $type[0];
    	//return substr($str, 5, strpos($str, ';')-5);
	}

	public function SaveAndPublishEvent($event_id)
	{
		$event = Event::find($event_id);
		$user_id = Auth::guard('site')->id();
		// Make sure you've got the Page model
		if($event) {
		    $event->status = '1';
		    $event->save();
			$msg = "Event Published Successfully.";
            $log = ActivityLog::createlog($user_id, "Event", $msg, $user_id);
            Session::flash('success_msg', $msg);
			Session::save();
			echo 1;
		 	exit;
        }
		 
	}

	public function validatePublishUrl(Request $request) {
		
		$data= $request->all();
		
		$event_id= $request['event_id'];
		
		if ($request->input('publish_url') !== '') {
			if($event_id!="0")
			{
				$event_data = Event::where('publish_url',$request->input('publish_url'))->where('id','!=',$event_id)->first();
			}else{
				$event_data = Event::where('publish_url',$request->input('publish_url'))->first();	
			}
            //pr($event_data);
			if(!$event_data)
			{
				die('true');
			}
        }
        die('false');
    }
	
	
	public function searchExperienceAjax(Request $request)
	{
		$data= $request->all();
		//pr($data);die;
		if(count($data) >0)
		{
			$data= $request->all();	
			$Events=array();
			$yelp_ids=array();
			$yelp_saved_Experiences=array();
			$search_title="";
			$current_location="";
			$page = 1;

			$user_id = Auth::guard('site')->id();
		
			$search_title = $data['search_terms'];	
			$current_location = $data['location'];
			if(isset($data['page'])){
				$page = $data['page'];
			}
			$ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
			$location_data = \Location::get($ip); //66.102.0.0
			
			//pr($location_data);die;
			if(count($data) > 0)
			{
				if($data['location']!="")
				{
					$current_location=$data['location'];
					$latitude="40.730610";
					$longitude= "-73.935242";
					//$latitude= $location_data['latitude'];
					//$longitude= $location_data['longitude'];
				}
				else {
					if(count($location_data) > 0)
					{
						$current_location='Chicago, IL';
						$latitude="40.730610";
						$longitude= "-73.935242";
						//$latitude= $location_data['latitude'];
						//$longitude= $location_data['longitude'];
					}
				}
			}
			
			$event_id= $data['event_id'];
			
			$Events = Event::where(['user_id' => $user_id])->whereIn('status', array('1', '4'))->pluck('title', 'id');
			
			if(count($Events) > 0)
			{
			 	$Events = $Events->toArray();
			}
			 
			 //check in database 
			$yelp_saved_Experiences = Experience::whereHas('getAllEvent', function($q) use($user_id,$event_id){
	    				 $q->where('user_id', $user_id);
						 $q->where('event_id', $event_id);
			})->where('yelp_exp_id','!=',NULL)->get();       
			
			 
			 if(count($yelp_saved_Experiences) > 0)
			 {
			 	foreach($yelp_saved_Experiences as $key=>$val)
				{
					$yelp_ids []= $val->yelp_exp_id;
				}
			 }
			 
			 //check yelp id in session
			 
			 $yelpExperiences = Session::get('yelp_experience');       
			
			 if(count($yelpExperiences) > 0)
			 {
			 	foreach($yelpExperiences as $key=>$val)
				{
					$yelp_ids []= $val['yelp_id'];
				}
			 }
			
			$accessToken=env('YELP_SECRET_KEY');
			 $client = new \Stevenmaguire\Yelp\v3\Client(array(
			    'accessToken' => $accessToken,
			    'apiHost' => 'api.yelp.com' // Optional, default 'api.yelp.com'
			 ));
			 
			$perPage = 6; //env('PER_PAGE_RECORDS');;
			$offset = ($page - 1) * $perPage;
			
			//echo $offset; 
			$parameters = [			   
				'term' => $search_title,
				'location' => $current_location,
				//'latitude' => $latitude,
		    	//'longitude' => $longitude,
			    'limit' => $perPage,
			    'offset' => $offset
			];
			//pr($parameters);die;
			
			$results= $client->getBusinessesSearchResults($parameters);
			
			//pr($results);die;
			if(count($results) > 0)
			{
				// $total_count = count($results->businesses);				
				$total = $results->total;
				if($total>=996){
					$total = 996;	
				}
				$items = json_decode(json_encode($results->businesses), True);	 						
				$itemCollection = collect($items);
		        $paginatedItems= new LengthAwarePaginator($itemCollection , $total, $perPage);
		        $paginatedItems->setPath($request->url());
			}
		}
		
		$html = view('site.search.index', ['results' => $paginatedItems,'yelpIds' =>$yelp_ids,'yelp_saved_Experiences' =>$yelp_saved_Experiences])->render();  
		$pagination = '<div>'.$paginatedItems->links('pagination.fynches').'</div>';
		return Response::json([
			'html' => $html,
			'pagination' => $pagination,
		], 200);
	}
	
	
	
	public function GetFundingReports($event_id)
	{
		$user_id = Auth::guard('site')->id();
		$funding_reports = array();
		$my_experience=array();
		
        // $funding_reports = Event::with(['getEventExperiences' => function ($q) use ($user_id){
                    // $q->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status');
                // }])->with(['FundingReport' => function ($q) use ($user_id){
                	// $q->with("getUser");
					// $q->select('*');
					// $q->where('user_id','!=',$user_id);
                // }])->select('title', 'user_id','id')->where('user_id',$user_id)->find($event_id);        
				
				
		$funding_reports = Experience::with(['FundingReport' => function ($q) use ($user_id){
                    $q->select('id','event_id','user_id','experience_id','description','payment_method','donated_amount','bonus_flag','sent_mail','make_annoymas','bonus_amount','status');
					$q->whereIn('status',array('succeeded','pending'));
					$q->with("getUser");
					$q->with("getEvent");
                }])->select('id', 'event_id','exp_name','description','gift_needed','status')->where('event_id',$event_id)->get();
				
		$bonus_amount = FundingReport::with(['getEvent' => function ($q) use ($user_id,$event_id){
                    $q->select('id','user_id','description','status');
					$q->where('user_id','!=',$user_id);
					$q->where('id',$event_id);
					$q->with("getUser");
					
                }])->select('id','event_id','user_id','experience_id','description','donated_amount','sent_mail','payment_method','bonus_flag','bonus_amount','make_annoymas','status')->where('bonus_flag',"1")->get();
		
		
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
                ->find($event_id);						
		//pr($bonus_amount->toArray());die;
		$global_data= GlobalSetting::first();
		$funding_report_url = "";	
		$event_status="0";
		$stripe_user_id="0";
		$preview_url="";
		//pr($funding_reports[0]->getEvent->status);die;
		if(count($funding_reports) > 0)
		{
			$event_status= $funding_reports[0]->getEvent->status;
			//$preview_url= $funding_reports[0]->getEvent->publish_url;
			$preview_url= url('/'.$funding_reports[0]->getEvent->publish_url);
			$stripe_user_id= $funding_reports[0]->getEvent->stripe_user_id;
		}
		
		
		//get user last event stripe id
		$get_user_stripe_id =  Event::orderBy('updated_at', 'desc')->where('user_id',$user_id)->first();
		$user_last_event_stripe_id="0";
		
		if(count($get_user_stripe_id) > 0)
		{
			$user_last_event_stripe_id= $get_user_stripe_id->stripe_user_id;
		}
		
		//pr($funding_reports->toArray());die;
		
		return view('site.event.funding-report', ['title_for_layout' => 'Funding Report','funding_reports'=>$funding_reports,'bonus_amount' =>$bonus_amount,'global_data' => $global_data, 'fundingRepotUrl' =>$funding_report_url,'current_event_id'=>$event_id,'event_status' => $event_status,'preview_url' =>$preview_url,'user_last_event_stripe_id' =>$user_last_event_stripe_id,'my_experience' =>$my_experience]);
	}

	public function AjaxRecommended(Request $request){	
		
		$get_yelp_session = Session::get('yelp_experience');
		
		$data= $request->all();
		//pr($data);die;
		$user_id = Auth::guard('site')->id();
		$favourite_activityKeywords="";
		$search_terms="";
		$final_keyword= array();
		$paginatedItems= array();
		$yelp_ids=array();
		$my_experience= array();  
		$comment= array();    
		$event_id="0";
		//pr($data);die;
		$TagKewords= array();
		$other_keywords= array();
		$favourite_activityKeywords=array();

		//pr($data);die;
		if(count($data) > 0)
		{
			
			if(array_key_exists('events_id', $data)){
				$event_id = $data["events_id"];
				$event="";
				if($event!="0")
				{
					$event = Event::with('getEventTags')->with('getCustomTags')->where('id',$event_id)->first();
				}

				$yelpExperiences = Session::get('yelp_experience');       
			
				if(count($yelpExperiences) > 0)
				{
				 	foreach($yelpExperiences as $key=>$val)
					{
						$yelp_ids []= $val['yelp_id'];
					}
				}
				
				if(count($event)>0){
					
					foreach($event->getCustomTags as $custom){
						$other_keywords[] = $custom->name;
					}

					foreach($event->getEventTags as $tag){
						$favourite_tags[] = $tag->tag_id;
					}
				}else{
					if(isset($data['favourite_activity']))
					{
						$favourite_activity= $data['favourite_activity'];
						$favourite_tags="";
						if($favourite_activity!="")
						{
							$favourite_tags= explode(',',$favourite_activity);	
						}
					}
					
					if(isset($data['other_tags']))
					{
						$other_tags= $data['other_tags'];
				
						if($other_tags!="" && is_array($other_tags))
						{
							$other_keywords= explode(',', $other_tags);	
						}else{
							$other_keywords=$data['other_tags'];
						}
					}
					
					
				}
				//pr($favourite_tags);exit;
			}else{
				
				if(isset($data['favourite_activity']))
				{
					$favourite_activity= $data['favourite_activity'];
				
					if($favourite_activity!="")
					{
						$favourite_tags= explode(',',$favourite_activity);	
					}
				}
				
				if(isset($data['other_tags']))
				{
					$other_tags= $data['other_tags'];
					if($other_tags!="" && is_array($other_tags))
					{
						$other_keywords= explode(',', $other_tags);	
					}else{
						$other_keywords=$other_tags;
					}
				}
			}
			
			
			if(!empty($favourite_tags))
			{
				$favourite_activityKeywords=  Tag::whereIn('id', $favourite_tags)->get();
			}
			
			
			if(!empty($favourite_activityKeywords))
			{
				foreach($favourite_activityKeywords as $key=>$val)
				{
					$TagKewords[]= $val->tag_name;
				}
			}
		
			if(count($TagKewords) > 0 && count($other_keywords) > 0)
			{
				if(is_array($other_keywords))
				{
					$final_keyword = array_merge($TagKewords,$other_keywords);	
				}else{
					//echo $other_keywords;die;
					$final_keywordd = array_push($TagKewords,$other_keywords);
					$final_keyword=$TagKewords;
				}
				
			}
			
			else if(count($TagKewords) > 0 && count($other_keywords) <= 0)
			{
				$final_keyword = $TagKewords;
			}
			else if(count($other_keywords) > 0 && count($TagKewords) <= 0)
			{
				$final_keyword = $other_keywords;
			}
			
			//pr($final_keyword);die;
			if(!empty($final_keyword))
			{
				 $search_terms= json_encode($final_keyword);
				 $ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
				 $location_data = \Location::get($ip); //66.102.0.0
				 $total_count= "0";
				 $page = 1;
				 
				 $get_steps_session = Session::get('steps');
				// pr($get_steps_session);die;
				 if(count($get_steps_session) > 0)
				 {
				 	$current_location=$get_steps_session['event_zipcode'];
				}else{
				 	$current_location = 'Chicago, IL';
				 }
				
		         $accessToken=env('YELP_SECRET_KEY');
				 $client = new \Stevenmaguire\Yelp\v3\Client(array(
				    'accessToken' => $accessToken,
				    'apiHost' => 'api.yelp.com' // Optional, default 'api.yelp.com'
				 ));
				 
				$perPage = 6;
				if(isset($data['page'])){
					$page = $data['page'];
				}

				$offset = ($page - 1) * $perPage;			    
			    $parameters = [			   
					'term' => $final_keyword,
					'location' => $current_location,
					// 'latitude' => $latitude,
		    		// 'longitude' => $longitude,
				    'limit' => $perPage,
				    'offset' => $offset
				];
				
				//pr($parameters);die;
				$results= $client->getBusinessesSearchResults($parameters);
				
				if(count($results) > 0)
				{
					$total_count = count($results->businesses);	
					$total = $results->total;
					if($total>=996){
						$total = 996;	
					}

					$items = json_decode(json_encode($results->businesses), True);	 						
					$itemCollection = collect($items);
			        $paginatedItems= new LengthAwarePaginator($itemCollection , $total, $perPage);
					$url = "/recommended-ajax";
					$paginatedItems->setPath($url);
				}
			}
			
			$yelpExperiences = Session::get('yelp_experience');  
			//pr($yelpExperiences);
			//$html = view('site.search.index', ['results' => $paginatedItems,'yelpIds' =>$yelp_ids])->render();  
			$pagination = '<div>'.$paginatedItems->links('pagination.fynches').'</div>';
			$html = view('site.search.index', ['results' => $paginatedItems,'yelpIds' =>$yelp_ids])->render();  			
			return Response::json([
				'html' => $html,
				'pagination' => $pagination,
			], 200);
			
		}
	}
	public function ShareEvent($id)
	{
		$event = Event::find($id);
		$user_id = Auth::guard('site')->id();
		$global_data= GlobalSetting::first();
		$funding_report_url = "";	
		$current_event_id=$id;
		$event_status="";
		$preview_url="";
		$my_experience=array();
		$user_last_event_stripe_id="0";
		
		if(count($event) > 0)
		{
			$event_status= $event->status;
			$preview_url= url('/'.$event->publish_url);
			$stripe_user_id= $event->stripe_user_id;
		}
		
		if($event_status=="1")
		{
			$funding_report_url=  url('/funding-report/'.$id);
		}
		
		$my_experience = Event::with(['getEventExperiences' => function ($q) {
                    $q->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status');
                }])->with(['getEventMappingMdedia' => function($query) use($id)
		        {
		            $query->select('video', 'image','image_type','id','event_id','flag_video');
		        
		        }])->with(['getEventTags' => function ($q) {
		            $q->select('*');
					$q->with('getTags');
		        }])->with(['getCustomTags' => function ($q) {
		            $q->select('*');
		        }])->with(['FundingReport' => function ($q) {
                    $q->select('id', 'event_id','experience_id','user_id','donated_amount');
                }])->select('title', 'user_id','id','stripe_user_id')
                ->find($id);
		
		//get user last event stripe id
		$get_user_stripe_id =  Event::orderBy('updated_at', 'desc')->where('user_id',$user_id)->first();
		$user_last_event_stripe_id="0";
		
		if(count($get_user_stripe_id) > 0)
		{
			$user_last_event_stripe_id= $get_user_stripe_id->stripe_user_id;
		}
				
		return view('site.event.share-event', ['title_for_layout' => 'Event','event_data' =>$event,'global_data' =>$global_data,'fundingRepotUrl' =>$funding_report_url,'current_event_id' =>$current_event_id,'event_status' =>$event_status,'preview_url' =>$preview_url,'user_last_event_stripe_id' =>$user_last_event_stripe_id,'my_experience' =>$my_experience]);
	}
	
	public function ShareMyEvent(Request $request)
	{
		$data= $request->all();
		$user_id = Auth::guard('site')->id();
		$previous_url =  url()->previous();
		
		//pr($data);die;
		if(count($data) > 0)
		{
			$search = array("[EVENT_NAME]","[EVENT_URL]","[DESCRIPTION]","[URL]");
        	$replace = array($data['event_name'],$data['publish_url'],$data['description'],env('SITE_URL').'/'.$data['publish_url']);
			
			if(isset($data['user_emails']) && count($data['user_emails']) > 0)
			{
				foreach($data['user_emails'] as $key=>$val)
				{
					$emailParams = array(
			            'subject' => $data['subject'],
			            'from' => config('constant.fromEmail'),
			            'to' => $val,
			            'email'=> $val,
			            'url'=>env('SITE_URL'),
			            'template'=>'share-an-event',
			            'search' => $search,
			            'replace' => $replace
			        );
					
					$result = User::SendEmail($emailParams);
				}
			}else{
					$emailParams = array(
			            'subject' => $data['subject'],
			            'from' => config('constant.fromEmail'),
			            'to' => $data['email'],
			            'email'=> $data['email'],
			            'url'=>env('SITE_URL'),
			            'template'=>'share-an-event',
			            'search' => $search,
			            'replace' => $replace
			        );
			     	$result = User::SendEmail($emailParams);   
			}
		}

		if($result)
		{
			$msg = "Event Shared Successfully.";
			$log = ActivityLog::createlog($user_id, "Event", $msg, $user_id);
	        Session::flash('success_msg', $msg);
			return redirect($previous_url);
		}else{
			$msg = "Something went wrong.";
			$log = ActivityLog::createlog($user_id, "Thank you mail", $msg, $user_id);
	        Session::flash('error_msg', $msg);
			return redirect($previous_url);
		}
	}

	public function SendThankyouEmail(Request $request)
	{
		$data= $request->all();
		$user_id = Auth::guard('site')->id();
		$previous_url =  url()->previous();
		
		//pr($data);die;
		//echo $previous_url;die;
		if(count($data) > 0)
		{
			$user_name="User";
			$user= User::where('email',$data['email']) -> first();
			if(count($user) > 0)
			{
				$user_name = $user->first_name.' '.$user->last_name;
			}
			//echo $user_name;die;
			$search = array("[USERNAME]","[DESCRIPTION]");
        	$replace = array($user_name,$data['description']);
			
			$emailParams = array(
	            'subject' => $data['subject'],
	            'from' => config('constant.fromEmail'),
	            'to' => $data['email'],
	            'email'=> $data['email'],
	            'url'=>env('SITE_URL'),
	            'template'=>'thank-you',
	            'search' => $search,
	            'replace' => $replace
	        );
			
	     	$result = User::SendEmail($emailParams);  
		}
		//echo $result;die;
		if($result)
		{
			FundingReport::where('id', $data['funding_report_id'])->update(array('sent_mail' => '1'));	
			$msg = "Mail Sent Successfully.";
			$log = ActivityLog::createlog($user_id, "Thank you mail", $msg, $user_id);
	        Session::flash('success_msg', $msg);
			return redirect($previous_url);
		}else{
			$msg = "Something went wrong.";
			$log = ActivityLog::createlog($user_id, "Thank you mail", $msg, $user_id);
	        Session::flash('error_msg', $msg);
			return redirect($previous_url);
		}
	}
	
	public function validateEventUrl(Request $request) {
		
		$data= $request->all();
		$event_id= $request['event_id'];
		
		if ($request->input('event_title') !== '') {
			if($event_id!="0")
			{
				$event_data = Event::where('publish_url',$request->input('event_title'))->where('id','!=',$event_id)->first();
			}else{
				$event_data = Event::where('publish_url',$request->input('event_title'))->first();	
			}
            
			if(!$event_data)
			{
				die('true');
			}
        }
        die('false');
    }
}
