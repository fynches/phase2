<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use App\Country;
use Illuminate\Support\Facades\DB;
use App\ActivityLog;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class CountryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('admin/Country/index', ['title_for_layout' => 'Country']);
    }

    public function getData() {
        //return Datatables::of(Country::query())->make(true);
         DB::statement(DB::raw('set @rownum=0'));

        $data = Country::orderBy('id','desc')->get(['country.*', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($data)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin/Country/create', ['title_for_layout' => 'Add Country']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
                    'name' => 'required|max:255|unique:country',
                    'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('/admin/country/create')
                            ->withInput()
                            ->withErrors($validator);
        }
        $country = new Country;
        $country->name = $request->name;
        $country->status = $request->status;
        $country->save();

        $msg = 'Country has been added successfully.';
        $log = ActivityLog::createlog(Auth::Id(), "Country", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        return redirect('/admin/country');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $country = Country::find($id);
		
        if (empty($country)) {
            Session::flash('error_msg', 'Country not found.');
            return redirect('/admin/country');
        }
        return view('admin/Country/show', ['title_for_layout' => 'Country',
            'country' => $country]);
    }

    public function edit($id) {
        $country = Country::find($id);
        if (empty($country)) {
            Session::flash('error_msg', 'Country not found.');
            return redirect('/admin/country');
        }
        return view('admin/Country/edit', ['title_for_layout' => 'Edit Country', 'country' => $country]);
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
                    'name' => 'required|max:255|unique:country,id,'.$id,
                    'status' => 'required',
        ]);
        if ($validator->fails()) {

            return redirect('admin/country/' . $id . '/edit')
                            ->withInput()
                            ->withErrors($validator);
        } else {

            $country = Country::find($id);
            $country->name = $request->name;
            $country->status = $request->status;
            $country->save();
            
            $msg = 'Country has been updated successfully.';
            $log = ActivityLog::createlog(Auth::Id(), "Country", $msg, Auth::Id());
            Session::flash('success_msg', $msg);
            return redirect('/admin/country');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
    	
        
        $country = Country::findOrFail($id);
        $country->delete();
        $msg = 'Country has been deleted successfully.';
        Session::flash('success_msg', $msg);
        echo 1;
        //exit;
    }
	
	// Multiple country delete
    function multiple_row_delete($id) {
       
        $ids = explode(",", $id);
        Country::destroy($ids);
        $msg = "Country Deleted Successfully.";
		$log = ActivityLog::createlog(Auth::Id(), "Country", $msg, Auth::Id());
        Session::flash('success_msg', $msg);
        Session::save();
        echo 1;
        exit;
     }

}
