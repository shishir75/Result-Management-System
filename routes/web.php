<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('register', 'CustomAuth\RegisterController@showRegistrationForm')->name('register')->middleware('web');
Route::post('register', 'CustomAuth\RegisterController@register')->middleware('web');

Route::get('login', 'CustomAuth\LoginController@showLoginForm')->name('login');
Route::post('login', 'CustomAuth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

// Register Route Group
Route::group(['as'=>'register.','prefix' => 'register', 'namespace' => 'Register', 'middleware' => ['auth', 'register'] ], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('dept', 'DeptController');
    Route::resource('hall', 'HallController');
    Route::resource('session', 'SessionController');
    Route::resource('designation', 'DesignationController');

});


// Exam Controller Route Group
Route::group(['as'=>'exam_controller.','prefix' => 'exam-controller', 'namespace' => 'Exam_Controller', 'middleware' => ['auth', 'examController'] ], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

});


// Dept. Office Route Group
Route::group(['as'=>'dept_office.','prefix' => 'dept-office', 'namespace' => 'Dept_Office', 'middleware' => ['auth', 'deptOffice'] ], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('teacher', 'TeacherController');
    Route::resource('course', 'CourseController');
    Route::resource('year-head', 'YearHeadController');
    Route::resource('student', 'StudentController');
    Route::resource('teacher-course', 'CourseTeacherController');
    Route::post('fetch-course', 'CourseTeacherController@fetch_course')->name('fetch_course');

});

// Teacher Route Group
Route::group(['as'=>'teacher.','prefix' => 'teacher', 'namespace' => 'Teacher', 'middleware' => ['auth', 'teacher'] ], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('course', 'CourseController@index')->name('course.index');
    Route::get('attendance/{course_teacher_id}', 'AttendanceController@create')->name('attendance.create');
    Route::post('attendance', 'AttendanceController@store')->name('attendance.store');
    Route::get('attendance/{session_id}/{course_id}/{teacher_id}', 'AttendanceController@show_all')->name('attendance.show_all');
    Route::get('attendance/all/{session_id}/{course_id}/{teacher_id}', 'AttendanceController@show_all_attend')->name('attendance.show_all_attend');
    Route::get('attendance/{session_id}/{course_id}/{teacher_id}/{attend_date}', 'AttendanceController@show_by_date')->name('attendance.show_by_date');
    Route::get('attendance/{session_id}/{course_id}/{teacher_id}/{attend_date}/edit', 'AttendanceController@edit_by_date')->name('attendance.edit_by_date');
    Route::put('attendance/{session_id}/{course_id}/{teacher_id}/{attend_date}', 'AttendanceController@update_by_date')->name('attendance.update_by_date');

});


// Student Route Group
Route::group(['as'=>'student.','prefix' => 'student', 'namespace' => 'Student', 'middleware' => ['auth', 'student'] ], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

});