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
        $session = Session::findOrFail($session_id);
        $course = Course::with('dept')->findOrFail($course_id);

        if ($request->hasFile('file')){
            $file = $request->file('file');

            $data = Excel::toArray(new SecondExaminerImport(), $file);


            if (!empty($data)  && count($data) > 0)
            {

                foreach ($data as $rows)
                {
                    foreach ($rows as $key => $value)
                    {
                        if ($key > 3)
                        {
                            $finalMarks = new FinalMarks();

                            $second_examiner_mark = FinalMarks::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->where('reg_no', $value[1])->where('exam_roll', $value[2])->first();

                            $check = FinalMarks::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->where('reg_no', $value[1])->where('exam_roll', $value[2])->count();

                            if ($check > 0)
                            {
                                $second_examiner_mark->teacher_2_marks = $value[3];
                                $second_examiner_mark->save();

                            } else {

                                $finalMarks->reg_no = $value[1];
                                $finalMarks->exam_roll = $value[2];
                                $finalMarks->teacher_2_marks = $value[3];
                                $finalMarks->session_id = $session->id;
                                $finalMarks->dept_id = $course->dept->id;
                                $finalMarks->course_id = $course->id;
                                $finalMarks->save();
                            }

                        }
                    }
                }

                Toastr::success('Second Examiner Marks added successfully', 'Success');
                return redirect()->route('teacher.second-examiner.show', [$session_id, $course_id]);
            }
        }
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
