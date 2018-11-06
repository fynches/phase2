<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Auth; 
use App\User;
use App\ChildInfo;
use App\ActivityLog;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use URL;
use App\Beta_Signup;
use App\Event;
use App\Tag;
use Carbon\Carbon;
use App\MappingEventMedia;


class EventController extends Controller {

    public function __construct() {
         
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //display Event listing
    public function index() {
    	
        $age_range = config('constant.age_range');
        //echo json_encode($age_range);exit;
        $age_range = json_encode($age_range);
        return view('admin/Event/index', ['title_for_layout' => 'List Events','age_range'=>$age_range]);
    } 
    
    //get user data
    public function getData(Request $request) {
        //DB::statement(DB::raw('set @rownum=0'));
        return Datatables::of(Event::with('users')
                ->whereNull('deleted_at')
				->where('status','!=','4')
                ->select("*"))
                ->make(true);
    }

    //get Event records
	public function getEventData() {
		
        $start = $_GET['start'];

        return $event = Datatables::of(Event::with('users')
                ->whereNull('deleted_at')    
				->where('status','!=','4')              
                ->select("*"))
                ->addColumn('rownum', $start + 1) 
                ->make(true); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //create user form listing
    public function create()
    {
        try {
            $users = DB::table('users')->where(['user_type' => config('constant.end_user_type')])->where(['user_status' => 'Active'])->whereNull('deleted_at')->pluck('first_name', 'id')->toArray();
            $tags = Tag::where(['status' => 'Active'])->get();

            return view('admin/Event/create', ['title_for_layout' => 'Add Event', 'user' => $users, 'tags' => $tags]);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
	
	
	public function multiple_img_upload(Request $request){
		
		if ($request->hasFile('files')) {
			
			$file = $request->file('files');
			
			foreach($file as $files){
				$filename = $files->getClientOriginalName();
				$extension = $files->getClientOriginalExtension();
				$picture = sha1($filename . time()) . '.' . $extension;
				
				
				//specify your folder
				
				$destinationPath = 'storage/Event_temp/';
				if (!file_exists($destinationPath)) {
		            mkdir($destinationPath, 0777, true);
		            chmod($destinationPath, 0777);
		        }
				$files->move($destinationPath, $picture);
				$destinationPath1='http://'.$_SERVER['HTTP_HOST'].'/'.$destinationPath. '/';
				
				$filest = array();
				$filest['name'] = $picture;
				$filest['size'] = $this->get_file_size($destinationPath.$picture);
				$filest['url'] = $destinationPath1.$picture;
				$filest['thumbnailUrl'] = $destinationPath1.$picture;
				$filesa['files'][]=$filest;
			}
			//pr($filesa);die;
			return  $filesa;
		}
	}
	
	function format_size($size) {
      $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
      if ($size == 0) { return('n/a'); } else {
      return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
	}
	
	protected function get_file_size($file_path, $clear_stat_cache = false) {
		if ($clear_stat_cache) {
			if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
				clearstatcache(true, $file_path);
			} else {
				clearstatcache();
			}
		}
		return $this->fix_integer_overflow(filesize($file_path));
	}
	protected function fix_integer_overflow($size) {
		if ($size < 0) {
			$size += 2.0 * (PHP_INT_MAX + 1);
		}
		return $size;
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //save user record
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:200',
                'description' => 'required',
                //'event_image' => 'required',
                'age_range' => 'required',
                'event_publish_date' => 'required',
                'event_end_date' => 'required',
                'zipcode' => 'required|max:5',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect('/admin/event/create')
                    ->withInput()
                    ->withErrors($validator);
            } else {

                $data = $request->all();

                if ($data['publish_url'] == "") {
                    $slug = str_replace(' ', '-', strtolower($data['title']));
                    $event_data = Event::where('publish_url', $slug)->first();

                    if (count($event_data) > 0) {
                        $total_slug_count = count($event_data) + 1;
                        $data['publish_url'] = $slug . $total_slug_count;
                    } else {
                        $data['publish_url'] = $slug;
                    }
                } else {
                    $event_data = Event::where('publish_url', $data['publish_url'])->first();
                    if (count($event_data) > 0) {
                        Session::flash('error_msg', 'Publish Url already Exists please try another!');
                        return redirect('/admin/event');
                    }
                }


                $event = Event::eventcreate($data);
                $destinationPath = 'storage/Event/';
                $old_directory = 'storage/Event_temp/';
                if (isset($data['event_image'])) {
                    if ($data['image_type'] == "0") {
                        for ($i = 0; $i < count($data['event_image']); $i++) {
                            $image = $data['event_image'][$i];
                            $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

                            $imgs_name = basename($image);
                            $old_file_name = 'storage/Event_temp/' . basename($image); // show only image name if you want to show full path then use this code // echo $image."<br />";

                            File::copy($old_file_name, base_path('public/storage/Event/' . $imgs_name . ''));
                        }
                    }
                }

                if ($event) {
                    $delete_temp_files = File::deleteDirectory($old_directory, true);
                    $msg = "Event Created Successfully.";
                    $log = ActivityLog::createlog(Auth::Id(), "Event", $msg, Auth::Id());
                    Session::flash('success_msg', $msg);
                    return redirect('/admin/event');
                } else {
                    Session::flash('error_msg', 'Something went wrong!');
                    return redirect('/admin/event');
                }
            }
        } catch (\Exception $ex) {
                return $ex->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    //display single Event record
    public function show($id) {
        $event = Event::with(['getUser' => function ($q) {
                    $q->select('first_name', 'last_name','id');
                }])->with(['getEventMappingMdedia' => function ($q) {
                    $q->select('image','image_type','id','event_id');
                }])->with('getEventTags')->select('id','user_id','title','description','event_publish_date','event_end_date','zipcode','status')
                ->find($id)->toArray();
        $childInfo=ChildInfo::where('event_id',$event['id'])->get();
        $tags = Tag::where(['status' => 'Active'])->get();
        if (empty($event)) {
            Session::flash('error_msg', 'Event not found.55');
            return redirect('/admin/event');
        }
        return view('admin/Event/show', ['title_for_layout' => 'Event Details',
            'event' => $event,'childInfo'=>$childInfo,'tags' =>$tags]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //edit Event record
    public function edit($id) {
        $users = DB::table('users')
            ->where(['user_type' => config('constant.end_user_type')])
            ->where(['user_status' => 'Active'])->whereNull('deleted_at')
            ->pluck('first_name', 'id')->toArray();
        //pr($users);exit;
        // $event = Event::with(['getEventMappingMdedia' => function ($q) {
                    // $q->select('video', 'image','image_type','id','event_id');
                // }])->find($id);
                
                
         $event = Event::with(['getEventMappingMdedia' => function($query) use($id)
		 {
		    $query->select('image','image_type','id','event_id');
		
		 }])->with('getEventTags')->where('id',$id)->get(); 
		
        $isAdmin = $ischngeprofile = false;
        $title_for_layout = 'Edit Event';

        if (empty($event)) {
            Session::flash('error_msg', 'Event not found.444');
            return redirect('/admin/event');
        }

        $userType = Auth::Id(); 
		
		$destinationPath = 'storage/Event/'; 
		$destinationPath1='http://'.$_SERVER['HTTP_HOST'].'/'.$destinationPath; 
        $edit_img_val = array();
        $filesa = array();
         
        //$filesa['files'][]=array();
          
		if(count($event) > 0)
        {   
            if(count($event[0]['getEventMappingMdedia'])>0 && ($event[0]['getEventMappingMdedia'][0]['image_type']=='0')){
            	foreach($event[0]['getEventMappingMdedia'] as $key=>$val){				
    				$picture = $val->image;
    				$filest = array();
    				$filest['name'] = $picture;
    				$filest['size'] = $this->get_file_size($destinationPath.$picture);
    				$filest['img_size'] = $this->format_size($filest['size']);
    				$filest['url'] = $destinationPath1.$picture;
    				$filest['thumbnailUrl'] = $destinationPath1.$picture;
    				$filest['mapping_id'] = $val->id;
    				$filesa['files'][]=$filest;
    		        $edit_img_val = json_encode($filesa['files']);  		
                }
            }
        }   

       
        
		$tags = Tag::where(['status' => 'Active'])->get();         
        return view('admin/Event/edit', ['title_for_layout' => $title_for_layout,
            'event' => $event, 'user'=>$users, 
            'isAdmin' => $isAdmin,'filesa' =>@$filesa['files'],'edit_img_val' =>@$edit_img_val,'tags' =>$tags]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //update user record or user profile
    public function update(Request $request, $id) {

		$event = Event::find($id);

        if (empty($event)) {
            Session::flash('error_msg', 'Event not found.333');
            return redirect('/admin/event');
        }

        $validator = Validator::make($request->all(), [
                    'title' => 'required|max:200',
                    'description' => 'required',
                    'age_range' => 'required',
                    'event_publish_date' => 'required',
                    'event_end_date' => 'required',
                    'zipcode' => 'required|max:5',
                    'status' => 'required'
                    
        ]);

        if ($validator->fails()) {
            return redirect('/admin/event/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $data = $request->all();

            if($data['publish_url']=="")
			{
				$slug = str_replace(' ', '-', strtolower($data['title']));	 
				$event_data = Event::where('publish_url',$slug)->first();	
				
				if(count($event_data)>0)
				{
					$total_slug_count= count($event_data)+1;
					$data['publish_url']= $slug.$total_slug_count;
				}else{
					$data['publish_url']= $slug;
				}
				
			}else{
				$event_data = Event::where('publish_url',$data['publish_url'])->first();
				if(count($event_data) > 0)
				{
					Session::flash('error_msg', 'Publish Url already Exists please try another!');
                	return redirect('/admin/event');
				}
			}
			 
        	$destinationPath = 'storage/Event/';
			$old_directory = 'storage/Event_temp';
			
			$files = glob("storage/Event_temp/*.*"); 
			foreach($files as $file) {
				$img_name= basename($file);
				$old_file_name=  'storage/Event_temp/'.$img_name;
				File::copy($old_file_name, base_path('public/storage/Event/'.$img_name.''));
			}
            //$user_profile_image = Input::file('image');
			if(Input::file('video_files') && $data['flag_video']=="1"){


                $file = Input::file('video_files');
                $timestamp = time();
                $filename = $timestamp . '_' . trim($file->getClientOriginalName());
                //echo $filename;
                $path_thumb =  'storage/Videos/';
                if (!file_exists($path_thumb)) {
                    mkdir($path_thumb, 0777, true);
                    chmod($path_thumb, 0777);
                }
                $path = 'storage/Videos/';
                $file->move($path, $filename);
                $data['video'] = $filename;
            }
			
            $eventresult = Event::eventupdate($data,$id);
            if($eventresult){
                $msg = "Event Updated Successfully.";
				$log = ActivityLog::createlog(Auth::Id(), "Event", $msg, Auth::Id());
                Session::flash('success_msg', $msg);
                return redirect('/admin/event');
            }else{
                Session::flash('error_msg', 'Something went wrong!');
                return redirect('/admin/event/' . $id . '/edit');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //delete event record 
        public function destroy($id) {
        $event = Event::with(['getEventExperiences' => function($query) use($id)
        {
            $query->select('event_id','exp_name','gift_needed','id');
        
        }])->with(['getEventMappingMdedia' => function($query) use($id)
        {
            $query->select('image','image_type','id','event_id');
        
        }])->with(['getEventFundingReport' => function($query) use($id)
        {
            $query->select('donated_amount','id','event_id','user_id');
        
        }])->where('id',$id)->first()->toArray();  
         
        $date = Carbon::parse($event['event_end_date']);
        $now = \Carbon\Carbon::now();
            $diff = 0;
        if($date > $now){
            $diff = $date->diffInDays($now);
        }
        $datePublish = $event['event_publish_date']; 
        if($event['event_publish_date'] === '0000-00-00 00:00:00'){
            $datePublish = '';
        }
        if($datePublish!=""){
            if(count($event['get_event_funding_report'])>0){  
                if($event['get_event_funding_report'][0]['donated_amount']!=""){ 
                    $msg = 'Your event does not delete due to your event have some fund.';
                    Session::flash('error_msg', $msg);
                    Session::save();
                    echo json_encode(array('status'=>0,'message'=>$msg));
                    exit;
                }
            }else if($diff<=30) {
                $msg = 'Your event does not delete due to it have few days remaining to expire.';
                Session::flash('error_msg', $msg);
                Session::save();
                echo json_encode(array('status'=>0,'message'=>$msg));
                exit;
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
                }
            }
        }
        
        $eventDelete->delete();
        $msg = "Event Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Event", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo json_encode(array('status'=>1));
        exit;
    }


    // Multiple event delete
    function multiple_row_delete($id) {       
        $ids = explode(",", $id); 
        foreach ($ids as $value) {  
            $event = Event::with(['getEventExperiences' => function($query) use($value)
                    {
                        $query->select('event_id','exp_name','gift_needed','id');
                    
                    }])->with(['getEventMappingMdedia' => function($query) use($value)
                    {
                        $query->select('video', 'image','image_type','id','event_id','flag_video');
                    
                    }])->with(['getEventFundingReport' => function($query) use($id)
                    {
                        $query->select('donated_amount','id','event_id','user_id');
                    
                    }])->where('id',$value)->first()->toArray();  
                     
            $date = Carbon::parse($event['event_end_date']);
            $now = \Carbon\Carbon::now();
            $diff = 0;
            if($date > $now){
                $diff = $date->diffInDays($now);   
            }

            $datePublish = $event['event_publish_date']; 
            if($event['event_publish_date'] === '0000-00-00 00:00:00'){
                $datePublish = '';
            }

            if($datePublish!=""){
                if(count($event['get_event_funding_report'])>0){ 
                    if($event['get_event_funding_report'][0]['donated_amount']!=""){ 
                        $ids = array_diff( $ids, [$value]); 
                        break;
                    }
                }else if($diff<=30) {
                    $ids = array_diff( $ids, [$value]); 
                    break;
                }
            }


            if(count($event['get_event_mapping_mdedia'])>0){                 
                foreach ($event['get_event_mapping_mdedia'] as $keys => $values) { 
                    $oldPicture = $values['image'];
                    $path_thumb =  'storage/Event/thumb/';
                    $destinationPath = 'storage/Event/'; 
                    if (file_exists($destinationPath . $oldPicture)) {
                        $unlink_success = File::delete($destinationPath . $oldPicture);
                    }
                    if (file_exists($path_thumb . $oldPicture)) {
                        $unlink_success = File::delete($path_thumb . $oldPicture);
                    }
                    if($values['image_type']=='1'){
                        $oldVideo = $values['video'];
                        $StorageVideoPath = 'storage/Videos/';
                        if (file_exists($StorageVideoPath . $oldVideo)) {
                            $unlink_success = File::delete($StorageVideoPath . $oldVideo);
                        }
                    }
                }
            }
             

            /*$event = Event::findOrFail($value);
            if (count($event)>0) {
                $MappingEventMedia = MappingEventMedia::where('event_id',$value)->first()->toArray();
                if(count($MappingEventMedia)>0){
                    $oldPicture = $MappingEventMedia['image'];
                    $path_thumb =  'storage/Event/thumb/';
                    $destinationPath = 'storage/Event/'; 
                    if (file_exists($destinationPath . $oldPicture)) {
                        $unlink_success = File::delete($destinationPath . $oldPicture);
                    }
                    if (file_exists($path_thumb . $oldPicture)) {
                        $unlink_success = File::delete($path_thumb . $oldPicture);
                    }
                }
            }*/
        }
        Event::destroy($ids);
        $msg = "Event Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Event", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
    }

	public function delete_event_img($id){
		
		$destinationPath = 'storage/Event/'; 
		$MappingEventMedia = MappingEventMedia::where('id',$id)->first();
		
		if(count($MappingEventMedia) > 0)
		{
			$name = $MappingEventMedia->image;
			if (file_exists($destinationPath . $name)) {
            	$unlink_success = File::delete($destinationPath . $name);
        	}
			DB::table('mapping_event_media')->where('id', '=', $id)->delete();
		}
		return 'success';
	}
	
	public function validatePublishUrl(Request $request) {
			
		$data= $request->all();
		$event_id="0";
		
		if(isset($data['event_id']))
		{
			$event_id= $request['event_id'];	
		}
		
		if ($request->input('publish_url') !== '') {
			
			if($event_id!="0")
			{
				$event_data = Event::where('publish_url',$request->input('publish_url'))->where('id','!=',$event_id)->first();	
			}else{
				$event_data = Event::where('publish_url',$request->input('publish_url'))->first();
			}
            
			if(!$event_data)
			{
				die('true');
			}
        }
        die('false');
    }
     
}
