<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Auth;
use App\StaticBlock;
use App\ActivityLog;
use Illuminate\Support\Facades\Input;
use File;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class StaticBlockController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin/StaticBlock/index', ['title_for_layout' => 'Static Block']);
    }

    /**
     * Fetch data tobe used in datatable
     */
    public function getData() {
        //return Datatables::of(StaticBlock::query())->make(true);
        DB::statement(DB::raw('set @rownum=0'));

        $data = StaticBlock::orderBy('id','desc')->get(['static_block.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin/StaticBlock/create', ['title_for_layout' => 'Add Pages']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'title' => 'required|max:255|unique:static_block',
                    'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/admin/staticblock/create')
                            ->withInput()
                            ->withErrors($validator);
        }

        $staticblock_data = $request->all();
        //create record
        $staticblock = StaticBlock::staticblockCreate($staticblock_data);

        if ($staticblock) {
            $msg = "Static Block added successfully.";
            $log = ActivityLog::createlog(Auth::Id(), "Static Block", $msg, Auth::Id());

            Session::flash('success_msg', $msg);
            return redirect('/admin/staticblock');
        } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/admin/staticblock');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $page = StaticBlock::find($id);

        if (empty($page)) {
            Session::flash('error_msg', 'Page not found.');
            return redirect('/admin/staticblock');
        }
        return view('admin/StaticBlock/show', ['title_for_layout' => 'Page View', 'page' => $page]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $page = StaticBlock::find($id);
        if (empty($page)) {
            Session::flash('error_msg', 'Page not found.');
            return redirect('/admin/staticblock');
        }

        return view('admin/StaticBlock/edit', ['title_for_layout' => 'Edit Page', 'page' => $page]);
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
                    'title' => 'required|max:255|unique:static_block,title,' . $id,
                    'description' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/staticblock/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        }

        $staticblock_data = $request->all();
        //echo "<pre>"; print_r($staticblock_data); exit;
        // update record
        $staticblock_data['update_id'] = $id;
        $staticblock = StaticBlock::staticblockUpdate($staticblock_data);

        if ($staticblock) {

            $msg = "Static Block updated successfully.";
            $log = ActivityLog::createlog(Auth::Id(), "Static Block", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/staticblock');
        } else {
            Session::flash('error_msg', 'Something went wrong!');
            return redirect('/admin/staticblock');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $block_data = StaticBlock::find($id);
        if ($block_data) {
            $staticblock = StaticBlock::where('id', $id)->delete();
            if ($staticblock) {
                echo 1;
            }
			$msg = "Static Block Deleted Successfully.";
			$log = ActivityLog::createlog(Auth::Id(), "Static Block", $msg, Auth::Id());
	        Session::flash('success_msg', $msg);
	        Session::save();
            exit;
        }
    }
	
	 // Multiple experience delete
    function multiple_row_delete($id) {       
        $ids = explode(",", $id);
        StaticBlock::destroy($ids);
        $msg = "Static Block Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Static Block", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
    }

}
