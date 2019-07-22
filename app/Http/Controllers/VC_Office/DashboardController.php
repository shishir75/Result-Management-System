<?php

namespace App\Http\Controllers\VC_Office;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('vc_office.dashboard');
    }
}
