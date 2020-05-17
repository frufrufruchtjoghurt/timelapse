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

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Auth::routes([
  'register' => false,
  // usage: Route::get('some_route')->middleware('verified')
  // 'verify' => true
]);

Route::post('/user', 'UserController@store')->name('user.store')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/user', 'UserController@index')->name('user.index')->middleware('auth');
Route::get('/users/create', 'UserController@create')->name('user.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/users/show', 'UserController@show')->name('user.show')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::delete('/users/{id}', 'UserController@destroy')->name('user.destroy')->middleware(['auth', 'can:isManagerOrAdmin']);

Route::get('/company', 'CompanyController@index')->name('company.index')->middleware('auth');
Route::post('/company', 'CompanyController@store')->name('company.store')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/company/create', 'CompanyController@create')->name('company.create')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::get('/company/show', 'CompanyController@show')->name('company.show')->middleware(['auth', 'can:isManagerOrAdmin']);
Route::delete('/company/{id}', 'CompanyController@destroy')->name('company.destroy')->middleware(['auth', 'can:isManagerOrAdmin']);
