<?php

use App\Http\Controllers\PasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    return redirect()->route('platform.main');
});

Route::get('home', function () {
    return redirect()->route('platform.main');
});

Route::get('reset/{token}{email}', function (Request $request) {
        $token = explode('?', explode('/', $request->getRequestUri())[2])[0];
        return view('auth.reset', [
            'resetToken' => $token,
            'email' => $request->email,
        ]);
    })
    ->name('password.set')
    ->middleware('check.token');

Route::get('invalidToken', function () {
        return view('auth.invalid');
    })
    ->name('password.token');

Route::get('noUser', function () {
    return view('auth.nouser');
})
    ->name('password.nouser');

Route::post('reset', [PasswordController::class, 'storePassword'])
    ->name('password.store');

Route::get('Timelapse-Systems_AGB.pdf', function () {
    return Storage::download('AGBs.pdf', 'Timelapse-Systems_AGB.pdf');
})->name('agb.download');

Route::get('Timelapse-Systems_Hilfe.pdf', function () {
    return Storage::download('Dokumentation_Tools_und_Einstellungen.pdf', 'Timelapse-Systems_Hilfe.pdf');
})->name('help.download');
