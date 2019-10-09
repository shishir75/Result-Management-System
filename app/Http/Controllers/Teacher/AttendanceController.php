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
                $attendance->session_id = $course->session->id;
                $attendance->teacher_id = $course->teacher->id;
                $attendance->attend_date = $date;
                $attendance->save();
            }

            Toastr::success("Attendance taken successfully!", "Success");
            return redirect()->route('teacher.attendance.show_all_attend',[$course->session->id,$course->course->id, $course->teacher->id]);

        } else {
            Toastr::error("Attendance already taken!", "Error");
            return redirect()->route('teacher.attendance.show_all_attend',[$course->session->id,$course->course->id, $course->teacher->id]);
        }

    }


    public function show_all($session_id,$course_id, $teacher_id)
    {
        $attendances = Attendance::with('session', 'course', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->orderBy('attend_date', 'desc')->get(['attend_date', 'session_id', 'course_id', 'teacher_id']);

        if (count($attendances) < 1)
        {
            Toastr::error("No Attendance added! Please Add Attendance!!", "Error");
            return redirect()->back();

        } else {
            return view('teacher.attendance.show_all', compact('attendances'));
        }


    }

    public function show_all_attend($session_id,$course_id, $teacher_id)
    {
        $dates = Attendance::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('attend_date');
        $students_data = Attendance::with('student', 'course', 'teacher', 'session')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get(['student_id', 'session_id', 'course_id', 'teacher_id']);

        if (count($students_data) < 1)
        {
            Toastr::error("No Attendance added! Please Add Attendance!!", "Error");
            return redirect()->back();

        } else {

            $check_submit = CourseTeacher::where('course_id', $course_id)->where('session_id', $session_id)->where('dept_id', $students_data[0]->course->dept_id)->first();

            if (count($dates) < 1)
            {
                Toastr::error("No Attendance added! Please Add Attendance!!", "Error");
                return redirect()->back();
            } else {
                return view('teacher.attendance.show_all_attend', compact('students_data', 'dates', 'session_id', 'course_id', 'teacher_id', 'check_submit'));
            }

        }

    }

    public function show_by_date($session_id,$course_id, $teacher_id, $attend_date)
    {
        $attendances = Attendance::with('session', 'course', 'student', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('attend_date', $attend_date)->orderBy('student_id', 'asc')->get();

        return view('teacher.attendance.show', compact('attendances'));
    }

    public function edit_by_date($session_id,$course_id, $teacher_id, $attend_date)
    {
        $attendances = Attendance::with('session', 'course', 'student', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('attend_date', $attend_date)->orderBy('student_id', 'asc')->get();

        return view('teacher.attendance.edit', compact('attendances'));
    }

    public function update_by_date(Request $request, $session_id,$course_id, $teacher_id, $attend_date)
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

        foreach ($attend as $student_id => $value)
        {
            $attendance = Attendance::where('session_id', $session_id)->where('course_id', $course_id)->where('teacher_id', $teacher_id)->where('attend_date', $attend_date)->where('student_id', $student_id)->first();
            $attendance->attend = $value;
            $attendance->save();
        }

        Toastr::success("Attendance updated successfully!", "Success");
        return redirect()->route('teacher.attendance.show_by_date', [ $attendance->session_id, $attendance->course_id, $attendance->teacher_id, $attendance->attend_date]);
    }






}
