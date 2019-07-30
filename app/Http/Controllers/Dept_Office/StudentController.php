<?php

namespace App\Http\Controllers\Dept_Office;

use App\Imports\StudentsImport;
use App\Models\Dept;
use App\Models\Hall;
use App\Models\Session;
use App\Models\Student;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dept = Dept::select('id', 'name')->where('name', Auth::user()->name)->first();
        $students = Student::with('dept')->latest()->where('dept_id', $dept->id)->orderBy('session', 'desc')->orderBy('class_roll', 'asc')->get();
        return view('dept_office.student.index', compact('students', 'dept'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dept_office.student.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')){
            $file = $request->file('file');

            $data = Excel::toArray(new StudentsImport(), $file);

            $dept = Dept::select('id')->where('name', Auth::user()->name)->first();

            if (!empty($data)  && count($data) > 0)
            {

                foreach ($data as $rows)
                {
                    foreach ($rows as $key => $value)
                    {
                        if ($key > 3)
                        {
                            $student = new Student();

                            $check = Student::where('session', $value[2])->where('class_roll', $value[3])->count();

                            if ($check > 0)
                            {
                                continue;

                            } else {

                                $student->name = $value[1];
                                $student->session = $value[2];
                                $student->class_roll = $value[3];
                                $student->reg_no = $value[4];
                                $student->exam_roll = $value[5];
                                $student->hall = $value[6];
                                $student->father_name = $value[7];
                                $student->mother_name = $value[8];
                                $student->dept_id = $dept->id;
                                $student->save();
                            }

                        }
                    }
                }

                Toastr::success('Students added successfully', 'Success');
                return redirect()->route('dept_office.student.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $sessions = Session::latest()->get();
        $halls = Hall::all();
        return view('dept_office.student.edit', compact('student', 'sessions', 'halls'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $inputs = $request->except('_token');
        $rules = [
            'name' => 'required',
            'session' => 'required',
            'class_roll' => 'required | integer',
            'reg_no' => 'required | integer',
            'exam_roll' => 'required | integer',
            'hall' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $session = $request->input('session');
        $class_roll = $request->input('class_roll');
        $reg_no = $request->input('reg_no');
        $exam_roll = $request->input('exam_roll');

        $check = Student::where('session', $session)->where('class_roll', $class_roll)->where('id', '!=', $student->id)->count();

        if ($check == 0)
        {
            $student->name = $request->input('name');
            $student->session = $session;
            $student->class_roll = $class_roll;
            $student->reg_no = $reg_no;
            $student->exam_roll = $exam_roll;
            $student->hall = $request->input('hall');
            $student->father_name = $request->input('father_name');
            $student->mother_name = $request->input('mother_name');
            $student->save();

            Toastr::success('Students updated successfully', 'Success');
            return redirect()->route('dept_office.student.index');

        } else {
            Toastr::error('Student already exits!!!', 'Error');
            return redirect()->back();
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        Toastr::success('Students deleted successfully', 'Success');
        return redirect()->route('dept_office.student.index');
    }
}
