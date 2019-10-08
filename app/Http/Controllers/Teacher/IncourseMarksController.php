<?php

namespace App\Http\Controllers\Teacher;

use App\Models\CourseTeacher;
use App\Models\FinalMarks;
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
        $course = CourseTeacher::where('teacher_id', $teacher->id)->findOrFail($id);

        if (isset($course))
        {
            if ($course->incourse_submit != 0)
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
