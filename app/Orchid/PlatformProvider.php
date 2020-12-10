<?php

namespace App\Orchid;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Laravel\Scout\Searchable;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemMenu;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\Menu;
use Orchid\Platform\OrchidServiceProvider;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        View::composer('platform::dashboard', function () use ($dashboard) {
            foreach ($this->registerMainMenu() as $itemMenu) {
                $dashboard->menu->add(Menu::MAIN, $itemMenu);
            }
        });

        View::composer('platform::partials.profile', function () use ($dashboard) {
            foreach ($this->registerProfileMenu() as $itemMenu) {
                $dashboard->menu->add(Menu::PROFILE, $itemMenu);
            }
        });

        foreach ($this->registerPermissions() as $permission) {
            $dashboard->registerPermissions($permission);
        }

        $dashboard->registerSearch($this->registerSearchModels());
    }

    /**
     * @return ItemMenu[]
     */
    public function registerMainMenu(): array
    {
        return [
            ItemMenu::label(__('Benutzer'))
                ->title('Verwaltung')
                ->icon('user')
                ->route('platform.users')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Firmen'))
                ->icon('building')
                ->route('platform.companies')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Kameras'))
                ->icon('camrecorder')
                ->route('platform.cameras')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Systeme'))
                ->icon('layers')
                ->route('platform.systems')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Komponenten'))
                ->icon('modules')
                ->slug('components')
                ->childs()
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Router'))
                ->icon('module')
                ->place('components')
                ->route('platform.routers')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Sim-Karten'))
                ->icon('module')
                ->place('components')
                ->route('platform.simcards')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Gehäuse'))
                ->icon('module')
                ->place('components')
                ->route('platform.fixtures')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('USV'))
                ->icon('module')
                ->place('components')
                ->route('platform.ups')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Heizungen'))
                ->icon('module')
                ->place('components')
                ->route('platform.heatings')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Photovoltaik'))
                ->icon('module')
                ->place('components')
                ->route('platform.photovoltaics')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

            ItemMenu::label(__('Projekte'))
                ->icon('notebook')
                ->route('platform.projects')
                ->canSee(Auth::user()->hasAccess('admin') || Auth::user()->hasAccess('manager')),

        ];
    }

    /**
     * @return ItemMenu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            ItemMenu::label('Profile')
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('Verwaltung'))
                ->addPermission('admin', __('Administrator'))
                ->addPermission('manager', __('Manager')),
        ];
    }

    /**
     * @return Searchable|string[]
     */
    public function registerSearchModels(): array
    {
        return [
            // ...Models
            // \App\Models\User::class
        ];
    }
}
