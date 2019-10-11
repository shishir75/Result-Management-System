<?php

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\IncourseMark;
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


if (!function_exists('point')) {

    function point($marks)
    {
        if ($marks >= 80)
        {
            $gpa = 4.00;

        } elseif ($marks < 80 && $marks >= 75)
        {
            $gpa = 3.75;

        } elseif ($marks < 75 && $marks >= 70)
        {
            $gpa = 3.50;

        } elseif ($marks < 70 && $marks >= 65)
        {
            $gpa = 3.25;

        } elseif ($marks < 65 && $marks >= 60)
        {
            $gpa = 3.00;

        } elseif ($marks < 60 && $marks >= 55)
        {
            $gpa = 2.75;

        } elseif ($marks < 55 && $marks >= 50)
        {
            $gpa = 2.50;

        } elseif ($marks < 50 && $marks >= 45)
        {
            $gpa = 2.25;

        } elseif ($marks < 45 && $marks >= 40)
        {
            $gpa = 2.00;

        } elseif ($marks < 40)
        {
            $gpa = 0.00;
        }

        return $gpa;

    }
}



if (!function_exists('gpa_calculate')) {

    function gpa_calculate($session_name, $semseter_id, $reg_no, $exam_roll)
    {
        $session = Session::where('name', $session_name)->first();
        $student = Student::where('reg_no', $reg_no)->where('exam_roll', $exam_roll)->first();

        $total_credits = Course::where('dept_id', $student->dept_id)->where('year_semester_id', $semseter_id)->sum('credit_hour');

        $courses = Course::where('dept_id', $student->dept_id)->where('year_semester_id', $semseter_id)->get();

        $total_grade_point = 0;
        foreach ($courses as $course)
        {
            $total_marks = IncourseMark::where('session_id', $session->id)->where('dept_id', $student->dept_id)->where('course_id', $course->id)->where('reg_no', $student->reg_no)->where('exam_roll', $student->exam_roll)->first();

            $total_grade_point += $total_marks->grade_point;
        }

        $gpa = number_format($total_grade_point / $total_credits, 2);
        return $gpa;

    }
}


if (!function_exists('latter_grade')) {

    function latter_grade($gpa)
    {
        if ($gpa == 4.00)
        {
            $grade = 'A+';

        } elseif ($gpa < 4.00 && $gpa >= 3.75)
        {
            $grade = 'A';
        } elseif ($gpa < 3.75 && $gpa >= 3.50)
        {
            $grade = 'A-';
        } elseif ($gpa < 3.50 && $gpa >= 3.25)
        {
            $grade = 'B+';
        } elseif ($gpa < 3.25 && $gpa >= 3.00)
        {
            $grade = 'B';
        } elseif ($gpa < 3.00 && $gpa >= 2.75)
        {
            $grade = 'B-';
        } elseif ($gpa < 2.75 && $gpa >= 2.50)
        {
            $grade = 'C+';
        } elseif ($gpa < 2.50 && $gpa >= 2.25)
        {
            $grade = 'C';
        } elseif ($gpa < 2.25 && $gpa >= 2.00)
        {
            $grade = 'D';
        } else
        {
            $grade = 'F';
        }

        return $grade;

    }
}


