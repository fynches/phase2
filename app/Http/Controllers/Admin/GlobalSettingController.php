<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\GlobalSetting;
use App\ActivityLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Yajra\Datatables\Datatables;

class GlobalSettingController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    // added by devang mavani for display global setting 
    public function index() {
    	
		$global_setting = GlobalSetting::first();
		return view('admin/GlobalSetting/index', ['title_for_layout' => 'Global Setting','global_setting' =>$global_setting]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
     //added by devang mavani for update global setting
    public function update(Request $request) {
    	
        $validator = Validator::make($request->all(), [
                    'secret_key' => 'required',
                    'publish_key' => 'required',
                    'commission' => 'required',
                    'fb_client_id' => 'required',
                    'fb_client_secret' => 'required',
                    'fb_redirect' => 'required',
                    'google_plus_client_id' => 'required',
                    'google_plus_secret' => 'required',
                    'google_plus_redirect' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/admin/globalsetting')
                            ->withInput()
                            ->withErrors($validator);
        }

        $global_setting_data = $request->all();
		
		//pr($global_setting_data);die;

        // Update global setting
        $global_setting = GlobalSetting::globalsetting_update($global_setting_data);
        
        if ($global_setting) {
            
            $msg = 'Global Setting updated successfully.';
            $log = ActivityLog::createlog(Auth::Id(), "Email Template", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/globalsetting');
        } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/admin/globalsetting');
        }
    }
}
