<?php

namespace App\Http\Controllers\Teacher;

use App\Imports\SecondExaminerImport;
use App\Models\Course;
use App\Models\External;
use App\Models\FinalMarks;
use App\Models\Session;
use App\Models\Teacher;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ThirdExaminerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher = Teacher::with('dept')->where('name', Auth::user()->name)->first();
        $third_examiner_courses = External::with('dept', 'course', 'session')->where('external_2', $teacher->id)->get();

        return view('teacher.thirdExaminer.index', compact('teacher', 'third_examiner_courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($session_id, $course_id)
    {
        $session = Session::findOrFail($session_id);
        $course = Course::with('dept')->findOrFail($course_id);

        $teacher = Teacher::where('name', Auth::user()->name)->first();

        $third_examiner_check = External::where('session_id', $session_id)->where('dept_id', $course->dept->id)->where('course_id', $course_id)->where('external_2', $teacher->id)->count();

        if ($third_examiner_check === 1)
        {
            $students = FinalMarks::where('session_id', $session_id)->where('dept_id', $course->dept->id)->where('course_id', $course_id)->get();
            return view('teacher.thirdExaminer.create', compact('course', 'session', 'students'));

        } else {
            Toastr::error('Unauthorized Access Denied!', 'Error');
            return redirect()->back();
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $session_id, $course_id)
    {
        $inputs = $request->except('_token');
        $rules = [
            'teacher_3_marks' => 'required',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }



        $session = Session::findOrFail($session_id);
        $course = Course::with('dept')->findOrFail($course_id);

        $teacher_3_marks = $request->input('teacher_3_marks');

        foreach ($teacher_3_marks as $exam_roll => $marks)
        {
            $student_exists = FinalMarks::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->where('exam_roll', $exam_roll)->first();

            if (!isset($student_exists))
            {
                continue;

            } else {

                if ($student_exists->teacher_1_marks - $student_exists->teacher_2_marks >= 12 | $student_exists->teacher_2_marks - $student_exists->teacher_1_marks >= 12)
                {
                    $student_exists->teacher_3_marks = $marks;
                    $student_exists->save();

                } else {
                    continue;
                }

            }
        }

        Toastr::success('Third Examiner Marks Added Successfully!', 'Success');
        return redirect()->back();


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($session_id, $course_id)
    {
        $session = Session::findOrFail($session_id);
        $course = Course::with('dept')->findOrFail($course_id);

        $teacher = Teacher::where('name', Auth::user()->name)->first();

        $second_examiner_check = External::where('session_id', $session_id)->where('dept_id', $course->dept->id)->where('course_id', $course_id)->where('external_1', $teacher->id)->count();

        if ($second_examiner_check === 1)
        {
            $second_examiner_marks = FinalMarks::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->get();
            return view('teacher.secondExaminer.show', compact('course', 'session', 'second_examiner_marks'));

        } else {
            Toastr::error('Unauthorized Access Denied!', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function download($session_id, $course_id)
    {
        $session = Session::findOrFail($session_id);
        $course = Course::with('dept')->findOrFail($course_id);

        $teacher = Teacher::where('name', Auth::user()->name)->first();

        $second_examiner_check = External::where('session_id', $session_id)->where('dept_id', $course->dept->id)->where('course_id', $course_id)->where('external_1', $teacher->id)->count();

        if ($second_examiner_check === 1)
        {
            $second_examiner_marks = FinalMarks::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->get();
            return view('teacher.secondExaminer.download', compact('course', 'session', 'second_examiner_marks'));

        } else {
            Toastr::error('Unauthorized Access Denied!', 'Error');
            return redirect()->back();
        }
    }
}
