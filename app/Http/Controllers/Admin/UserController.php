<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use App\User;
use App\Countries;
use App\States;
use App\Cities;
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
use App\Event;
use App\Experience;
use App\Events\UserCreated;
use App\Beta_Signup;
use Mail; 

class UserController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //display user listing
    public function index() {
        return view('admin/User/index', ['title_for_layout' => 'List Users']);
    }

    //display Admin User listing
    public function admin_index() {
        return view('admin/Admin/index', ['title_for_layout' => 'List Admin Users']);
    }

    //create admin form listing
    public function admin_create() {
        return view('admin/Admin/create', ['title_for_layout' => 'Add Admin']);
    }

    //Get Admin Users
    public function list_admins() {
        
        DB::statement(DB::raw('set @rownum=0'));
        $users = User::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'first_name',
            'last_name',
            'email',
            'user_status',
            'created_at',
            'updated_at'])->where('user_type','2');
        $datatables = Datatables::of($users);

        return $datatables->make(true);
    }

    public function create_admin(Request $request) {
        

        $validator = Validator::make($request->all(), [
                    'firstname' => 'required|max:200',
                    'lastname' => 'required|max:200',
                    'email' => 'required|max:200|unique:users,email,NULL,id,deleted_at,NULL',
                    'password' => 'required|min:6',
                    'user_status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/user/admin_create')
                            ->withInput()
                            ->withErrors($validator);
        }

        $data = $request->all();
        $data['rand_code'] = str_random(18);
        $user = User::admincreate($data); 
        if ($user) {
            $msg = "Admin Registered Successfully.";
            $log = ActivityLog::createlog(Auth::Id(), "Admin User", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/user/admin_index');
        } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/admin/user/admin_index');
        }
    }

    //get user data
    public function getData() {
    	
    	DB::statement(DB::raw('set @rownum=0'));

        $data = User::where('user_type','3')->orderBy('id','desc')->get(['users.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($data)->make(true);
    }

    //display beta user listing
    public function getbetaSignupData(){
		return view('admin/BetaSignup/index', ['title_for_layout' => 'List Beta Signup Users']);
	}

    //get beta user records
	public function getBetaData() {
        DB::statement(DB::raw('set @rownum=0'));

        $data = Beta_Signup::orderBy('id','desc')->get(['beta_signup.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($data)
            ->addColumn('created_date', function($data){
                return $created_date= date("d-m-Y", strtotime($data->created_at));
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //create user form listing
    public function create() {
        $usertype = [
            '2' => 'Admin',
            '3' => 'User'
        ];
        return view('admin/User/create', ['title_for_layout' => 'Add User', 'usertype' => $usertype]);
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
                'firstname' => 'required|max:200',
                'lastname' => 'required|max:200',
                'email' => 'required|max:200|unique:users,email,NULL,id,deleted_at,NULL',
                'password' => 'required|min:6',
                'user_status' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('/admin/user/create')
                    ->withInput()
                    ->withErrors($validator);
            }

            $data = $request->all();
            $data['rand_code'] = str_random(18);
            $user = User::usercreate($data);
            if ($user) {
                $msg = "User Registered Successfully.";
                $log = ActivityLog::createlog(Auth::Id(), "Admin User", $msg, Auth::Id());
                Session::flash('success_msg', $msg);
                return redirect('/admin/user');
            } else {
                Session::flash('error_msg', 'Something went wrong!');
                return redirect('/admin/user');
            }
        } catch (Exception $ex) {
            return $ex;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //display single user record
    public function show($id) {

        $user = User::find($id);
        if (empty($user)) {
            Session::flash('error_msg', 'User not found.');
            return redirect('/admin/user');
        }
        return view('admin/User/show', ['title_for_layout' => 'User Details',
            'user' => $user]);
    }

    //display single user record
    public function show_admin_info($id) {

        $user = User::find($id);

        if (empty($user)) {
            Session::flash('error_msg', 'User not found.');
            return redirect('/admin/user/admin_index');
        }
        return view('admin/Admin/show', ['title_for_layout' => 'Admin Details',
            'user' => $user]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //edit user record or edit user profile
    public function edit($id) {

        $user = User::find($id);
       
        $isAdmin = $ischngeprofile = false;
        $title_for_layout = 'Edit Details';

        if (empty($user)) {
            Session::flash('error_msg', 'User not found.');
            return redirect('/admin/user');
        }
        $userType = $user->user_type;
        if ($userType == "1") {
            $isAdmin = true;
        }
        // Checking that auth user change his profile
        $authId = Auth::Id();
        $userId = $user->id;
        if ($authId == $userId) {
            $ischngeprofile = 1; //check admin
            $title_for_layout = 'Edit Profile';
        }else{
            $ischngeprofile = 2; //check other user
        }

        $profileImage = $user->profile_image;

        $usertype = [
            '2' => 'Admin',
            '3' => 'User'
        ];
        
        $usertype = [
            'Admin' => 'Admin',
            'User' => 'User'
        ];
        
        return view('admin/User/edit', ['title_for_layout' => $title_for_layout,
            'user' => $user, 'ischngeprofile' => $ischngeprofile,
            'isAdmin' => $isAdmin, 'usertype'=>$usertype]
        );
    }

    public function edit_admin($id) {

        $user = User::find($id);
       
        $isAdmin = $ischngeprofile = false;
        $title_for_layout = 'Edit Details';

        if (empty($user)) {
            Session::flash('error_msg', 'User not found.');
            return redirect('/admin/user/edit_admin/'.$id);
        }
        $userType = $user->user_type;
        if ($userType == "1") {
            $isAdmin = true;
        }
        // Checking that auth user change his profile
        $authId = Auth::Id();
        $userId = $user->id;
        if ($authId == $userId) {
            $ischngeprofile = 1; //check admin
            $title_for_layout = 'Edit Profile';
        }else{
            $ischngeprofile = 2; //check other user
        }

        $profileImage = $user->profile_image;

        $usertype = [
            '2' => 'Admin',
            '3' => 'User'
        ];
        
        
        return view('admin/Admin/edit', ['title_for_layout' => $title_for_layout,
            'user' => $user, 'ischngeprofile' => $ischngeprofile,
            'isAdmin' => $isAdmin,'usertype' =>$usertype]
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
        $user = User::find($id);
        if (empty($user)) {
            Session::flash('error_msg', 'User not found.');
            return redirect('/admin/user');
        }

        $validator = Validator::make($request->all(), [
                    'firstname' => 'required|max:200',
                    'lastname' => 'required|max:200',
                    'email' => 'required|max:200|unique:users,email,' . $id . ',id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/user/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $data = $request->all();
           
            if ($request->ischngeprofile == '1' || $request->ischngeprofile == '2') {
                
                $files = Input::file('profile_image');
                if ($files && !empty($files)) {
                    $oldPicture = $user->profile_image;
                    $rules = array('file' => 'mimes:jpg,jpeg,png,gif');
                    $validator = Validator::make(array('file' => $files), $rules);
                    if ($validator->passes()) {
                        $destinationPath = 'storage/adminProfileImages/';
                        $timestamp = time();
                        $filename = $timestamp . '_' . trim($files->getClientOriginalName());
                        //echo $filename;
                        $path_thumb =  'storage/adminProfileImages/thumb/';
                        if (!file_exists($path_thumb)) {
                            mkdir($path_thumb, 0777, true);
                            chmod($path_thumb, 0777);
                        }
                        
                        Image::make($files->getRealPath())->resize(300, 300)->save('storage/adminProfileImages/thumb/' . $filename);
                        $upload_success = $files->move($destinationPath, $filename);

                        if (file_exists('storage/adminProfileImages/' . $filename)) {
                            chmod('storage/adminProfileImages/' . $filename, 0777);
                        }
                        if (file_exists('storage/adminProfileImages/thumb/' . $filename)) {
                            chmod('storage/adminProfileImages/thumb/' . $filename, 0777);
                        }
                        if ($upload_success) {
                            $unlink_success = File::delete($destinationPath . $oldPicture);
                        }
                        
                    } else {
                        $filename = $oldPicture;
                        return Redirect::to('/user/' . $id . '/edit')->withInput()->withErrors($validator);
                    }
                    $user->profile_image = $filename;
                }
            }
           // echo  $user->profile_image;die;
            $data['profile_image'] = $user->profile_image;
            $data['user_id'] = $id;

            $userresult = User::userupdate($data);
            if($userresult){
                if ($request->ischngeprofile == '1') {
                    $msg = "Profile Updated Successfully.";
					$log = ActivityLog::createlog(Auth::Id(), "User", $msg, Auth::Id());
                    Session::flash('success_msg', $msg);
                    return redirect('/admin/user/' . $id . '/edit');
                } else {
                    $msg = "User Updated Successfully.";
					$log = ActivityLog::createlog(Auth::Id(), "User", $msg, Auth::Id());
                    Session::flash('success_msg', $msg);
                    return redirect('/admin/user');
                }
            }else{
                Session::flash('error_msg', 'Something went wrong!');
                return redirect('/admin/user/' . $id . '/edit');
            }
        }
    }

    public function update_admin(Request $request){
		try{
		$id= $request->user_id;
        
        $user = User::find($id);
        if (empty($user)) {
            Session::flash('error_msg', 'User not found.');
            return redirect('/admin/user/edit_admin/' . $id . '/edit');
        }

        $validator = Validator::make($request->all(), [
                    'firstname' => 'required|max:200',
                    'lastname' => 'required|max:200',
                    'email' => 'required|max:200|unique:users,email,' . $id . ',id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/user/edit_admin/' . $id )
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $data = $request->all();
           
            if ($request->ischngeprofile == '1' || $request->ischngeprofile == '2') {
                
                $files = Input::file('profile_image');
                if ($files && !empty($files)) {
                    $oldPicture = $user->profile_image;
                    $rules = array('file' => 'mimes:jpg,jpeg,png,gif');
                    $validator = Validator::make(array('file' => $files), $rules);
                    if ($validator->passes()) {
                        $destinationPath = 'storage/adminProfileImages/';
                        $timestamp = time();
                        $filename = $timestamp . '_' . trim($files->getClientOriginalName());
                        //echo $filename;
                        $path_thumb =  'storage/adminProfileImages/thumb/';
                        if (!file_exists($path_thumb)) {
                            mkdir($path_thumb, 0777, true);
                            chmod($path_thumb, 0777);
                        }
                        
                        Image::make($files->getRealPath())->resize(300, 300)->save('storage/adminProfileImages/thumb/' . $filename);
                        $upload_success = $files->move($destinationPath, $filename);

                        if (file_exists('storage/adminProfileImages/' . $filename)) {
                            chmod('storage/adminProfileImages/' . $filename, 0777);
                        }
                        if (file_exists('storage/adminProfileImages/thumb/' . $filename)) {
                            chmod('storage/adminProfileImages/thumb/' . $filename, 0777);
                        }
                        if ($upload_success) {
                            $unlink_success = File::delete($destinationPath . $oldPicture);
                        }
                        
                    } else {
                        
                        $filename = $oldPicture;
                        return Redirect::to('/user/' . $id . '/edit')->withInput()->withErrors($validator);
                    }
                    $user->profile_image = $filename;
                }
            }
           // echo  $user->profile_image;die;
            $data['profile_image'] = $user->profile_image;
            $data['user_id'] = $id;
            $userresult = User::adminupdate($data);
            if($userresult){
                if ($request->ischngeprofile == '1') {
                    $msg = "Profile Updated Successfully.";
					$log = ActivityLog::createlog(Auth::Id(), "Admin User", $msg, Auth::Id());
                    Session::flash('success_msg', $msg);
                    return redirect('/admin/user/edit_admin/' . $id . '/edit');
                } else {
                    $msg = "Admin Updated Successfully.";
					$log = ActivityLog::createlog(Auth::Id(), "Admin User", $msg, Auth::Id());
                    Session::flash('success_msg', $msg);
                    return redirect('/admin/user/admin_index');
                }
            }else{
                Session::flash('error_msg', 'Something went wrong!');
                return redirect('/admin/user/edit_admin/' . $id . '/edit');
            }
        }
		} catch (\Exception $ex) {
		    dd($ex);
            return $ex->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //delete user record 
    public function destroy($id) {
        
		$user = User::findOrFail($id);
        
		/*sent email to user*/
		$user_name= $user->first_name.' '.$user->last_name;
		$search = array("[USERNAME]");
        $replace = array($user_name);
        
		
        $emailParams = array(
            'subject' => 'Fynches Delete Account',
            'from' => config('constant.fromEmail'),
            'to' =>$user['email'],
            'email'=>$user['email'],
            'template'=>'deleted-account',
            'search' => $search,
            'replace' => $replace
        );
		
		
		 //$event = DB::table('event')->where(['user_id' => $id])->whereNull('deleted_at')->get();
		
		 $users = User::with(['getFundingReport' => function ($q) {
                    $q->select('event_id', 'experience_id','user_id','id','donated_amount');
                }])->select('id','first_name','last_name','email')
                ->find($id)->toArray();
		
		 
		 if($users['get_funding_report']!= "")
		 {
		 	$msg="This User cannot be deleted.";
	        Session::flash('error_msg', $msg);
	        Session::save(); 
			echo 1;
        	exit;	
		 }
		else{
			
			 $event = Event::where('user_id', $id)->get();
			
			 if(count($event) > 0)
			 {
			 	foreach($event as $key=>$val)
				{
					$event_id = $val->id;
					
					$experience = Experience::where('event_id', $event_id)->get();
					
					if(count($experience) > 0)
					{
						foreach($experience as $key2=>$val2)
						{
							$experience_id= $val2->id;
							$delete_experience = Experience::find( $experience_id );
							$delete_experience ->delete();
						}
					}
					$delete_event = Event::find( $event_id );
					$delete_event ->delete();
				}
			 }
			
			 
			 $result = User::SendEmail($emailParams); 
			 $user->delete();
			 
			 $msg = "User Deleted Successfully.";
			 $log = ActivityLog::createlog(Auth::Id(), "Admin User", $msg, Auth::Id());
	         Session::flash('success_msg', $msg);
	         Session::save();
	         echo 1;
	         exit;
		 }
	}
    
    //delete single record for beta signup user
    public function delete_betaUser($id) {
        
        $user = Beta_Signup::findOrFail($id);
        $user->delete();
        $msg = "Beta User Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Beta User", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
    }

    // Beta signup Multiple user delete
    function multiple_row_delete($id) {
       
        $ids = explode(",", $id);
        Beta_Signup::destroy($ids);
        $msg = "Beta User Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Beta User", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
    }

     // Users Multiple user delete
    function multiple_user_row_delete($id) {
       
        $ids = explode(",", $id);
		
		/*sent email to user*/
		
		if(!empty($ids))
		{
			foreach($ids as $id)
			{
				$user = User::findOrFail($id);
				$user_name= $user->first_name.' '.$user->last_name;
				$search = array("[USERNAME]");
		        $replace = array($user_name);
		        
				
		        $emailParams = array(
		            'subject' => 'Fynches Delete Account',
		            'from' => config('constant.fromEmail'),
		            'to' =>$user['email'],
		            'email'=>$user['email'],
		            'template'=>'deleted-account',
		            'search' => $search,
		            'replace' => $replace
		        );
		        
				$users = User::with(['getFundingReport' => function ($q) use($id) {
                    $q->select('event_id', 'experience_id','user_id','id','donated_amount');
					$q->where('user_id',$id);
                }])->select('id','first_name','last_name','email')
                ->find($id)->toArray();
				
				
				if($users['get_funding_report']=="")
				{
					$event = Event::where('user_id', $id)->get();
					
					if(count($event) > 0)
					 {
					 	foreach($event as $key=>$val)
						{
							$event_id = $val->id;
							
							$experience = Experience::where('event_id', $event_id)->get();
							
							if(count($experience) > 0)
							{
								foreach($experience as $key2=>$val2)
								{
									$experience_id= $val2->id;
									$delete_experience = Experience::find( $experience_id );
									$delete_experience ->delete();
								}
							}
							$delete_event = Event::find( $event_id );
							$delete_event ->delete();
						}
					 }  
					  
					 $result = User::SendEmail($emailParams); 
					
					 User::destroy($id);
			         $msg = "User Deleted Successfully.";
					 $log = ActivityLog::createlog(Auth::Id(), "Admin User", $msg, Auth::Id());
			         Session::flash('success_msg', $msg);
			         Session::save();
				}
			}
		}
		
		 echo 1;
		 exit;
       
     }

    //export csv of beta signup user 
    public function export()
    {
        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=beta_users.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        $list = Beta_Signup::orderBy('id', 'DESC')->select('id as Sr No','first_name as FirstName','email as Email','created_at as Created Date')->get()->toArray();
		
		if(count($list) > 0)
		{
			# add headers for each column in the CSV download
	        array_unshift($list, array_keys($list[0]));
	
	        $callback = function() use ($list) 
	        {
	            $i=0;
	            $FH = fopen('php://output', 'w');
	            
	            foreach ($list as $row) { 
	                if($i!=0){
	                    $row['Sr No']= $i;
	                }
	                fputcsv($FH, $row);
	                $i++;
	            }
	            fclose($FH);
	        };
	        
	       return Response::stream($callback, 200, $headers); 
		}else{
			return redirect('/admin/betaSignup');
		}
        
    }
}
