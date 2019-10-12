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

        return view('student.dashboard', compact('courses'));
    }

    public function show($course_id)
    {
        $name = Auth::user()->name;
        $student = Student::with('dept')->where('name', $name)->first();
        $session = Session::where('name', $student->session)->first();
        $course = Course::findOrFail($course_id);

        $present_count = Attendance::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->where('attend', 'P')->count();
        $total_count = Attendance::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->count();
        if ($total_count > 0)
        {
            $attendance = ($present_count/$total_count)*10;
        }

        $tutorials = Tutorial::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('tutorial_no', 'asc')->get();
        $tutorials_best_two = Tutorial::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();
        $assignments = Assignment::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('assignment_no', 'asc')->get();
        $assignments_best_two = Assignment::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();
        $reports = Report::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('report_no', 'asc')->get();
        $reports_best_two = Report::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();
        $quizzes = Quiz::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('quiz_no', 'asc')->get();
        $quizzes_best_two = Quiz::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();

        if (count($tutorials_best_two) >= 2)
        {
            $tutorial_marks = ($tutorials_best_two[0]->marks + $tutorials_best_two[1]->marks )/2;
            if (!isset($tutorial_marks))
            {
                $tutorial_marks = 0;
            }

        }

        if (count($assignments_best_two) >= 2)
        {
            $assignment_marks = ($assignments_best_two[0]->marks + $assignments_best_two[1]->marks )/2;
            if (!isset($assignment_marks))
            {
                $assignment_marks = 0;
            }
        }

        if (count($reports_best_two) >= 2)
        {
            $report_marks = ($reports_best_two[0]->marks + $reports_best_two[1]->marks )/2;
            if (!isset($report_marks))
            {
                $report_marks = 0;
            }
        }

        if (count($quizzes_best_two) >= 2)
        {
            $quiz_marks = ($quizzes_best_two[0]->marks + $quizzes_best_two[1]->marks )/2;
            if (!isset($quiz_marks))
            {
                $quiz_marks = 0;
            }
        }

        $check_submit = CourseTeacher::where('dept_id', $student->dept->id)->where('session_id', $session->id)->where('course_id', $course->id)->first();


        return view('student.details', compact('course','attendance','tutorials', 'assignments', 'present_count', 'total_count', 'reports', 'quizzes', 'tutorial_marks', 'assignment_marks', 'report_marks', 'quiz_marks', 'check_submit'));

    }

}
