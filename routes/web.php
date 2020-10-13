<?php

use App\Http\Controllers\CameraController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\HeatingController;
use App\Http\Controllers\PhotovoltaicController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RouterController;
use App\Http\Controllers\SimController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\UpsController;
use App\Http\Controllers\UserController;
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

Auth::routes(['register' => false]);

// Group of all routes, which require authentication
Route::middleware('auth')->group(function ()
{
  Route::get('/', [Controller::class, 'index'])->name('home');

  // Group of routes, which require the user to be either manager or administrator
  Route::middleware('can:isManagerOrAdmin')->group(function ()
  {
    Route::prefix('user')->name('user.')->group(function ()
    {
      Route::post('', [UserController::class, 'store'])->name('store');
      Route::get('', [UserController::class, 'index'])->name('index');
      Route::get('create', [UserController::class, 'create'])->name('create');
      Route::get('list', [UserController::class, 'list'])->name('list');
      Route::post('{id}', [UserController::class, 'save'])->name('save');
      Route::get('{id}', [UserController::class, 'show'])->name('show');
      Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit');
      Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('company')->name('company.')->group(function ()
    {
      Route::get('', [CompanyController::class, 'index'])->name('index');
      Route::post('', [CompanyController::class, 'store'])->name('store');
      Route::get('create', [CompanyController::class, 'create'])->name('create');
      Route::get('list', [CompanyController::class, 'list'])->name('list');
      Route::post('{id}', [CompanyController::class, 'save'])->name('save');
      Route::get('{id}', [CompanyController::class, 'show'])->name('show');
      Route::get('{id}/edit', [CompanyController::class, 'edit'])->name('edit');
      Route::delete('{id}', [CompanyController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('project')->name('project.')->group(function ()
    {
      Route::get('', [ProjectController::class, 'index'])->name('index');
      Route::post('', [ProjectController::class, 'store'])->name('store');
      Route::get('create', [ProjectController::class, 'create'])->name('create');
      Route::get('create/users', [ProjectController::class, 'users'])->name('users');
      Route::get('list', [ProjectController::class, 'list'])->name('list');
    });

    Route::prefix('camera')->name('camera.')->group(function ()
    {
      Route::get('', [CameraController::class, 'index'])->name('index');
      Route::get('create', [CameraController::class, 'create'])->name('create');
      Route::get('list', [CameraController::class, 'list'])->name('list');
      Route::get('{id}/edit', [CameraController::class, 'edit'])->name('edit');
      Route::post('{id}/edit', [CameraController::class, 'save'])->name('save');
      Route::delete('{id}', [CameraController::class, 'destroy'])->name('destroy');
      Route::post('', [CameraController::class, 'store'])->name('store');
    });

    Route::prefix('system')->group(function ()
    {
      Route::name('system.')->group(function ()
      {
        Route::get('', [SystemController::class, 'index'])->name('index');
        Route::get('list', [SystemController::class, 'list'])->name('list');
        Route::get('create', [SystemController::class, 'create'])->name('create');
        Route::get('{id_f}/{id_r}/{id_u}', [SystemController::class, 'show'])->name('show');
        Route::post('{id_f}/{id_r}/{id_u}', [SystemController::class, 'save'])->name('save');
        Route::get('{id_f}/{id_r}/{id_u}/edit', [SystemController::class, 'edit'])->name('edit');
        Route::delete('{id_f}/{id_r}/{id_u}', [SystemController::class, 'destroy'])->name('destroy');
        Route::post('', [SystemController::class, 'store'])->name('store');
      });

      Route::prefix('fixture')->name('fixture.')->group(function ()
      {
        Route::get('', [FixtureController::class, 'index'])->name('index');
        Route::get('create', [FixtureController::class, 'create'])->name('create');
        Route::get('list', [FixtureController::class, 'list'])->name('list');
        Route::get('{id}/edit', [FixtureController::class, 'edit'])->name('edit');
        Route::post('{id}/edit', [FixtureController::class, 'save'])->name('save');
        Route::delete('{id}', [FixtureController::class, 'destroy'])->name('destroy');
        Route::post('', [FixtureController::class, 'store'])->name('store');
      });

      Route::prefix('heating')->name('heating.')->group(function ()
      {
        Route::get('', [HeatingController::class, 'index'])->name('index');
        Route::get('create', [HeatingController::class, 'create'])->name('create');
        Route::get('list', [HeatingController::class, 'list'])->name('list');
        Route::get('{id}/edit', [HeatingController::class, 'edit'])->name('edit');
        Route::post('{id}/edit', [HeatingController::class, 'save'])->name('save');
        Route::delete('{id}', [HeatingController::class, 'destroy'])->name('destroy');
        Route::post('', [HeatingController::class, 'store'])->name('store');
      });

      Route::prefix('photovoltaic')->name('photovoltaic.')->group(function ()
      {
        Route::get('', [PhotovoltaicController::class, 'index'])->name('index');
        Route::get('create', [PhotovoltaicController::class, 'create'])->name('create');
        Route::get('list', [PhotovoltaicController::class, 'list'])->name('list');
        Route::get('{id}/edit', [PhotovoltaicController::class, 'edit'])->name('edit');
        Route::post('{id}/edit', [PhotovoltaicController::class, 'save'])->name('save');
        Route::delete('{id}', [PhotovoltaicController::class, 'destroy'])->name('destroy');
        Route::post('', [PhotovoltaicController::class, 'store'])->name('store');
      });

      Route::prefix('router')->name('router.')->group(function ()
      {
        Route::get('', [RouterController::class, 'index'])->name('index');
        Route::get('create', [RouterController::class, 'create'])->name('create');
        Route::get('list', [RouterController::class, 'list'])->name('list');
        Route::get('{id}/edit', [RouterController::class, 'edit'])->name('edit');
        Route::post('{id}/edit', [RouterController::class, 'save'])->name('save');
        Route::delete('{id}', [RouterController::class, 'destroy'])->name('destroy');
        Route::post('', [RouterController::class, 'store'])->name('store');
      });

      Route::prefix('sim')->name('sim.')->group(function ()
      {
        Route::get('', [SimController::class, 'index'])->name('index');
        Route::get('create', [SimController::class, 'create'])->name('create');
        Route::get('list', [SimController::class, 'list'])->name('list');
        Route::get('{id}/edit', [SimController::class, 'edit'])->name('edit');
        Route::post('{id}/edit', [SimController::class, 'save'])->name('save');
        Route::delete('{id}', [SimController::class, 'destroy'])->name('destroy');
        Route::post('', [SimController::class, 'store'])->name('store');
      });

      Route::prefix('ups')->name('ups.')->group(function ()
      {
        Route::get('', [UpsController::class, 'index'])->name('index');
        Route::get('create', [UpsController::class, 'create'])->name('create');
        Route::get('list', [UpsController::class, 'list'])->name('list');
        Route::get('{id}/edit', [UpsController::class, 'edit'])->name('edit');
        Route::post('{id}/edit', [UpsController::class, 'save'])->name('save');
        Route::delete('{id}', [UpsController::class, 'destroy'])->name('destroy');
        Route::post('', [UpsController::class, 'store'])->name('store');
      });

    });

  });

  Route::middleware('project.access')->prefix('project')->name('project.')->group(function ()
  {
    Route::get('{id}', [ProjectController::class, 'show'])->name('show');

  });

});
