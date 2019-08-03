<?php

namespace App\Http\Controllers\Teacher;

use App\Models\CourseTeacher;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tutorial;
use App\Models\Year;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TutorialController extends Controller
{
    public function create($course_teacher_id)
    {
        $course = CourseTeacher::with('session', 'course', 'teacher','dept')->where('id', $course_teacher_id)->first();
        $session = Session::findOrFail($course->session_id);
        $students = Student::where('session', $session->name)->orderBy('class_roll')->get();

        $semester = Semester::where('id', $course->course->year_semester_id)->first();
        $year = Year::where('id', $course->course->year_semester_id)->first();

        $user_name = Auth::user()->name;
        $teacher = Teacher::where('name', $user_name)->first();
        if ($teacher->id !== $course->teacher_id )
        {
            return redirect()->route('teacher.course.index');
        }

        return view('teacher.tutorial.create', compact('students', 'course', 'semester', 'year'));
    }

    public function store(Request $request)
    {
        $inputs = $request->except('_token');
        $rules = [
            'tutorial_marks' => 'required | min:0 | max:20',
            'tutorial_no' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tutorial_marks = $request->input('tutorial_marks');
        $tutorial_no = $request->input('tutorial_no');
        $course_teacher_id = $request->input('course_teacher');

        $course = CourseTeacher::with('session', 'course', 'teacher','dept')->where('id', $course_teacher_id)->first();


        $user_name = Auth::user()->name;
        $teacher = Teacher::where('name', $user_name)->first();
        if ($teacher->id !== $course->teacher_id )
        {
            return redirect()->route('teacher.course.index');
        }

        $check = Tutorial::distinct('tutorial_no')->where('session_id', $course->session->id)->where('course_id', $course->course->id)->where('tutorial_no', $tutorial_no)->count('tutorial_no');

        if ($check == 0)
        {
            foreach ($tutorial_marks as $student_id => $value)
            {
                $tutorial = new Tutorial();
                $tutorial->student_id = $student_id;
                $tutorial->marks = $value;
                $tutorial->course_id = $course->course->id;
                $tutorial->session_id = $course->session->id;
                $tutorial->teacher_id = $course->teacher->id;
                $tutorial->tutorial_no = $tutorial_no;
                $tutorial->save();
            }

            Toastr::success("Tutorial Marks added successfully!", "Success");
            return redirect()->route('teacher.course.index');

            //return redirect()->route('teacher.attendance.show_all_attend',[$course->session->id,$course->course->id, $course->teacher->id]);

        } else {
            Toastr::error("Tutorial Marks already added!", "Error");
            return redirect()->route('teacher.course.index');
            //return redirect()->route('teacher.attendance.show_all_attend',[$course->session->id,$course->course->id, $course->teacher->id]);
        }
    }
}
