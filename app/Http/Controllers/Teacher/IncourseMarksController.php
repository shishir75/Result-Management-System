<?php

namespace App\Http\Controllers\Teacher;

use App\Models\CourseTeacher;
use App\Models\FinalMarks;
use App\Models\IncourseMark;
use App\Models\Student;
use App\Models\Teacher;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IncourseMarksController extends Controller
{
    public function approved($id)
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        $course = CourseTeacher::where('teacher_id', $teacher->id)->where('course_id', $id)->first();

        if (isset($course))
        {
            if ($course->incourse_submit != 0)
            {
                Toastr::error('You have already submitted!', 'Error');
                return redirect()->back();

            } else {

                $students = Student::where('dept_id', $teacher->dept_id)->get();

                foreach ($students as $student)
                {
                    $marks = new IncourseMark();

                    $incourse_marks = incourse_marks($student->id, $course->course->id);

                    if ($incourse_marks != false)
                    {
                        $marks->session_id = $course->session_id;
                        $marks->dept_id = $teacher->dept_id;
                        $marks->course_id = $course->course_id;
                        $marks->reg_no = $student->reg_no;
                        $marks->exam_roll = $student->exam_roll;
                        $marks->marks = $incourse_marks;
                        $marks->save();

                    } else {
                        return redirect()->back();
                        break;
                    }


                }


                $check_marks_exists = IncourseMark::where('session_id', $course->session_id)->where('dept_id', $course->dept_id)->where('course_id', $course->course_id)->where('marks', '!=', null)->count();

                if ($check_marks_exists > 0)
                {
                    $course->incourse_submit = 1;
                    $course->save();

                    Toastr::success('Course Marks Submitted Successfully!', 'Success');
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
