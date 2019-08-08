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
    Route::get('course/attendance/{course_teacher_id}', 'AttendanceController@create')->name('attendance.create');
    Route::post('course/attendance', 'AttendanceController@store')->name('attendance.store');
    Route::get('course/attendance/{session_id}/{course_id}/{teacher_id}', 'AttendanceController@show_all')->name('attendance.show_all');
    Route::get('course/attendance/all/{session_id}/{course_id}/{teacher_id}', 'AttendanceController@show_all_attend')->name('attendance.show_all_attend');
    Route::get('course/attendance/{session_id}/{course_id}/{teacher_id}/{attend_date}', 'AttendanceController@show_by_date')->name('attendance.show_by_date');
    Route::get('course/attendance/{session_id}/{course_id}/{teacher_id}/{attend_date}/edit', 'AttendanceController@edit_by_date')->name('attendance.edit_by_date');
    Route::put('course/attendance/{session_id}/{course_id}/{teacher_id}/{attend_date}', 'AttendanceController@update_by_date')->name('attendance.update_by_date');

    Route::get('course/tutorial/{course_teacher_id}', 'TutorialController@create')->name('tutorial.create');
    Route::post('course/tutorial', 'TutorialController@store')->name('tutorial.store');
    Route::get('course/tutorial/{session_id}/{course_id}/{teacher_id}', 'TutorialController@show')->name('tutorial.show');
    Route::get('course/tutorial/all/{session_id}/{course_id}/{teacher_id}', 'TutorialController@show_all')->name('tutorial.show_all');
    Route::get('course/tutorial/{session_id}/{course_id}/{teacher_id}/{tutorial_no}/edit', 'TutorialController@edit_by_tutorial_no')->name('tutorial.edit_by_tutorial_no');
    Route::put('course/tutorial/{session_id}/{course_id}/{teacher_id}/{tutorial_no}', 'TutorialController@update_by_tutorial_no')->name('tutorial.update_by_tutorial_no');
    Route::delete('course/tutorial/{session_id}/{course_id}/{teacher_id}/{tutorial_no}', 'TutorialController@delete_by_tutorial_no')->name('tutorial.delete_by_tutorial_no');


    Route::get('course/assignment/{course_teacher_id}', 'AssignmentController@create')->name('assignment.create');
    Route::post('course/assignment', 'AssignmentController@store')->name('assignment.store');
    Route::get('course/assignment/{session_id}/{course_id}/{teacher_id}', 'AssignmentController@show')->name('assignment.show');
    Route::get('course/assignment/all/{session_id}/{course_id}/{teacher_id}', 'AssignmentController@show_all')->name('assignment.show_all');
    Route::get('course/assignment/{session_id}/{course_id}/{teacher_id}/{assignment_no}/edit', 'AssignmentController@edit_by_assignment_no')->name('assignment.edit_by_assignment_no');
    Route::put('course/assignment/{session_id}/{course_id}/{teacher_id}/{assignment_no}', 'AssignmentController@update_by_assignment_no')->name('assignment.update_by_assignment_no');
    Route::delete('course/assignment/{session_id}/{course_id}/{teacher_id}/{assignment_no}', 'AssignmentController@delete_by_assignment_no')->name('assignment.delete_by_assignment_no');

    Route::get('course/report/{course_teacher_id}', 'ReportController@create')->name('report.create');
    Route::post('course/report', 'ReportController@store')->name('report.store');
    Route::get('course/report/{session_id}/{course_id}/{teacher_id}', 'ReportController@show')->name('report.show');
    Route::get('course/report/all/{session_id}/{course_id}/{teacher_id}', 'ReportController@show_all')->name('report.show_all');
    Route::get('course/report/{session_id}/{course_id}/{teacher_id}/{report_no}/edit', 'ReportController@edit_by_report_no')->name('report.edit_by_report_no');
    Route::put('course/report/{session_id}/{course_id}/{teacher_id}/{report_no}', 'ReportController@update_by_report_no')->name('report.update_by_report_no');
    Route::delete('course/report/{session_id}/{course_id}/{teacher_id}/{report_no}', 'ReportController@delete_by_report_no')->name('report.delete_by_report_no');

    Route::get('course/quiz/{course_teacher_id}', 'QuizController@create')->name('quiz.create');
    Route::post('course/quiz', 'QuizController@store')->name('quiz.store');
    Route::get('course/quiz/{session_id}/{course_id}/{teacher_id}', 'QuizController@show')->name('quiz.show');
    Route::get('course/quiz/all/{session_id}/{course_id}/{teacher_id}', 'QuizController@show_all')->name('quiz.show_all');
    Route::get('course/quiz/{session_id}/{course_id}/{teacher_id}/{quiz_no}/edit', 'QuizController@edit_by_quiz_no')->name('quiz.edit_by_quiz_no');
    Route::put('course/quiz/{session_id}/{course_id}/{teacher_id}/{quiz_no}', 'QuizController@update_by_quiz_no')->name('quiz.update_by_quiz_no');
    Route::delete('course/quiz/{session_id}/{course_id}/{teacher_id}/{quiz_no}', 'QuizController@delete_by_quiz_no')->name('quiz.delete_by_quiz_no');

});


// Student Route Group
Route::group(['as'=>'student.','prefix' => 'student', 'namespace' => 'Student', 'middleware' => ['auth', 'student'] ], function (){

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('courses', 'CourseController@index')->name('course.index');

});