<?php

namespace App\Http\Controllers\CustomAuth;

use App\Models\Dept;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
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


    public function showRegistrationForm()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $inputs = $request->except('_token');
        $rules = [
            'role_id' => 'required | int | max:255',
            'name' => 'required | string | max:255',
            'email' => 'required | string | email | max:255 | unique:users',
            'password' => 'required | string | min:8 | confirmed',
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role_id = $request->input('role_id');
        $name = $request->input('name');

        if ($role_id == 3)
        {
            $check_dept = Dept::where('name', $name)->count();
            $duplicate_check = User::where('name', $name)->count();

            if ($check_dept == 0)
            {
                session()->flash('error', 'You are not authorised to register! Please contact Register Office.');
                return redirect()->back();

            } elseif ($check_dept == 1)
            {
                if ($duplicate_check == 0)
                {
                    $user = new User();
                    $user->role_id = $role_id;
                    $user->name = $name;
                    $user->email = $request->input('email');
                    $user->password = Hash::make($request->input('password'));
                    $user->save();

                    session()->flash('success', 'You have successfully registered!!');
                    return redirect()->route('login');

                } else {
                    session()->flash('error', 'You are already registered! Please try to login!!');
                    return redirect()->back();
                }

            } else {
                session()->flash('error', 'Department Registration Error!!');
                return redirect()->back();
            }

        } elseif ($role_id == 4)
        {
            $check_teacher = Teacher::where('name', $name)->count();
            $duplicate_check = User::where('name', $name)->count();

            if ($check_teacher == 0)
            {
                session()->flash('error', 'You are not authorised to register! Please contact Dept Office.');
                return redirect()->back();

            } elseif ($check_teacher == 1)
            {
                if ($duplicate_check == 0)
                {
                    $user = new User();
                    $user->role_id = $role_id;
                    $user->name = $name;
                    $user->email = $request->input('email');
                    $user->password = Hash::make($request->input('password'));
                    $user->save();

                    session()->flash('success', 'You have successfully registered! Please Login!!');
                    return redirect()->route('login');

                } else {
                    session()->flash('error', 'You are already registered! Please try to login!!');
                    return redirect()->back();
                }

            } else {
                session()->flash('error', 'Teacher Registration Error!!');
                return redirect()->back();
            }

        } elseif ($role_id == 5)
        {
            $check_student = Student::where('name', $name)->count();
            $duplicate_check = User::where('name', $name)->count();

            if ($check_student == 0)
            {
                session()->flash('error', 'You are not authorised to register! Please contact Dept Office.');
                return redirect()->back();

            } elseif ($check_student == 1)
            {
                if ($duplicate_check == 0)
                {
                    $user = new User();
                    $user->role_id = $role_id;
                    $user->name = $name;
                    $user->email = $request->input('email');
                    $user->password = Hash::make($request->input('password'));
                    $user->save();

                    session()->flash('success', 'You have successfully registered! Please Login!!');
                    return redirect()->route('login');

                } else {
                    session()->flash('error', 'You are already registered! Please try to login!!');
                    return redirect()->back();
                }

            } else {
                session()->flash('error', 'Student Registration Error!!');
                return redirect()->back();
            }
        }

        else
        {
            session()->flash('error', 'Role Error!!');
            return redirect()->back();
        }
    }
}
