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
        Route::get('/categories/{id}', 'CourseController@listCategoryByCourseId')->name('category.get');
        Route::post('/users/{id}/assign-user-to-course', 'CourseController@assignUserToCourse')->name('assign.users');
    });
    Route::group(['prefix' => 'subjects', 'as' => 'subjects'], function () {
        Route::get('/courses/{id}', 'SubjectController@listCourseBySubjectId')->name('courses.list');
        Route::get('/{id}/count', 'SubjectController@countTaskCourseById')->name('courses.tasks.count');
        Route::post('/{id}/assign-user-to-subject', 'SubjectController@assignUserToSubject')->name('assign.users');
    });
    Route::group(['prefix' => 'tasks', 'as' => 'tasks'], function () {
        Route::get('/subjects/{id}', 'TaskController@getSubjectsByTaskId')->name('subjects.list');
        Route::get('/users/{id}', 'TaskController@getUsersByTaskId')->name('users.id');
        Route::post('/{id}/assign-user-to-task', 'TaskController@assignUserToTask')->name('tasks.assignUser');
    });
    Route::group(['prefix' => 'reports', 'as' => 'reports'], function () {
        Route::put('/comment/{id}', 'TaskController@updateComment')->name('comment');
        Route::get('/{id}', 'TaskController@getUserTask')->name('list');
    });
    Route::group(['prefix' => 'users', 'as' => 'users'], function () {
        Route::get('/subjects/{id}', 'UserController@countSubject')->name('subject.count');
        Route::get('/tasks/{id}', 'UserController@countTask')->name('tasks.count');
        Route::get('/username/{id}', 'UserController@userName')->name('username');
    });
});

