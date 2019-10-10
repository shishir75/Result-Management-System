<?php

namespace App\Http\Controllers\Exam_Controller;

use App\Models\Course;
use App\Models\Dept;
use App\Models\ExamControllerApproval;
use App\Models\FinalMarks;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Year;
use App\Models\YearHead;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function session($slug)
    {
        $dept = Dept::where('slug', $slug)->first();
        if (isset($dept))
        {
            $courses = FinalMarks::with('session', 'dept')->where('dept_id', $dept->id)->where('teacher_1_marks', '!=', null)->where('teacher_2_marks', '!=', null)->distinct()->get(['session_id', 'dept_id']);

            if (count($courses) >= 1)
            {
                return view('exam_controller.session.index', compact('courses', 'dept'));

            } else {
                Toastr::error('No Data Available Right Now!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }

    }

    public function year_semester($slug, $session_id)
    {
        $dept = Dept::where('slug', $slug)->first();

        if (isset($dept) && isset($session_id))
        {
            $courses = FinalMarks::with('session', 'dept', 'course')->where('dept_id', $dept->id)->where('session_id', $session_id)->where('teacher_1_marks', '!=', null)->where('teacher_2_marks', '!=', null)->distinct()->get(['session_id', 'dept_id', 'course_id']);

            $semesters = Semester::all();
            $years = Year::all();

            if (count($courses) > 0)
            {
                return view('exam_controller.year_semester.index', compact('courses', 'dept', 'semesters', 'years'));

            } else {
                Toastr::error('Unauthorized Access Denied!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }

    }

    public function course($slug,$session_id, $year_semester_id)
    {
        $dept = Dept::where('slug', $slug)->first();
        $session = Session::findOrFail($session_id);
        if (isset($dept) && isset($year_semester_id) && isset($session))
        {
            $courses = Course::with('dept')->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->get();

            if (count($courses) >= 1)
            {
                $semester = Semester::findOrFail($year_semester_id);
                $year = Year::findOrFail($year_semester_id);

                $check_approval = ExamControllerApproval::where('session_id', $session->id)->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->first();

                return view('exam_controller.course.index', compact('courses', 'semester', 'year', 'session', 'year_semester_id', 'check_approval'));

            }  else {
                Toastr::error('Unauthorized Access Denied!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }
    }

    public function marks($slug,$session_id, $year_semester_id, $course_id)
    {
        $dept = Dept::where('slug', $slug)->first();

        if (isset($dept) && isset($session_id) && isset($course_id))
        {
            $marks = FinalMarks::with('session', 'dept', 'course')->where('session_id', $session_id)->where('dept_id', $dept->id)->where('course_id', $course_id)->get();

            if (count($marks) > 0) {

                $semester = Semester::findOrFail($year_semester_id);
                $year = Year::findOrFail($year_semester_id);
                return view('exam_controller.marks.index', compact('marks', 'year', 'semester'));

            } else {
                Toastr::error('No Marks Submitted yet!', 'Error');
                return redirect()->back();
            }
        }

    }


    public function approved($slug,$session_id, $year_semester_id)
    {
        $dept = Dept::where('slug', $slug)->first();
        $session = Session::findOrFail($session_id);
        if (isset($dept) && isset($year_semester_id) && isset($session))
        {
            $check_approval = ExamControllerApproval::where('session_id', $session->id)->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->where('status', 1)->count();
            if ($check_approval < 1)
            {
                $approval = new ExamControllerApproval();
                $approval->session_id = $session->id;
                $approval->dept_id = $dept->id;
                $approval->year_semester_id = $year_semester_id;
                $approval->status = 1;
                $approval->save();

                Toastr::success('Result has been approved!', 'Success');
                return redirect()->back();

            } else {
                Toastr::error('This result has already been approved!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }
    }


    public function result($slug,$session_id, $year_id, $semester_id = null)
    {
        $dept = Dept::where('slug', $slug)->first();
        $session = Session::find($session_id);
        $year = Year::find($year_id);
        // $semester = Semester::find($semester_id);

        if (isset($semester_id))
        {
            $semester_code = $year->id .'-'. $semester_id;

            $semester = Semester::where('code', $semester_code)->first();
        }

        if (isset($session) && isset($year))
        {
            $students = Student::with('dept')->where('dept_id', $dept->id)->get();
            return view('exam_controller.result', compact('students', 'session', 'semester', 'year', 'semester_id'));

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }
    }



    public function download($slug,$session_id, $year_id, $semester_id = null)
    {
        $dept = Dept::where('slug', $slug)->first();
        $session = Session::find($session_id);
        $year = Year::find($year_id);
        // $semester = Semester::find($semester_id);

        if (isset($semester_id))
        {
            $semester_code = $year->id .'-'. $semester_id;

            $semester = Semester::where('code', $semester_code)->first();
        }

        if (isset($session) && isset($year))
        {
            $students = Student::with('dept')->where('dept_id', $dept->id)->get();
            return view('exam_controller.download', compact('students', 'session', 'semester', 'year', 'semester_id'));

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }
    }


}
