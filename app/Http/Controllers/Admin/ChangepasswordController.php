<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Hash;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;
use Session;
use App\ActivityLog;


class ChangepasswordController extends Controller
{
    
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(){
        return view('admin/User/changepassword', ['title_for_layout' => 'Change Password']);
    }
    
    public function update(Request $request){
            $validator = Validator::make($request->all(), [
              'password' => 'required|min:6',
              'conform_password' => 'required|min:6|same:password' 
            ]);
            
            if ($validator->fails()) {
                return redirect('/admin/changepassword')
                        ->withErrors($validator)
                        ->withInput();
            }
            
            if (!Auth::validate(array('email' => Auth::user()->email, 'password' => $request->old_password ))) {
                $validator->getMessageBag()->add('password', 'Old password is incorrect. Please try again!');
                return redirect("/admin/changepassword")->withErrors($validator);
            }
            
            $user = \Auth::user();
            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            Session::flash('success_msg', 'Password updated successfully.!');
			ActivityLog::createlog(Auth::Id(), "Password Update", $msg, Auth::Id());
            return redirect('/admin/betaSignup');
    }

    public function password($id,$user_type) {
    	return view('admin/User/password', ['title_for_layout' => 'Change Password', 'id' => $id,'type' =>$user_type]);
    }

    public function updatePassword(Request $request) {

        $validator = Validator::make($request->all(), [
                    'password' => 'required|min:6',
                    'confirmpassword' => 'required|min:6|same:password'
        ]);

        if ($validator->fails()) {
            return redirect('/admin/user')
                            ->withErrors($validator)
                            ->withInput();
        }

        $user = Auth::user();
        $post_data = $request->all();
		$user_type= $request['user_type'];
		
		$update_password = user::updatePassword($post_data);

        if ($update_password) {
            $msg = "Password updated successfully.";
            ActivityLog::createlog(Auth::Id(), "Password Update", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
			if($user_type=="1")
			{
				return redirect('/admin/user');	
			}else{
				return redirect('/admin/user/admin_index');
			}
            
        } else {
            Session::flash('error_msg', $msg);
            return redirect('/admin/user/admin_index');
        }
    }
    
    
}
