<?php

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if(Auth::guard('admin')->check()) return redirect()->to('admin');
    if(Auth::guard('teacher')->check()) return redirect()->to('teacher');
    if(Auth::guard('student')->check()) return redirect()->to('student');
    return view('welcome');
})->name('login');

Route::get('/home', function () {
    if(Auth::guard('admin')->check()) return redirect()->to('admin');
    if(Auth::guard('teacher')->check()) return redirect()->to('teacher');
    if(Auth::guard('student')->check()) return redirect()->to('student');
    return view('welcome');
})->name('home');


//Login Logout
Route::post('/login/user', 'LoginController@login');
Route::get('/logout', 'LoginController@logout');

Route::group(['prefix' => 'admin', 'middleware' => 'check_admin_stat'], function () {
    // Page 
    Route::get('/', 'AdminController@student');
    Route::get('student', 'AdminController@student');
    Route::get('student/report', 'AdminController@studentReport');
    Route::get('position', 'AdminController@position');
    Route::get('position/report', 'AdminController@positionReport');
    Route::get('faculty', 'AdminController@faculty');
    Route::get('faculty/report', 'AdminController@facultyReport');
    Route::get('major', 'AdminController@major');
    Route::get('major/report', 'AdminController@majorReport');
    Route::get('teacher', 'AdminController@teacher');
    Route::get('teacher/report', 'AdminController@teacherReport');
    Route::get('subject', 'AdminController@subject');
    Route::get('subject/report', 'AdminController@subjectReport');
    Route::get('admin', 'AdminController@admin');
    Route::get('admin/report', 'AdminController@adminReport');
    Route::get('course_regist', 'AdminController@course_regist');
    Route::get('course_regist/report', 'AdminController@course_registReport');
    Route::get('setting', 'AdminController@setting');
    // End Page 
    // Position
    Route::post('position/update', 'PositionController@update');
    Route::post('position/insert', 'PositionController@insert');
    Route::get('position/delete/{id}', 'PositionController@delete');
    Route::get('position/getall', 'PositionController@getAll');
    // End Position
    // Faculty
    Route::post('faculty/update', 'FacultyController@update');
    Route::post('faculty/insert', 'FacultyController@insert');
    Route::get('faculty/delete/{id}', 'FacultyController@delete');
    Route::get('faculty/getall', 'FacultyController@getAll');
    // End Faculty
    // Major
    Route::post('major/update', 'MajorController@update');
    Route::post('major/insert', 'MajorController@insert');
    Route::get('major/delete/{id}', 'MajorController@delete');
    Route::get('major/getall', 'MajorController@getAll');
    Route::get('major/get/{id}', 'MajorController@getAllWhere');
    Route::get('major/getfac/{id}', 'MajorController@getFac');
    Route::get('major/getmajor/{id}', 'MajorController@getMajor');
    // End Major
    // Student
    Route::get('student/getall', 'StudentController@getAll');
    Route::get('student/get/{major}/{fac}/{yn}', 'StudentController@get');
    Route::get('student/delete/{id}', 'StudentController@delete');
    Route::post('student/insert', 'StudentController@insert');
    Route::post('student/update', 'StudentController@update');
    Route::post('student/updatepassword', 'StudentController@updatePassword');
    // End Student
    // Teacher
    Route::get('teacher/getall', 'TeacherController@getAll');
    Route::get('teacher/get/{id}', 'TeacherController@get');
    Route::get('teacher/get/{m_id}/{fac_id}', 'TeacherController@getReport');
    Route::get('teacher/getmajor/{id}', 'TeacherController@getMajor');
    Route::get('teacher/delete/{id}', 'TeacherController@delete');
    Route::post('teacher/insert', 'TeacherController@insert');
    Route::post('teacher/update', 'TeacherController@update');
    Route::post('teacher/updatepassword', 'TeacherController@updatePassword');
    // End Teacher
    // Subject
    Route::get('subject/getall', 'SubjectController@getAll');
    Route::get('subject/delete/{id}', 'SubjectController@delete');
    Route::post('subject/insert', 'SubjectController@insert');
    Route::post('subject/update', 'SubjectController@update');
    // End Subject
    // Admin
    Route::get('admin/getall', 'AdminController@getAll');
    Route::get('admin/delete/{id}', 'AdminController@delete');
    Route::post('admin/insert', 'AdminController@insert');
    Route::post('admin/update', 'AdminController@update');
    Route::post('admin/updatepassword', 'AdminController@updatePassword');
    // End Admin
    // Course Regist
    Route::get('course_regist/getyear', 'CourseRegistController@getYear');
    Route::get('course_regist/getdummy', 'CourseRegistController@dummy');
    Route::get('course_regist/dummy', 'CourseRegistController@getdummy');
    Route::get('course_regist/getyn', 'CourseRegistController@getYn');
    //$m_id,$s_id,$yn,$year,$term
    Route::get('course_regist/getinclass/{s_id}/{year}/{term}', 'CourseRegistController@getInclass');
    Route::get('course_regist/getnotinclass/{yn}/{fac_id}/{m_id}/{s_id}/{year}/{term}', 'CourseRegistController@getNotInclass');
    Route::post('course_regist/insert', 'CourseRegistController@insert');
    Route::post('course_regist/remove', 'CourseRegistController@remove');
    // End Course Regist
});

Route::group(['prefix' => 'teacher', 'middleware' => 'check_techer_stat'], function () {
    // PAGE
    Route::get('/', 'TeacherController@index');  
    Route::get('/report', 'TeacherController@report');  
    Route::get('/report2/{s_id}/{year}/{term}', 'TeacherController@report2');  
    Route::get('/report3', 'TeacherController@report3');  
    Route::get('/student/report', 'TeacherController@studentReport');  
    Route::get('/setting', 'TeacherController@setting');  
    // API
    Route::post('/update', 'TeacherController@update');
    Route::post('/updatepassword', 'TeacherController@updatePassword');
    Route::get('major/get/{id}', 'MajorController@getAllWhere');
    Route::get('faculty/getall', 'FacultyController@getAll');
    Route::get('course_regist/getyn', 'CourseRegistController@getYn');
    Route::get('student/getall', 'StudentController@getAll');
    Route::get('student/get/{major}/{fac}/{yn}', 'StudentController@get');
    Route::get('/getdummy', 'CourseRegistController@dummy');  
    Route::get('/getdata/{date}/{s_id}/{year}/{term}', 'CheckController@getdata');    
    Route::get('/getsubject/{t_id}', 'SubjectController@get');    
    Route::post('/carlendar/', 'CheckController@carlendar');   
    Route::post('/check/insert', 'CheckController@insert');
    Route::post('/check/updatedetail', 'CheckController@updateDetail');   
});

Route::group(['prefix' => 'student', 'middleware' => 'check_student_stat'], function () {
    // PAGE
    Route::get('/','StudentController@index');
    Route::get('/setting','StudentController@setting');
    // API
    Route::post('/update', 'StudentController@update');
    Route::post('/updatepassword', 'StudentController@updatePassword');
    Route::get('/getsubject/{id}','CourseRegistController@getSubject');
    Route::get('/getinfo/{cr_id}','CheckController@getInfo');
});
