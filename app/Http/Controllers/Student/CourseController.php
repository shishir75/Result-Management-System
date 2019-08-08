<?php

namespace App\Http\Controllers\Student;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\Quiz;
use App\Models\Report;
use App\Models\Session;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $name = Auth::user()->name;
        $student = Student::where('name', $name)->first();

        $session = Session::where('name', $student->session)->first();

        $courses = CourseTeacher::with('course', 'teacher')->where('session_id', $session->id)->where('dept_id', $student->dept_id)->orderBy('code', 'desc')->get();

        return view('student.course', compact('courses'));
    }

    public function show($course_id)
    {
        $name = Auth::user()->name;
        $student = Student::where('name', $name)->first();
        $session = Session::where('name', $student->session)->first();
        $course = Course::findOrFail($course_id);

        $present_count = Attendance::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->where('attend', 'P')->count();
        $total_count = Attendance::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->count();

        $tutorials = Tutorial::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('tutorial_no', 'asc')->get();
        $assignments = Assignment::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('assignment_no', 'asc')->get();
        $reports = Report::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('report_no', 'asc')->get();
        $quizzes = Quiz::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('quiz_no', 'asc')->get();

        return view('student.details', compact('course','tutorials', 'assignments', 'present_count', 'total_count', 'reports', 'quizzes'));

    }

}
