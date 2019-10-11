<?php

namespace App\Http\Controllers\Exam_Controller;

use App\Models\Course;
use App\Models\Dept;
use App\Models\ExamControllerApproval;
use App\Models\FinalMarks;
use App\Models\IncourseMark;
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
        $session = Session::find($session_id);
        if (isset($dept) && isset($year_semester_id) && isset($session))
        {
            $courses = Course::with('dept')->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->get();

            if (count($courses) >= 1)
            {
                $semester = Semester::find($year_semester_id);

                $code = explode('-', $semester->code);
                $year_code = substr($code[0], 0, 1);

                $year = Year::find($year_code);

                $check_approval = ExamControllerApproval::where('session_id', $session->id)->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->first();

                return view('exam_controller.course.index', compact('courses', 'semester', 'year', 'session', 'year_semester_id', 'check_approval', 'dept'));

            }  else {
                Toastr::error('No Course Data Found!', 'Error');
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

                $semester = Semester::find($year_semester_id);
                $year = Year::find($year_semester_id);
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


    public function result($slug,$session_id, $year_semester_id)
    {
        $dept = Dept::where('slug', $slug)->first();
        $session = Session::find($session_id);

        $semester = Semester::find($year_semester_id);

        $code = explode('-', $semester->code);
        $year_code = substr($code[0], 0, 1);

        $year = Year::find($year_code);


        if (isset($session) && isset($year))
        {
            $students = Student::with('dept')->where('dept_id', $dept->id)->get();
            $check_approval = ExamControllerApproval::where('session_id', $session->id)->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->first();

            return view('exam_controller.result', compact('students', 'session', 'semester', 'year', 'year_semester_id', 'check_approval'));

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }
    }



    public function download($slug,$session_id, $year_semester_id)
    {
        $dept = Dept::where('slug', $slug)->first();
        $session = Session::find($session_id);

        $semester = Semester::find($year_semester_id);

        $code = explode('-', $semester->code);
        $year_code = substr($code[0], 0, 1);

        $year = Year::find($year_code);


        if (isset($session) && isset($year))
        {
            $students = Student::with('dept')->where('dept_id', $dept->id)->get();
            $check_approval = ExamControllerApproval::where('session_id', $session->id)->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->first();

            return view('exam_controller.download', compact('students', 'session', 'semester', 'year', 'year_semester_id', 'check_approval'));

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }
    }


    public function marks_sheet($slug, $session_id, $year_semester_id, $exam_roll)
    {
        $dept = Dept::where('slug', $slug)->first();
        $session = Session::find($session_id);
        $student = Student::where('exam_roll', $exam_roll)->first();
        $semester = Semester::find($year_semester_id);

        $code = explode('-', $semester->code);
        $year_code = substr($code[0], 0, 1);
        $year = Year::find($year_code);

        $courses = Course::with( 'dept')->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->get();
        $total_credit = Course::where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->sum('credit_hour');

        $check_approval = ExamControllerApproval::where('session_id', $session->id)->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->first();

        $grade_point = 0;
        foreach ($courses as $course)
        {
            $marks = IncourseMark::where('dept_id', $dept->id)->where('session_id', $session->id)->where('course_id', $course->id)->where('exam_roll', $student->exam_roll)->first();

            $grade_point += $marks->grade_point;
        }

        $cgpa = number_format(round($grade_point / $total_credit, 2), 2);

        return view('exam_controller.personal_result', compact('courses', 'year', 'student', 'year_semester_id', 'session', 'dept', 'cgpa', 'check_approval'));

    }


    public function marks_sheet_download($slug, $session_id, $year_semester_id, $exam_roll)
    {
        $dept = Dept::where('slug', $slug)->first();
        $session = Session::find($session_id);
        $student = Student::where('exam_roll', $exam_roll)->first();
        $semester = Semester::find($year_semester_id);

        $code = explode('-', $semester->code);
        $year_code = substr($code[0], 0, 1);
        $year = Year::find($year_code);

        $courses = Course::with( 'dept')->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->get();
        $total_credit = Course::where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->sum('credit_hour');

        $check_approval = ExamControllerApproval::where('session_id', $session->id)->where('dept_id', $dept->id)->where('year_semester_id', $year_semester_id)->first();

        $grade_point = 0;
        foreach ($courses as $course)
        {
            $marks = IncourseMark::where('dept_id', $dept->id)->where('session_id', $session->id)->where('course_id', $course->id)->where('exam_roll', $student->exam_roll)->first();

            $grade_point += $marks->grade_point;
        }

        $cgpa = number_format(round($grade_point / $total_credit, 2), 2);

        return view('exam_controller.personal_result_download', compact('courses', 'year', 'student', 'year_semester_id', 'session', 'dept', 'cgpa', 'check_approval', 'semester'));

    }


}
