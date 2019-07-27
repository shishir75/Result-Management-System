<?php

namespace App\Http\Controllers\CustomAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $inputs = $request->except('_token');
        $rules = [
            'email' => 'required | email',
            'password' => 'required',
        ];
        $validator = Validator::make($inputs, $rules);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            if (Auth::check() && Auth::user()->role->id == 1)
            {
                return redirect()->route('register.dashboard');

            } elseif (Auth::check() && Auth::user()->role->id == 2)
            {
                return redirect()->route('exam_controller.dashboard');

            } elseif (Auth::check() && Auth::user()->role->id == 3)
            {
                return redirect()->route('dept_office.dashboard');

            } elseif (Auth::check() && Auth::user()->role->id == 4)
            {
                return redirect()->route('teacher.dashboard');

            } else
            {
                return redirect()->route('student.dashboard');

            }

        } else {

            return redirect()->back()->withInput()->with('error', "Your Email and Password doesn't match!");
        }

    }
}
