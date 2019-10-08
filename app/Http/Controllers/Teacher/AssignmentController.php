<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Assignment;
use App\Models\CourseTeacher;
use App\Models\Semester;
use App\Models\Session;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Year;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
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

        $existing_assignments = Assignment::where('session_id', $course->session->id)->where('course_id', $course->course->id)->where('teacher_id', $course->teacher->id)->distinct()->get('assignment_no');

        return view('teacher.assignment.create', compact('students', 'course', 'semester', 'year', 'existing_assignments'));
    }

    public function store(Request $request)
    {
        $inputs = $request->except('_token');
        $rules = [
            'assignment_marks' => 'required | min:0 | max:20',
            'assignment_no' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $assignment_marks = $request->input('assignment_marks');
        $assignment_no = $request->input('assignment_no');
        $course_teacher_id = $request->input('course_teacher');

        $course = CourseTeacher::with('session', 'course', 'teacher','dept')->where('id', $course_teacher_id)->first();


        $user_name = Auth::user()->name;
        $teacher = Teacher::where('name', $user_name)->first();
        if ($teacher->id !== $course->teacher_id )
        {
            return redirect()->route('teacher.course.index');
        }

        $check = Assignment::distinct('assignment_no')->where('session_id', $course->session->id)->where('course_id', $course->course->id)->where('assignment_no', $assignment_no)->count('assignment_no');

        if ($check == 0)
        {
            foreach ($assignment_marks as $student_id => $value)
            {
                $assignment = new Assignment();
                $assignment->student_id = $student_id;
                $assignment->marks = $value;
                $assignment->course_id = $course->course->id;
                $assignment->session_id = $course->session->id;
                $assignment->teacher_id = $course->teacher->id;
                $assignment->assignment_no = $assignment_no;
                $assignment->save();
            }

            Toastr::success("Assignment Marks added successfully!", "Success");
            return redirect()->route('teacher.assignment.show',[$course->session->id,$course->course->id, $course->teacher->id]);

        } else {
            Toastr::error("Assignment Marks already added!", "Error");
            return redirect()->route('teacher.assignment.show',[$course->session->id,$course->course->id, $course->teacher->id]);
        }
    }


    public function show($session_id,$course_id, $teacher_id)
    {
        $assignment_nos = Assignment::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('assignment_no');
        $students_data = Assignment::with('student', 'course', 'teacher', 'session')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get(['student_id', 'session_id', 'course_id', 'teacher_id']);

        if (count($students_data) < 1)
        {
            Toastr::error("No Assignment Marks added! Please Add Assignment Marks!!", "Error");
            return redirect()->back();

        } else {

            $check_submit = CourseTeacher::where('course_id', $course_id)->where('session_id', $session_id)->where('dept_id', $students_data[0]->course->dept_id)->first();

            if (count($assignment_nos) < 1)
            {
                Toastr::error("No Assignment Marks added! Please Add Tutorial Marks!!", "Error");
                return redirect()->back();
            } else {
                return view('teacher.assignment.show', compact('students_data', 'assignment_nos', 'session_id', 'course_id', 'teacher_id', 'check_submit'));
            }

        }
    }


    public function show_all($session_id,$course_id, $teacher_id)
    {
        $assignments = Assignment::with('session', 'course', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->orderBy('assignment_no', 'asc')->get(['assignment_no', 'session_id', 'course_id', 'teacher_id']);
        $assignment_nos = Assignment::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('assignment_no');

        if (count($assignment_nos) < 1)
        {
            Toastr::error("No Assignment Marks added! Please Add Assignment Marks!!", "Error");
            return redirect()->back();
        }
        return view('teacher.assignment.show_all', compact('assignments'));
    }


    public function edit_by_assignment_no($session_id,$course_id, $teacher_id, $assignment_no)
    {
        $assignments = Assignment::with('session', 'course', 'student', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('assignment_no', $assignment_no)->orderBy('student_id', 'asc')->get();

        return view('teacher.assignment.edit', compact('assignments'));
    }

    public function update_by_assignment_no(Request $request, $session_id,$course_id, $teacher_id, $assignment_no)
    {
        $inputs = $request->except('_token');
        $rules = [
            'assignment_marks' => 'required',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $assignment_marks = $request->input('assignment_marks');

        foreach ($assignment_marks as $student_id => $marks)
        {
            $assignment = Assignment::where('session_id', $session_id)->where('course_id', $course_id)->where('teacher_id', $teacher_id)->where('assignment_no', $assignment_no)->where('student_id', $student_id)->first();
            $assignment->marks = $marks;
            $assignment->save();
        }

        Toastr::success("Assignment Marks updated successfully!", "Success");
        return redirect()->route('teacher.assignment.show', [ $session_id,$course_id, $teacher_id]);
    }

    public function delete_by_assignment_no($session_id,$course_id, $teacher_id, $assignment_no)
    {
        $assignments = Assignment::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('assignment_no', $assignment_no)->get();
        foreach ($assignments as $assignment)
        {
            $assignment->delete();
        }

        Toastr::success("Assignment have been deleted successfully!", "Success");

        $assignment_nos = Assignment::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('assignment_no');
        if (count($assignment_nos) < 1)
        {
            return redirect()->route('teacher.course.index');
        } else {
            return redirect()->back();
        }
    }

}
