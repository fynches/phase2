<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\EmailTemplates;
use App\ActivityLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Yajra\Datatables\Datatables;

class EmailTemplatesController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin/EmailTemplates/index', ['title_for_layout' => 'Email Templates']);
    }

    /**
     * Fetch data tobe used in datatable
     */
    public function getData() {
        DB::statement(DB::raw('set @rownum=0'));
        $data = EmailTemplates::orderBy('id','desc')->get(['email_templates.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($data)->make(true);
        //return Datatables::of(EmailTemplates::query())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin/EmailTemplates/create', ['title_for_layout' => 'Add Email Template']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'subject' => 'required|max:255|unique:email_templates',
                    'content' => 'required',
                    'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/emailtemplates/create')
                            ->withInput()
                            ->withErrors($validator);
        }

        $email_template_data = $request->all();

        // Create emailtemplate
        $emailtemplate = EmailTemplates::emailTemplate_insert($email_template_data);
        
        if ($emailtemplate) {
            
            $msg = 'EmailTemplate added successfully.';
            $log = ActivityLog::createlog(Auth::Id(), "Email Template", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/emailtemplates');
        } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/admin/emailtemplates');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $EmailTemplates = EmailTemplates::find($id);
        if (empty($EmailTemplates)) {
            Session::flash('error_msg', 'EmailTemplates not found.');
            return redirect('/admin/emailtemplates');
        }
        return view('admin/EmailTemplates/show', ['title_for_layout' => 'EmailTemplate',
            'EmailTemplates' => $EmailTemplates]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $EmailTemplates = EmailTemplates::find($id);
        // pr($EmailTemplates);exit;
        if (empty($EmailTemplates)) {
            Session::flash('error_msg', 'EmailTemplate not found.');
            return redirect('/admin/emailtemplates');
        }
        return view('admin/EmailTemplates/edit', ['title_for_layout' => 'Edit Email Template', 'EmailTemplates' => $EmailTemplates]);
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
                    'subject' => 'required|max:255',
                    'content' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/emailtemplates/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        }

        $email_template_data = $request->all();
        //update record
        $email_template_data['update_id'] = $id;
        $emailtemplate = EmailTemplates::emailTemplate_update($email_template_data);
        if ($emailtemplate) {

            $msg = 'EmailTemplate updated successfully.';
            $log = ActivityLog::createlog(Auth::Id(), "Email Template", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/emailtemplates');
        } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/admin/emailtemplates');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
        $email_template = EmailTemplates::find($id);
        if ($email_template) {
            $emailtemplate = EmailTemplates::where('id', $id)->delete();
            
            $msg = "EmailTemplates Deleted Successfully.";
			$log = ActivityLog::createlog(Auth::Id(), "Email Template", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            Session::save();
            echo 1;
            exit;
            
        }
    }

    // Beta signup Multiple user delete
    function multiple_emailTemplate_row_delete($id) {
       
        $ids = explode(",", $id);
        EmailTemplates::destroy($ids);
        $msg = "EmailTemplates Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Email Template", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
     }

}
