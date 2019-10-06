<?php

namespace App\Http\Controllers\Exam_Controller;

use App\Models\Dept;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeptController extends Controller
{
    public function index()
    {
        $depts = Dept::all();
        return view('exam_controller.dept.index', compact('depts'));
    }
}
