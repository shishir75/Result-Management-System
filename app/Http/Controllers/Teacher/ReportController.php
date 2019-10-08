<?php

namespace App\Http\Controllers\Teacher;

use App\Models\CourseTeacher;
use App\Models\Report;
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

class ReportController extends Controller
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
        if (($teacher->id !== $course->teacher_id) || $course->course->is_lab === 0)
        {
            Toastr::error('You are not authorized to access this!!', 'Error');
            return redirect()->route('teacher.course.index');
        }

        $existing_reports = Report::where('session_id', $course->session->id)->where('course_id', $course->course->id)->where('teacher_id', $course->teacher->id)->distinct()->get('report_no');

        return view('teacher.report.create', compact('students', 'course', 'semester', 'year', 'existing_reports'));
    }

    public function store(Request $request)
    {
        $inputs = $request->except('_token');
        $rules = [
            'report_marks' => 'required | min:0 | max:10',
            'report_no' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $report_marks = $request->input('report_marks');
        $report_no = $request->input('report_no');
        $course_teacher_id = $request->input('course_teacher');

        $course = CourseTeacher::with('session', 'course', 'teacher','dept')->where('id', $course_teacher_id)->first();


        $user_name = Auth::user()->name;
        $teacher = Teacher::where('name', $user_name)->first();
        if (($teacher->id !== $course->teacher_id) || $course->course->is_lab === 0)
        {
            Toastr::error('You are not authorized to access this!!', 'Error');
            return redirect()->route('teacher.course.index');
        }

        $check = Report::distinct('report_no')->where('session_id', $course->session->id)->where('course_id', $course->course->id)->where('report_no', $report_no)->count('report_no');

        if ($check == 0)
        {
            foreach ($report_marks as $student_id => $value)
            {
                $report = new Report();
                $report->student_id = $student_id;
                $report->marks = $value;
                $report->course_id = $course->course->id;
                $report->session_id = $course->session->id;
                $report->teacher_id = $course->teacher->id;
                $report->report_no = $report_no;
                $report->save();
            }

            Toastr::success("Report Marks added successfully!", "Success");
            return redirect()->route('teacher.report.show',[$course->session->id,$course->course->id, $course->teacher->id]);

        } else {
            Toastr::error("Report Marks already added!", "Error");
            return redirect()->route('teacher.report.show',[$course->session->id,$course->course->id, $course->teacher->id]);
        }
    }


    public function show($session_id,$course_id, $teacher_id)
    {
        $report_nos = Report::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('report_no');
        $students_data = Report::with('student', 'course', 'teacher', 'session')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get(['student_id', 'session_id', 'course_id', 'teacher_id']);

        if (count($students_data) < 1)
        {
            Toastr::error("No Report Marks added! Please Add Report Marks!!", "Error");
            return redirect()->back();

        } else {

            $check_submit = CourseTeacher::where('course_id', $course_id)->where('session_id', $session_id)->where('dept_id', $students_data[0]->course->dept_id)->first();

            if (count($report_nos) < 1)
            {
                Toastr::error("No Report Marks added! Please Add Report Marks!!", "Error");
                return redirect()->back();
            } else {
                return view('teacher.report.show', compact('students_data', 'report_nos', 'session_id', 'course_id', 'teacher_id', 'check_submit'));
            }

        }
    }


    public function show_all($session_id,$course_id, $teacher_id)
    {
        $reports = Report::with('session', 'course', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->orderBy('report_no', 'asc')->get(['report_no', 'session_id', 'course_id', 'teacher_id']);
        $report_nos = Report::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('report_no');

        if (count($report_nos) < 1)
        {
            Toastr::error("No Report Marks added! Please Add Report Marks!!", "Error");
            return redirect()->back();
        }
        return view('teacher.report.show_all', compact('reports'));
    }


    public function edit_by_report_no($session_id,$course_id, $teacher_id, $report_no)
    {
        $reports = Report::with('session', 'course', 'student', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('report_no', $report_no)->orderBy('student_id', 'asc')->get();

        return view('teacher.report.edit', compact('reports'));
    }

    public function update_by_report_no(Request $request, $session_id,$course_id, $teacher_id, $report_no)
    {
        $inputs = $request->except('_token');
        $rules = [
            'report_marks' => 'required',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $report_marks = $request->input('report_marks');

        foreach ($report_marks as $student_id => $marks)
        {
            $report = Report::where('session_id', $session_id)->where('course_id', $course_id)->where('teacher_id', $teacher_id)->where('report_no', $report_no)->where('student_id', $student_id)->first();
            $report->marks = $marks;
            $report->save();
        }

        Toastr::success("Report Marks updated successfully!", "Success");
        return redirect()->route('teacher.report.show', [ $session_id,$course_id, $teacher_id]);
    }

    public function delete_by_report_no($session_id,$course_id, $teacher_id, $report_no)
    {
        $reports = Report::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('report_no', $report_no)->get();
        foreach ($reports as $report)
        {
            $report->delete();
        }

        Toastr::success("Report have been deleted successfully!", "Success");

        $report_nos = Report::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('report_no');
        if (count($report_nos) < 1)
        {
            return redirect()->route('teacher.course.index');
        } else {
            return redirect()->back();
        }
    }
}
