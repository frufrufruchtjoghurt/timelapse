<?php

declare(strict_types=1);

use App\Orchid\Screens\Camera\CameraEditScreen;
use App\Orchid\Screens\Camera\CameraListScreen;
use App\Orchid\Screens\Company\CompanyListScreen;
use App\Orchid\Screens\Cooling\CoolingEditScreen;
use App\Orchid\Screens\Cooling\CoolingListScreen;
use App\Orchid\Screens\Fixture\FixtureEditScreen;
use App\Orchid\Screens\Fixture\FixtureListScreen;
use App\Orchid\Screens\Heating\HeatingEditScreen;
use App\Orchid\Screens\Heating\HeatingListScreen;
use App\Orchid\Screens\Photovoltaic\PhotovoltaicEditScreen;
use App\Orchid\Screens\Photovoltaic\PhotovoltaicListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Project\ProjectArchiveScreen;
use App\Orchid\Screens\Project\ProjectDeeplinksScreen;
use App\Orchid\Screens\Project\ProjectEditScreen;
use App\Orchid\Screens\Project\ProjectListScreen;
use App\Orchid\Screens\Project\ProjectViewScreen;
use App\Orchid\Screens\Router\RouterEditScreen;
use App\Orchid\Screens\Router\RouterListScreen;
use App\Orchid\Screens\SimCard\SimCardEditScreen;
use App\Orchid\Screens\SimCard\SimCardListScreen;
use App\Orchid\Screens\SupplyUnit\SupplyUnitEditScreen;
use App\Orchid\Screens\SupplyUnit\SupplyUnitListScreen;
use App\Orchid\Screens\System\SystemEditScreen;
use App\Orchid\Screens\System\SystemListScreen;
use App\Orchid\Screens\Ups\UpsEditScreen;
use App\Orchid\Screens\Ups\UpsListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\Company\CompanyEditScreen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

Route::middleware(['symlinks'])->group(function () {
    // Main
    Route::screen('/dashboard', PlatformScreen::class)
        ->name('platform.main');

    Route::middleware(['project.access'])->group(function () {
        Route::screen('view/{id}', ProjectViewScreen::class)
            ->name('platform.view')
            ->breadcrumbs(function (Trail $trail, $id = null) {
                return $trail
                    ->parent('platform.index')
                    ->push(__('Details'), route('platform.view', $id));
            });

        Route::screen('archive/{id}', ProjectArchiveScreen::class)
            ->name('platform.archive')
            ->breadcrumbs(function (Trail $trail, $id = null) {
                return $trail
                    ->parent('platform.index')
                    ->push(__('Archiv'), route('platform.archive', $id));
            });

        Route::screen('deeplink/{id}', ProjectDeeplinksScreen::class)
            ->name('platform.deeplink')
            ->breadcrumbs(function (Trail $trail, $id = null) {
                return $trail
                    ->parent('platform.index')
                    ->push(__('Details'), route('platform.deeplink', $id));
            });
    });
});

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Profil'), route('platform.profile'));
    });

// Platform > Users
Route::screen('users/createOrEdit/{users?}', UserEditScreen::class)
    ->name('platform.users.edit')
    ->breadcrumbs(function (Trail $trail, $user = null) {
        return $trail
            ->parent('platform.users')
            ->push(__('Bearbeiten'), route('platform.users.edit', $user));
    });

// Platform > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.users')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Kunden'), route('platform.users'));
    });

// Platform > Companies
Route::screen('company/createOrEdit/{companies?}', CompanyEditScreen::class)
    ->name('platform.companies.edit')
    ->breadcrumbs(function (Trail $trail, $company = null) {
        return $trail
            ->parent('platform.companies')
            ->push(__('Bearbeiten'), route('platform.companies.edit', $company));
    });

// Platform > Companies > Company
Route::screen('company', CompanyListScreen::class)
    ->name('platform.companies')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Firmen'), route('platform.companies'));
    });

// Platform > Cameras
Route::screen('camera/createOrEdit/{cameras?}', CameraEditScreen::class)
    ->name('platform.cameras.edit')
    ->breadcrumbs(function (Trail $trail, $camera = null) {
        return $trail
            ->parent('platform.cameras')
            ->push(__('Bearbeiten'), route('platform.cameras.edit', $camera));
    });

