<?php

use App\Http\Controllers\CameraController;
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

Route::get('/camera', 'CameraController@index')->name('camera.index')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/camera/create', 'CameraController@create')->name('camera.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::post('/camera', 'CameraController@store')->name('camera.store')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/system', 'SystemController@index')->name('system.index')->middleware('auth');
Route::get('/system/create', 'SystemController@create')->name('system.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::post('/system', 'SystemController@store')->name('system.store')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/system/fixture', 'FixtureController@index')->name('fixture.index')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/system/fixture/create', 'FixtureController@create')->name('fixture.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::post('/system/fixture', 'FixtureController@store')->name('fixture.store')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/system/heater', 'HeatingController@index')->name('heating.index')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/system/heater/create', 'HeatingController@create')->name('heating.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::post('/system/heater', 'HeatingController@store')->name('heating.store')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/system/photovoltaic', 'PhotovoltaicController@index')->name('photovoltaic.index')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/system/photovoltaic/create', 'PhotovoltaicController@create')->name('photovoltaic.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::post('/system/photovoltaic', 'PhotovoltaicController@store')->name('photovoltaic.store')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/system/router', 'RouterController@index')->name('router.index')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/system/router/create', 'RouterController@create')->name('router.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::post('/system/router', 'RouterController@store')->name('router.store')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/system/sim', 'SimController@index')->name('sim.index')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/system/sim/create', 'SimController@create')->name('sim.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/system/sim', 'SimController@store')->name('sim.store')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/system/ups', 'UpsController@index')->name('ups.index')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/system/ups/create', 'UpsController@create')->name('ups.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/system/ups', 'UpsController@store')->name('ups.store')->middleware(['auth', 'can:isManagerOrAdmin']);
