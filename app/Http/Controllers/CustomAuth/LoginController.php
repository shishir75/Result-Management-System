<?php

namespace App\Http\Controllers\CustomAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    protected $redirectTo;

    public function __construct()
    {
        if (Auth::check() && Auth::user()->role->id == 1)
        {
            $this->redirectTo = route('register.dashboard');

        } elseif (Auth::check() && Auth::user()->role->id == 2)
        {
            $this->redirectTo = route('exam_controller.dashboard');

        } elseif (Auth::check() && Auth::user()->role->id == 3)
        {
            $this->redirectTo = route('dept_office.dashboard');

        } elseif (Auth::check() && Auth::user()->role->id == 4)
        {
            $this->redirectTo = route('teacher.dashboard');

        } else
        {
            $this->redirectTo = route('student.dashboard');

        }

        $this->middleware('guest');
    }

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
            return redirect()->$this->redirectTo;

        } else {

            return redirect()->back()->withInput()->with('error', "Your Email and Password doesn't match!");
        }

    }
}