// Platform > Cameras > Camera
Route::screen('camera', CameraListScreen::class)
    ->name('platform.cameras')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Kameras'), route('platform.cameras'));
    });

// Platform > SupplyUnits
Route::screen('supplyunit/createOrEdit/{supplyunits?}', SupplyUnitEditScreen::class)
    ->name('platform.supplyunits.edit')
    ->breadcrumbs(function (Trail $trail, $supplyunit = null) {
        return $trail
            ->parent('platform.supplyunits')
            ->push(__('Bearbeiten'), route('platform.supplyunits.edit', $supplyunit));
    });

// Platform > SupplyUnits > SupplyUnit
Route::screen('supplyunit', SupplyUnitListScreen::class)
    ->name('platform.supplyunits')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Versorgungseinheiten'), route('platform.supplyunits'));
    });

// Platform > Routers
Route::screen('router/createOrEdit/{routers?}', RouterEditScreen::class)
    ->name('platform.routers.edit')
    ->breadcrumbs(function (Trail $trail, $router = null) {
        return $trail
            ->parent('platform.routers')
            ->push(__('Bearbeiten'), route('platform.routers.edit', $router));
    });

// Platform > Routers > Router
Route::screen('router', RouterListScreen::class)
    ->name('platform.routers')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Router'), route('platform.routers'));
    });

// Platform > Fixtures
Route::screen('fixture/createOrEdit/{fixtures?}', FixtureEditScreen::class)
    ->name('platform.fixtures.edit')
    ->breadcrumbs(function (Trail $trail, $fixture = null) {
        return $trail
            ->parent('platform.fixtures')
            ->push(__('Bearbeiten'), route('platform.fixtures.edit', $fixture));
    });

// Platform > Fixtures > Fixture
Route::screen('fixture', FixtureListScreen::class)
    ->name('platform.fixtures')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Gehäuse'), route('platform.fixtures'));
    });

// Platform > SimCards
Route::screen('simcard/createOrEdit/{simcards?}', SimCardEditScreen::class)
    ->name('platform.simcards.edit')
    ->breadcrumbs(function (Trail $trail, $simcard = null) {
        return $trail
            ->parent('platform.simcards')
            ->push(__('Bearbeiten'), route('platform.simcards.edit', $simcard));
    });

// Platform > SimCards > SimCard
Route::screen('simcard', SimCardListScreen::class)
    ->name('platform.simcards')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Sim-Karten'), route('platform.simcards'));
    });

// Platform > Ups
Route::screen('ups/createOrEdit/{ups?}', UpsEditScreen::class)
    ->name('platform.ups.edit')
    ->breadcrumbs(function (Trail $trail, $ups = null) {
        return $trail
            ->parent('platform.ups')
            ->push(__('Bearbeiten'), route('platform.ups.edit', $ups));
    });

// Platform > Ups > Ups
Route::screen('ups', UpsListScreen::class)
    ->name('platform.ups')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('USVs'), route('platform.ups'));
    });

// Platform > Photovoltaics
Route::screen('photovoltaic/createOrEdit/{photovoltaics?}', PhotovoltaicEditScreen::class)
    ->name('platform.photovoltaics.edit')
    ->breadcrumbs(function (Trail $trail, $photovoltaic = null) {
        return $trail
            ->parent('platform.photovoltaics')
            ->push(__('Bearbeiten'), route('platform.photovoltaics.edit', $photovoltaic));
    });

// Platform > Photovoltaics > Photovoltaic
Route::screen('photovoltaic', PhotovoltaicListScreen::class)
    ->name('platform.photovoltaics')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Photovoltaik'), route('platform.photovoltaics'));
    });

// Platform > Projects
Route::screen('project/createOrEdit/{projects?}', ProjectEditScreen::class)
    ->name('platform.projects.edit')
    ->breadcrumbs(function (Trail $trail, $project = null) {
        return $trail
            ->parent('platform.projects')
            ->push(__('Bearbeiten'), route('platform.projects.edit', $project));
    });

// Platform > Projects > Project
Route::screen('project', ProjectListScreen::class)
    ->name('platform.projects')
    ->breadcrumbs(function (Trail $trail) {
        return $trail
            ->parent('platform.index')
            ->push(__('Projekte'), route('platform.projects'));
    });
