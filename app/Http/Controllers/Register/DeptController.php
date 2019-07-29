<?php

namespace App\Http\Controllers\Register;

use App\Models\Dept;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DeptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depts = Dept::all();
        return view('register.dept.index', compact('depts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('register.dept.create');
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
            'name' => 'required | unique:depts',
            'short_name' => 'required | unique:depts',
        ];
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $dept = new Dept();
        $dept->name = $request->input('name');
        $dept->slug = Str::slug($request->input('name'));
        $dept->short_name = $request->input('short_name');
        if ($request->input('is_semester'))
        {
            $dept->is_semester = 1;
        } else {
            $dept->is_semester = 0;
        }
        $dept->save();

        Toastr::success('Department created successfully', 'Success!');
        return redirect()->route('register.dept.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dept  $dept
     * @return \Illuminate\Http\Response
     */
    public function show(Dept $dept)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dept  $dept
     * @return \Illuminate\Http\Response
     */
    public function edit(Dept $dept)
    {
        return view('register.dept.edit', compact('dept'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dept  $dept
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dept $dept)
    {
        $inputs = $request->except('_token');
        $rules = [
            'name' => 'required',
            'short_name' => 'required',
        ];
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $dept->name = $request->input('name');
        $dept->slug = Str::slug($request->input('name'));
        $dept->short_name = $request->input('short_name');
        if ($request->input('is_semester'))
        {
            $dept->is_semester = 1;
        } else {
            $dept->is_semester = 0;
        }
        $dept->save();

        Toastr::success('Department updated successfully', 'Success!');
        return redirect()->route('register.dept.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dept  $dept
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dept $dept)
    {
        $dept->delete();
        Toastr::success('Department deleted successfully', 'Success!');
        return redirect()->route('register.dept.index');
    }
}
