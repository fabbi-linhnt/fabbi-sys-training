<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('register', 'Auth\AuthController@register');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::post('me', 'Auth\AuthController@me');
});

Route::resource('/test', 'Api\HomeController');
Route::resource('/tasks', 'Api\TaskController');
Route::get('/tasks/subject-task/{id}', 'Api\TaskController@getSubjectOfTask');
Route::get('/subject/list','Api\SubjectController@index');
Route::get('/subject/add','Api\SubjectController@store');
Route::resource('/course', 'Api\CourseController');
Route::get('/category','Api\CategoryController@index');
Route::get('/course/category/{id}','Api\CourseController@listCategoryByCourseId');


Route::resource('/course', 'Api\CourseController');
Route::get('/course', 'Api\CourseController@index');
Route::resource('/subjects','Api\SubjectController');
Route::get('/subjects/count/{id}','Api\SubjectController@countTaskCourseSubjectById');
Route::get('/subjects/courses/{id}','Api\SubjectController@ListCourseBySubjetID');
Route::get('/subject/all', 'Api\SubjectController@getAllSubject');
Route::resource('user','Api\UserController');
Route::get('/user/countSubject/{id}', 'Api\UserController@countSubject');
Route::get('/user/countTask/{id}', 'Api\UserController@countTask');
Route::get('/user/userName/{id}', 'Api\UserController@userName');
Route::get('/user-task/{id}', 'Api\TaskController@getUserTask');
Route::get('/user/getInfo/{id}','Api\UserController@getUserInfo');
Route::put('user-task/{id}', 'Api\TaskController@updateComment');
Route::get('/courses/list','Api\SubjectController@listCourses' );
Route::put('/is_active/update/{id}','Api\SubjectController@updateActive' );
Route::get('/categories', 'Api\CategoryController@category');
Route::resource('/category', 'Api\CategoryController');
Route::get('/task/users/{id}', 'Api\TaskController@getUsersByTaskId');

