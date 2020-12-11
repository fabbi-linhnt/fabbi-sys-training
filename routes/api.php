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
Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    Route::resource('/categories', 'CategoryController');
    Route::resource('/courses', 'CourseController');
    Route::resource('/subjects', 'SubjectController');
    Route::resource('/tasks', 'TaskController');
    Route::resource('/users', 'UserController');

    Route::group(['prefix' => 'courses', 'as' => 'courses'], function () {
        Route::get('/{id}/categories', 'CourseController@listCategoryByCourseId')->name('category.get');
        Route::post('/{id}/assign-user-to-course', 'CourseController@assignUserToCourse')->name('assign.users');
        Route::get('/{id}/users', 'CourseController@getListUserByCourseId')->name('courses.listUser');
    });
    Route::group(['prefix' => 'subjects', 'as' => 'subjects'], function () {
        Route::get('/{id}/count', 'SubjectController@countTaskCourseById')->name('count.courses.tasks');
        Route::post('/{id}/assign-user-to-subject', 'SubjectController@assignUserToSubject')->name('assign.users');
        Route::get('/{id}/change-status', 'SubjectController@updateActive')->name('change.status');
        Route::get('/{id}/users', 'SubjectController@getListUserBySubjectId')->name('subjects.listUser');
        Route::get('/{id}/courses', 'SubjectController@getListCourseBySubjectId')->name('subjects.listCourse');
    });
    Route::group(['prefix' => 'tasks', 'as' => 'tasks'], function () {
        Route::get('/{id}/subjects', 'TaskController@getSubjectsByTaskId')->name('subjects.list');
        Route::get('/{id}/users', 'TaskController@getUsersByTaskId')->name('users.id');
        Route::post('/{id}/assign-user-to-task', 'TaskController@assignUserToTask')->name('tasks.assignUser');
        Route::get('/{id}/users', 'TaskController@getListUserByTaskId')->name('tasks.listUser');
        Route::get('/{id}/subjects', 'TaskController@getListSubjectByTaskId')->name('tasks.listSubject');
    });
    Route::group(['prefix' => 'reports', 'as' => 'reports'], function () {
        Route::put('/{id}/comment', 'TaskController@updateComment')->name('comment');
        Route::get('/{id}/reports', 'TaskController@getUserTask')->name('reports.list');
    });
    Route::group(['prefix' => 'users', 'as' => 'users'], function () {
        Route::get('/{id}/user-info', 'UserController@getUserInfo')->name('users.info');
        Route::get('/{id}/courses', 'UserController@getListCourseByUserId')->name('users.listCourse');
    });
});

