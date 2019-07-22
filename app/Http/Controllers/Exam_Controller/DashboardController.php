<?php

namespace App\Http\Controllers\Exam_Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('exam_controller.dashboard');
    }
}
