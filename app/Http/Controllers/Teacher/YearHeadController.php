<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Course;
use App\Models\CourseTeacher;
use App\Models\ExamControllerApproval;
use App\Models\FinalMarks;
use App\Models\IncourseMark;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Year;
use App\Models\YearHead;
use App\Models\YearHeadApproval;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                $check_approval = YearHeadApproval::where('session_id', $session->id)->where('dept_id', $teacher->dept_id)->where('course_id', $course->id)->first();

                return view('teacher.yearHead.marks', compact('final_marks', 'check_approval'));

            } else {
                Toastr::error('No Marks Submitted yet!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }

    }


    public function approved($session_id, $course_id)
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        $session = Session::find($session_id);
        $course = Course::find($course_id);

        if (isset($session) && isset($course))
        {
            $check_approval = YearHeadApproval::where('session_id', $session->id)->where('dept_id', $teacher->dept_id)->where('course_id', $course->id)->count();
            if ($check_approval < 1)
            {
                $code = explode('-', $course->course_code);
                $year_code = substr($code[1], 0, 1);

                $year = Year::find($year_code);

                $check_right_course_teacher = YearHead::where('session_id', $session->id)->where('dept_id', $teacher->dept_id)->where('year_id', $year->id)->where('teacher_id', $teacher->id)->count();
                if ($check_right_course_teacher < 1)
                {
                    Toastr::error('Unauthorized Access Denied!', 'Error');
                    return redirect()->back();

                } else {

                    $students = Student::where('dept_id', $teacher->dept_id)->get();
                    if (count($students) > 0)
                    {
                        foreach ($students as $student)
                        {
                            $marks = IncourseMark::with('course')->where('session_id', $session->id)->where('dept_id', $teacher->dept_id)->where('course_id', $course->id)->where('reg_no', $student->reg_no)->where('exam_roll', $student->exam_roll)->first();

                            $final_mark = FinalMarks::where('session_id', $session->id)->where('dept_id', $teacher->dept_id)->where('course_id', $course->id)->where('reg_no', $student->reg_no)->where('exam_roll', $student->exam_roll)->first();

                            $numbers = array($final_mark->teacher_1_marks, $final_mark->teacher_2_marks, $final_mark->teacher_3_marks);
                            rsort($numbers);

                            if ($final_mark->teacher_1_marks - $final_mark->teacher_2_marks >= 12 | $final_mark->teacher_2_marks - $final_mark->teacher_1_marks >= 12)
                            {
                                if ($final_mark->teacher_2_marks != null)
                                {
                                    $final_marks_average = ($numbers[0] + $numbers[1]) / 2;

                                } else {
                                    $final_marks_average = ($final_mark->teacher_1_marks + $final_mark->teacher_2_marks) / 2;
                                }

                            } else {
                                $final_marks_average = ($final_mark->teacher_1_marks + $final_mark->teacher_2_marks) / 2;
                            }

                            if (isset($marks))
                            {
                                $total_marks = round($marks->marks + $final_marks_average);

                                $gpa = point($total_marks);
                                $credit_hour = $marks->course->credit_hour;

                                $marks->theory_marks = $final_marks_average;
                                $marks->grade_point = $gpa * $credit_hour;
                                $marks->save();

                            } else {
                                continue;
                            }
                        }

                    } else {
                        Toastr::error('No students Found!', 'Error');
                        return redirect()->back();
                    }

                    $year_head_approval = new YearHeadApproval();
                    $year_head_approval->session_id = $session->id;
                    $year_head_approval->dept_id = $teacher->dept_id;
                    $year_head_approval->course_id = $course->id;
                    $year_head_approval->approved = 1;
                    $year_head_approval->save();

                    Toastr::success('Course Marks approved successfully!', 'Success');
                    return redirect()->route('teacher.year-head.course', [$session->id, $year->id]);
                }


            } else {
                Toastr::error('Course already approved!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }


    }


    public function result($session_id, $year_id, $semester_id = null)
    {
        $session = Session::find($session_id);
        $year = Year::find($year_id);
       // $semester = Semester::find($semester_id);

        $teacher = Teacher::with('dept')->where('name', Auth::user()->name)->first();

        if (isset($semester_id))
        {
            $semester_code = $year->id .'-'. $semester_id;

            $semester = Semester::where('code', $semester_code)->first();
        }

        if ($teacher->dept->is_semester == 1)
        {
            $year_semester_id = $semester->id;

        } else {
            $year_semester_id = $year->id;
        }

        if (isset($session) && isset($year))
        {
            $check_year_head = YearHead::where('dept_id', $teacher->dept->id)->where('session_id', $session->id)->where('year_id', $year->id)->where('teacher_id', $teacher->id)->get();

            if (count($check_year_head) > 0)
            {
                if ($teacher->dept->is_semester == 1)
                {
                    $students = Student::with('dept')->where('dept_id', $teacher->dept->id)->get();
                    $check_approval = ExamControllerApproval::where('session_id', $session->id)->where('dept_id', $teacher->dept->id)->where('year_semester_id', $year_semester_id)->first();
                    return view('teacher.yearHead.result', compact('students', 'session', 'semester', 'year', 'semester_id', 'check_approval'));
                }

            } else {
                Toastr::error('Unauthorized Access Denied!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }
    }



    public function download($session_id, $year_id, $semester_id = null)
    {
        $session = Session::find($session_id);
        $year = Year::find($year_id);
        // $semester = Semester::find($semester_id);

        $teacher = Teacher::with('dept')->where('name', Auth::user()->name)->first();

        if (isset($semester_id))
        {
            $semester_code = $year->id .'-'. $semester_id;

            $semester = Semester::where('code', $semester_code)->first();
        }

        if ($teacher->dept->is_semester == 1)
        {
            $year_semester_id = $semester->id;

        } else {
            $year_semester_id = $year->id;
        }

        if (isset($session) && isset($year))
        {
            $check_year_head = YearHead::where('dept_id', $teacher->dept->id)->where('session_id', $session->id)->where('year_id', $year->id)->where('teacher_id', $teacher->id)->get();

            if (count($check_year_head) > 0)
            {
                if ($teacher->dept->is_semester == 1)
                {
                    $students = Student::with('dept')->where('dept_id', $teacher->dept->id)->get();
                    $check_approval = ExamControllerApproval::where('session_id', $session->id)->where('dept_id', $teacher->dept->id)->where('year_semester_id', $year_semester_id)->first();
                    return view('teacher.yearHead.download', compact('students', 'session', 'semester', 'year', 'semester_id', 'check_approval'));
                }

            } else {
                Toastr::error('Unauthorized Access Denied!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }
    }



    public function personal_result($session_id, $year_id, $semester_id = null)
    {
        $session = Session::find($session_id);
        $year = Year::find($year_id);
        // $semester = Semester::find($semester_id);

        if (isset($semester_id))
        {
            $semester_code = $year->id .'-'. $semester_id;

            $semester = Semester::where('code', $semester_code)->first();
        }


        $teacher = Teacher::with('dept')->where('name', Auth::user()->name)->first();

        if (isset($session) && isset($year))
        {
            $check_year_head = YearHead::where('dept_id', $teacher->dept->id)->where('session_id', $session->id)->where('year_id', $year->id)->where('teacher_id', $teacher->id)->get();

            if (count($check_year_head) > 0)
            {
                if ($teacher->dept->is_semester == 1)
                {
                    $courses = Course::with('dept')->where('dept_id', $teacher->dept->id)->where('year_semester_id', $semester->id)->get();
                    return view('teacher.yearHead.result', compact('courses'));
                }



            } else {
                Toastr::error('Unauthorized Access Denied!', 'Error');
                return redirect()->back();
            }

        } else {
            Toastr::error('Invalid URL!', 'Error');
            return redirect()->back();
        }
    }

}
