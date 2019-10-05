<?php

namespace App\Http\Controllers\Teacher;

use App\Imports\FinalMarksImport;
use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Dept;
use App\Models\FinalMarks;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class FinalMarksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher = Teacher::with('dept')->where('name', Auth::user()->name)->first();
        $course_teachers = CourseTeacher::with('session', 'course', 'teacher')->where('teacher_id', $teacher->id)->orderBy('id', 'desc')->get();
        return view('teacher.finalMarks.index', compact('teacher', 'course_teachers'));
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

        $course_teacher_check = CourseTeacher::where('session_id', $session_id)->where('dept_id', $course->dept->id)->where('course_id', $course_id)->where('teacher_id', $teacher->id)->where('status', 0)->count();

        if ($course_teacher_check === 1)
        {
            return view('teacher.finalMarks.create', compact('course', 'session'));

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

            $data = Excel::toArray(new FinalMarksImport(), $file);

            //$dept = Dept::select('id')->where('name', Auth::user()->name)->first();

            if (!empty($data)  && count($data) > 0)
            {

                foreach ($data as $rows)
                {
                    foreach ($rows as $key => $value)
                    {
                        if ($key > 3)
                        {
                            $finalMarks = new FinalMarks();

                            $course_teacher_marks = FinalMarks::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->where('reg_no', $value[1])->where('exam_roll', $value[2])->first();

                            $check = FinalMarks::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->where('reg_no', $value[1])->where('exam_roll', $value[2])->count();

                            if ($check > 0)
                            {
                                $course_teacher_marks->teacher_1_marks = $value[3];
                                $course_teacher_marks->save();

                            } else {

                                $finalMarks->reg_no = $value[1];
                                $finalMarks->exam_roll = $value[2];
                                $finalMarks->teacher_1_marks = $value[3];
                                $finalMarks->session_id = $session->id;
                                $finalMarks->dept_id = $course->dept->id;
                                $finalMarks->course_id = $course->id;
                                $finalMarks->save();
                            }

                        }
                    }
                }

                Toastr::success('Course Written Marks added successfully', 'Success');
                return redirect()->route('teacher.final-marks.show', [$session_id, $course_id]);
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

        $course_teacher_check = CourseTeacher::where('session_id', $session_id)->where('dept_id', $course->dept->id)->where('course_id', $course_id)->where('teacher_id', $teacher->id)->count();

        if ($course_teacher_check === 1)
        {
            $final_marks = FinalMarks::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->get();
            return view('teacher.finalMarks.show', compact('course', 'session', 'final_marks'));

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

        $course_teacher_check = CourseTeacher::where('session_id', $session_id)->where('dept_id', $course->dept->id)->where('course_id', $course_id)->where('teacher_id', $teacher->id)->count();

        if ($course_teacher_check === 1)
        {
            $final_marks = FinalMarks::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->get();
            return view('teacher.finalMarks.download', compact('course', 'session', 'final_marks'));

        } else {
            Toastr::error('Unauthorized Access Denied!', 'Error');
            return redirect()->back();
        }
    }

    public function approved($id)
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        $course = CourseTeacher::where('teacher_id', $teacher->id)->findOrFail($id);

        if (isset($course))
        {
            if ($course->status != 0)
            {
                Toastr::error('You have already submitted!', 'Error');
                return redirect()->back();

            } else {

                $check_marks_exists = FinalMarks::where('session_id', $course->session_id)->where('dept_id', $course->dept_id)->where('course_id', $course->course_id)->where('teacher_1_marks', '!=', null)->count();

                if ($check_marks_exists > 0)
                {
                    $course->status = 1;
                    $course->save();

                    Toastr::success('Course Data Submitted Successfully!', 'Success');
                    return redirect()->back();

                } else {
                    Toastr::error('You have not added marks yet! Please add course marks!!', 'Error');
                    return redirect()->back();
                }


            }

        } else {
            Toastr::error('Unauthorized Access Denied!', 'Error');
            return redirect()->back();
        }
    }
}
