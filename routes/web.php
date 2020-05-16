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

Route::get('/', function () {
    return redirect('user.index');
})->middleware('auth');

Auth::routes([
  // disable registration process on page
  'register' => false,

  // usage: Route::get('some_route')->middleware('verified')
  // 'verify' => true
]);

Route::get('/dashboard', 'UserController@index')->name('user.index');
