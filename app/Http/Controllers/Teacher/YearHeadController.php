<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\FinalMarks;
use App\Models\Session;
use App\Models\Teacher;
use App\Models\Year;
use App\Models\YearHead;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class YearHeadController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        $years = YearHead::with('session', 'dept', 'year', 'teacher')->where('dept_id', $teacher->dept_id)->where('teacher_id', $teacher->id)->get();
        if (count($years) > 0)
        {
            return view('teacher.yearHead.index', compact('years'));

        } else {
            Toastr::error('You are not appointed for any year head for any session!', 'Error');
            return redirect()->back();
        }
    }

    public function course($session_id, $year_id)
    {
        $teacher = Teacher::with('dept')->where('name', Auth::user()->name)->first();
        $session = Session::find($session_id);
        $year = Year::find($year_id);

        $courses = Course::with('dept')->where('dept_id', $teacher->dept->id)->get();

        if (count($courses) > 0 && !empty($session) && !empty($year))
        {
            $check = YearHead::where('dept_id', $teacher->dept->id)->where('session_id', $session->id)->where('year_id', $year->id)->where('teacher_id', $teacher->id)->get();

            if (count($check) > 0)
            {
                return view('teacher.yearHead.course', compact('courses', 'session', 'year'));

            } else {
                Toastr::error('Unauthorized Access Denied!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }

    }


    public function marks($session_id, $course_id)
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        $session = Session::find($session_id);
        $course = Course::find($course_id);

        if (isset($session) && isset($course))
        {
            $final_marks = FinalMarks::with('session', 'dept', 'course')->where('session_id', $session->id)->where('dept_id', $teacher->dept_id)->where('course_id', $course->id)->get();

            if (count($final_marks) > 0)
            {
                return view('teacher.yearHead.marks', compact('final_marks'));

            } else {
                Toastr::error('No Marks Submitted yet!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }



    }

}
