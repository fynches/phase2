<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Auth; 
use App\User; 
use App\Cms;
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

class CmsController extends Controller
{   
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin/Cms/index', ['title_for_layout' => 'List Static Content Pages']);
    }

    //get user data
    public function getData() {
        $start = $_GET['start'];  
        return Datatables::of(Cms::whereNull('deleted_at')                 
                ->select("*"))
                ->addColumn('rownum', $start + 1) 
                ->make(true); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/Cms/create', ['title_for_layout' => 'Add Static Content Pages']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $validator = Validator::make($request->all(), [
                    'title' => 'required|max:200',
                    'description' => 'required', 
                    'slug' => 'required|unique:cms,slug,NULL,id,deleted_at,NULL',
                    'status' => 'required',
                    'cms_image' => 'mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect('/admin/cms/create')
                            ->withInput()
                            ->withErrors($validator);
        } 
        $data = $request->all();

        $files = Input::file('cms_image');  
            if ($request->image_type == '0') {                    
                $files = Input::file('cms_image'); 
                if ($files && !empty($files)) {  
                    $rules = array('file' => 'mimes:jpg,jpeg,png|max:2048');
                    $validator = Validator::make(array('file' => $files), $rules);
                    if ($validator->passes()) {
                        //pr($files);exit;
                        $destinationPath = 'storage/Cms/';
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                            chmod($destinationPath, 0777);
                        }
                        $timestamp = time();
                        $filename = $timestamp . '_' . trim($files->getClientOriginalName());
                        //echo $filename; 
                        $upload_success = $files->move($destinationPath, $filename);
                        //pr($filename);exit;
                        if (file_exists($destinationPath . $filename)) {
                            chmod('storage/Cms/' . $filename, 0777);
                        }
                         
                          
                    }  
                    //echo $filename;
                    $data['image'] = $filename; 
                }
            } 
        //pr($data);exit;
        $event = Cms::cmscreate($data);

        if ($event) {
            $msg = "Static Content Pages Created Successfully.";
            $log = ActivityLog::createlog(Auth::Id(), "Static Content Pages", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/cms');
        } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/admin/cms');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cms = Cms::find($id)->toArray();
        //pr($event);exit;
        if (empty($cms)) {
            Session::flash('error_msg', 'Static Content Pages not found.');
            return redirect('/admin/cms');
        }
        return view('admin/Cms/show', ['title_for_layout' => 'Static Content Pages Details',
            'cms' => $cms]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cms = Cms::find($id);
        $title_for_layout = 'Edit Static Content Pages';
        return view('admin/Cms/edit', ['title_for_layout' => $title_for_layout,
            'cms' => $cms]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cms = Cms::find($id);

        if (empty($cms)) {
            Session::flash('error_msg', 'Static Content Pages not found.');
            return redirect('/admin/cms');
        }

        $validator = Validator::make($request->all(), [
                    'title' => 'required|max:200',
                    'description' => 'required', 
                    'slug' => 'required',
                    'status' => 'required',
                    'cms_image' => 'mimes:jpg,jpeg,png|max:2048'
        ]); 
       

        if ($validator->fails()) {
            return redirect('/admin/cms/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $data = $request->all();
            
            $files = Input::file('cms_image'); 
            if ($request->image_type == '0') {
                $oldPicture = ""; 
                if($cms['featured_image']!=''){
                    $oldPicture = $cms['featured_image'];
                    //$data['featured_image'] =  $oldPicture;
                }

                $files = Input::file('cms_image');
                if ($files && !empty($files)) { 

                    $rules = array('cms_image' => 'mimes:jpg,jpeg,png');
                    $validator = Validator::make(array('cms_image' => $files), $rules);
                    if ($validator->fails()) {
                        return redirect('/admin/cms/' . $id . '/edit')
                                        ->withInput()
                                        ->withErrors($validator);
                    }

                    if ($validator->passes()) { 
                        $destinationPath = 'storage/Cms/';  
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                            chmod($destinationPath, 0777);
                        }
                        $timestamp = time();
                        $filename = $timestamp . '_' . trim($files->getClientOriginalName());
                        //echo $filename;
                          
                        $upload_success = $files->move($destinationPath, $filename);
                        //pr($filename);exit;
                        if (file_exists('storage/Cms/' . $filename)) {
                            chmod('storage/Cms/' . $filename, 0777);
                        }
                        
                        if ($oldPicture!="") {
                            if (file_exists($destinationPath. $oldPicture)) {
                                $unlink_success = File::delete($destinationPath . $oldPicture);
                            }                         
                        } 
                    } else {                        
                        $filename = ""; 
                        return Redirect::to('/admin/cms/' . $id . '/edit')->withInput()->withErrors($validator);
                    } 
                    $data['image'] = $filename;
                }

            } 

          
            $cmsresult = Cms::cmsupdate($data,$id);
            if($cmsresult){
                $msg = "Static Content Pages Updated Successfully.";
				$log = ActivityLog::createlog(Auth::Id(), "Static Content Pages", $msg, Auth::Id());
                Session::flash('success_msg', $msg);
                return redirect('/admin/cms');
            }else{
                Session::flash('error_msg', 'Something went wrong!');
                return redirect('/admin/cms/' . $id . '/edit');
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
        $cms = Cms::findOrFail($id);
        
        if (count($cms)>0){ 
            $oldPicture = $cms['featured_image'];
            $destinationPath = 'storage/Cms/'; 
            if (file_exists($destinationPath . $oldPicture)) {
                $unlink_success = File::delete($destinationPath . $oldPicture);
            } 
        }        
        $cms->delete();
        $msg = "Static Content Pages Deleted Successfully.";
        $log = ActivityLog::createlog(Auth::Id(), "Static Content Pages", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
    }


    // Multiple event delete
    public function multiple_row_delete($id) {       
        $ids = explode(",", $id);
        foreach ($ids as $value) {  
            $cms = Cms::findOrFail($value);
            if (count($cms)>0) { 
                    $oldPicture = $cms['featured_image'];
                    $destinationPath = 'storage/Cms/'; 
                    if (file_exists($destinationPath . $oldPicture)) {
                        $unlink_success = File::delete($destinationPath . $oldPicture);
                    } 
            }
        }
        Cms::destroy($ids);
        $msg = "Static Content Pages Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Static Content Pages", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
    }
}
