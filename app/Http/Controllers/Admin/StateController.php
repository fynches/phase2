<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
use App\State;
use App\Country;
use App\ActivityLog;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class StateController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('admin/State/index', ['title_for_layout' => 'State']);
    }

    public function getData() {
         return Datatables::of(State::with('country')
                 ->select("*"))
                 ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    	$country = DB::table('country')->where(['status' => 'Active'])->pluck('name', 'id')->toArray();
    	return view('admin/State/create', ['title_for_layout' => 'Add State','country' =>$country]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
		
		//pr($request->all());die;
        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255|unique:state',
                    'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/admin/State/create')
                            ->withInput()
                            ->withErrors($validator);
        }
        $state = new State;
        $state->name = $request->name;
		$state->country_id = $request->country;
        $state->status = $request->status;
        $state->save();

        $msg = 'State has been added successfully.';
        $log = ActivityLog::createlog(Auth::Id(), "State", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        return redirect('/admin/state');
    }
	
	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
    	
         $state = State::with(['country' => function($query) use($id)
		 {
		    $query->select('id', 'name');
		
		 }])->where('id',$id)->get(); 
		
		//pr($state);die;
        if (empty($state)) {
            Session::flash('error_msg', 'State not found.');
            return redirect('/admin/state');
        }
        return view('admin/State/show', ['title_for_layout' => 'State',
            'state' => $state]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //$state = State::find($id);
        
        $state = State::with(['country' => function($query) use($id)
		 {
		    $query->select('id', 'name');
		
		 }])->where('id',$id)->get(); 
        
		$country = DB::table('country')->where(['status' => 'Active'])->pluck('name', 'id')->toArray();
		
		//pr($state);die;
        if (empty($state)) {
            Session::flash('error_msg', 'State not found.');
            return redirect('/admin/state');
        }
        return view('admin/State/edit', ['title_for_layout' => 'Edit State', 'state' => $state,'country' =>$country]);
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
                    'name' => 'required|max:255|unique:state,id,'.$id,
                    'status' => 'required',
        ]);
        if ($validator->fails()) {

            return redirect('admin/state/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        } else {

            $state = State::find($id);
            $state->name = $request->name;
			$state->country_id = $request->country;
            $state->status = $request->status;
            $state->save();
            
            $msg = 'State has been updated successfully.';
            $log = ActivityLog::createlog(Auth::Id(), "State", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/state');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //echo $id;die;
        $state = State::findOrFail($id);
        $state->delete();
        $msg = 'State has been deleted successfully.';
        Session::flash('success_msg', $msg);
        echo 1;
        //exit;
    }

}
