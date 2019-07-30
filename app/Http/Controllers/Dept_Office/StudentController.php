<?php

namespace App\Http\Controllers\Dept_Office;

use App\Imports\StudentsImport;
use App\Models\Dept;
use App\Models\Student;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $students = Student::with('dept')->latest()->where('dept_id', $dept->id)->get();
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
                                break;

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
