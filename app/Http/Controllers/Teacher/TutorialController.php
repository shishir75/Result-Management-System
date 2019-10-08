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

        $existing_tutorials = Tutorial::where('session_id', $course->session->id)->where('course_id', $course->course->id)->where('teacher_id', $course->teacher->id)->distinct()->get('tutorial_no'); // array of arrays instead of objects

        return view('teacher.tutorial.create', compact('students', 'course', 'semester', 'year', 'existing_tutorials'));
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
            return redirect()->route('teacher.tutorial.show',[$course->session->id,$course->course->id, $course->teacher->id]);

        } else {
            Toastr::error("Tutorial Marks already added!", "Error");
            return redirect()->route('teacher.tutorial.show',[$course->session->id,$course->course->id, $course->teacher->id]);
        }
    }


    public function show($session_id,$course_id, $teacher_id)
    {
        $students_data = Tutorial::with('student', 'course', 'teacher', 'session')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get(['student_id', 'session_id', 'course_id', 'teacher_id']);

        $tutorial_nos = Tutorial::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('tutorial_no');

        if (count($students_data) < 1)
        {
            Toastr::error("No Tutorial Marks added! Please Add Tutorial Marks!!", "Error");
            return redirect()->back();

        } else {

            $check_submit = CourseTeacher::where('course_id', $course_id)->where('session_id', $session_id)->where('dept_id', $students_data[0]->course->dept_id)->first();

            if (count($tutorial_nos) < 1)
            {
                Toastr::error("No Tutorial Marks added! Please Add Tutorial Marks!!", "Error");
                return redirect()->back();
            } else {
                return view('teacher.tutorial.show', compact('students_data', 'tutorial_nos', 'session_id', 'course_id', 'teacher_id', 'check_submit'));
            }

        }

    }


    public function show_all($session_id,$course_id, $teacher_id)
    {
        $tutorials = Tutorial::with('session', 'course', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->orderBy('tutorial_no', 'asc')->get(['tutorial_no', 'session_id', 'course_id', 'teacher_id']);
        $tutorial_nos = Tutorial::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('tutorial_no');

        if (count($tutorial_nos) < 1)
        {
            Toastr::error("No Tutorial Marks added! Please Add Tutorial Marks!!", "Error");
            return redirect()->back();
        }
        return view('teacher.tutorial.show_all', compact('tutorials'));
    }


    public function edit_by_tutorial_no($session_id,$course_id, $teacher_id, $tutorial_no)
    {
        $tutorials = Tutorial::with('session', 'course', 'student', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('tutorial_no', $tutorial_no)->orderBy('student_id', 'asc')->get();

        return view('teacher.tutorial.edit', compact('tutorials'));
    }

    public function update_by_tutorial_no(Request $request, $session_id,$course_id, $teacher_id, $tutorial_no)
    {
        $inputs = $request->except('_token');
        $rules = [
            'tutorial_marks' => 'required',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tutorial_marks = $request->input('tutorial_marks');

        foreach ($tutorial_marks as $student_id => $marks)
        {
            $tutorial = Tutorial::where('session_id', $session_id)->where('course_id', $course_id)->where('teacher_id', $teacher_id)->where('tutorial_no', $tutorial_no)->where('student_id', $student_id)->first();
            $tutorial->marks = $marks;
            $tutorial->save();
        }

        Toastr::success("Tutorial Marks updated successfully!", "Success");
        return redirect()->route('teacher.tutorial.show', [ $session_id,$course_id, $teacher_id]);
    }

    public function delete_by_tutorial_no($session_id,$course_id, $teacher_id, $tutorial_no)
    {
        $tutorials = Tutorial::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('tutorial_no', $tutorial_no)->get();
        foreach ($tutorials as $tutorial)
        {
            $tutorial->delete();
        }

        Toastr::success("Tutorial have been deleted successfully!", "Success");

        $tutorial_nos = Tutorial::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('tutorial_no');
        if (count($tutorial_nos) < 1)
        {
            return redirect()->route('teacher.course.index');
        } else {
            return redirect()->back();
        }
    }



}
