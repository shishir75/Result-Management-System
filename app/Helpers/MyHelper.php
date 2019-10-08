<?php

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Report;
use App\Models\Session;
use App\Models\Student;
use App\Models\Tutorial;
use Brian2694\Toastr\Facades\Toastr;

if (!function_exists('incourse_marks')) {

    function incourse_marks($student_id, $course_id)
    {
        $student = Student::find($student_id);
        $session = Session::where('name', $student->session)->first();
        $course = Course::find($course_id);

        $present_count = Attendance::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->where('attend', 'P')->count();
        $total_count = Attendance::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->count();

        if ($total_count > 0)
        {
            $attendance = ($present_count/$total_count)*10;
        } else {
            Toastr::error('No attendance data found', 'Error');
            return false;
        }

        $tutorials_best_two = Tutorial::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();
        $assignments_best_two = Assignment::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();
        $reports_best_two = Report::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();
        $quizzes_best_two = Quiz::where('course_id', $course->id)->where('session_id', $session->id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();

        if (count($tutorials_best_two) >= 2)
        {
            $tutorial_marks = ($tutorials_best_two[0]->marks + $tutorials_best_two[1]->marks )/2;
            if (!isset($tutorial_marks))
            {
                Toastr::error('Not enough tutorial data found', 'Error');
                return false;
            }
        } else {
            Toastr::error('Not enough tutorial data found', 'Error');
            return false;
        }

        if (count($assignments_best_two) >= 2)
        {
            $assignment_marks = ($assignments_best_two[0]->marks + $assignments_best_two[1]->marks )/2;
            if (!isset($assignment_marks))
            {
                Toastr::error('Not enough assignment data found', 'Error');
                return false;
            }
        } else {
            Toastr::error('Not enough assignment data found', 'Error');
            return false;
        }

        if ($course->is_lab == 1)
        {
            if (count($reports_best_two) >= 2)
            {
                $report_marks = ($reports_best_two[0]->marks + $reports_best_two[1]->marks )/2;
                if (!isset($report_marks))
                {
                    Toastr::error('Not enough report data found', 'Error');
                    return false;
                }
            } else {
                Toastr::error('Not enough report data found', 'Error');
                return false;
            }

            if (count($quizzes_best_two) >= 2)
            {
                $quiz_marks = ($quizzes_best_two[0]->marks + $quizzes_best_two[1]->marks )/2;
                if (!isset($quiz_marks))
                {
                    Toastr::error('Not enough quiz data found', 'Error');
                    return false;
                }
            } else {
                Toastr::error('Not enough quiz data found', 'Error');
                return false;
            }
        }

        if ($course->is_lab != 1)
        {
            $total_incourse_marks = $attendance + $tutorial_marks + $assignment_marks;

        } else {
            $total_incourse_marks = $attendance + $tutorial_marks + $assignment_marks + $report_marks + $quiz_marks;
        }

        return $total_incourse_marks;
    }
}
