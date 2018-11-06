<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\Testimonial;
use App\ActivityLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Yajra\Datatables\Datatables;

class TestimonialController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin/Testimonial/index', ['title_for_layout' => 'Testimonial']);
    }

    /**
     * Fetch data tobe used in datatable
     */
    public function getData() {
        DB::statement(DB::raw('set @rownum=0'));
        $data = Testimonial::orderBy('id','desc')->get(['testimonial.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($data)->make(true);
        //return Datatables::of(EmailTemplates::query())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin/Testimonial/create', ['title_for_layout' => 'Add Testimonial']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
    	
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:191',
                    'description' => 'required',
                    'author_name' => 'required|max:191',
                    'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/testimonial/create')
                            ->withInput()
                            ->withErrors($validator);
        }

        $testimonial_data = $request->all();
		
		$files = Input::file('image');
	    if ($files && !empty($files)) {
	        
	        $rules = array('file' => 'mimes:jpg,jpeg,png,gif');
	        $validator = Validator::make(array('file' => $files), $rules);
	        if ($validator->passes()) {
	            $destinationPath = 'storage/testimonialImages/';
	            $timestamp = time();
	            $filename = $timestamp . '_' . trim($files->getClientOriginalName());
	            //echo $filename;
	            $path_thumb =  'storage/testimonialImages/thumb/';
	            if (!file_exists($path_thumb)) {
	                mkdir($path_thumb, 0777, true);
	                chmod($path_thumb, 0777);
	            }
	            
	            Image::make($files->getRealPath())->resize(300, 300)->save('storage/testimonialImages/thumb/' . $filename);
	            $upload_success = $files->move($destinationPath, $filename);
	
	            if (file_exists('storage/testimonialImages/' . $filename)) {
	                chmod('storage/testimonialImages/' . $filename, 0777);
	            }
	            if (file_exists('storage/testimonialImages/thumb/' . $filename)) {
	                chmod('storage/testimonialImages/thumb/' . $filename, 0777);
	            }
	            
	            
	        } else {
	            return Redirect::to('/testimonial/create')->withInput()->withErrors($validator);
	        }
	        $image_name = $filename;
			
			$testimonial_data['image'] = $image_name;
	    }
        
		

        // Create testimonial
        $testimonial = Testimonial::testimonial_insert($testimonial_data);
        
        if ($testimonial) {
            
            $msg = 'Testimonial added successfully.';
            $log = ActivityLog::createlog(Auth::Id(), "Testimonial", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/testimonial');
        } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/admin/testimonial');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $testimonial = Testimonial::find($id);
		
        if (empty($testimonial)) {
            Session::flash('error_msg', 'Testimonial not found.');
            return redirect('/admin/testimonial');
        }
        return view('admin/Testimonial/show', ['title_for_layout' => 'Testimonial',
            'testimonial' => $testimonial]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
    	
        $testimonial = Testimonial::find($id);
        
        if (empty($testimonial)) {
            Session::flash('error_msg', 'Testimonial not found.');
            return redirect('/admin/testimonial');
        }
        return view('admin/Testimonial/edit', ['title_for_layout' => 'Edit Testimonial', 'testimonial' => $testimonial]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
    	
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:191',
                    'description' => 'required',
                    'author_name' => 'required|max:191',
                    'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/testimonial/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        }else{
        	$testimonial_data = $request->all();
			
			if (key_exists('image', $testimonial_data)) {
				
                $testimonial_profile_image = Input::file('image');

                if ($testimonial_profile_image) {

                    $rules = array('file' => 'mimes:jpg,jpeg,png,bmp,gif');
                    $validator = Validator::make(array('file' => $testimonial_profile_image), $rules);


                    $testimonial_image = '';
                    if ($validator->passes()) {
                        $destinationPath = 'storage/testimonialImages/';
                        $timestamp = time() . uniqid();
                        $filename = $timestamp . '_' . trim($testimonial_profile_image->getClientOriginalName());
                        $path_thumb =  'storage/testimonialImages/thumb/';
					
                        if (!File::exists($path_thumb)) {
                        	mkdir($path_thumb, 0777, true);
                            chmod($path_thumb, 0777);
                        }

                        Image::make($testimonial_profile_image->getRealPath())->resize(110, 90, function($constraint) {
                            $constraint->aspectRatio();
                        })->save('storage/testimonialImages/thumb/' . $filename);
                        $upload_success = $testimonial_profile_image->move($destinationPath, $filename);

                        $testimonial_image = $filename;
                    }
                }
            } else {
                if (isset($testimonial_data['old_images'])) {
                    $testimonial_image = $testimonial_data['old_images'];
                } else {
                    $testimonial_image = '';
                }
            }
            
			$testimonial_data['image'] = $testimonial_image;
	        //update record
	        $testimonial_data['update_id'] = $id;
			
	        $testimonial = Testimonial::testimonial_update($testimonial_data);
			
	        if ($testimonial) {
	
	            $msg = 'Testimonial updated successfully.';
	            $log = ActivityLog::createlog(Auth::Id(), "Testimonial", $msg, Auth::Id());
	            Session::flash('success_msg', $msg);
	            return redirect('/admin/testimonial');
	        } else {
	            Session::flash('error_msg', 'Something went wrong!');
	            return redirect('/admin/testimonial');
	        }
        }
 }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
        $testimonial = Testimonial::find($id);
		
        if ($testimonial) {
            $testimonial = Testimonial::where('id', $id)->delete();
            
            $msg = "Testimonial Deleted Successfully.";
			$log = ActivityLog::createlog(Auth::Id(), "Testimonial", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            Session::save();
            echo 1;
            exit;
            
        }
    }

    // Beta signup Multiple user delete
    function multiple_row_delete($id) {
       
        $ids = explode(",", $id);
        Testimonial::destroy($ids);
        $msg = "Testimonial Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Testimonial", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
     }

}
