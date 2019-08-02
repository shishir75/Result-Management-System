<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Attendance;
use App\Models\CourseTeacher;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Year;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index($course_teacher_id)
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

        return view('teacher.attendance.create', compact('students', 'course', 'semester', 'year'));

    }

    public function store(Request $request)
    {
        $inputs = $request->except('_token');
        $rules = [
          'attend' => 'required',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $attend = $request->input('attend');
        $course_teacher_id = $request->input('course_teacher');

        $course = CourseTeacher::with('session', 'course', 'teacher','dept')->where('id', $course_teacher_id)->first();

        $date = Carbon::now()->format('Y-m-d');

        $user_name = Auth::user()->name;
        $teacher = Teacher::where('name', $user_name)->first();
        if ($teacher->id !== $course->teacher_id )
        {
            return redirect()->route('teacher.course.index');
        }

        $check = Attendance::distinct('attend_date')->where('course_id', $course->course->id)->where('attend_date', $date)->count('attend_date');

        if ($check == 0)
        {
            foreach ($attend as $student_id => $value)
            {
                $attendance = new Attendance();
                $attendance->student_id = $student_id;
                $attendance->attend = $value;
                $attendance->course_id = $course->course->id;
                $attendance->teacher_id = $course->teacher->id;
                $attendance->attend_date = $date;
                $attendance->save();
            }

            Toastr::success("Attendance taken successfully!", "Success");
            return redirect()->route('teacher.course.index');

        } else {
            Toastr::error("Attendance already taken!", "Error");
            return redirect()->route('teacher.course.index');
        }



    }




}
