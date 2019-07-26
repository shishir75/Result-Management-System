<?php

namespace App\Http\Controllers\Register;

use App\Models\Hall;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $halls = Hall::all();
        return view('register.hall.index', compact('halls'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('register.hall.create');
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
            'name' => 'required | unique:halls'
        ];
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hall = new Hall();
        $hall->name = $request->input('name');
        $hall->slug = Str::slug($request->input('name'));
        $hall->save();

        Toastr::success('Hall created successfully', 'Success!');
        return redirect()->route('register.hall.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dept  $dept
     * @return \Illuminate\Http\Response
     */
    public function show(Hall $hall)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dept  $dept
     * @return \Illuminate\Http\Response
     */
    public function edit(Hall $hall)
    {
        return view('register.hall.edit', compact('hall'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dept  $dept
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hall $hall)
    {
        $inputs = $request->except('_token');
        $rules = [
            'name' => 'required'
        ];
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hall->name = $request->input('name');
        $hall->slug = Str::slug($request->input('name'));
        $hall->save();

        Toastr::success('Hall updated successfully', 'Success!');
        return redirect()->route('register.hall.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dept  $dept
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hall $hall)
    {
        $hall->delete();
        Toastr::success('Hall deleted successfully', 'Success!');
        return redirect()->route('register.hall.index');
    }
}
