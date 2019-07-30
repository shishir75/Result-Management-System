<?php

namespace App\Http\Controllers\Dept_Office;

use App\Models\Dept;
use App\Models\Session;
use App\Models\Teacher;
use App\Models\Year;
use App\Models\YearHead;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class YearHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dept_name = Auth::user()->name;
        $dept = Dept::select('id', 'name')->where('name', $dept_name)->first();
        $heads = YearHead::with('session', 'year', 'teacher')->where('dept_id', $dept->id)->latest()->get();
        return view('dept_office.yearHead.index', compact('heads', 'dept_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dept = Dept::select('id')->where('name', Auth::user()->name)->first();
        $years = Year::all();
        $sessions = Session::latest()->get();
        $teachers = Teacher::where('dept_id', $dept->id)->orderBy('designation_id')->get();
        return view('dept_office.yearHead.create', compact('years', 'sessions', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->except('_token');
        $rules = [
          'session_id' => 'required | integer',
          'year_id' => 'required | integer',
          'teacher_id' => 'required | integer',
        ];
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $session_id = $request->input('session_id');
        $year_id = $request->input('year_id');
        $teacher_id = $request->input('teacher_id');

        $check = YearHead::where('session_id', $session_id)->where('year_id', $year_id)->count();

        $dept = Dept::select('id')->where('name', Auth::user()->name)->first();

        if ($check == 0)
        {
            $head = new YearHead();
            $head->session_id = $session_id;
            $head->year_id = $year_id;
            $head->teacher_id = $teacher_id;
            $head->dept_id = $dept->id;
            $head->save();

            Toastr::success('Teacher successfully assign', 'Success');
            return redirect()->route('dept_office.year-head.index');

        } else {
            Toastr::error('You have already assign a Teacher', 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\YearHead  $yearHead
     * @return \Illuminate\Http\Response
     */
    public function show(YearHead $yearHead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\YearHead  $yearHead
     * @return \Illuminate\Http\Response
     */
    public function edit(YearHead $yearHead)
    {
        $dept = Dept::select('id')->where('name', Auth::user()->name)->first();
        $years = Year::all();
        $sessions = Session::latest()->get();
        $teachers = Teacher::where('dept_id', $dept->id)->orderBy('designation_id')->get();
        return view('dept_office.yearHead.edit', compact('years', 'sessions', 'teachers', 'yearHead'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\YearHead  $yearHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YearHead $yearHead)
    {
        $inputs = $request->except('_token');
        $rules = [
            'session_id' => 'required | integer',
            'year_id' => 'required | integer',
            'teacher_id' => 'required | integer',
        ];
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $session_id = $request->input('session_id');
        $year_id = $request->input('year_id');
        $teacher_id = $request->input('teacher_id');

        $check = YearHead::where('session_id', $session_id)->where('year_id', $year_id)->where('id','!=', $yearHead->id)->count();

        $dept = Dept::select('id')->where('name', Auth::user()->name)->first();

        if ($check == 0)
        {
            $yearHead->session_id = $session_id;
            $yearHead->year_id = $year_id;
            $yearHead->teacher_id = $teacher_id;
            $yearHead->dept_id = $dept->id;
            $yearHead->save();

            Toastr::success('Teacher assign successfully updated', 'Success');
            return redirect()->route('dept_office.year-head.index');

        } else {
            Toastr::error('You have already assign a Teacher', 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\YearHead  $yearHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(YearHead $yearHead)
    {
        $yearHead->delete();
        Toastr::success('Teacher assign successfully deleted', 'Success');
        return redirect()->route('dept_office.year-head.index');
    }
}
