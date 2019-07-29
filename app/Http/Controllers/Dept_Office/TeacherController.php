<?php

namespace App\Http\Controllers\Dept_Office;

use App\Models\Dept;
use App\Models\Designation;
use App\Models\Teacher;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dept_name = Auth::user()->name;
        $dept = Dept::select('id')->where('name', $dept_name)->first();

        $teachers = Teacher::with('designation')->where('dept_id', $dept->id)->orderBy('designation_id')->get();

        return view('dept_office.teacher.index', compact('teachers', 'dept_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designations = Designation::all();
        return view('dept_office.teacher.create', compact('designations'));
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
            'name' => 'required | min:5',
            'designation_id' => 'required | int',
            'image' => 'required | image',
            'research' => 'nullable',
            'about' => 'nullable',
        ];
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $image = $request->file('image');
        $slug = Str::slug($request->input('name'));

        if (isset($image))
        {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('teacher'))
            {
                Storage::disk('public')->makeDirectory('teacher');
            }

            $postImage = Image::make($image)->resize(350, 320)->stream();
            Storage::disk('public')->put('teacher/'.$imageName, $postImage);

        } else
        {
            $imageName = 'default.png';
        }

        $dept_name = Auth::user()->name;
        $dept = Dept::select('id')->where('name', $dept_name)->first();

        $teacher = new Teacher();
        $teacher->dept_id = $dept->id;
        $teacher->name = $request->input('name');
        $teacher->designation_id = $request->input('designation_id');
        $teacher->image = $imageName;
        $teacher->research = $request->input('research');
        $teacher->about = $request->input('about');
        $teacher->save();

        Toastr::success('Teacher added Successfully', 'Success!!!');
        return redirect()->route('dept_office.teacher.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        $designations = Designation::all();
        return view('dept_office.teacher.edit', compact('teacher', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $inputs = $request->except('_token');
        $rules = [
            'name' => 'required | min:5',
            'designation_id' => 'required | int',
            'image' => 'nullable',
            'research' => 'nullable',
            'about' => 'nullable',
        ];
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $image = $request->file('image');
        $slug = Str::slug($request->input('name'));

        if (isset($image))
        {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('teacher'))
            {
                Storage::disk('public')->makeDirectory('teacher');
            }

            // delete old photo
            if (Storage::disk('public')->exists('teacher/'.$teacher->image))
            {
                Storage::disk('public')->delete('teacher/'.$teacher->image);
            }

            $postImage = Image::make($image)->resize(350, 320)->stream();
            Storage::disk('public')->put('teacher/'.$imageName, $postImage);

        } else
        {
            $imageName = $teacher->image;
        }

        $teacher->name = $request->input('name');
        $teacher->designation_id = $request->input('designation_id');
        $teacher->image = $imageName;
        $teacher->research = $request->input('research');
        $teacher->about = $request->input('about');
        $teacher->save();

        Toastr::success('Teacher updated Successfully', 'Success!!!');
        return redirect()->route('dept_office.teacher.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        // delete old photo
        if (Storage::disk('public')->exists('teacher/'.$teacher->image))
        {
            Storage::disk('public')->delete('teacher/'.$teacher->image);
        }
        $teacher->delete();

        Toastr::success('Teacher deleted Successfully', 'Success!!!');
        return redirect()->route('dept_office.teacher.index');
    }
}
