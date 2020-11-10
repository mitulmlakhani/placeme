<?php

use Illuminate\Support\Facades\Route;

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


Route::middleware(['fire_authenticated'])->group(function() {
    Route::get('register', 'Auth\RegisterController@showForm'); 
    Route::post('register', 'Auth\RegisterController@register')->name('register');

    Route::get('login', 'Auth\LoginController@showForm'); 
    Route::post('login', 'Auth\LoginController@login')->name('login');
});

Route::middleware(['fire_auth'])->group(function() {
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    
    Route::get('profile', 'HomeController@profile')->name('profile');
    Route::post('profile', 'HomeController@profileSave')->name('profile.save');

    Route::get('/', 'HomeController@index');
    Route::get('course', 'CourseController@index')->name('course.index');
    Route::get('course/request/{id}', 'CourseController@sendRequest')->name('course.request');

    Route::get('course/suggession', 'SuggestCourseController@index')->name('course.suggession.index');
    Route::get('course/suggession/create', 'SuggestCourseController@create')->name('course.suggession.create');
    Route::post('course/suggession/save', 'SuggestCourseController@store')->name('course.suggession.store');
    Route::get('course/suggession/like/{id}', 'SuggestCourseController@like')->name('course.suggession.like');
});


Route::group(['namespace' => 'Professor', 'prefix' => 'professor', 'as' => 'professor.'], function() {
    Route::middleware(['fire_authenticated'])->group(function() {
        Route::get('register', 'Auth\RegisterController@showForm'); 
        Route::post('register', 'Auth\RegisterController@register')->name('register');
    
        Route::get('login', 'Auth\LoginController@showForm'); 
        Route::post('login', 'Auth\LoginController@login')->name('login');
    });

    Route::middleware(['fire_auth'])->group(function() {
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('/', 'HomeController@index');

        Route::get('profile', 'HomeController@profile')->name('profile');
        Route::post('profile', 'HomeController@profileSave')->name('profile.save');

        Route::get('course', 'CourseController@index')->name('course.index');
        Route::get('course/create', 'CourseController@create')->name('course.create');
        Route::post('course/save', 'CourseController@store')->name('course.store');
        Route::get('course/{id}', 'CourseController@view')->name('course.view');
        
        Route::get('course/{id}/approve/{user_id}', 'CourseController@approve')->name('course.approve');
        Route::get('course/{id}/reject/{user_id}', 'CourseController@reject')->name('course.reject');

    });
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::middleware(['fire_authenticated'])->group(function() {
        Route::get('login', 'Auth\LoginController@showForm'); 
        Route::post('login', 'Auth\LoginController@login')->name('login');
    });

    Route::middleware(['fire_auth'])->group(function() {
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('/', 'HomeController@index');

        Route::get('profile', 'HomeController@profile')->name('profile');
        Route::post('profile', 'HomeController@profileSave')->name('profile.save');

        Route::get('course/suggession', 'SuggestCourseController@index')->name('course.suggession.index');
        Route::get('course/suggession/{id}/reject', 'SuggestCourseController@reject')->name('course.suggession.reject');

        Route::get('course', 'CourseController@index')->name('course.index');
        Route::get('course/create', 'CourseController@create')->name('course.create');
        Route::post('course/save', 'CourseController@store')->name('course.store');
        Route::get('course/{id}', 'CourseController@view')->name('course.view');
        
        Route::get('course/{id}/approve', 'CourseController@approveCourse')->name('course.approve');
        Route::get('course/{id}/reject', 'CourseController@rejectCourse')->name('course.reject');
        
        Route::get('course/{id}/approve/student/{user_id}', 'CourseController@approve')->name('course.student.approve');
        Route::get('course/{id}/reject/student/{user_id}', 'CourseController@reject')->name('course.student.reject');
    });
});

