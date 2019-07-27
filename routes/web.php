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

Route::get('/home', 'HomeController@index')->name('home');

// Register Route Group
Route::group(['as'=>'register.','prefix' => 'register', 'namespace' => 'Register', 'middleware' => ['auth', 'register'] ], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('dept', 'DeptController');
    Route::resource('hall', 'HallController');
    Route::resource('session', 'SessionController');
    Route::resource('semester', 'SemesterController');
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

});

// Teacher Route Group
Route::group(['as'=>'teacher.','prefix' => 'teacher', 'namespace' => 'Teacher', 'middleware' => ['auth', 'teacher'] ], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

});


// Student Route Group
Route::group(['as'=>'student.','prefix' => 'student', 'namespace' => 'Student', 'middleware' => ['auth', 'student'] ], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

});