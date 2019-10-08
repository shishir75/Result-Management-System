<?php

namespace App\Http\Controllers\Teacher;

use App\Models\CourseTeacher;
use App\Models\Quiz;
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

class QuizController extends Controller
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

        $existing_quizzes = Quiz::where('session_id', $course->session->id)->where('course_id', $course->course->id)->where('teacher_id', $course->teacher->id)->distinct()->get('quiz_no');

        return view('teacher.quiz.create', compact('students', 'course', 'semester', 'year', 'existing_quizzes'));
    }

    public function store(Request $request)
    {
        $inputs = $request->except('_token');
        $rules = [
            'quiz_marks' => 'required | min:0 | max:10',
            'quiz_no' => 'required | integer',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $quiz_marks = $request->input('quiz_marks');
        $quiz_no = $request->input('quiz_no');
        $course_teacher_id = $request->input('course_teacher');

        $course = CourseTeacher::with('session', 'course', 'teacher','dept')->where('id', $course_teacher_id)->first();


        $user_name = Auth::user()->name;
        $teacher = Teacher::where('name', $user_name)->first();
        if (($teacher->id !== $course->teacher_id) || $course->course->is_lab === 0)
        {
            Toastr::error('You are not authorized to access this!!', 'Error');
            return redirect()->route('teacher.course.index');
        }

        $check = Quiz::distinct('report_no')->where('session_id', $course->session->id)->where('course_id', $course->course->id)->where('quiz_no', $quiz_no)->count('quiz_no');

        if ($check == 0)
        {
            foreach ($quiz_marks as $student_id => $value)
            {
                $quiz = new Quiz();
                $quiz->student_id = $student_id;
                $quiz->marks = $value;
                $quiz->course_id = $course->course->id;
                $quiz->session_id = $course->session->id;
                $quiz->teacher_id = $course->teacher->id;
                $quiz->quiz_no = $quiz_no;
                $quiz->save();
            }

            Toastr::success("Quiz / Viva Marks added successfully!", "Success");
            return redirect()->route('teacher.quiz.show',[$course->session->id,$course->course->id, $course->teacher->id]);

        } else {
            Toastr::error("Quiz / Viva Marks already added!", "Error");
            return redirect()->route('teacher.quiz.show',[$course->session->id,$course->course->id, $course->teacher->id]);
        }
    }


    public function show($session_id,$course_id, $teacher_id)
    {
        $quiz_nos = Quiz::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('quiz_no');
        $students_data = Quiz::with('student', 'course', 'teacher', 'session')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get(['student_id', 'session_id', 'course_id', 'teacher_id']);

        if (count($students_data) < 1)
        {
            Toastr::error("No Quiz / Viva Marks added! Please Add Quiz / Viva Marks!!", "Error");
            return redirect()->back();

        } else {

            $check_submit = CourseTeacher::where('course_id', $course_id)->where('session_id', $session_id)->where('dept_id', $students_data[0]->course->dept_id)->first();

            if (count($quiz_nos) < 1)
            {
                Toastr::error("No Quiz / Viva Marks added! Please Add Quiz / Viva Marks!!", "Error");
                return redirect()->back();
            } else {
                return view('teacher.quiz.show', compact('students_data', 'quiz_nos', 'session_id', 'course_id', 'teacher_id', 'check_submit'));
            }

        }

    }


    public function show_all($session_id,$course_id, $teacher_id)
    {
        $quizzes = Quiz::with('session', 'course', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->orderBy('quiz_no', 'asc')->get(['quiz_no', 'session_id', 'course_id', 'teacher_id']);
        $quiz_nos = Quiz::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('quiz_no');

        if (count($quiz_nos) < 1)
        {
            Toastr::error("No Quiz / Viva Marks added! Please Add Quiz / Viva Marks!!", "Error");
            return redirect()->back();
        }
        return view('teacher.quiz.show_all', compact('quizzes'));
    }


    public function edit_by_quiz_no($session_id,$course_id, $teacher_id, $quiz_no)
    {
        $quizzes = Quiz::with('session', 'course', 'student', 'teacher')->where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('quiz_no', $quiz_no)->orderBy('student_id', 'asc')->get();

        return view('teacher.quiz.edit', compact('quizzes'));
    }

    public function update_by_quiz_no(Request $request, $session_id,$course_id, $teacher_id, $quiz_no)
    {
        $inputs = $request->except('_token');
        $rules = [
            'quiz_marks' => 'required',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $quiz_marks = $request->input('quiz_marks');

        foreach ($quiz_marks as $student_id => $marks)
        {
            $quiz = Quiz::where('session_id', $session_id)->where('course_id', $course_id)->where('teacher_id', $teacher_id)->where('quiz_no', $quiz_no)->where('student_id', $student_id)->first();
            $quiz->marks = $marks;
            $quiz->save();
        }

        Toastr::success("Quiz / Viva Marks updated successfully!", "Success");
        return redirect()->route('teacher.quiz.show', [ $session_id,$course_id, $teacher_id]);
    }

    public function delete_by_quiz_no($session_id,$course_id, $teacher_id, $quiz_no)
    {
        $quizzes = Quiz::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('quiz_no', $quiz_no)->get();
        foreach ($quizzes as $quiz)
        {
            $quiz->delete();
        }

        Toastr::success("Quiz / Viva have been deleted successfully!", "Success");

        $quiz_nos = Quiz::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->distinct()->get('quiz_no');
        if (count($quiz_nos) < 1)
        {
            return redirect()->route('teacher.course.index');
        } else {
            return redirect()->back();
        }

    }
}
