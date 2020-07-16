<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', 'Controller@index')->middleware('auth');

Auth::routes(['register' => false]);

Route::post('/user', 'UserController@store')->name('user.store')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/user', 'UserController@index')->name('user.index')->middleware('auth');
Route::get('/users/create', 'UserController@create')->name('user.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/users/list', 'UserController@list')->name('user.list')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::post('/users/{id}', 'UserController@save')->name('user.save')->middleware('auth', 'can:isManagerOrAdmin');
Route::get('/users/{id}', 'UserController@show')->name('user.show')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/users/{id}/edit', 'UserController@edit')->name('user.edit')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::delete('/users/{id}', 'UserController@destroy')->name('user.destroy')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/company', 'CompanyController@index')->name('company.index')->middleware('auth');
Route::post('/company', 'CompanyController@store')->name('company.store')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/company/create', 'CompanyController@create')->name('company.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/company/list', 'CompanyController@list')->name('company.list')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::post('/company/{id}', 'CompanyController@save')->name('company.save')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/company/{id}', 'CompanyController@show')->name('company.show')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/company/{id}/edit', 'CompanyController@edit')->name('company.edit')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::delete('/company/{id}', 'CompanyController@destroy')->name('company.destroy')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/project', 'ProjectController@index')->name('project.index')->middleware('auth');
Route::post('/project', 'ProjectController@store')->name('project.store')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/project/create', 'ProjectController@create')->name('project.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/project/create/users', 'ProjectController@usersSelector')->name('project.users')->middleware(['auth', 'can:isManagerOrAdmin']);
