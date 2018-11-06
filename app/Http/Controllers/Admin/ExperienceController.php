<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Auth; 
use App\User;
use App\Countries;
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
use App\Experience;
use App\Event;

class ExperienceController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //display Event listing
    public function index($id="") {
    	
		//$events =Event::with('users')->where(['status' => '1','id' =>$id])->whereNull('deleted_at')->get();
		$events =Event::with('users')->where(['id' =>$id])->whereNull('deleted_at')->get();
		return view('admin/Experience/index', ['title_for_layout' => 'List Experiences','event_id' =>$id,'events' =>$events]);
    } 
    
   
	public function getData($id="") {
        	
		
        $start = $_GET['start'];
         
        return Datatables::of(Experience::with('events')
                ->whereNull('deleted_at')   
				->where('event_id',$id)                 
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
    public function create($event_id="") {
        
        $events =Event::with('users')->where(['status' => '1','id' =>$event_id])->whereNull('deleted_at')->get();
		return view('admin/Experience/create', ['title_for_layout' => 'Add Experience','event'=> $events,'event_id'=>$event_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //save user record
    public function store(Request $request) {
    	
        $validator = Validator::make($request->all(), [
                    'exp_name' => 'required|max:200',
                    'description' => 'required',
                    'event_id' => 'required',
                    'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/experience/create')
                            ->withInput()
                            ->withErrors($validator);
        }

        $data = $request->all();
		
		$event_id= $data['event_id'];
		
		
		$files = Input::file('image');
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
	            
	            Image::make($files->getRealPath())->resize(300, 300)->save('storage/experienceImages/thumb/' . $filename);
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
        
		$data['image'] = $image_name;
        
        $experience = Experience::experiencecreate($data);

        if ($experience) {
            $msg = "Experience Created Successfully.";
            $log = ActivityLog::createlog(Auth::Id(), "Experience", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/event/experience/'.$event_id.'');
        } else {
            Session::flash('error_msg', 'Something went wrong!');
             return redirect('/admin/event/experience/'.$event_id.'');
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

        $experience = Experience::with(['getEvent' => function ($q) {
                    $q->select('title', 'user_id','id');
                }])->select('id','event_id','exp_name','description','image','experience_from','gift_needed','status')
                ->find($id);
         
		if(count($experience) > 0)
		{
			$event =Event::with('users')->where(['status' => '1','id' =>$experience->event_id])->whereNull('deleted_at')->get();	
			
			$funding_exp = Experience::with(['FundingReport' => function ($q) {
                    $q->select('donated_amount', 'user_id','event_id','experience_id','id');
					
                }])->select('id','event_id','exp_name','image','description','status')
                ->find($id)->toArray();
		}    
		 
        if (empty($experience)) {
            Session::flash('error_msg', 'Event not found.');
            return redirect('/admin/experience');
        }
        return view('admin/Experience/show', ['title_for_layout' => 'Experience Details',
            'experience' => $experience,'event' => $event,'funding_report' =>$funding_exp]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //edit Experience record
    public function edit($id) {

        $experience = Experience::find($id);
		
        $title_for_layout = 'Edit Details';

        if (empty($experience)) {
            Session::flash('error_msg', 'Experience not found.');
            return redirect('/admin/experience');
        }
        if(count($experience) > 0)
		{
			$event =Event::with('users')->where(['status' => '1','id' =>$experience->event_id])->whereNull('deleted_at')->get();	
			
			$funding_exp = Experience::with(['FundingReport' => function ($q) {
                    $q->select('donated_amount', 'user_id','event_id','experience_id','id');
					
                }])->select('id','event_id','exp_name','image','description','status')
                ->find($id)->toArray();
		}   
		
		
		return view('admin/Experience/edit', ['title_for_layout' => $title_for_layout,
            'experience' => $experience, 'event' => $event,'funding_report' =>$funding_exp]
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
    	
        $experience = Experience::find($id);

        if (empty($experience)) {
            Session::flash('error_msg', 'Experience not found.');
            return redirect('/admin/experience');
        }

        $validator = Validator::make($request->all(), [
                    'exp_name' => 'required|max:200',
                    'description' => 'required',
                    'event_id' => 'required',
                    'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/experience/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $data = $request->all();
			
			$event_id= $data['event_id'];
			// pr($data);die;
			// pr($_FILES);die;
            
            if (key_exists('image', $data)) {
                    $user_profile_image = Input::file('image');
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

                            Image::make($user_profile_image->getRealPath())->resize(110, 90, function($constraint) {
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
			//pr($data);die;
            $experienceresult = Experience::experienceupdate($data,$id);
			
            if($experienceresult){
                $msg = "Experience Updated Successfully.";
				$log = ActivityLog::createlog(Auth::Id(), "Experience", $msg, Auth::Id());
                Session::flash('success_msg', $msg);
                return redirect('/admin/event/experience/'.$event_id.'');
            }else{
                Session::flash('error_msg', 'Something went wrong!');
                 return redirect('/admin/event/experience/'.$event_id.'');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //delete experience record 
    public function destroy($id) {
    	
		        
        $experience = Experience::findOrFail($id);
        $experience->delete();
        $msg = "Experience Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Experience", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
    }


    // Multiple experience delete
    function multiple_row_delete($id) {       
        $ids = explode(",", $id);
        Experience::destroy($ids);
        $msg = "Experience Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Experience", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
    }
}
